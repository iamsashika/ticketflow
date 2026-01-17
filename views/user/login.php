<?php 
// Set page title
$pageTitle = 'Login';
require_once __DIR__ . '/../layout/header.php'; 
?>

<main class="container">
    <div style="max-width: 500px; margin: 0 auto;">
        <div class="card">
            <div class="card-header">
                <h1>Login</h1>
            </div>
            <div class="card-body">
                <form method="POST" action="/Event/login">
                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-input" required 
                               placeholder="Enter your email">
                    </div>
                    
                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" required 
                               placeholder="Enter your password">
                    </div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Login</button>
                </form>
                
                <div class="mt-3 text-center">
                    <p>Don't have an account? 
                        <a href="/Event/register">Register here</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
