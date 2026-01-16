<?php 
// Set page title
$pageTitle = 'Manage Events - Admin';
require_once __DIR__ . '/../layout/header.php'; 
?>

<main class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h1>Manage Events</h1>
        <div style="display: flex; gap: 10px;">
            <a href="/Event/admin/categories" class="btn btn-outline">Manage Categories</a>
            <a href="/Event/admin/createEvent" class="btn btn-primary">+ Create New Event</a>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="/Event/admin/events" style="display: flex; gap: 1rem;">
                <input type="text" name="search" class="form-input" 
                       placeholder="Search events by title or description..." 
                       value="<?php echo isset($search) ? htmlspecialchars($search) : ''; ?>"
                       style="margin-bottom: 0;">
                <button type="submit" class="btn btn-secondary">Search</button>
                <?php if (isset($search) && $search): ?>
                    <a href="/Event/admin/events" class="btn btn-outline">Clear</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <?php if (empty($events)): ?>
        <div class="card">
            <div class="card-body text-center">
                <p>No events found.</p>
                <a href="/Event/admin/createEvent" class="btn btn-primary mt-2">Create Your First Event</a>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Date & Time</th>
                        <th>Venue</th>
                        <th>Price</th>
                        <th>Seats</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($events as $event): ?>
                        <tr>
                            <td><?php echo $event['id']; ?></td>
                            <td><?php echo htmlspecialchars($event['title']); ?></td>
                            <td><?php echo htmlspecialchars($event['category_name'] ?? 'N/A'); ?></td>
                            <td>
                                <?php echo date('M j, Y', strtotime($event['event_date'])); ?><br>
                                <small><?php echo date('g:i A', strtotime($event['event_time'])); ?></small>
                            </td>
                            <td><?php echo htmlspecialchars($event['venue']); ?></td>
                            <td>Rs. <?php echo number_format($event['ticket_price'], 2); ?></td>
                            <td><?php echo $event['available_seats']; ?> / <?php echo $event['capacity']; ?></td>
                            <td>
                                <span style="text-transform: capitalize; 
                                             padding: 0.25rem 0.5rem; 
                                             border-radius: var(--radius-sm); 
                                             font-size: 0.875rem;
                                             background: <?php echo $event['status'] === 'upcoming' ? '#d1fae5' : ($event['status'] === 'completed' ? '#e5e7eb' : '#fee2e2'); ?>;
                                             color: <?php echo $event['status'] === 'upcoming' ? '#065f46' : ($event['status'] === 'completed' ? '#1f2937' : '#991b1b'); ?>;">
                                    <?php echo htmlspecialchars($event['status']); ?>
                                </span>
                            </td>
                            <td>
                                <a href="/Event/admin/viewEvent/<?php echo $event['id']; ?>" 
                                   class="btn btn-outline" style="font-size: 0.875rem; padding: 0.25rem 0.5rem;">View</a>
                                <a href="/Event/admin/editEvent/<?php echo $event['id']; ?>" 
                                   class="btn btn-secondary" style="font-size: 0.875rem; padding: 0.25rem 0.5rem;">Edit</a>
                                <a href="/Event/admin/deleteEvent/<?php echo $event['id']; ?>" 
                                   class="btn btn-danger" 
                                   style="font-size: 0.875rem; padding: 0.25rem 0.5rem;"
                                   onclick="return confirm('Are you sure you want to delete this event? This will also delete all registrations for this event.');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
