<?php
/**
 * Event Helper Functions
 * -----------------------
 * Helper functions for event-related utilities
 */

/**
 * Get price range for events with ticket types
 * Returns array with min_price and max_price
 */
function event_get_price_range($events) {
    if (empty($events)) {
        return $events;
    }
    
    // Add price range to each event
    foreach ($events as &$event) {
        $ticketTypes = ticket_type_get_by_event($event['id']);
        
        if (!empty($ticketTypes)) {
            $prices = array_column($ticketTypes, 'price');
            $event['min_price'] = min($prices);
            $event['max_price'] = max($prices);
            $event['has_multiple_tiers'] =  count($ticketTypes) > 1;
        } else {
            // Fallback to event's ticket_price
            $event['min_price'] = $event['ticket_price'];
            $event['max_price'] = $event['ticket_price'];
            $event['has_multiple_tiers'] = false;
        }
    }
    
    return $events;
}

/**
 * Format price display for event listings
 * Shows range if multiple tiers, single price otherwise
 */
function event_format_price_display($event) {
    if (isset($event['has_multiple_tiers']) && $event['has_multiple_tiers']) {
        if ($event['min_price'] == $event['max_price']) {
            return 'Rs. ' . number_format($event['min_price'], 2);
        } else {
            return 'Rs. ' . number_format($event['min_price'], 2) . ' - ' . number_format($event['max_price'], 2);
        }
    }
    
    // Single tier or fallback
    $price = isset($event['min_price']) ? $event['min_price'] : $event['ticket_price'];
    return 'Rs. ' . number_format($price, 2);
}

?>
