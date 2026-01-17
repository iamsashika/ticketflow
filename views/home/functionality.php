<?php
$pageTitle = 'Functionalities';
require_once __DIR__ . '/../layout/header.php';
?>

<main class="container">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-3 text-center">System Functionalities</h1>
            <p class="lead text-center mb-5">Comprehensive list of all features and facilities available in the TicketFlow system.</p>

            <!-- Public/Guest Features -->
            <div class="mb-5">
                <h3 style="color: var(--primary-light); margin-bottom: 1.5rem;">🌐 Public User Facilities</h3>
                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 8px;">
                    <ul style="list-style-type: none; padding: 0;">
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Browse all upcoming events in an interactive card grid
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            View detailed event information (venue, date, time, description)
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Filter events by category
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Search events by name
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            View ticket pricing and seat availability
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Access Help and Functionality pages
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Registered User Features -->
            <div class="mb-5">
                <h3 style="color: var(--success-color); margin-bottom: 1.5rem;">👤 Registered User Facilities</h3>
                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 8px;">
                    <p style="color: var(--text-secondary); margin-bottom: 1rem; font-style: italic;">
                        Includes all Public User features, plus:
                    </p>
                    <ul style="list-style-type: none; padding: 0;">
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            User registration and secure login
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Profile management with custom avatar upload
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Book tickets for multiple ticket types (VIP, Standard, etc.)
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Real-time seat availability validation
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Review order summary before payment
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Secure payment processing
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            View all registrations in "My Registrations" dashboard
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Cancel bookings if plans change
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Receive unique ticket identification numbers
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Admin Features -->
            <div class="mb-5">
                <h3 style="color: var(--secondary-color); margin-bottom: 1.5rem;">⚡ Admin/Organizer Facilities</h3>
                <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.1); padding: 1.5rem; border-radius: 8px;">
                    <ul style="list-style-type: none; padding: 0;">
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Access comprehensive admin dashboard with statistics
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Create new events with full details
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Edit existing event information
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Delete events from the system
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Upload and manage event banner images
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Define multiple ticket types per event with custom pricing
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Set capacity limits for each ticket tier
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Change event status (Upcoming, Cancelled, Completed)
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            View all registrations across all events
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            View registrations for specific events
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Create, edit, and delete event categories
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            View and manage registered users
                        </li>
                        <li style="margin-bottom: 0.75rem; padding-left: 1.5rem; position: relative;">
                            <span style="position: absolute; left: 0;">✓</span>
                            Access real-time system statistics (total users, events, revenue)
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
