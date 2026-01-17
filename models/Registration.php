<?php

/**
 * Registration Functions
 * ----------------------
 * Handles event registration logic (SAFE & ACCURATE)
 */

/**
 * Internal Registration Logic (WITH LOCKING)
 * ------------------------------------------
 */
function _registration_create_logic($userId, $eventId, $ticketTypeId, $quantity)
{
    // 1. LOCK ticket type row
    $ticket = db_query_one(
        "SELECT capacity 
         FROM ticket_types 
         WHERE id = :id 
         FOR UPDATE",
        [':id' => $ticketTypeId]
    );

    if (!$ticket) {
        throw new Exception("Ticket type not found");
    }

    // 2. Calculate already reserved seats
    $reservedRow = db_query_one(
        "SELECT COALESCE(SUM(quantity), 0) AS reserved
         FROM registrations
         WHERE ticket_type_id = :id
         AND status = 'confirmed'",
        [':id' => $ticketTypeId]
    );

    $reserved  = (int)$reservedRow['reserved'];
    $available = (int)$ticket['capacity'] - $reserved;

    if ($quantity > $available) {
        throw new Exception("Not enough seats available");
    }

    // 3. Check existing registration for same user + ticket type
    $existing = db_query_one(
        "SELECT id, status, quantity 
         FROM registrations
         WHERE user_id = :u
         AND event_id = :e
         AND ticket_type_id = :t
         FOR UPDATE",
        [
            ':u' => $userId,
            ':e' => $eventId,
            ':t' => $ticketTypeId
        ]
    );

    if ($existing) {
        if ($existing['status'] === 'confirmed') {
            // Add to existing quantity
            $newQuantity = $existing['quantity'] + $quantity;

            db_execute(
                "UPDATE registrations
                 SET quantity = :qty,
                     registration_date = CURRENT_TIMESTAMP
                 WHERE id = :id",
                [
                    ':qty' => $newQuantity,
                    ':id'  => $existing['id']
                ]
            );

            return $existing['id'];
        } else {
            // Reactivate cancelled registration
            db_execute(
                "UPDATE registrations
                 SET status = 'confirmed',
                     quantity = :qty,
                     registration_date = CURRENT_TIMESTAMP
                 WHERE id = :id",
                [
                    ':qty' => $quantity,
                    ':id'  => $existing['id']
                ]
            );

            return $existing['id'];
        }
    }

    // 4. Create new registration
    $ticketNumber = 'EVT-' . $eventId . '-' . $userId . '-' . $ticketTypeId . '-' . time();

    db_execute(
        "INSERT INTO registrations
        (user_id, event_id, ticket_type_id, ticket_number, status, quantity)
        VALUES
        (:user_id, :event_id, :ticket_type_id, :ticket_number, 'confirmed', :quantity)",
        [
            ':user_id'        => $userId,
            ':event_id'       => $eventId,
            ':ticket_type_id' => $ticketTypeId,
            ':ticket_number'  => $ticketNumber,
            ':quantity'       => $quantity
        ]
    );

    return db_last_id();
}

/**
 * Single Registration
 * -------------------
 */
function registration_create($userId, $eventId, $ticketTypeId, $quantity = 1)
{
    if (!$userId) return false;

    $quantity = max(1, (int)$quantity);

    db_transaction_start();
    try {
        $id = _registration_create_logic($userId, $eventId, $ticketTypeId, $quantity);
        db_transaction_commit();
        return $id;
    } catch (Exception $e) {
        db_transaction_rollback();
        error_log("Registration Create Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Batch Registration
 * ------------------
 */
function registration_create_batch($userId, $eventId, $items)
{
    if (!$userId || empty($items)) return false;

    db_transaction_start();
    try {
        foreach ($items as $item) {
            $ticketTypeId = (int)$item['ticket_type_id'];
            $quantity     = (int)$item['quantity'];

            if ($quantity > 0) {
                _registration_create_logic($userId, $eventId, $ticketTypeId, $quantity);
            }
        }

        db_transaction_commit();
        return true;
    } catch (Exception $e) {
        db_transaction_rollback();
        error_log("Batch Registration Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Cancel Registration
 * -------------------
 */
function registration_cancel($registrationId)
{
    db_transaction_start();

    try {
        // Lock registration row
        $reg = db_query_one(
            "SELECT id, ticket_type_id, quantity, status
             FROM registrations
             WHERE id = :id
             FOR UPDATE",
            [':id' => $registrationId]
        );

        if (!$reg || $reg['status'] !== 'confirmed') {
            throw new Exception("Invalid registration");
        }

        // Cancel registration
        db_execute(
            "UPDATE registrations
             SET status = 'cancelled'
             WHERE id = :id",
            [':id' => $registrationId]
        );

        // Seats are AUTO-released because availability is calculated dynamically
        db_transaction_commit();
        return true;
    } catch (Exception $e) {
        db_transaction_rollback();
        error_log("Cancel Registration Error: " . $e->getMessage());
        return false;
    }
}

/**
 * Check if User is Registered
 * ---------------------------
 */
function registration_check($userId, $eventId)
{
    $result = db_query_one(
        "SELECT id
         FROM registrations
         WHERE user_id = :u
         AND event_id = :e
         AND status = 'confirmed'",
        [
            ':u' => $userId,
            ':e' => $eventId
        ]
    );

    return (bool)$result;
}

/**
 * Get User Registrations
 * ---------------------
 */
function registration_get_by_user($userId)
{
    return db_query_all(
        "SELECT r.*,
                e.title AS event_title,
                e.event_date,
                e.event_time,
                e.venue,
                tt.name AS ticket_type_name,
                tt.price AS ticket_price,
                c.name AS category_name
         FROM registrations r
         JOIN events e ON r.event_id = e.id
         LEFT JOIN ticket_types tt ON r.ticket_type_id = tt.id
         LEFT JOIN categories c ON e.category_id = c.id
         WHERE r.user_id = :user_id
         ORDER BY r.registration_date DESC",
        [':user_id' => $userId]
    );
}

/**
 * Admin: Get All Registrations
 * ----------------------------
 */
function registration_get_all()
{
    return db_query_all(
        "SELECT r.*,
                e.title AS event_title,
                e.event_date,
                e.event_time,
                tt.name AS ticket_type_name,
                tt.price AS ticket_price,
                u.email AS user_email,
                u.phone AS user_phone,
                CONCAT(u.first_name, ' ', u.last_name) AS user_name
         FROM registrations r
         JOIN events e ON r.event_id = e.id
         JOIN users u ON r.user_id = u.id
         LEFT JOIN ticket_types tt ON r.ticket_type_id = tt.id
         ORDER BY r.registration_date DESC"
    );
}

/**
 * Admin: Get Registrations by Event
 * --------------------------------
 */
function registration_get_by_event($eventId)
{
    return db_query_all(
        "SELECT r.*,
                u.email AS user_email,
                u.phone AS user_phone,
                CONCAT(u.first_name, ' ', u.last_name) AS user_name
         FROM registrations r
         JOIN users u ON r.user_id = u.id
         WHERE r.event_id = :event_id
         ORDER BY r.registration_date DESC",
        [':event_id' => $eventId]
    );
}

/**
 * Count Registrations
 * -------------------
 */
function registration_count()
{
    $row = db_query_one("SELECT COUNT(*) AS count FROM registrations WHERE status = 'confirmed'");
    return $row ? (int)$row['count'] : 0;
}
