<?php
// Set page title
$pageTitle = 'All Events';
require_once __DIR__ . '/../layout/header.php';
?>

<main class="container">
    <h1 class="mb-3">Browse Events</h1>

    <!-- Filter Section -->
    <div class="card mb-3">
        <div class="card-body">
            <form method="GET" action="/Event/events">
                <div class="grid grid-3">
                    <!-- Category Filter -->
                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <select name="category" class="form-select">
                            <option value="">All Categories</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"
                                    <?php echo (isset($filters['category']) && $filters['category'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Search -->
                    <div class="form-group">
                        <label class="form-label">Search</label>
                        <input type="text" name="search" class="form-input"
                            placeholder="Search by title.."
                            value="<?php echo isset($filters['search']) ? htmlspecialchars($filters['search']) : ''; ?>">
                    </div>

                    <!-- Submit Button -->
                    <div class="form-group" style="display: flex; align-items: flex-end; gap: 0.5rem;">
                        <button type="submit" class="btn btn-primary" style="flex: 1; height: 48px;">Filter</button>
                        <a href="/Event/events" class="btn btn-outline" style="flex: 1; height: 48px; display: flex; align-items: center; justify-content: center; text-decoration: none;">Clear</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Events List -->
    <?php if (empty($events)): ?>
        <div class="card">
            <div class="card-body text-center">
                <p>No events found matching your criteria.</p>
                <a href="/Event/events" class="btn btn-primary mt-2">Clear Filters</a>
            </div>
        </div>
    <?php else: ?>
        <div class="grid grid-3">
            <?php foreach ($events as $event): ?>
                <!-- Event Card -->
                <div class="card event-card">
                    <!-- Event Banner or Placeholder -->
                    <?php if (!empty($event['image'])): ?>
                        <div class="event-card-image">
                            <img src="/Event/public/uploads/events/<?php echo htmlspecialchars($event['image']); ?>"
                                alt="<?php echo htmlspecialchars($event['title']); ?>">
                            <!-- Category Badge Overlay -->
                            <?php if ($event['category_name']): ?>
                                <div class="category-badge">
                                    <?php echo htmlspecialchars($event['category_name']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="event-card-placeholder">
                            <span class="placeholder-text">📅</span>
                            <!-- Category Badge Overlay -->
                            <?php if ($event['category_name']): ?>
                                <div class="category-badge">
                                    <?php echo htmlspecialchars($event['category_name']); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div class="event-card-content">
                        <div class="card-header">
                            <?php echo htmlspecialchars($event['title']); ?>
                        </div>
                        <div class="card-body">
                            <p><?php echo htmlspecialchars(substr($event['description'], 0, 100)); ?>...</p>
                            <p><strong>Date:</strong> <?php echo date('F j, Y', strtotime($event['event_date'])); ?></p>
                            <p><strong>Time:</strong> <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                            <p><strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                            <p><strong>Price:</strong> Rs. <?php echo number_format($event['ticket_price'], 2); ?></p>

                            <?php if ($event['available_seats'] > 0): ?>
                                <p><strong>Available:</strong> <?php echo $event['available_seats']; ?> seats</p>
                                <a href="/Event/event/details/<?php echo $event['id']; ?>"
                                    class="btn btn-primary mt-2">View Details</a>
                            <?php else: ?>
                                <p style="color: var(--danger-color); font-weight: 500;">Fully Booked</p>
                                <a href="/Event/event/details/<?php echo $event['id']; ?>"
                                    class="btn btn-outline mt-2">View Details</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<style>
    /* Event Card Styles - Same as home page */
    .event-card {
        padding: 0 !important;
        overflow: hidden;
    }

    .event-card-image {
        width: 100%;
        height: 200px;
        overflow: hidden;
        position: relative;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
    }

    .event-card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .event-card:hover .event-card-image img {
        transform: scale(1.05);
    }

    .event-card-placeholder {
        width: 100%;
        height: 200px;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
    }

    .placeholder-text {
        font-size: 4rem;
        opacity: 0.7;
    }

    .event-card-content {
        padding: var(--spacing-lg);
    }

    .event-card-content .card-header {
        margin-bottom: var(--spacing-md);
    }

    .event-card-content .card-body p {
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    /* Category Badge Overlay */
    .category-badge {
        position: absolute;
        top: 0.75rem;
        left: 0.75rem;
        background: rgba(16, 185, 129, 0.95);
        backdrop-filter: blur(10px);
        color: white;
        padding: 0.5rem 1rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: var(--radius-md);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>