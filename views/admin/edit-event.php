<?php
// Set page title
$pageTitle = 'Edit Event - Admin';
require_once __DIR__ . '/../layout/header.php';
?>

<main class="container">
    <h1 class="mb-3">Edit Event</h1>

    <div style="max-width: 800px; margin: 0 auto;">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="/Event/admin/editEvent/<?php echo $event['id']; ?>" enctype="multipart/form-data">
                    <!-- Category -->
                    <div class="form-group">
                        <label class="form-label">Category *</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">Select a category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['id']; ?>"
                                    <?php echo ($event['category_id'] == $category['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Title -->
                    <div class="form-group">
                        <label class="form-label">Event Title *</label>
                        <input type="text" name="title" class="form-input" required
                            placeholder="Enter event title"
                            value="<?php echo htmlspecialchars($event['title']); ?>">
                    </div>

                    <!-- Description -->
                    <div class="form-group">
                        <label class="form-label">Description *</label>
                        <textarea name="description" class="form-textarea" required
                            placeholder="Enter event description"><?php echo htmlspecialchars($event['description']); ?></textarea>
                    </div>

                    <!-- Venue -->
                    <div class="form-group">
                        <label class="form-label">Venue *</label>
                        <input type="text" name="venue" class="form-input" required
                            placeholder="Enter event venue"
                            value="<?php echo htmlspecialchars($event['venue']); ?>">
                    </div>

                    <div class="grid grid-2">
                        <!-- Event Date -->
                        <div class="form-group">
                            <label class="form-label">Event Date *</label>
                            <input type="date" min="<?= date('Y-m-d') ?>" name="event_date" class="form-input" required
                                value="<?php echo $event['event_date']; ?>">
                        </div>

                        <!-- Event Time -->
                        <div class="form-group">
                            <label class="form-label">Event Time *</label>
                            <input type="time" name="event_time" class="form-input" required
                                value="<?php echo $event['event_time']; ?>">
                        </div>
                    </div>

                    <div class="grid grid-2">
                        <!-- Hidden fields for legacy support (Auto-calculated) -->
                        <input type="hidden" name="ticket_price" value="<?php echo $event['ticket_price']; ?>">
                        <input type="hidden" name="capacity" value="<?php echo $event['capacity']; ?>">

                        <!-- Event Summary Display -->

                        <?php if (false) { ?>
                            <div class="form-group" style="background: rgba(99, 102, 241, 0.1); padding: 1rem; border-radius: var(--radius-md); border: 1px solid rgba(99, 102, 241, 0.2); grid-column: span 2;">
                                <label class="form-label" style="color: var(--primary-light); margin-bottom: 0.5rem;">Event Summary (Auto-calculated)</label>
                                <div style="display: flex; gap: 2rem;">
                                    <div>
                                        <span style="font-size: 0.85rem; color: var(--text-secondary); display: block;">Total Capacity</span>
                                        <span id="displayTotalCapacity" style="font-size: 1.25rem; font-weight: bold; color: white;">
                                            <?php echo $event['capacity']; ?> seats
                                        </span>
                                    </div>
                                    <div>
                                        <span style="font-size: 0.85rem; color: var(--text-secondary); display: block;">Starting Price</span>
                                        <span id="displayMinPrice" style="font-size: 1.25rem; font-weight: bold; color: white;">
                                            Rs. <?php echo number_format($event['ticket_price'], 2); ?>
                                        </span>
                                    </div>
                                </div>
                                <small style="display: block; margin-top: 0.5rem; color: var(--text-secondary); font-size: 0.8rem;">
                                    * Values are automatically calculated from your Ticket Tiers below.
                                </small>
                            </div>
                        <?php } ?>
                    </div>

                    <!-- TICKET TIERS SECTION -->
                    <?php if (false) { ?>
                        <div class="form-group" style="margin-top: 2rem; padding-top: 2rem; border-top: 2px solid rgba(99, 102, 241, 0.2);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                <div>
                                    <label class="form-label" style="margin: 0;">🎫 Ticket Tiers *</label>
                                    <small style="color: var(--text-secondary); display: block; margin-top: 0.25rem;">
                                        Manage ticket types. Removing a tier will delete it from the system.
                                    </small>
                                </div>
                                <button type="button" id="addTierBtn" class="btn btn-secondary" style="padding: 0.5rem 1rem;">
                                    + Add Tier
                                </button>
                            </div>



                            <div id="ticketTiersContainer">
                                <!-- Existing Tiers -->
                                <?php if (!empty($ticketTypes)): ?>
                                    <?php foreach ($ticketTypes as $index => $tier): ?>
                                        <div class="ticket-tier-row" data-tier-index="<?php echo $index; ?>">
                                            <input type="hidden" name="tier_id[]" value="<?php echo $tier['id']; ?>">
                                            <div style="background: rgba(51, 65, 85, 0.5); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; border: 1px solid rgba(100, 116, 139, 0.3);">
                                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                                    <strong style="color: var(--primary-light);">Tier #<?php echo $index + 1; ?></strong>
                                                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                                                        <span style="font-size: 0.8rem; color: var(--text-secondary);">
                                                            (<?php echo $tier['available_seats']; ?> avl / <?php echo $tier['capacity']; ?> cap)
                                                        </span>
                                                        <button type="button" class="remove-tier-btn" style="background: var(--danger-color); color: white; border: none; padding: 0.4rem 0.8rem; border-radius: var(--radius-sm); cursor: pointer; font-size: 0.875rem;">
                                                            Remove
                                                        </button>
                                                    </div>
                                                </div>

                                                <div class="grid grid-2" style="gap: 1rem;">
                                                    <div class="form-group" style="margin: 0;">
                                                        <label class="form-label">Tier Name *</label>
                                                        <input type="text" name="tier_name[]" class="form-input" required
                                                            placeholder="e.g., VIP, Gold" value="<?php echo htmlspecialchars($tier['name']); ?>">
                                                    </div>

                                                    <div class="form-group" style="margin: 0;">
                                                        <label class="form-label">Display Order</label>
                                                        <input type="number" name="tier_order[]" class="form-input"
                                                            min="0" placeholder="0" value="<?php echo $tier['display_order']; ?>">
                                                    </div>
                                                </div>

                                                <div class="form-group" style="margin-top: 1rem; margin-bottom: 1rem;">
                                                    <label class="form-label">Description (Optional)</label>
                                                    <textarea name="tier_description[]" class="form-textarea" rows="2"
                                                        placeholder="Desc"><?php echo htmlspecialchars($tier['description']); ?></textarea>
                                                </div>

                                                <div class="grid grid-2" style="gap: 1rem;">
                                                    <div class="form-group" style="margin: 0;">
                                                        <label class="form-label">Price (Rs.) *</label>
                                                        <input type="number" name="tier_price[]" class="form-input" required
                                                            min="0" step="0.01" placeholder="0.00" value="<?php echo $tier['price']; ?>">
                                                    </div>

                                                    <div class="form-group" style="margin: 0;">
                                                        <label class="form-label">Capacity *</label>
                                                        <input type="number" name="tier_capacity[]" class="form-input" required
                                                            min="1" placeholder="Seats" value="<?php echo $tier['capacity']; ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <!-- Default empty tier if none exist -->
                                    <div class="ticket-tier-row" data-tier-index="0">
                                        <input type="hidden" name="tier_id[]" value="">
                                        <div style="background: rgba(51, 65, 85, 0.5); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; border: 1px solid rgba(100, 116, 139, 0.3);">
                                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                                                <strong style="color: var(--primary-light);">Tier #1</strong>
                                                <button type="button" class="remove-tier-btn" style="background: var(--danger-color); color: white; border: none; padding: 0.4rem 0.8rem; border-radius: var(--radius-sm); cursor: pointer; font-size: 0.875rem;">
                                                    Remove
                                                </button>
                                            </div>

                                            <div class="grid grid-2" style="gap: 1rem;">
                                                <div class="form-group" style="margin: 0;">
                                                    <label class="form-label">Tier Name *</label>
                                                    <input type="text" name="tier_name[]" class="form-input" required
                                                        placeholder="e.g., General Admission" value="General Admission">
                                                </div>

                                                <div class="form-group" style="margin: 0;">
                                                    <label class="form-label">Display Order</label>
                                                    <input type="number" name="tier_order[]" class="form-input"
                                                        min="0" value="0">
                                                </div>
                                            </div>

                                            <div class="form-group" style="margin-top: 1rem; margin-bottom: 1rem;">
                                                <label class="form-label">Description (Optional)</label>
                                                <textarea name="tier_description[]" class="form-textarea" rows="2"
                                                    placeholder="Desc"></textarea>
                                            </div>

                                            <div class="grid grid-2" style="gap: 1rem;">
                                                <div class="form-group" style="margin: 0;">
                                                    <label class="form-label">Price (Rs.) *</label>
                                                    <input type="number" name="tier_price[]" class="form-input" required
                                                        min="0" step="0.01" value="0">
                                                </div>

                                                <div class="form-group" style="margin: 0;">
                                                    <label class="form-label">Capacity *</label>
                                                    <input type="number" name="tier_capacity[]" class="form-input" required
                                                        min="1" placeholder="Seats">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                        </div>
                    <?php } ?>

                    <!-- Status -->
                    <div class="form-group">
                        <label class="form-label">Status *</label>
                        <select name="status" class="form-select" required>
                            <option value="upcoming" <?php echo ($event['status'] === 'upcoming') ? 'selected' : ''; ?>>Upcoming</option>
                            <option value="ongoing" <?php echo ($event['status'] === 'ongoing') ? 'selected' : ''; ?>>Ongoing</option>
                            <option value="completed" <?php echo ($event['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="cancelled" <?php echo ($event['status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>

                    <!-- Event Banner -->
                    <div class="form-group">
                        <label class="form-label">Event Banner Image</label>

                        <!-- Current Image -->
                        <?php if ($event['image']): ?>
                            <div id="currentImage" style="margin-bottom: 1rem;">
                                <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.5rem;">Current Image:</p>
                                <img src="/Event/public/uploads/events/<?php echo htmlspecialchars($event['image']); ?>"
                                    alt="Current event image"
                                    style="max-width: 300px; max-height: 200px; border-radius: var(--radius); box-shadow: var(--shadow);">
                            </div>
                        <?php else: ?>
                            <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.5rem;">No image uploaded for this event</p>
                        <?php endif; ?>

                        <input type="file"
                            name="event_image"
                            id="eventImageInput"
                            class="form-input"
                            accept="image/jpeg,image/jpg,image/png,image/gif,image/webp"
                            style="padding: 0.5rem;">
                        <small style="color: var(--text-secondary); display: block; margin-top: 0.25rem;">
                            Leave empty to keep current image. Upload new to replace. Max size: 5MB.
                        </small>

                        <!-- Image Preview -->
                        <div id="imagePreview" style="display: none; margin-top: 1rem;">
                            <p style="font-size: 0.9rem; color: var(--text-secondary); margin-bottom: 0.5rem;">New Image Preview:</p>
                            <img id="previewImg" src="" alt="Preview"
                                style="max-width: 100%; max-height: 300px; border-radius: var(--radius); box-shadow: var(--shadow);">
                            <button type="button" id="removeImageBtn"
                                style="margin-top: 0.5rem; padding: 0.5rem 1rem; background: var(--danger-color); color: white; border: none; border-radius: var(--radius-sm); cursor: pointer;">
                                Remove New Image
                            </button>
                        </div>
                    </div>

                    <!-- Event Info -->
                    <div class="alert" style="background: #fef3c7; color: #92400e; border-left: 4px solid var(--warning-color);">
                        <p><strong>Current Registrations:</strong> <?php echo ($event['capacity'] - $event['available_seats']); ?> / <?php echo $event['capacity']; ?> seats booked</p>
                        <p><strong>Available Seats:</strong> <?php echo $event['available_seats']; ?></p>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="grid grid-2">
                        <a href="/Event/admin/events" class="btn btn-outline">Cancel</a>
                        <button type="submit" class="btn btn-primary">Update Event</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<script>
    // Ticket Tier Management & Image Upload (Edit Page)
    document.addEventListener('DOMContentLoaded', function() {

        const eventForm = document.querySelector('form[action^="/Event/admin/editEvent"]');

        if (!eventForm) return;

        eventForm.addEventListener('submit', function(e) {

            const titleInput = eventForm.querySelector('input[name="title"]');
            const venueInput = eventForm.querySelector('input[name="venue"]');
            const dateInput = eventForm.querySelector('input[name="event_date"]');
            const timeInput = eventForm.querySelector('input[name="event_time"]');

            const title = titleInput.value.trim();
            const venue = venueInput.value.trim();
            const dateValue = dateInput.value;
            const timeValue = timeInput.value;

            // Title
            if (title === '') {
                alert('Event title cannot be empty.');
                titleInput.focus();
                e.preventDefault();
                return;
            }

            // Venue
            if (venue === '') {
                alert('Venue cannot be empty.');
                venueInput.focus();
                e.preventDefault();
                return;
            }

            if (dateValue && timeValue) {
                const now = new Date();

                const selectedDate = new Date(dateValue);
                selectedDate.setHours(0, 0, 0, 0);

                const today = new Date();
                today.setHours(0, 0, 0, 0);

                // Past date
                if (selectedDate < today) {
                    alert('Event date cannot be in the past.');
                    dateInput.focus();
                    e.preventDefault();
                    return;
                }

                // Past time if today
                if (selectedDate.getTime() === today.getTime()) {
                    const [hours, minutes] = timeValue.split(':');
                    const selectedDateTime = new Date();
                    selectedDateTime.setHours(hours, minutes, 0, 0);

                    if (selectedDateTime < now) {
                        alert('Event time cannot be in the past.');
                        timeInput.focus();
                        e.preventDefault();
                        return;
                    }
                }
            }
        });

        // ---- Ticket Tiers Logic ----
        let tierIndex = document.querySelectorAll('.ticket-tier-row').length;

        // Add Tier Button
        document.getElementById('addTierBtn').addEventListener('click', function() {
            // Find max data-tier-index to avoid conflicts
            const rows = document.querySelectorAll('.ticket-tier-row');
            let maxIndex = -1;
            rows.forEach(row => {
                const index = parseInt(row.getAttribute('data-tier-index'));
                if (index > maxIndex) maxIndex = index;
            });

            tierIndex = maxIndex + 1;
            const container = document.getElementById('ticketTiersContainer');
            const displayOrder = document.querySelectorAll('.ticket-tier-row').length;

            const tierHTML = `
            <div class="ticket-tier-row" data-tier-index="${tierIndex}">
                <input type="hidden" name="tier_id[]" value=""> <!-- Empty for new tier -->
                <div style="background: rgba(51, 65, 85, 0.5); padding: 1rem; border-radius: var(--radius-md); margin-bottom: 1rem; border: 1px solid rgba(100, 116, 139, 0.3);">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
                        <strong style="color: var(--primary-light);">Tier #${document.querySelectorAll('.ticket-tier-row').length + 1}</strong>
                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                            <span style="font-size: 0.8rem; color: var(--success-color); font-weight: bold;">(NEW)</span>
                            <button type="button" class="remove-tier-btn" style="background: var(--danger-color); color: white; border: none; padding: 0.4rem 0.8rem; border-radius: var(--radius-sm); cursor: pointer; font-size: 0.875rem;">
                                Remove
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-2" style="gap: 1rem;">
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label">Tier Name *</label>
                            <input type="text" name="tier_name[]" class="form-input" required 
                                   placeholder="e.g., VIP, Gold">
                        </div>
                        
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label">Display Order</label>
                            <input type="number" name="tier_order[]" class="form-input" 
                                   min="0" value="${displayOrder}" placeholder="${displayOrder}">
                        </div>
                    </div>
                    
                    <div class="form-group" style="margin-top: 1rem; margin-bottom: 1rem;">
                        <label class="form-label">Description (Optional)</label>
                        <textarea name="tier_description[]" class="form-textarea" rows="2"
                                  placeholder="Desc"></textarea>
                    </div>
                    
                    <div class="grid grid-2" style="gap: 1rem;">
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label">Price (Rs.) *</label>
                            <input type="number" name="tier_price[]" class="form-input" required 
                                   min="0" step="0.01" placeholder="0.00">
                        </div>
                        
                        <div class="form-group" style="margin: 0;">
                            <label class="form-label">Capacity *</label>
                            <input type="number" name="tier_capacity[]" class="form-input" required 
                                   min="1" placeholder="Seats">
                        </div>
                    </div>
                </div>
            </div>
        `;

            container.insertAdjacentHTML('beforeend', tierHTML);
            updateTierNumbers();
        });

        // Remove Tier Button (Event Delegation)
        document.getElementById('ticketTiersContainer').addEventListener('click', function(e) {
            const removeBtn = e.target.closest('.remove-tier-btn');
            if (removeBtn) {
                const tierRow = removeBtn.closest('.ticket-tier-row');
                const container = document.getElementById('ticketTiersContainer');

                // Allow removing any tier, but warn if it's an existing one (has ID)
                const idInput = tierRow.querySelector('input[name="tier_id[]"]');
                if (idInput && idInput.value) {
                    if (!confirm('WARNING: Removing this tier will delete it permanently! Current bookings might be affected (though we usually prevent deletion if bookings exist). Are you sure?')) {
                        return;
                    }
                }

                // If it's the last one, warn but allow (we'll just create a new empty one on page reload maybe? or block?)
                if (container.querySelectorAll('.ticket-tier-row').length <= 1) {
                    alert('At least one ticket tier is required! Modify the existing one instead.');
                    return;
                }

                tierRow.remove();
                updateTierNumbers();
            }
        });

        // Update tier numbers
        function updateTierNumbers() {
            const tiers = document.querySelectorAll('.ticket-tier-row');
            tiers.forEach((tier, index) => {
                const header = tier.querySelector('strong');
                if (header) {
                    header.textContent = `Tier #${index + 1}`;
                }
            });
            updateTotals();
        }

        // Auto-calculate Totals
        function updateTotals() {
            const tiers = document.querySelectorAll('.ticket-tier-row');
            let totalCapacity = 0;
            let minPrice = Infinity;
            let hasTiers = false;

            tiers.forEach(tier => {
                const capInput = tier.querySelector('input[name="tier_capacity[]"]');
                const priceInput = tier.querySelector('input[name="tier_price[]"]');

                if (capInput && capInput.value) {
                    totalCapacity += parseInt(capInput.value) || 0;
                    hasTiers = true;
                }

                if (priceInput && priceInput.value !== '') {
                    const price = parseFloat(priceInput.value) || 0;
                    if (price < minPrice) minPrice = price;
                    hasTiers = true;
                }
            });

            if (hasTiers) {
                const mainCapacity = document.querySelector('input[name="capacity"]');
                const mainPrice = document.querySelector('input[name="ticket_price"]');

                // Hidden inputs for backend
                if (mainCapacity) mainCapacity.value = totalCapacity;
                if (mainPrice && minPrice !== Infinity) mainPrice.value = minPrice;

                // Visual Display
                const displayCap = document.getElementById('displayTotalCapacity');
                const displayPrice = document.getElementById('displayMinPrice');

                if (displayCap) displayCap.textContent = totalCapacity + ' seats';
                if (displayPrice && minPrice !== Infinity) displayPrice.textContent = 'Rs. ' + minPrice.toFixed(2);
            }
        }

        // Listen for input changes
        document.getElementById('ticketTiersContainer').addEventListener('input', function(e) {
            if (e.target.name === 'tier_capacity[]' || e.target.name === 'tier_price[]') {
                updateTotals();
            }
        });

        // ---- Image Upload Logic (Existing) ----
        const imageInput = document.getElementById('eventImageInput');
        const imagePreview = document.getElementById('imagePreview');
        const previewImg = document.getElementById('previewImg');
        const removeBtn = document.getElementById('removeImageBtn');

        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];

                if (file) {
                    // Validate file type
                    const validTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif', 'image/webp'];
                    if (!validTypes.includes(file.type)) {
                        alert('Please select a valid image file (JPG, PNG, GIF, or WEBP)');
                        imageInput.value = '';
                        return;
                    }

                    // Validate file size (5MB)
                    const maxSize = 5 * 1024 * 1024; // 5MB in bytes
                    if (file.size > maxSize) {
                        alert('File size must be less than 5MB');
                        imageInput.value = '';
                        return;
                    }

                    // Show preview
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImg.src = e.target.result;
                        imagePreview.style.display = 'block';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Remove image
            if (removeBtn) {
                removeBtn.addEventListener('click', function() {
                    imageInput.value = '';
                    imagePreview.style.display = 'none';
                    previewImg.src = '';
                });
            }
        }
    });
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>