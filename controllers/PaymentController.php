<?php

/**
 * Payment Controller
 * ------------------
 * Handles payment before registration
 */

function payment_review($eventId)
{
    if (!is_logged_in()) {
        redirect('/login');
    }

    $quantities = $_POST['quantities'] ?? [];

    if (empty($quantities)) {
        $_SESSION['error'] = 'Please select tickets';
        redirect('/event/details/' . $eventId);
    }

    $items = [];
    $total = 0;

    foreach ($quantities as $ticketTypeId => $qty) {
        $qty = (int)$qty;
        if ($qty <= 0) continue;

        $ticket = ticket_type_get_by_id($ticketTypeId);
        if (!$ticket || $ticket['event_id'] != $eventId) continue;

        $lineTotal = $ticket['price'] * $qty;
        $total += $lineTotal;

        $items[] = [
            'ticket_type_id' => $ticketTypeId,
            'name'           => $ticket['name'],
            'price'          => $ticket['price'],
            'quantity'       => $qty,
            'total'          => $lineTotal
        ];
    }

    if (empty($items)) {
        $_SESSION['error'] = 'Invalid ticket selection';
        redirect('/event/details/' . $eventId);
    }

    // 🔐 SAVE PAYMENT CONTEXT (IMPORTANT)
    $_SESSION['payment_context'] = [
        'event_id' => $eventId,
        'items'    => $items,
        'total'    => $total
    ];

    view('payment/review', [
        'eventId' => $eventId,
        'items'   => $items,
        'total'   => $total
    ]);
}

function payment_form($eventId)
{
    if (!is_logged_in()) {
        redirect('/login');
    }

    if (empty($_SESSION['payment_context'])) {
        $_SESSION['error'] = 'Payment session expired';
        redirect('/event/details/' . $eventId);
    }

    $context = $_SESSION['payment_context'];

    if ($context['total'] == 0) {
        redirect('/payment/process/' . $eventId);
    }



    view('payment/form', [
        'eventId' => $eventId,
        'total'   => $context['total']
    ]);
}

function payment_process($eventId)
{
    if (!is_logged_in()) {
        redirect('/login');
    }

    if (empty($_SESSION['payment_context'])) {
        $_SESSION['error'] = 'Payment session expired';
        redirect('/event/details/' . $eventId);
    }

    // ✅ PAYMENT ASSUMED SUCCESSFUL
    $_SESSION['payment_success'] = true;

    // Rebuild quantities for registration
    $quantities = [];
    foreach ($_SESSION['payment_context']['items'] as $item) {
        $quantities[$item['ticket_type_id']] = $item['quantity'];
    }

    unset($_SESSION['payment_context']);

    // Forward to registration
    $_POST['quantities'] = $quantities;

    registration_register($eventId);
}
