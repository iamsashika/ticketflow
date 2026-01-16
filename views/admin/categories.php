<?php 
// Set page title
$pageTitle = 'Manage Categories - Admin';
require_once __DIR__ . '/../layout/header.php'; 
?>

<main class="container">
    <h1 class="mb-3">Manage Categories</h1>
    
    <div class="grid grid-2">
        <!-- List Categories -->
        <div class="card">
            <div class="card-header">
                <h2>Existing Categories</h2>
            </div>
            <div class="card-body">
                <?php if (empty($categories)): ?>
                    <p>No categories found.</p>
                <?php else: ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($categories as $category): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($category['name']); ?></td>
                                    <td>
                                        <div style="display: flex; gap: 0.5rem; align-items: center;">
                                            <button onclick="openEditModal(<?php echo $category['id']; ?>, '<?php echo htmlspecialchars($category['name'], ENT_QUOTES); ?>')" 
                                                    class="btn btn-secondary" 
                                                    style="font-size: 0.875rem; padding: 0.4rem 0.75rem; width: 75px;">
                                                Edit
                                            </button>
                                            <a href="/Event/admin/deleteCategory/<?php echo $category['id']; ?>" 
                                               class="btn btn-danger" 
                                               style="font-size: 0.875rem; padding: 0.4rem 0.75rem; width: 75px; display: inline-block; text-align: center; box-sizing: border-box;"
                                               onclick="return deleteCategoryConfirm('<?php echo htmlspecialchars($category['name'], ENT_QUOTES); ?>')">Delete</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Create Category -->
        <div class="card" style="height: fit-content;">
            <div class="card-header">
                <h2>Create New Category</h2>
            </div>
            <div class="card-body">
                <form method="POST" action="/Event/admin/categories">
                    <div class="form-group">
                        <label class="form-label">Category Name</label>
                        <input type="text" name="name" class="form-input" required placeholder="E.g. Music, Tech">
                    </div>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </form>
            </div>
        </div>
    </div>
</main>

<!-- Edit Category Modal -->
<div id="editModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center;">
    <div style="background: white; padding: 2rem; border-radius: var(--radius); max-width: 500px; width: 90%;">
        <h2 style="margin-bottom: 1.5rem;">Edit Category</h2>
        <form id="editForm" method="POST">
            <div class="form-group">
                <label class="form-label">Category Name</label>
                <input type="text" id="editCategoryName" name="name" class="form-input" required>
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="button" onclick="closeEditModal()" class="btn btn-outline" style="flex: 1;">Cancel</button>
                <button type="submit" class="btn btn-primary" style="flex: 1;">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
// Edit Modal Functions
function openEditModal(id, name) {
    const modal = document.getElementById('editModal');
    const form = document.getElementById('editForm');
    const nameInput = document.getElementById('editCategoryName');
    
    // Set form action
    form.action = '/Event/admin/editCategory/' + id;
    
    // Set current name
    nameInput.value = name;
    
    // Show modal
    modal.style.display = 'flex';
}

function closeEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// Delete Confirmation
function deleteCategoryConfirm(name) {
    return confirm('Are you sure you want to delete the category "' + name + '"?\\n\\nNote: Categories with associated events cannot be deleted.');
}

// Close modal on outside click
document.getElementById('editModal')?.addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditModal();
    }
});

// Close modal on ESC key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeEditModal();
    }
});
</script>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
