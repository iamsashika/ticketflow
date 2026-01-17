<?php 
// Set page title
$pageTitle = 'Manage Registrations - Admin';
require_once __DIR__ . '/../layout/header.php'; 

// =======================
// Calculate statistics
// =======================
$totalRegistrations = count($registrations);
$totalRevenue = 0;

foreach ($registrations as $reg) {
    if ($reg['status'] === 'confirmed') {
        $price = (float) $reg['ticket_price'];
        $qty   = $reg['quantity'] > 0 ? (int)$reg['quantity'] : 1;
        $totalRevenue += ($price * $qty);
    }
}
?>

<main class="container">
    <h1 class="mb-3">Event Registrations Management</h1>

    <!-- Statistics Cards -->
    <div class="grid grid-3 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 0.5rem;">
                    <?= $totalRegistrations ?>
                </h3>
                <p style="color: var(--text-secondary); font-weight: 500;">Total Registrations</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center">
                <h3 style="font-size: 2.5rem; color: var(--secondary-color); margin-bottom: 0.5rem;">
                    Rs. <?= number_format($totalRevenue, 2) ?>
                </h3>
                <p style="color: var(--text-secondary); font-weight: 500;">Total Revenue</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body text-center">
                <h3 style="font-size: 2.5rem; color: var(--warning-color); margin-bottom: 0.5rem;">
                    Rs. <?= $totalRegistrations > 0 ? number_format($totalRevenue / $totalRegistrations, 2) : '0.00' ?>
                </h3>
                <p style="color: var(--text-secondary); font-weight: 500;">Average Ticket Price</p>
            </div>
        </div>
    </div>

    <!-- Registrations Table -->
    <?php if (empty($registrations)): ?>
        <div class="card">
            <div class="card-body text-center">
                <p>No registrations yet.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-header">
                <h2>All Registrations</h2>
            </div>

            <div style="overflow-x:auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Ticket #</th>
                            <th>User</th>
                            <th>Event</th>
                            <th>Ticket Type</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrations as $registration): 
                            $price = (float) $registration['ticket_price'];
                            $qty   = $registration['quantity'] > 0 ? (int)$registration['quantity'] : 1;
                            $total = $price * $qty;
                            $tier  = $registration['ticket_type_name'] ?? 'Standard';
                        ?>
                            <tr>
                                <td>
                                    <code style="background: var(--light-color); padding: 0.25rem 0.5rem; border-radius: var(--radius-sm); font-size: 0.85rem;">
                                        <?= htmlspecialchars($registration['ticket_number']) ?>
                                    </code>
                                </td>

                                <td>
                                    <div><?= htmlspecialchars($registration['user_name']) ?></div>
                                    <small><?= htmlspecialchars($registration['user_email']) ?></small>
                                </td>

                                <td>
                                    <div><?= htmlspecialchars($registration['event_title']) ?></div>
                                    <small><?= date('M j, Y', strtotime($registration['event_date'])) ?></small>
                                </td>

                                <td>
                                    <span class="badge" style="background: rgba(99,102,241,.1); color: var(--primary-color);">
                                        <?= htmlspecialchars($tier) ?>
                                    </span>
                                </td>

                                <td>
                                    <div>Rs. <?= number_format($price, 2) ?></div>
                                    <?php if ($qty > 1): ?>
                                        <small class="text-secondary">
                                            x <?= $qty ?> (Total: <?= number_format($total, 2) ?>)
                                        </small>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <span style="
                                        text-transform: capitalize;
                                        padding: 0.25rem 0.5rem;
                                        border-radius: var(--radius-sm);
                                        font-size: 0.875rem;
                                        background: <?= $registration['status'] === 'confirmed' ? '#d1fae5' : '#fee2e2' ?>;
                                        color: <?= $registration['status'] === 'confirmed' ? '#065f46' : '#991b1b' ?>;
                                    ">
                                        <?= htmlspecialchars($registration['status']) ?>
                                    </span>
                                </td>

                                <td>
                                    <button
                                        onclick='openDetails(<?= json_encode($registration, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'
                                        class="btn btn-primary"
                                        style="font-size:0.8rem;padding:0.4rem 0.8rem;">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</main>

<!-- ================= MODAL ================= -->
<div id="detailsModal" class="modal-overlay" style="
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.5);
    backdrop-filter:blur(5px);
    z-index:1000;
    justify-content:center;
    align-items:center;
">
    <div class="modal-content" style="
        background:var(--bg-secondary);
        padding:2rem;
        border-radius:var(--radius-lg);
        width:90%;
        max-width:500px;
        position:relative;
        box-shadow:var(--shadow-xl);
    ">
        <button onclick="closeModal()" style="
            position:absolute;
            top:1rem;
            right:1rem;
            background:none;
            border:none;
            font-size:1.5rem;
            cursor:pointer;
        ">&times;</button>

        <h2 style="margin-bottom:1.5rem;color:var(--primary-color);">Ticket Details</h2>
        <div id="modalBody"></div>

        <div style="margin-top:2rem;text-align:right;">
            <button onclick="closeModal()" class="btn btn-secondary">Close</button>
        </div>
    </div>
</div>

<script>
function openDetails(data) {
    const price = parseFloat(data.ticket_price);
    const qty   = data.quantity > 0 ? data.quantity : 1;
    const tier  = data.ticket_type_name || 'Standard';
    const total = price * qty;

    document.getElementById('modalBody').innerHTML = `
        <div style="margin-bottom:1rem;border-bottom:1px solid rgba(255,255,255,.1);padding-bottom:1rem;">
            <small>Ticket Number</small>
            <div style="font-family:monospace;font-weight:bold;">${data.ticket_number}</div>
        </div>

        <div class="grid grid-2" style="gap:1rem;margin-bottom:1rem;">
            <div>
                <small>Event</small>
                <div>${data.event_title}</div>
            </div>
            <div>
                <small>Purchased On</small>
                <div>${new Date(data.registration_date).toLocaleDateString()}</div>
            </div>
        </div>

        <div style="margin-bottom:1rem;">
            <small>Attendee</small>
            <div>${data.user_name}</div>
            <div>${data.user_email}</div>
            <div>${data.user_phone || 'N/A'}</div>
        </div>

        <div style="background:rgba(255,255,255,.05);padding:1rem;border-radius:var(--radius-sm);">
            <div style="display:flex;justify-content:space-between;">
                <span>Ticket Type</span><strong>${tier}</strong>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span>Price</span><span>Rs. ${price.toFixed(2)}</span>
            </div>
            <div style="display:flex;justify-content:space-between;">
                <span>Quantity</span><span>${qty}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-weight:bold;margin-top:.5rem;">
                <span>Total Paid</span>
                <span style="color:var(--success-color);">Rs. ${total.toFixed(2)}</span>
            </div>
        </div>
    `;

    document.getElementById('detailsModal').style.display = 'flex';
}

function closeModal() {
    document.getElementById('detailsModal').style.display = 'none';
}

window.onclick = function(e) {
    if (e.target.id === 'detailsModal') closeModal();
}
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
