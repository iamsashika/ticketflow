<?php 
// Set page title
$pageTitle = 'Home';
require_once __DIR__ . '/../layout/header.php'; 
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">TicketFlow</h1>
            <p class="hero-subtitle">Discover and register for amazing events in Sri Lanka</p>
            <a href="/Event/events" class="btn btn-primary btn-hero">Browse Events</a>
        </div>
    </div>
</section>

<!-- Upcoming Events Section -->
<main class="container">
    <div class="section-header">
        <h2 class="text-center mb-3">Upcoming Events</h2>
        <p class="section-description">Don't miss out on these exciting events happening soon!</p>
    </div>
    
    <?php if (empty($upcomingEvents)): ?>
        <div class="empty-state">
            <p class="text-center">No upcoming events at the moment. Check back later!</p>
        </div>
    <?php else: ?>
        <div class="grid grid-3">
            <?php foreach ($upcomingEvents as $event): ?>
                <!-- Event Card -->
                <div class="card event-card">
                    <?php if (!empty($event['image'])): ?>
                        <div class="event-card-image">
                            <img src="/Event/public/uploads/events/<?php echo htmlspecialchars($event['image']); ?>" 
                                 alt="<?php echo htmlspecialchars($event['title']); ?>">
                            <!-- Category Badge -->
                            <?php if (!empty($event['category_name'])): ?>
                                <div class="category-badge">
                                    <?php echo htmlspecialchars($event['category_name']); ?>
                                </div>
                            <?php endif; ?>
                            <!-- Countdown Timer -->
                            <div class="countdown-timer" data-endtime="<?php echo $event['event_date'] . ' ' . $event['event_time']; ?>"></div>
                        </div>
                    <?php else: ?>
                        <div class="event-card-placeholder">
                            <span class="placeholder-text">📅</span>
                            <!-- Category Badge -->
                            <?php if (!empty($event['category_name'])): ?>
                                <div class="category-badge">
                                    <?php echo htmlspecialchars($event['category_name']); ?>
                                </div>
                            <?php endif; ?>
                            <!-- Countdown Timer -->
                            <div class="countdown-timer" data-endtime="<?php echo $event['event_date'] . ' ' . $event['event_time']; ?>"></div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="event-card-content">
                        <div class="card-header">
                            <?php echo htmlspecialchars($event['title']); ?>
                        </div>
                        <div class="card-body">
                            <p class="event-info"><span class="info-icon">📅</span> <strong>Date:</strong> <?php echo date('F j, Y', strtotime($event['event_date'])); ?></p>
                            <p class="event-info"><span class="info-icon">🕐</span> <strong>Time:</strong> <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                            <p class="event-info"><span class="info-icon">📍</span> <strong>Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                            <p class="event-info"><span class="info-icon">💰</span> <strong>Price:</strong> <?php echo event_format_price_display($event); ?></p>
                            <p class="event-info"><span class="info-icon">🎫</span> <strong>Available Seats:</strong> <?php echo $event['available_seats']; ?> / <?php echo $event['capacity']; ?></p>
                            
                            <a href="/Event/event/details/<?php echo $event['id']; ?>" 
                               class="btn btn-primary mt-2">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="text-center mt-4">
            <a href="/Event/events" class="btn btn-outline btn-view-all">View All Events →</a>
        </div>
    <?php endif; ?>
</main>

<!-- Enhanced Styling -->
<style>
/* Hero Section with Animated Gradient */
.hero {
    background: linear-gradient(-45deg, #6366f1, #8b5cf6, #6366f1, #a855f7);
    background-size: 400% 400%;
    animation: gradientShift 15s ease infinite;
    color: var(--white);
    padding: 5rem 0;
    margin-bottom: 3rem;
    position: relative;
    overflow: hidden;
}

@keyframes gradientShift {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

.hero::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at 20% 50%, rgba(255,255,255,0.1) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(255,255,255,0.1) 0%, transparent 50%);
    pointer-events: none;
}

