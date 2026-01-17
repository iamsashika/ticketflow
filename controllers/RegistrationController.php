<?php

# Registration Controller


// Register for Event
function registration_register($eventId)
{

    if (!is_logged_in()) {
        redirect('/user/login');
    }


    if (empty($_SESSION['payment_success'])) {
        $_SESSION['error'] = "Please complete payment before registering.";
        redirect('/event/details/' . $eventId);
    }


    unset($_SESSION['payment_success']);

    $userId     = $_SESSION['user_id'];
    $quantities = $_POST['quantities'] ?? [];

    $itemsToBuy   = [];
    $totalTickets = 0;

    foreach ($quantities as $ticketTypeId => $qty) {
        $qty = (int)$qty;

        // Skip empty quantities
        if ($qty <= 0) {
            continue;
        }

        // Fetch ticket type
        $ticketType = ticket_type_get_by_id($ticketTypeId);

        // Validate ticket belongs to event
        if (!$ticketType || (int)$ticketType['event_id'] !== (int)$eventId) {
            continue;
        }


        $availableSeats = (int)$ticketType['available_seats'];

        if ($qty > $availableSeats) {
            $_SESSION['error'] =
                "Only {$availableSeats} seat(s) available for '{$ticketType['name']}'. 
                 Please adjust your selection.";

            redirect('/event/details/' . $eventId);
        }

        $itemsToBuy[] = [
            'ticket_type_id' => $ticketTypeId,
            'quantity'       => $qty
        ];

        $totalTickets += $qty;
    }

    // Ensure at least one ticket selected
    if (empty($itemsToBuy)) {
        $_SESSION['error'] = "Please select at least one ticket.";
        redirect('/event/details/' . $eventId);
    }

    // Create registrations
    try {
        $result = registration_create_batch($userId, $eventId, $itemsToBuy);

        if ($result) {
            $_SESSION['success'] =
                "Registration successful! You purchased {$totalTickets} ticket(s).";
            redirect('/my-registrations');
        } else {
            $_SESSION['error'] = "Registration failed. Please try again.";
            redirect('/event/details/' . $eventId);
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        redirect('/event/details/' . $eventId);
    }
}


// Cancel Registration
function registration_cancel_action($registrationId)
{



    if (!is_logged_in()) {
        redirect('/user/login');
    }


    $result = registration_cancel($registrationId);

    if ($result) {
        $_SESSION['success'] = "Registration cancelled successfully.";
    } else {
        $_SESSION['error'] = "Failed to cancel registration.";
    }

    redirect('/my-registrations');
}
