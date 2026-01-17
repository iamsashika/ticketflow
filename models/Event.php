<?php

/**
 * Event Functions
 * ---------------
 * Simple functions to handle event data.
 */

/**
 * Get all events
 * --------------
 * Supports filters for category, search, and status.
 */
function event_get_all($filters = [], $limit = null)
{
    $sql = "SELECT e.*, c.name as category_name 
            FROM events e 
            LEFT JOIN categories c ON e.category_id = c.id";

    $conditions = [];
    $params = [];

    // Always exclude deleted events
    $conditions[] = "e.is_deleted = 0";

    // Status Filter
    if (isset($filters['status'])) {
        $conditions[] = "e.status = :status";
        $params[':status'] = $filters['status'];
    }

    // Category Filter
    if (!empty($filters['category'])) {
        $conditions[] = "e.category_id = :category";
        $params[':category'] = $filters['category'];
    }

    // Search Filter
    if (!empty($filters['search'])) {
        $conditions[] = "(e.title LIKE :search_title OR e.description LIKE :search_desc)";
        $params[':search_title'] = '%' . $filters['search'] . '%';
        $params[':search_desc']  = '%' . $filters['search'] . '%';
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY e.event_date DESC";

    if (!empty($limit)) {
        $sql .= " LIMIT " . (int)$limit;
    }

    return db_query_all($sql, $params);
}

/**
 * Get single event by ID
 * ----------------------
 */
function event_get_by_id($id)
{
    $sql = "SELECT e.*, c.name as category_name 
            FROM events e 
            LEFT JOIN categories c ON e.category_id = c.id 
            WHERE e.id = :id
              AND e.is_deleted = 0";

    return db_query_one($sql, [':id' => $id]);
}

/**
 * Get upcoming events (Limit)
 * ---------------------------
 */
function event_get_upcoming($limit = 3)
{
    $sql = "SELECT *
            FROM events
            WHERE status = 'upcoming'
              AND is_deleted = 0
            ORDER BY event_date ASC
            LIMIT " . (int)$limit;

    return db_query_all($sql);
}

/**
 * Create new event
 * ----------------
 * is_deleted defaults to 0 in DB
 */
function event_create($data)
{
    $sql = "INSERT INTO events 
            (title, category_id, description, venue, event_date, event_time, ticket_price,
             capacity, available_seats, image, created_by, status) 
            VALUES 
            (:title, :category_id, :description, :venue, :event_date, :event_time, :ticket_price,
             :capacity, :available_seats, :image, :created_by, :status)";

    $params = [
        ':title'           => $data['title'],
        ':category_id'     => $data['category_id'],
        ':description'     => $data['description'],
        ':venue'           => $data['venue'],
        ':event_date'      => $data['event_date'],
        ':event_time'      => $data['event_time'],
        ':ticket_price'    => $data['ticket_price'],
        ':capacity'        => $data['capacity'],
        ':available_seats' => $data['capacity'],
        ':image'           => $data['image'],
        ':created_by'      => $data['created_by'],
        ':status'          => $data['status'],
    ];

    if (db_execute($sql, $params)) {
        return db_last_id();
    }

    return false;
}

/**
 * Update event
 * ------------
 */
function event_update($id, $data)
{
    $fields = [
        'title = :title',
        'category_id = :category_id',
        'description = :description',
        'venue = :venue',
        'event_date = :event_date',
        'event_time = :event_time',
        'capacity = :capacity',
        'status = :status',
        'updated_at = NOW()'
    ];

    $params = [
        ':id'          => $id,
        ':title'       => $data['title'],
        ':category_id' => $data['category_id'],
        ':description' => $data['description'],
        ':venue'       => $data['venue'],
        ':event_date'  => $data['event_date'],
        ':event_time'  => $data['event_time'],
        ':capacity'    => $data['capacity'],
        ':status'      => $data['status'],
    ];



    if (!empty($data['image'])) {
        $fields[] = 'image = :image';
        $params[':image'] = $data['image'];
    }

    $sql = "
        UPDATE events SET
            " . implode(", ", $fields) . "
        WHERE id = :id
          AND is_deleted = 0
    ";

    return db_execute($sql, $params);
}

/**
 * Delete event (Soft Delete)
 * --------------------------
 */
function event_delete($id)
{
    db_transaction_start();

    try {
        $sql = "UPDATE events 
                SET is_deleted = 1 
                WHERE id = :id";

        if (!db_execute($sql, [':id' => $id])) {
            throw new Exception('Failed to soft delete event');
        }

        db_transaction_commit();
        return true;
    } catch (Exception $e) {
        db_transaction_rollback();
        return false;
    }
}

/**
 * Check if event has seats
 * ------------------------
 */
function event_has_seats($id)
{
    $sql = "SELECT available_seats
            FROM events
            WHERE id = :id
              AND is_deleted = 0";

    $event = db_query_one($sql, [':id' => $id]);

    return ($event && $event['available_seats'] > 0);
}

/**
 * Get Total Event Count
 * ---------------------
 */
function event_count($status = null)
{
    if ($status) {
        $sql = "SELECT COUNT(*) as count
                FROM events
                WHERE status = :status
                  AND is_deleted = 0";

        $result = db_query_one($sql, [':status' => $status]);
    } else {
        $sql = "SELECT COUNT(*) as count
                FROM events
                WHERE is_deleted = 0";

        $result = db_query_one($sql);
    }

    return $result ? $result['count'] : 0;
}

/**
 * Seat Summary
 * ------------
 */
function event_get_seat_summary($eventId)
{
    $sql = "
        SELECT
            COALESCE(SUM(tt.capacity), 0) AS total_capacity,
            COALESCE(
                SUM(tt.capacity) - SUM(COALESCE(r.total_reserved, 0)),
                0
            ) AS available_seats
        FROM ticket_types tt
        INNER JOIN events e ON e.id = tt.event_id
        LEFT JOIN (
            SELECT ticket_type_id, SUM(quantity) AS total_reserved
            FROM registrations
            WHERE status = 'confirmed'
            GROUP BY ticket_type_id
        ) r ON r.ticket_type_id = tt.id
        WHERE tt.event_id = :event_id
          AND e.is_deleted = 0
    ";

    return db_query_one($sql, [':event_id' => $eventId]);
}
