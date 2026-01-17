<?php
// Set page title
$pageTitle = 'Admin Dashboard';
require_once __DIR__ . '/../layout/header.php';
?>

<main class="container">

    <style>
        .table tbody tr:hover {
            background-color: #f5f5f5;
            color: #000000;
        }
    </style>

    <h1 class="mb-3">Admin Dashboard</h1>

    <!-- Statistics Cards -->
    <div class="grid grid-4">

        <!-- Total Events -->
        <a href="/Event/admin/events" style="text-decoration:none;">
            <div class="card">
                <div class="card-body text-center">
                    <h3 style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 0.5rem;">
                        <?= $stats['total_events']; ?>
                    </h3>
                    <p style="color: var(--text-secondary); font-weight: 500;">Total Events</p>
                </div>
            </div>
        </a>

        <!-- Upcoming Events -->
        <a href="/Event/admin/events" style="text-decoration:none;">
            <div class="card">
                <div class="card-body text-center">
                    <h3 style="font-size: 2.5rem; color: var(--secondary-color); margin-bottom: 0.5rem;">
                        <?= $stats['upcoming_events']; ?>
                    </h3>
                    <p style="color: var(--text-secondary); font-weight: 500;">Upcoming Events</p>
                </div>
            </div>
        </a>

        <!-- Total Users -->
        <a href="/Event/admin/users" style="text-decoration:none;">
            <div class="card">
                <div class="card-body text-center">
                    <h3 style="font-size: 2.5rem; color: var(--warning-color); margin-bottom: 0.5rem;">
                        <?= $stats['total_users']; ?>
                    </h3>
                    <p style="color: var(--text-secondary); font-weight: 500;">Total Users</p>
                </div>
            </div>
        </a>

        <!-- Total Registrations -->
        <a href="/Event/admin/registrations" style="text-decoration:none;">
            <div class="card">
                <div class="card-body text-center">
                    <h3 style="font-size: 2.5rem; color: var(--danger-color); margin-bottom: 0.5rem;">
                        <?= $stats['total_registrations']; ?>
                    </h3>
                    <p style="color: var(--text-secondary); font-weight: 500;">Total Registrations</p>
                </div>
            </div>
        </a>

    </div>

    <!-- Quick Actions -->
    <div class="card mt-3">
        <div class="card-header">
            <h2>Quick Actions</h2>
        </div>
        <div class="card-body">
            <div class="grid grid-4">
                <a href="/Event/admin/createEvent" class="btn btn-primary">Create New Event</a>
                <a href="/Event/admin/events" class="btn btn-secondary">Manage Events</a>
                <a href="/Event/admin/registrations" class="btn btn-outline">View Registrations</a>
                <a href="/Event/admin/users" class="btn btn-outline">Manage Users</a>
            </div>
        </div>
    </div>

    <!-- Recent Events -->
    <div class="card mt-3">
        <div class="card-header">
            <h2>Recent Events</h2>
        </div>
        <div class="card-body">
            <?php if (empty($recentEvents)): ?>
                <p>No events created yet.</p>
            <?php else: ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Date</th>
                            <th>Venue</th>
                            <th>Available Seats</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody> <?php foreach ($recentEvents as $event): ?> <tr onclick="window.location.href='/Event/admin/viewEvent/<?= $event['id']; ?>'" style="cursor: pointer;">
                                <td><?= $event['id']; ?></td>
                                <td><?= htmlspecialchars($event['title']); ?></td>
                                <td><?= date('M j, Y', strtotime($event['event_date'])); ?></td>
                                <td><?= htmlspecialchars($event['venue']); ?></td>
                                <td><?= $event['available_seats']; ?> / <?= $event['capacity']; ?></td>
                                <td style="text-transform: capitalize;"> <?= htmlspecialchars($event['status']); ?> </td>
                            </tr> <?php endforeach; ?> </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>