<?php 
// Set page title
$pageTitle = htmlspecialchars($event['title']);
require_once __DIR__ . '/../layout/header.php'; 
?>

<main class="container">
    <!-- Back Button -->
   <a href="/Event/events" class="btn btn-outline mb-3">← Back to Events</a>
    
    <!-- Event Details Card -->
    <div class="card">
        <?php if ($event['category_name']): ?>
            <div style="background: var(--secondary-color); color: white; padding: 1rem; font-size: 1rem; font-weight: 500;">
                <?php echo htmlspecialchars($event['category_name']); ?>
            </div>
        <?php endif; ?>
        
        <!-- Event Banner Image -->
        <?php if ($event['image']): ?>
            <div style="width: 100%; max-height: 400px; overflow: hidden;">
                <img src="/Event/public/uploads/events/<?php echo htmlspecialchars($event['image']); ?>" 
                     alt="<?php echo htmlspecialchars($event['title']); ?>"
                     style="width: 100%; height: auto; object-fit: cover; cursor: pointer; transition: transform 0.3s ease;"
                     onclick="openLightbox(this.src)"
                     onmouseover="this.style.transform='scale(1.02)'"
                     onmouseout="this.style.transform='scale(1)'">
            </div>
        <?php endif; ?>
        
        <div class="card-header">
            <h1><?php echo htmlspecialchars($event['title']); ?></h1>
        </div>
        
        <div class="card-body">
            <!-- Event Information -->
            <h3>Event Details</h3>
            <p><strong>Description:</strong></p>
            <p><?php echo nl2br(htmlspecialchars($event['description'])); ?></p>
            
            <div class="grid grid-2" style="margin-top: 2rem;">
                <div>
                    <p><strong>📍 Venue:</strong> <?php echo htmlspecialchars($event['venue']); ?></p>
                    <p><strong>📅 Date:</strong> <?php echo date('l, F j, Y', strtotime($event['event_date'])); ?></p>
                </div>
                <div>
                    <p><strong>🕐 Time:</strong> <?php echo date('g:i A', strtotime($event['event_time'])); ?></p>
                    <p><strong>Status:</strong> 
                        <span style="text-transform: capitalize; color: var(--secondary-color); font-weight: 500;">
                            <?php echo htmlspecialchars($event['status']); ?>
                        </span>
                    </p>
                    <p><strong>Total Capacity:</strong> <?php echo $event['capacity']; ?> Seats</p>
                </div>
            </div>

            <!-- Detailed Ticket Info Summary -->
            <div style="margin-top: 2rem; background: rgba(51, 65, 85, 0.3); padding: 1.5rem; border-radius: var(--radius-md);">
                <h4 style="margin-top: 0; margin-bottom: 1rem;">Ticket Information</h4>
                <?php if (empty($ticketTypes)): ?>
                    <p class="text-secondary">No ticket information available.</p>
                <?php else: ?>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="text-align: left; border-bottom: 1px solid rgba(255,255,255,0.1);">
                                <th style="padding: 0.5rem;">Ticket Type</th>
                                <th style="padding: 0.5rem;">Price</th>
                                <th style="padding: 0.5rem;">Available</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($ticketTypes as $ticket): ?>
                                <tr style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                                    <td style="padding: 0.75rem 0.5rem;">
                                        <strong><?php echo htmlspecialchars($ticket['name']); ?></strong>
                                    </td>
                                    <td style="padding: 0.75rem 0.5rem;">
                                        Rs. <?php echo number_format($ticket['price'], 2); ?>
                                    </td>
                                    <td style="padding: 0.75rem 0.5rem;">
                                        <?php if ($ticket['available_seats'] <= 0): ?>
                                            <span style="color: var(--danger-color); font-weight: bold;">SOLD OUT</span>
                                        <?php else: ?>
                                            <span style="color: var(--success-color);">
                                                <?php echo $ticket['available_seats']; ?> / <?php echo $ticket['capacity']; ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Ticket Types Section -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <h2>🎫 Select Your Ticket</h2>
        </div>


        
        <div class="card-body">
            <?php if ($isRegistered): ?>
                <!-- Already Registered -->
                <div class="alert alert-success">
                    <p><strong>✓ You are registered for this event!</strong></p>
                    <p>Check your registrations to view your ticket details.</p>
                </div>
                <a href="/Event/my-registrations" class="btn btn-secondary">View My Registrations</a>
            
            <?php elseif (!isset($_SESSION['user_id'])): ?>
                <!-- Not Logged In -->
                <div class="alert" style="background: #fef3c7; color: #92400e; border-left: 4px solid var(--warning-color);">
                    <p><strong>Login Required</strong></p>
                    <p>Please login to register for this event.</p>
                </div>
                <a href="/Event/login" class="btn btn-primary">Login to Register</a>
            
            <?php elseif (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin')): ?>
                <!-- Admin Cannot Register -->
                <div class="alert" style="background: #e0e7ff; color: #3730a3; border-left: 4px solid var(--primary-color);">
                    <p><strong>Admin Access</strong></p>
                    <p>As an administrator, you cannot register for events. You can manage this event from the Admin Panel.</p>
                </div>
                <a href="/Event/admin/events" class="btn btn-outline">Manage Events</a>
            
            <?php else: ?>
                <!-- Show Ticket Types -->
                <?php if (empty($ticketTypes)): ?>
                    <div class="alert alert-error">
                        <p><strong>No Tickets Available</strong></p>
                        <p>This event doesn't have any ticket types configured yet.</p>
                    </div>
                <?php else: ?>

                    <form method="POST" action="/Event/payment/review/<?= $event['id'] ?>" id="ticketForm">
                        <div class="ticket-types-grid">
                            <?php foreach ($ticketTypes as $ticketType): ?>
                                <?php 
                                $isSoldOut = $ticketType['available_seats'] <= 0;
                                $percentAvailable = ($ticketType['capacity'] > 0) ? ($ticketType['available_seats'] / $ticketType['capacity']) * 100 : 0;
                                ?>
                                
                                <div class="ticket-type-card <?php echo $isSoldOut ? 'sold-out' : ''; ?>">
                                    <div class="ticket-card-content">
                                        <div class="ticket-header">
                                            <div class="ticket-name"><?php echo htmlspecialchars($ticketType['name']); ?></div>
                                            <div class="ticket-price">
                                                Rs. <?php echo number_format($ticketType['price'], 2); ?>
                                            </div>
                                        </div>
                                        
                                        <?php if ($ticketType['description']): ?>
                                            <div class="ticket-description">
                                                <?php echo htmlspecialchars($ticketType['description']); ?>
                                            </div>
                                        <?php endif; ?>
                                        
                                        <div class="ticket-footer">
                                            <div class="ticket-availability">
                                                <?php if ($isSoldOut): ?>
                                                    <span class="sold-out-badge">SOLD OUT</span>
                                                <?php else: ?>
                                                    <div class="availability-text">
                                                        <?php echo $ticketType['available_seats']; ?> left
                                                    </div>
                                                <?php endif; ?>
                                            </div>

                                            <?php if (!$isSoldOut): ?>
                                                <div class="quantity-control">
                                                    <label class="qty-label">Qty:</label>
                                                    <input type="number" 
                                                           name="quantities[<?php echo $ticketType['id']; ?>]" 
                                                           class="ticket-qty-input" 
                                                           min="0" 
                                                           max="<?php echo min(10, $ticketType['available_seats']); ?>" 
                                                           value="0"
                                                           data-price="<?php echo $ticketType['price']; ?>">
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <!-- Order Summary -->
                        <div class="order-summary" style="background: rgba(99, 102, 241, 0.1); padding: 1.5rem; border-radius: var(--radius-md); border: 1px solid rgba(99, 102, 241, 0.2); margin-top: 2rem; max-width: 500px; margin-left: auto; margin-right: auto;">
                            <h3 style="text-align: center; margin-top: 0; margin-bottom: 1rem;">Order Summary</h3>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 0.5rem; font-size: 1.1rem;">
                                <span>Total Tickets:</span>
                                <span id="summaryCount">0</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; margin-bottom: 1.5rem; font-size: 1.5rem; font-weight: bold; color: var(--primary-light);">
                                <span>Total Price:</span>
                                <span id="summaryTotal">Rs. 0.00</span>
                            </div>
                            
                            <div style="text-align: center;">
                                <button type="submit" id="registerBtn" class="btn btn-primary" style="padding: 1rem 3rem; font-size: 1.1rem; width: 100%;" disabled>
                                    Checkout
                                </button>
                                <small style="display: block; margin-top: 0.5rem; color: var(--text-secondary);">
                                    Select tickets to proceed
                                </small>
                            </div>
                        </div>
                    </form>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<!-- Ticket Type Selection Styles & Scripts -->
<style>
.ticket-types-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin: 1.5rem 0;
}

