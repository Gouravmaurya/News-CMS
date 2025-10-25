<?php
$page_title = 'Manage Categories';
require_once 'includes/admin_header.php';

$error = '';
$success = '';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    // Check if category has articles
    $count = db_fetch_one("SELECT COUNT(*) as count FROM articles WHERE category_id = ?", [$id])['count'];
    if ($count > 0) {
        $error = "Cannot delete category with $count articles. Please reassign or delete articles first.";
    } else {
        db_execute("DELETE FROM categories WHERE id = ?", [$id]);
        $success = 'Category deleted successfully';
    }
}

// Handle create/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $slug = sanitize($_POST['slug'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $order_num = (int)($_POST['order_num'] ?? 0);
    $id = (int)($_POST['id'] ?? 0);
    
    if (empty($name) || empty($slug)) {
        $error = 'Name and slug are required';
    } else {
        if ($id > 0) {
            // Update
            $sql = "UPDATE categories SET name = ?, slug = ?, description = ?, order_num = ? WHERE id = ?";
            db_execute($sql, [$name, $slug, $description, $order_num, $id]);
            $success = 'Category updated successfully';
        } else {
            // Create
            $sql = "INSERT INTO categories (name, slug, description, order_num) VALUES (?, ?, ?, ?)";
            db_insert($sql, [$name, $slug, $description, $order_num]);
            $success = 'Category created successfully';
        }
    }
}

// Get category for editing
$edit_category = null;
if (isset($_GET['edit'])) {
    $edit_id = (int)$_GET['edit'];
    $edit_category = db_fetch_one("SELECT * FROM categories WHERE id = ?", [$edit_id]);
}

$categories = get_categories();
?>

<?php if ($error): ?>
<div class="alert alert-error">
    <i class="fa-solid fa-exclamation-circle"></i>
    <?php echo $error; ?>
</div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success">
    <i class="fa-solid fa-check-circle"></i>
    <?php echo $success; ?>
</div>
<?php endif; ?>

<div class="page-header">
    <div>
        <h2><i class="fa-solid fa-folder"></i> Manage Categories</h2>
        <p>Organize your news articles into categories</p>
    </div>
</div>

