<?php
// Set page title
$pageTitle = 'My Profile';
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
    <h1 class="mb-3">My Profile</h1>

    <div style="max-width: 700px; margin: 0 auto;">
        <div class="card">
            <div class="card-header">
                <h2>Profile Information</h2>
            </div>
            <div class="card-body">

                <form method="POST" action="/Event/profile" id="profileForm" novalidate>

                    <div class="grid grid-2">
                        <!-- First Name -->
                        <div class="form-group">
                            <label class="form-label">First Name</label>
                            <input
                                type="text"
                                name="first_name"
                                class="form-input"
                                required
                                value="<?php echo htmlspecialchars($user['first_name']); ?>">
                            <small class="error-msg"></small>
                        </div>

                        <!-- Last Name -->
                        <div class="form-group">
                            <label class="form-label">Last Name</label>
                            <input
                                type="text"
                                name="last_name"
                                class="form-input"
                                required
                                value="<?php echo htmlspecialchars($user['last_name']); ?>">
                            <small class="error-msg"></small>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input
                            type="email"
                            name="email"
                            class="form-input"
                            required
                            value="<?php echo htmlspecialchars($user['email']); ?>">
                        <small class="error-msg"></small>
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label class="form-label">Phone Number</label>
                        <input
                            type="tel"
                            name="phone"
                            class="form-input"
                            required
                            value="<?php echo htmlspecialchars($user['phone']); ?>">
                        <small class="error-msg"></small>
                    </div>

                    <!-- Account Info (Read-only) -->
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <input
                            type="text"
                            class="form-input"
                            readonly
                            value="<?php echo ucfirst($user['role']); ?>"
                            style="background: var(--light-color);">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Member Since</label>
                        <input
                            type="text"
                            class="form-input"
                            readonly
                            value="<?php echo date('F j, Y', strtotime($user['created_at'])); ?>"
                            style="background: var(--light-color);">
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary">
                        Update Profile
                    </button>

                </form>

            </div>
        </div>
    </div>
</main>

<script>
    document.getElementById('profileForm').addEventListener('submit', function(e) {
        e.preventDefault();

        let isValid = true;
        const form = this;

        // Reset errors
        form.querySelectorAll('.error-msg').forEach(el => el.textContent = '');
        form.querySelectorAll('.form-input').forEach(el => el.classList.remove('error'));

        const nameRegex = /^[A-Za-z][A-Za-z\s]{2,}$/;
        const phoneRegex = /^0\d{9}$/;

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

        if (isValid) {
            form.submit();
        }
    });
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>