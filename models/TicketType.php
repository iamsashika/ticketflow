<?php

# Ticket Type Model

// Create Ticket Type
function ticket_type_create($event_id, $data)
{
    $sql = "INSERT INTO ticket_types (event_id, name, description, price, capacity, available_seats, display_order) 
            VALUES (:event_id, :name, :description, :price, :capacity, :available_seats, :display_order)";

    $params = [];
    $params[':event_id'] = $event_id;
    $params[':name'] = $data['name'];
    $params[':description'] = $data['description'] ?? '';
    $params[':price'] = $data['price'];
    $params[':capacity'] = $data['capacity'];
    $params[':available_seats'] = $data['capacity']; // Initially all seats available
    $params[':display_order'] = $data['display_order'] ?? 0;

    return db_execute($sql, $params);
}


// Get all ticket types for an event
function ticket_type_get_by_event($event_id)
{
    $sql = "SELECT * FROM ticket_types 
            WHERE event_id = :event_id 
            ORDER BY display_order ASC, price DESC";

    $params = [':event_id' => $event_id];

    return db_query_all($sql, $params);
}

// Get ticket type by ID
function ticket_type_get_by_id($id)
{
    $sql = "SELECT * FROM ticket_types WHERE id = :id";

    $params = [':id' => $id];

    return db_query_one($sql, $params);
}

// Update ticket type
function ticket_type_update($id, $data)
{
    $sql = "UPDATE ticket_types SET 
            name = :name,
            description = :description,
            price = :price,
            capacity = :capacity,
            display_order = :display_order
            WHERE id = :id";

    $params = [];
    $params[':id'] = $id;
    $params[':name'] = $data['name'];
    $params[':description'] = $data['description'] ?? '';
    $params[':price'] = $data['price'];
    $params[':capacity'] = $data['capacity'];
    $params[':display_order'] = $data['display_order'] ?? 0;

    return db_execute($sql, $params);
}

// Delete ticket type
function ticket_type_delete($id)
{
    // Check if any registrations exist
    $check_sql = "SELECT COUNT(*) as count FROM registrations WHERE ticket_type_id = :id";
    $result = db_query_one($check_sql, [':id' => $id]);

    if ($result && $result['count'] > 0) {
        return false; // Cannot delete ticket type with existing registrations
    }

    $sql = "DELETE FROM ticket_types WHERE id = :id";

    return db_execute($sql, [':id' => $id]);
}


// Check if ticket type has available seats
function ticket_type_has_seats($id)
{
    $sql = "SELECT available_seats FROM ticket_types WHERE id = :id";

    $ticket_type = db_query_one($sql, [':id' => $id]);

    return ($ticket_type && $ticket_type['available_seats'] > 0);
}


// Decrease available seats for ticket type
function ticket_type_decrease_seats($id, $quantity = 1)
{
    $sql = "UPDATE ticket_types 
            SET available_seats = available_seats - :quantity 
            WHERE id = :id AND available_seats >= :quantity";

    return db_execute($sql, [':id' => $id, ':quantity' => $quantity]);
}

// Increase available seats for ticket type
function ticket_type_increase_seats($id, $quantity = 1)
{
    $sql = "UPDATE ticket_types 
            SET available_seats = available_seats + :quantity 
            WHERE id = :id";

    return db_execute($sql, [':id' => $id, ':quantity' => $quantity]);
}

// Get number of bookings for a ticket type
function ticket_type_get_bookings($id)
{
    $sql = "SELECT COUNT(*) as count 
            FROM registrations 
            WHERE ticket_type_id = :id AND status = 'confirmed'";

    $result = db_query_one($sql, [':id' => $id]);

    return $result ? $result['count'] : 0;
}


// Create multiple ticket types for an event
function ticket_type_create_bulk($event_id, $ticket_types)
{
    if (empty($ticket_types)) {
        return false;
    }

    db_transaction_start();

    try {
        foreach ($ticket_types as $ticket_type) {
            if (!ticket_type_create($event_id, $ticket_type)) {
                throw new Exception("Failed to create ticket type");
            }
        }

        db_transaction_commit();
        return true;
    } catch (Exception $e) {
        db_transaction_rollback();
        return false;
    }
}

function ticket_types_with_availability($eventId)
{
    $sql = "
        SELECT 
            tt.*,
            (tt.capacity - COALESCE(r.total_reserved, 0)) AS available_seats
        FROM ticket_types tt
        LEFT JOIN (
            SELECT ticket_type_id, SUM(quantity) AS total_reserved
            FROM registrations
            WHERE status = 'confirmed'
            GROUP BY ticket_type_id
        ) r ON r.ticket_type_id = tt.id
        WHERE tt.event_id = :event_id
        ORDER BY tt.display_order ASC
    ";

    return db_query_all($sql, [':event_id' => $eventId]);
}
