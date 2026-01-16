<?php
// Set page title
$pageTitle = 'Create User - Admin';
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
                <h1>Create New User</h1>
            </div>

            <div class="card-body">
                <form method="POST" action="/Event/admin/createUser" id="createUserForm" novalidate>

                    <div class="grid grid-2">
                        <!-- First Name -->
                        <div class="form-group">
                            <label class="form-label">First Name *</label>
                            <input type="text" name="first_name" class="form-input" required>
                            <small class="error-msg"></small>
                        </div>

                        <!-- Last Name -->
                        <div class="form-group">
                            <label class="form-label">Last Name *</label>
                            <input type="text" name="last_name" class="form-input" required>
                            <small class="error-msg"></small>
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-input" required>
                        <small class="error-msg"></small>
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label class="form-label">Phone *</label>
                        <input type="tel" name="phone" class="form-input" placeholder="0771234567" required>
                        <small class="error-msg"></small>
                    </div>

                    <!-- Role -->
                    <div class="form-group">
                        <label class="form-label">Role *</label>
                        <select name="role" class="form-input" required>
                            <option value="">Select role</option>
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        <small class="error-msg"></small>
                    </div>

                    <!-- Password -->
                    <div class="form-group">
                        <label class="form-label">Password *</label>
                        <input type="password" name="password" class="form-input" placeholder="At least 3 characters" required>
                        <small class="error-msg"></small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-group">
                        <label class="form-label">Confirm Password *</label>
                        <input type="password" name="confirm_password" class="form-input" required>
                        <small class="error-msg"></small>
                    </div>

                    <div class="grid grid-2">
                        <a href="/Event/admin/users" class="btn btn-outline">Cancel</a>
                        <button type="submit" class="btn btn-primary">Create User</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</main>

<script>
    document.getElementById('createUserForm').addEventListener('submit', function(e) {
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

        if (!nameRegex.test(form.first_name.value.trim())) {
            setError(form.first_name, 'Minimum 3 characters, must start with a letter');
        }

        if (!nameRegex.test(form.last_name.value.trim())) {
            setError(form.last_name, 'Minimum 3 characters, must start with a letter');
        }

        if (!form.email.checkValidity()) {
            setError(form.email, 'Enter a valid email address');
        }

        if (!phoneRegex.test(form.phone.value.trim())) {
            setError(form.phone, 'Phone must be 10 digits and start with 0');
        }

        if (!form.role.value) {
            setError(form.role, 'Please select a role');
        }

        if (form.password.value.length < 3) {
            setError(form.password, 'Password must be at least 3 characters');
        }

        if (form.password.value !== form.confirm_password.value) {
            setError(form.confirm_password, 'Passwords do not match');
        }

        if (isValid) {
            form.submit();
        }
    });
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>