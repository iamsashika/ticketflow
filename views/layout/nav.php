<!-- Navigation Bar -->
<nav class="navbar">
    <div class="container">
        <!-- Logo -->
        <div class="navbar-brand">
            <a href="<?php echo (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin')) ? '/Event/admin' : '/Event/'; ?>">TicketFlow</a>
        </div>
        
        <!-- Navigation Menu -->
        <ul class="navbar-menu">
            <?php if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'admin' && $_SESSION['role'] !== 'super_admin')): ?>
                <!-- Guest/Public User Menu -->
                <li><a href="/Event/">Home</a></li>
                <li><a href="/Event/events">Events</a></li>
            <?php endif; ?>
            
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- Logged in user menu -->
                <?php if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'): ?>
                    <!-- Regular User Links -->
                    <?php if (isset($_SESSION['role']) && $_SESSION['role'] !== 'super_admin'): ?>
                        <li><a href="/Event/my-registrations">My Registrations</a></li>
                    <?php endif; ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'super_admin')): ?>
                    <!-- Admin Menu: Dashboard, Events, Users, Profile, Logout -->
                    <li><a href="/Event/admin" style="font-weight: bold;">Dashboard</a></li>
                    <li><a href="/Event/admin/events">Events</a></li>
                    <li><a href="/Event/admin/users">Users</a></li>
                <?php endif; ?>
                
                <li><a href="/Event/profile">Profile</a></li>
                <li><a href="/Event/logout">Logout</a></li>
            <?php else: ?>
                <!-- Guest menu -->
                <li><a href="/Event/login">Login</a></li>
                <li><a href="/Event/register" class="btn-primary">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
