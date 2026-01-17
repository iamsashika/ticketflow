<?php
$pageTitle = 'About Us';
require_once __DIR__ . '/../layout/header.php';
?>

<main class="container">
    <div class="card">
        <div class="card-body">
            <h1 class="mb-3">About Us</h1>
            <p class="lead">Welcome to TicketFlow, your premier destination for event discovery and ticketing in Sri Lanka.</p>
            
            <div class="mb-4">
                <h3>Who We Are</h3>
                <p>Founded in 2026, TicketFlow was created with a simple mission: to connect people with the experiences they love. Whether it's a music festival, a tech conference, or a local workshop, we make it easy for organizers to sell tickets and for attendees to find their next adventure.</p>
            </div>

            <div class="mb-4">
                <h3>Our Vision</h3>
                <p>To revolutionize the event industry in Sri Lanka by providing a seamless, secure, and user-friendly digital platform that empowers both event creators and goers.</p>
            </div>

            <div class="mb-4">
                <h3>Why Choose Us?</h3>
                <ul style="list-style-type: disc; margin-left: 20px;">
                    <li><strong>Secure Payments:</strong> We prioritize your security with top-tier encryption.</li>
                    <li><strong>Easy Booking:</strong> Get your tickets in just a few clicks.</li>
                    <li><strong>Wide Variety:</strong> From sports to arts, we have it all.</li>
                </ul>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
