<?php

# Home Controller

// Home Page
function home_index()
{

    $events = event_get_upcoming(6);
    $categories = category_get_all();


    foreach ($events as &$event) {
        $seatSummary = event_get_seat_summary($event['id']);

        $event['total_capacity']  = (int)$seatSummary['total_capacity'];
        $event['available_seats'] = max(0, (int)$seatSummary['available_seats']);
    }
    unset($event);


    $events = event_get_price_range($events);


    view('home/index', [
        'upcomingEvents' => $events,
        'categories'     => $categories
    ]);
}
