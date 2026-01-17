<?php 
// Set page title
$pageTitle = 'Manage Users - Admin';
require_once __DIR__ . '/../layout/header.php'; 

// Calculate statistics
$totalUsers = count($users);
$adminCount = 0;
$regularUserCount = 0;

foreach ($users as $user) {
    if ($user['role'] === 'admin') {
        $adminCount++;
    } else {
        $regularUserCount++;
    }
}
?>

<main class="container">
    <h1 class="mb-3">User Management</h1>
    
    <!-- Statistics Cards -->
    <div class="grid grid-3 mb-3">
        <div class="card">
            <div class="card-body text-center">
                <h3 style="font-size: 2.5rem; color: var(--primary-color); margin-bottom: 0.5rem;">
                    <?php echo $totalUsers; ?>
                </h3>
                <p style="color: var(--text-secondary); font-weight: 500;">Total Users</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <h3 style="font-size: 2.5rem; color: var(--secondary-color); margin-bottom: 0.5rem;">
                    <?php echo $regularUserCount; ?>
                </h3>
                <p style="color: var(--text-secondary); font-weight: 500;">Regular Users</p>
            </div>
        </div>
        
        <div class="card">
            <div class="card-body text-center">
                <h3 style="font-size: 2.5rem; color: var(--warning-color); margin-bottom: 0.5rem;">
                    <?php echo $adminCount; ?>
                </h3>
                <p style="color: var(--text-secondary); font-weight: 500;">Administrators</p>
            </div>
        </div>
    </div>
    
    <!-- Users Table -->
    <?php if (empty($users)): ?>
        <div class="card">
            <div class="card-body text-center">
                <p>No users registered yet.</p>
            </div>
        </div>
    <?php else: ?>
        <div class="card">
            <div class="card-header">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h1>Manage Users</h1>
        <div style="display: flex; gap: 10px;">
            <a href="/Event/admin/createUser" class="btn btn-primary" style="color: #ffffff !important; -webkit-text-fill-color: initial !important; text-decoration: none !important; display: inline-block !important; line-height: normal !important; padding: 10px 20px !important;">
                <span style="color: white !important; font-weight: bold; vertical-align: middle;">+ Create User</span>
            </a>
            <?php if (isset($_SESSION['email']) && $_SESSION['email'] === 'admin@ticketflow.com'): ?>
                <a href="/Event/admin/createAdmin" class="btn btn-secondary" style="background-color: var(--secondary-color); color: #ffffff !important; -webkit-text-fill-color: initial !important; text-decoration: none !important; display: inline-block !important; line-height: normal !important; padding: 10px 20px !important;">
                    <span style="color: white !important; font-weight: bold; vertical-align: middle;">+ Create Admin</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
            </div>
            <div style="overflow-x: auto;">
                <table class="table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Registration Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['phone']); ?></td>
                                <td>
                                    <?php if ($user['email'] === 'admin@ticketflow.com'): ?>
                                        <span class="badge" style="background-color: var(--secondary-color); color: white;">Super Admin</span>
                                    <?php elseif ($user['role'] === 'admin'): ?>
                                        <span class="badge" style="background-color: var(--warning-color); color: #333;">Admin</span>
                                    <?php else: ?>
                                        <span class="badge" style="background-color: var(--primary-color); color: white;">User</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo date('M j, Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <span style="padding: 0.25rem 0.5rem; 
                                                 border-radius: var(--radius-sm); 
                                                 font-size: 0.875rem;
                                                 background: #d1fae5;
                                                 color: #065f46;">
                                        Active
                                    </span>
                                </td>
                                <td>
                                    <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                        <form method="POST" action="/Event/admin/deleteUser/<?php echo $user['id']; ?>" style="display: inline-block; margin: 0;">
                                            <button type="submit" 
                                                    class="btn btn-danger"
                                                    style="font-size: 0.875rem; padding: 0.5rem 1rem; position: relative; z-index: 50; color: white !important; -webkit-text-fill-color: initial !important; border: none; cursor: pointer;"
                                                    onclick="return confirm('Are you sure? This will delete the user and their registrations.');">
                                                Delete
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="text-muted" style="font-size: 0.875rem;">(You)</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif; ?>
</main>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