<!-- Form Card -->
<div class="card form-card">
    <div class="card-header">
        <h3>
            <i class="fa-solid fa-<?php echo $edit_category ? 'edit' : 'plus-circle'; ?>"></i>
            <?php echo $edit_category ? 'Edit Category' : 'Add New Category'; ?>
        </h3>
    </div>
    <div class="card-body">
        <form method="POST" class="category-form">
            <?php if ($edit_category): ?>
            <input type="hidden" name="id" value="<?php echo $edit_category['id']; ?>">
            <?php endif; ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="name">
                        <i class="fa-solid fa-tag"></i>
                        Category Name <span class="required">*</span>
                    </label>
                    <input type="text" id="name" name="name" required 
                           class="form-control"
                           value="<?php echo htmlspecialchars($edit_category['name'] ?? ''); ?>"
                           placeholder="e.g., Crime, Sports, Politics">
                </div>
                
                <div class="form-group">
                    <label for="slug">
                        <i class="fa-solid fa-link"></i>
                        Slug <span class="required">*</span>
                    </label>
                    <input type="text" id="slug" name="slug" required 
                           class="form-control"
                           value="<?php echo htmlspecialchars($edit_category['slug'] ?? ''); ?>"
                           placeholder="e.g., crime, sports, politics">
                    <small>URL-friendly version (auto-generated)</small>
                </div>
                
                <div class="form-group">
                    <label for="order_num">
                        <i class="fa-solid fa-sort-numeric-down"></i>
                        Display Order
                    </label>
                    <input type="number" id="order_num" name="order_num" 
                           class="form-control"
                           value="<?php echo $edit_category['order_num'] ?? 0; ?>"
                           placeholder="0">
                    <small>Lower numbers appear first</small>
                </div>
            </div>
            
            <div class="form-group">
                <label for="description">
                    <i class="fa-solid fa-align-left"></i>
                    Description (Optional)
                </label>
                <textarea id="description" name="description" rows="2" 
                          class="form-control"
                          placeholder="Brief description of this category"><?php echo htmlspecialchars($edit_category['description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-save"></i>
                    <?php echo $edit_category ? 'Update Category' : 'Create Category'; ?>
                </button>
                
                <?php if ($edit_category): ?>
                <a href="categories.php" class="btn btn-outline btn-lg">
                    <i class="fa-solid fa-times"></i> Cancel
                </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Categories Table -->
<div class="card table-card">
    <div class="card-header">
        <h3>
            <i class="fa-solid fa-list"></i>
            All Categories (<?php echo count($categories); ?>)
        </h3>
    </div>
    <div class="card-body">
        <?php if (empty($categories)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-folder-open"></i>
                <h4>No Categories Yet</h4>
                <p>Create your first category using the form above</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="5%">Order</th>
                            <th width="25%">Name</th>
                            <th width="25%">Slug</th>
                            <th width="30%">Description</th>
                            <th width="10%" class="text-center">Articles</th>
                            <th width="15%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): 
                            $article_count = db_fetch_one("SELECT COUNT(*) as count FROM articles WHERE category_id = ?", [$category['id']])['count'];
                        ?>
                        <tr>
                            <td class="text-center">
                                <span class="order-badge"><?php echo $category['order_num']; ?></span>
                            </td>
                            <td>
                                <strong><?php echo htmlspecialchars($category['name']); ?></strong>
                            </td>
                            <td>
                                <code class="slug-code"><?php echo $category['slug']; ?></code>
                            </td>
                            <td>
                                <span class="text-muted">
                                    <?php echo $category['description'] ? htmlspecialchars($category['description']) : '—'; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-info">
                                    <i class="fa-solid fa-newspaper"></i>
                                    <?php echo $article_count; ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <div class="action-buttons">
                                    <a href="<?php echo BASE_URL; ?>/category.php?slug=<?php echo $category['slug']; ?>" 
                                       target="_blank" 
                                       class="btn-icon btn-sm" 
                                       title="View">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    <a href="?edit=<?php echo $category['id']; ?>" 
                                       class="btn-icon btn-sm btn-warning" 
                                       title="Edit">
                                        <i class="fa-solid fa-edit"></i>
                                    </a>
                                    <a href="?delete=<?php echo $category['id']; ?>" 
                                       class="btn-icon btn-sm btn-danger" 
                                       title="Delete"
                                       onclick="return confirm('Delete <?php echo htmlspecialchars($category['name']); ?>?\n\nThis category has <?php echo $article_count; ?> article(s).')">
                                        <i class="fa-solid fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.page-header p {
    color: #666;
    margin-top: 5px;
}

.form-card {
    margin-bottom: 30px;
}

.table-card {
    margin-bottom: 30px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 20px;
}

.form-control {
    width: 100%;
    padding: 10px 12px;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    font-size: 14px;
    transition: all 0.2s;
}

.form-control:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: #2c3e50;
    font-size: 14px;
}

.form-group label i {
    color: #007bff;
    margin-right: 5px;
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #6c757d;
    font-size: 12px;
}

.required {
    color: #dc3545;
}

.form-actions {
    display: flex;
    gap: 10px;
    margin-top: 25px;
}

.btn-lg {
    padding: 12px 24px;
    font-size: 15px;
}

.table-responsive {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table thead {
    background: #f8f9fa;
}

.data-table th {
    padding: 15px;
    text-align: left;
    font-weight: 600;
    color: #2c3e50;
    border-bottom: 2px solid #dee2e6;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.data-table td {
    padding: 15px;
    border-bottom: 1px solid #e9ecef;
    vertical-align: middle;
}

.data-table tbody tr {
    transition: background 0.2s;
}

.data-table tbody tr:hover {
    background: #f8f9fa;
}

.text-center {
    text-align: center;
}

.text-muted {
    color: #6c757d;
    font-size: 14px;
}

.order-badge {
    display: inline-block;
    width: 32px;
    height: 32px;
    line-height: 32px;
    background: #e3f2fd;
    color: #1976d2;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
}

.slug-code {
    background: #f8f9fa;
    padding: 6px 10px;
    border-radius: 6px;
    font-family: 'Courier New', monospace;
    font-size: 13px;
    color: #495057;
    border: 1px solid #e9ecef;
}

.badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 13px;
    font-weight: 500;
}

.badge-info {
    background: #d1ecf1;
    color: #0c5460;
}

.action-buttons {
    display: flex;
    gap: 6px;
    justify-content: center;
}

.btn-icon {
    width: 32px;
    height: 32px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    background: #f8f9fa;
    color: #495057;
    transition: all 0.2s;
    border: 1px solid #dee2e6;
    text-decoration: none;
}

.btn-icon:hover {
    background: #007bff;
    color: white;
    border-color: #007bff;
    transform: translateY(-2px);
}

.btn-icon.btn-warning:hover {
    background: #ffc107;
    border-color: #ffc107;
    color: #000;
}

.btn-icon.btn-danger:hover {
    background: #dc3545;
    border-color: #dc3545;
}

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: #6c757d;
}

.empty-state i {
    font-size: 64px;
    color: #dee2e6;
    margin-bottom: 20px;
}

.empty-state h4 {
    margin: 0 0 10px 0;
    color: #495057;
}

.empty-state p {
    margin: 0;
    color: #6c757d;
}

@media (max-width: 1024px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .data-table {
        font-size: 13px;
    }
    
    .data-table th,
    .data-table td {
        padding: 10px;
    }
}
</style>

<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const slugInput = document.getElementById('slug');
    if (!slugInput.value || slugInput.dataset.auto !== 'false') {
        const slug = this.value
            .toLowerCase()
            .trim()
            .replace(/[^a-z0-9-]/g, '-')
            .replace(/-+/g, '-')
            .replace(/^-|-$/g, '');
        slugInput.value = slug;
    }
});

document.getElementById('slug').addEventListener('input', function() {
    this.dataset.auto = 'false';
});
</script>

<?php require_once 'includes/admin_footer.php'; ?>
