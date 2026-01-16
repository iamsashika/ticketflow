<?php
// Set page title
$pageTitle = 'Register';
require_once __DIR__ . '/../layout/header.php';
?>

<style>
    /* ===== Validation Styles ===== */
    .error-msg {
        color: #d32f2f;
        font-size: 12px;
        margin-top: 4px;
        display: block;
    }

    .form-input.error {
        border: 1px solid #d32f2f;
    }
</style>

<main class="container">
    <div style="max-width: 600px; margin: 0 auto;">
        <div class="card">
            <div class="card-header">
                <h1>Create Account</h1>
            </div>
            <div class="card-body">

                <form method="POST" action="/Event/register" id="registerForm" novalidate>

                    <div class="grid grid-2">
                        <!-- First Name -->
                        <div class="form-group">
                            <label class="form-label">First Name *</label>
                            <input
                                type="text"
                                name="first_name"
                                class="form-input"
                                placeholder="Enter first name"
                                required>
                            <small class="error-msg"></small>
                        </div>

                        <!-- Last Name -->
                        <div class="form-group">
                            <label class="form-label">Last Name *</label>
                            <input
                                type="text"
                                name="last_name"
                                class="form-input"
                                placeholder="Enter last name"
                                required>
                            <small class="error-msg"></small>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input
                            type="email"
                            name="email"
                            class="form-input"
                            placeholder="Enter your email"
                            required>
                        <small class="error-msg"></small>
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <input
                            type="tel"
                            name="phone"
                            class="form-input"
                            placeholder="e.g. 0771234567"
                            required>
                        <small class="error-msg"></small>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input
                            type="password"
                            name="password"
                            class="form-input"
                            placeholder="At least 3 characters"
                            required>
                        <small class="error-msg"></small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label">Confirm Password *</label>
                        <input
                            type="password"
                            name="confirm_password"
                            class="form-input"
                            placeholder="Re-enter password"
                            required>
                        <small class="error-msg"></small>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;">
                        Register
                    </button>

                </form>

                <div class="mt-3 text-center">
                    <p>
                        Already have an account?
                        <a href="/Event/login">Login here</a>
                    </p>
                </div>

            </div>
        </div>
    </div>
</main>

<script>
    document.getElementById('registerForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let isValid = true;
        const form = this;

        // Reset errors
        form.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
        form.querySelectorAll('.form-input').forEach(el => el.classList.remove('error'));

        const nameRegex = /^[A-Za-z][A-Za-z\s]{2,}$/; // At least 3 letters, letters and spaces only
        const phoneRegex = /^0\d{9}$/; // 10 digits starting with 0

        function setError(input, message) {
            input.classList.add('error');
            input.closest('.form-group').querySelector('.error-msg').textContent = message;
            isValid = false;
        }

        // First Name
        if (!nameRegex.test(form.first_name.value.trim())) {
            setError(form.first_name, 'Minimum 3 characters, must start with a letter');
        }

        // Last Name
        if (!nameRegex.test(form.last_name.value.trim())) {
            setError(form.last_name, 'Minimum 3 characters, must start with a letter');
        }

        // Email
        if (!form.email.checkValidity()) {
            setError(form.email, 'Enter a valid email address');
        }

        // Phone
        if (!phoneRegex.test(form.phone.value.trim())) {
            setError(form.phone, 'Phone must be 10 digits and start with 0');
        }

        // Password
        if (form.password.value.length < 3) {
            setError(form.password, 'Password must be at least 3 characters');
        }

        // Confirm Password
        if (form.password.value !== form.confirm_password.value) {
            setError(form.confirm_password, 'Passwords do not match');
        }

        if (isValid) {
            form.submit();
        }
    });
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>