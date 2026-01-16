<?php

# Event Controller


// List Events
function event_index()
{

    $filters = [];

    if (!empty($_GET['category'])) {
        $filters['category'] = $_GET['category'];
    }

    if (!empty($_GET['search'])) {
        $filters['search'] = trim($_GET['search']);
    }


    $events = event_get_all($filters);
    $categories = category_get_all();


    foreach ($events as &$event) {
        $seatSummary = event_get_seat_summary($event['id']);

        $event['total_capacity']  = (int)$seatSummary['total_capacity'];
        $event['available_seats'] = max(0, (int)$seatSummary['available_seats']);
    }
    unset($event);


    $events = event_get_price_range($events);

    // 5. View
    view('event/index', [
        'events'     => $events,
        'categories' => $categories,
        'filters'    => $filters
    ]);
}


// Event Details
function event_details($id)
{
    // 1. Event Info
    $event = event_get_by_id($id);

    if (!$event) {
        redirect('/event');
    }

    /**
     * 2. FIX: Use ticket types WITH CALCULATED AVAILABILITY
     * ------------------------------------------------------
     * NEVER use ticket_type_get_by_event()
     */
    $ticketTypes = ticket_types_with_availability($id);

    /**
     * 3. Add Event Seat Summary
     */
    $seatSummary = event_get_seat_summary($id);
    $event['total_capacity']  = (int)$seatSummary['total_capacity'];
    $event['available_seats'] = max(0, (int)$seatSummary['available_seats']);

    // 4. User Registration Status
    $isRegistered = false;
    if (is_logged_in()) {
        $isRegistered = registration_check($_SESSION['user_id'], $id);
    }

    // 5. View
    view('event/details', [
        'event'        => $event,
        'ticketTypes'  => $ticketTypes,
        'isRegistered' => $isRegistered
    ]);
}

// Event Payment Redirect
function event_pay($id)
{
    // Check Login
    if (!is_logged_in()) {
        redirect('/login');
        return;
    }

    // Ensure event exists
    $event = event_get_by_id($id);
    if (!$event) {
        redirect('/event');
        return;
    }

    // Redirect to payment form
    redirect('/payment/form/' . $id);
}
