<?php 
// Set page title
$pageTitle = '404 - Page Not Found';
require_once __DIR__ . '/../layout/header.php'; 
?>

<!-- 404 Error Page -->
<main class="container">
    <div class="error-page">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>Sorry, the page you are looking for does not exist.</p>
        <a href="/Event/" class="btn btn-primary">Go to Home</a>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