.ticket-type-card {
    display: block;
}

.ticket-card-content {
    background: rgba(51, 65, 85, 0.6);
    border: 1px solid rgba(100, 116, 139, 0.3);
    border-radius: var(--radius-lg);
    padding: 1.5rem;
    height: 100%;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.ticket-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.ticket-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-primary);
}

.ticket-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--primary-light);
    white-space: nowrap;
}

.ticket-description {
    font-size: 0.9rem;
    color: var(--text-secondary);
    line-height: 1.4;
    flex-grow: 1;
}

.ticket-footer {
    margin-top: auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
}

.availability-text {
    font-size: 0.9rem;
    color: var(--text-secondary);
}

.quantity-control {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.qty-label {
    font-weight: 600;
    color: var(--text-primary);
}

.ticket-qty-input {
    width: 70px;
    padding: 0.5rem;
    border-radius: var(--radius-sm);
    border: 1px solid rgba(255, 255, 255, 0.2);
    background: rgba(0, 0, 0, 0.2);
    color: white;
    text-align: center;
    font-size: 1.1rem;
    font-weight: bold;
}

.ticket-qty-input:focus {
    outline: none;
    border-color: var(--primary-color);
    background: rgba(0, 0, 0, 0.4);
}

.sold-out-badge {
    background: var(--danger-color);
    color: white;
    padding: 0.4rem 0.8rem;
    border-radius: var(--radius-md);
    font-weight: 600;
    font-size: 0.85rem;
}

.ticket-type-card.sold-out .ticket-card-content {
    opacity: 0.6;
    background: rgba(51, 65, 85, 0.3);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.ticket-qty-input');
    const totalCountSpan = document.getElementById('summaryCount');
    const totalPriceSpan = document.getElementById('summaryTotal');
    const registerBtn = document.getElementById('registerBtn');
    
    function updateTotals() {
        let totalCount = 0;
        let totalPrice = 0;
        
        inputs.forEach(input => {
            const qty = parseInt(input.value) || 0;
            const price = parseFloat(input.dataset.price) || 0;
            
            if (qty > 0) {
                totalCount += qty;
                totalPrice += (qty * price);
                // Highlight active cards
                input.closest('.ticket-card-content').style.borderColor = 'var(--primary-color)';
                input.closest('.ticket-card-content').style.background = 'rgba(99, 102, 241, 0.1)';
            } else {
                input.closest('.ticket-card-content').style.borderColor = 'rgba(100, 116, 139, 0.3)';
                input.closest('.ticket-card-content').style.background = 'rgba(51, 65, 85, 0.6)';
            }
        });
        
        totalCountSpan.textContent = totalCount;
        totalPriceSpan.textContent = 'Rs. ' + totalPrice.toFixed(2); // Assuming simple currency formatting
        
        if (totalCount > 0) {
            registerBtn.disabled = false;
            registerBtn.style.opacity = '1';
        } else {
            registerBtn.disabled = true;
            registerBtn.style.opacity = '0.7';
        }
    }
    
    inputs.forEach(input => {
        input.addEventListener('input', updateTotals);
        input.addEventListener('change', updateTotals); // For spinner clicks
    });
});
</script>

<!-- Lightbox Modal -->
<div id="lightboxModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 2000; justify-content: center; align-items: center; cursor: zoom-out;">
    <div style="position: relative; max-width: 90%; max-height: 90%;">
        <button onclick="closeLightbox(event)" style="position: absolute; top: -40px; right: 0; background: none; border: none; color: white; font-size: 2rem; cursor: pointer;">&times;</button>
        <img id="lightboxImage" src="" alt="Full size banner" style="max-width: 100%; max-height: 90vh; border-radius: var(--radius-md); box-shadow: 0 0 20px rgba(0,0,0,0.5);">
    </div>
</div>

<script>
function openLightbox(src) {
    const modal = document.getElementById('lightboxModal');
    const img = document.getElementById('lightboxImage');
    img.src = src;
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden'; // Prevent scrolling
}

function closeLightbox(event) {
    if (event) event.stopPropagation();
    const modal = document.getElementById('lightboxModal');
    modal.style.display = 'none';
    document.body.style.overflow = ''; // Restore scrolling
}

// Close on background click
document.getElementById('lightboxModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeLightbox();
    }
});

// Close on Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('lightboxModal').style.display === 'flex') {
        closeLightbox();
    }
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>