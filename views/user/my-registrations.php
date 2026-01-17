<?php 
// Set page title
$pageTitle = 'My Registrations';
require_once __DIR__ . '/../layout/header.php'; 
?>

<main class="container">
    <h1 class="mb-3">My Event Registrations</h1>
    
    <?php if (empty($registrations)): ?>
        <div class="card">
            <div class="card-body text-center">
                <p>You haven't registered for any events yet.</p>
                <a href="/Event/events" class="btn btn-primary mt-2">Browse Events</a>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-2">
            <?php foreach ($registrations as $registration): ?>
                <!-- Registration Card -->
                <div class="card">
                    <?php if ($registration['category_name']): ?>
                        <div style="background: var(--secondary-color); color: white; padding: 0.5rem; font-size: 0.875rem; font-weight: 500;">
                            <?php echo htmlspecialchars($registration['category_name']); ?>
                        </div>
                    <?php endif; ?>
                    
                    <div class="card-header">
                        <?php echo htmlspecialchars($registration['event_title']); ?>
                    </div>
                    <div class="card-body">
                        <p><strong>Ticket Number:</strong> <code><?php echo htmlspecialchars($registration['ticket_number']); ?></code></p>
                        
                        <?php if (isset($registration['ticket_type_name'])): ?>
                            <p><strong>Ticket Type:</strong> 
                                <span class="ticket-type-badge">
                                    <?php echo htmlspecialchars($registration['ticket_type_name']); ?>
                                </span>
                            </p>
                        <?php endif; ?>
                        
                        <p><strong>Event Date:</strong> <?php echo date('F j, Y', strtotime($registration['event_date'])); ?></p>
                        <p><strong>Event Time:</strong> <?php echo date('g:i A', strtotime($registration['event_time'])); ?></p>
                        <p><strong>Venue:</strong> <?php echo htmlspecialchars($registration['venue']); ?></p>
                        <p><strong>Ticket Price:</strong> Rs. <?php echo number_format($registration['ticket_price'], 2); ?></p>
                        <p><strong>Quantity:</strong> <?php echo $registration['quantity'] ?? 1; ?> Tickets</p>
                        <p><strong>Total Paid:</strong> Rs. <?php echo number_format($registration['ticket_price'] * ($registration['quantity'] ?? 1), 2); ?></p>
                        <p><strong>Registered On:</strong> <?php echo date('F j, Y', strtotime($registration['registration_date'])); ?></p>
                        
                        <p><strong>Status:</strong> 
                            <span style="text-transform: capitalize; color: <?php echo $registration['status'] == 'confirmed' ? 'green' : 'red'; ?>;">
                                <?php echo htmlspecialchars($registration['status']); ?>
                            </span>
                        </p>
                        
                        <div class="mt-2">
                            <a href="/Event/event/details/<?php echo $registration['event_id']; ?>" 
                               class="btn btn-outline">View Event</a>
                            
                            <?php if ($registration['status'] == 'confirmed'): ?>
                                <a href="/Event/registration/cancel/<?php echo $registration['id']; ?>" 
                                   class="btn btn-danger">
                                    Cancel Registration
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<style>
code {
    background: var(--light-color);
    padding: 0.25rem 0.5rem;
    border-radius: var(--radius-sm);
    font-family: 'Courier New', monospace;
    font-size: 0.9rem;
}

.ticket-type-badge {
    display: inline-block;
    background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.4);
}
</style>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
