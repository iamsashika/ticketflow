<?php

/**
 * Registration Controller
 * -----------------------
 */

function registration_register($eventId)
{
    if (!is_logged_in()) {
        redirect('/user/login');
    }

    // 🔐 PAYMENT GUARD
    if (empty($_SESSION['payment_success'])) {
        $_SESSION['error'] = "Please complete payment before registering.";
        redirect('/event/details/' . $eventId);
    }

    // Single-use payment flag
    unset($_SESSION['payment_success']);

    $user = $_SESSION['user_id'];
    $quantities = $_POST['quantities'] ?? [];

    $itemsToBuy = [];
    $totalTickets = 0;

    foreach ($quantities as $tId => $qty) {
        $qty = (int)$qty;
        if ($qty <= 0) continue;

        $ticketType = ticket_type_get_by_id($tId);
        if ($ticketType && $ticketType['event_id'] == $eventId) {
            $itemsToBuy[] = [
                'ticket_type_id' => $tId,
                'quantity'       => $qty
            ];
            $totalTickets += $qty;
        }
    }

    if (empty($itemsToBuy)) {
        $_SESSION['error'] = "Please select at least one ticket.";
        redirect('/event/details/' . $eventId);
    }

    try {
        $result = registration_create_batch($user, $eventId, $itemsToBuy);

        if ($result) {
            $_SESSION['success'] =
                "Registration successful! You purchased $totalTickets ticket(s).";
            redirect('/my-registrations');
        } else {
            $_SESSION['error'] = "Registration failed.";
            redirect('/event/details/' . $eventId);
        }
    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
        redirect('/event/details/' . $eventId);
    }
}


/**
 * Cancel registration
 * -------------------
 */
function registration_cancel_action($registrationId)
{


    // 1. Check Login
    if (!is_logged_in()) {
        redirect('/user/login');
    }

    // 2. Cancel
    $result = registration_cancel($registrationId);

    if ($result) {
        $_SESSION['success'] = "Registration cancelled successfully.";
    } else {
        $_SESSION['error'] = "Failed to cancel registration.";
    }

    redirect('/my-registrations');
}