.hero-content {
    text-align: center;
    position: relative;
    z-index: 1;
    animation: fadeInUp 1s ease-out;
}

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.hero-title {
    font-size: 3.5rem;
    margin-bottom: 1rem;
    font-weight: 700;
    text-shadow: 0 4px 20px rgba(0,0,0,0.3);
    letter-spacing: -0.5px;
}

.hero-subtitle {
    font-size: 1.35rem;
    margin-bottom: 2rem;
    opacity: 0.95;
    font-weight: 300;
    animation: fadeInUp 1s ease-out 0.2s both;
}

.btn-hero {
    padding: 0.875rem 2.5rem;
    font-size: 1.1rem;
    font-weight: 600;
    box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    animation: fadeInUp 1s ease-out 0.4s both;
    transition: all 0.3s ease;
}

.btn-hero:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(0,0,0,0.4);
    color: #67e8f9; /* Bright cyan for visibility */
}

/* Section Header */
.section-header {
    margin-bottom: 2.5rem;
}

.section-header h2 {
    font-size: 2.25rem;
    margin-bottom: 0.5rem;
    font-weight: 700;
}

.section-description {
    text-align: center;
    font-size: 1.1rem;
    color: var(--text-muted);
    margin-bottom: 0;
}

/* Event Card Styles */
.event-card {
    padding: 0 !important;
    overflow: hidden;
    transition: all 0.3s ease;
    border: 1px solid rgba(255, 255, 255, 0.1);
}

.event-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(99, 102, 241, 0.3);
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
    transition: transform 0.5s ease;
}

.event-card:hover .event-card-image img {
    transform: scale(1.1);
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

/* Category Badge */
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

.event-card-content {
    padding: var(--spacing-lg);
}

.event-card-content .card-header {
    margin-bottom: var(--spacing-md);
    font-size: 1.25rem;
    font-weight: 600;
}

.event-card-content .card-body {
    display: flex;
    flex-direction: column;
    gap: 0.6rem;
}

.event-info {
    margin-bottom: 0;
    font-size: 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-icon {
    font-size: 1.1rem;
    flex-shrink: 0;
}

/* View All Button */
.btn-view-all {
    padding: 0.875rem 2rem;
    font-size: 1.05rem;
    font-weight: 600;
    transition: all 0.3s ease;
}

.btn-view-all:hover {
    transform: translateX(5px);
    border-color: var(--primary-color);
    color: var(--primary-color);
}

/* Empty State */
.empty-state {
    padding: 3rem;
    text-align: center;
    color: var(--text-muted);
    font-size: 1.1rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .hero-title {
        font-size: 2.5rem;
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
    }
    
    .section-header h2 {
        font-size: 1.75rem;
    }
}

/* Countdown Timer */
.countdown-timer {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    background: rgba(0, 0, 0, 0.7);
    color: white;
    text-align: center;
    padding: 0.5rem;
    font-weight: 600;
    font-size: 0.9rem;
    backdrop-filter: blur(4px);
    border-top: 1px solid rgba(255,255,255,0.1);
    z-index: 2;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function updateTimers() {
        const timers = document.querySelectorAll('.countdown-timer');
        const now = new Date().getTime();
        
        timers.forEach(timer => {
            const end = new Date(timer.dataset.endtime).getTime();
            const diff = end - now;
            
            if (diff < 0) {
                timer.innerHTML = "Event Started";
                timer.style.background = "rgba(16, 185, 129, 0.9)"; // Green
                return;
            }
            
            const days = Math.floor(diff / (1000 * 60 * 60 * 24));
            const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((diff % (1000 * 60)) / 1000);
            
            timer.innerHTML = `Starts in: ${days}d ${hours}h ${minutes}m ${seconds}s`;
        });
    }
    
    updateTimers(); // Run once immediately
    setInterval(updateTimers, 1000);
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
