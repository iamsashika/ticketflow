<?php
$pageTitle = 'Help & Support';
require_once __DIR__ . '/../layout/header.php';
?>

<main class="container">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-3">Help Center</h1>
            <p class="lead">Simple guides to help you use TicketFlow.</p>

            <div class="mb-5">
                <h3>User Guides</h3>
                
                <div style="margin-top: 2rem;">
                    <h4 style="font-size: 1.1rem; color: var(--primary-color);">Account Basics</h4>
                    
                    <div style="margin-bottom: 1rem; padding-left: 1rem;">
                        <strong>How do I create an account?</strong>
                        <p style="color: var(--text-secondary); margin-top: 0.5rem; font-size: 0.95rem;">
                            Click "Register" in the top menu. Enter your name, email, and password. Once registered, you can log in immediately.
                        </p>
                    </div>

                    <div style="margin-bottom: 1rem; padding-left: 1rem;">
                        <strong>I forgot my password.</strong>
                        <p style="color: var(--text-secondary); margin-top: 0.5rem; font-size: 0.95rem;">
                            Currently, please contact our support team to reset your password manually.
                        </p>
                    </div>
                </div>

                <div style="margin-top: 2rem;">
                    <h4 style="font-size: 1.1rem; color: var(--primary-color);">Booking Tickets</h4>
                    
                    <div style="margin-bottom: 1rem; padding-left: 1rem;">
                        <strong>How do I buy a ticket?</strong>
                        <ol style="color: var(--text-secondary); margin-top: 0.5rem; padding-left: 1.2rem; font-size: 0.95rem;">
                            <li>Browser to "Events" and click "View Details" on an event.</li>
                            <li>Select the number of tickets you want for each category.</li>
                            <li>Click "Checkout", review your order, and confirm payment.</li>
                        </ol>
                    </div>

                    <div style="margin-bottom: 1rem; padding-left: 1rem;">
                        <strong>Where are my tickets?</strong>
                        <p style="color: var(--text-secondary); margin-top: 0.5rem; font-size: 0.95rem;">
                            After purchase, go to <strong>My Registrations</strong> in the top menu. You will see all your active bookings there.
                        </p>
                    </div>
                    
                    <div style="margin-bottom: 1rem; padding-left: 1rem;">
                        <strong>Can I cancel a booking?</strong>
                        <p style="color: var(--text-secondary); margin-top: 0.5rem; font-size: 0.95rem;">
                            Yes. Go to "My Registrations" and click the "Cancel" button next to the ticket you wish to remove.
                        </p>
                    </div>
                </div>
            </div>

            <div class="mb-4" style="background: #334155; color: #f8fafc; padding: 2rem; border-radius: 8px;">
                <h3 style="color: #ffffff;">Still need help?</h3>
                <p style="color: #cbd5e1;">Our support team is available 24/7 to assist you.</p>
                <p><strong>Email:</strong> support@ticketflow.lk</p>
                <p><strong>Phone:</strong> +94 11 234 5678</p>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
