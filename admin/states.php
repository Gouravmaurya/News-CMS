<?php
$page_title = 'Manage States';
require_once 'includes/admin_header.php';

$error = '';
$success = '';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    // Check if state has articles
    $count = db_fetch_one("SELECT COUNT(*) as count FROM article_states WHERE state_id = ?", [$id])['count'];
    if ($count > 0) {
        $error = "Cannot delete state with $count linked articles. Please unlink articles first.";
    } else {
        db_execute("DELETE FROM states WHERE id = ?", [$id]);
        $success = 'State deleted successfully';
    }
}

// Handle create/update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitize($_POST['name'] ?? '');
    $slug = sanitize($_POST['slug'] ?? '');
    $order_num = (int) ($_POST['order_num'] ?? 0);
    $id = (int) ($_POST['id'] ?? 0);

    if (empty($name) || empty($slug)) {
        $error = 'Name and slug are required';
    } else {
        if ($id > 0) {
            // Update
            $sql = "UPDATE states SET name = ?, slug = ?, order_num = ? WHERE id = ?";
            db_execute($sql, [$name, $slug, $order_num, $id]);
            $success = 'State updated successfully';
        } else {
            // Create
            $sql = "INSERT INTO states (name, slug, order_num) VALUES (?, ?, ?)";
            db_insert($sql, [$name, $slug, $order_num]);
            $success = 'State created successfully';
        }
    }
}

// Get state for editing
$edit_state = null;
if (isset($_GET['edit'])) {
    $edit_id = (int) $_GET['edit'];
    $edit_state = db_fetch_one("SELECT * FROM states WHERE id = ?", [$edit_id]);
}

$states = get_states();
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
        <h2><i class="fa-solid fa-map-marker-alt"></i> Manage States</h2>
        <p>Tag articles with Indian states for location-based news</p>
    </div>
</div>

<!-- Form Card -->
<div class="card form-card">
    <div class="card-header">
        <h3>
            <i class="fa-solid fa-<?php echo $edit_state ? 'edit' : 'plus-circle'; ?>"></i>
            <?php echo $edit_state ? 'Edit State' : 'Add New State'; ?>
        </h3>
    </div>
    <div class="card-body">
        <form method="POST" class="state-form">
            <?php if ($edit_state): ?>
                <input type="hidden" name="id" value="<?php echo $edit_state['id']; ?>">
            <?php endif; ?>

            <div class="form-row">
                <div class="form-group">
                    <label for="name">
                        <i class="fa-solid fa-map-marker-alt"></i>
                        State Name <span class="required">*</span>
                    </label>
                    <input type="text" id="name" name="name" required class="form-control"
                        value="<?php echo htmlspecialchars($edit_state['name'] ?? ''); ?>"
                        placeholder="e.g., Maharashtra, Tamil Nadu, Delhi">
                </div>

                <div class="form-group">
                    <label for="slug">
                        <i class="fa-solid fa-link"></i>
                        Slug <span class="required">*</span>
                    </label>
                    <input type="text" id="slug" name="slug" required class="form-control"
                        value="<?php echo htmlspecialchars($edit_state['slug'] ?? ''); ?>"
                        placeholder="e.g., maharashtra, tamil-nadu, delhi">
                    <small>URL-friendly version (auto-generated)</small>
                </div>

                <div class="form-group">
                    <label for="order_num">
                        <i class="fa-solid fa-sort-numeric-down"></i>
                        Display Order
                    </label>
                    <input type="number" id="order_num" name="order_num" class="form-control"
                        value="<?php echo $edit_state['order_num'] ?? 0; ?>" placeholder="0">
                    <small>Lower numbers appear first</small>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fa-solid fa-save"></i>
                    <?php echo $edit_state ? 'Update State' : 'Create State'; ?>
                </button>

                <?php if ($edit_state): ?>
                    <a href="states.php" class="btn btn-outline btn-lg">
                        <i class="fa-solid fa-times"></i> Cancel
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- States Table -->
<div class="card table-card">
    <div class="card-header">
        <h3>
            <i class="fa-solid fa-list"></i>
            All States (<?php echo count($states); ?>)
        </h3>
    </div>
    <div class="card-body">
        <?php if (empty($states)): ?>
            <div class="empty-state">
                <i class="fa-solid fa-map"></i>
                <h4>No States Yet</h4>
                <p>Add your first state using the form above</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="10%">Order</th>
                            <th width="35%">State Name</th>
                            <th width="35%">Slug</th>
                            <th width="15%" class="text-center">Articles</th>
                            <th width="15%" class="text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($states as $state):
                            $article_count = db_fetch_one("SELECT COUNT(*) as count FROM article_states WHERE state_id = ?", [$state['id']])['count'];
                            ?>
                            <tr>
                                <td class="text-center">
                                    <span class="order-badge order-badge-green"><?php echo $state['order_num']; ?></span>
                                </td>
                                <td>
                                    <strong>
                                        <i class="fa-solid fa-map-marker-alt" style="color: #28a745;"></i>
                                        <?php echo htmlspecialchars($state['name']); ?>
                                    </strong>
                                </td>
                                <td>
                                    <code class="slug-code"><?php echo $state['slug']; ?></code>
                                </td>
                                <td class="text-center">
                                    <span class="badge badge-success">
                                        <i class="fa-solid fa-newspaper"></i>
                                        <?php echo $article_count; ?>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <div class="action-buttons">
                                        <a href="<?php echo BASE_URL; ?>/state.php?slug=<?php echo $state['slug']; ?>"
                                            target="_blank" class="btn-icon btn-sm" title="View">
                                            <i class="fa-solid fa-eye"></i>
                                        </a>
                                        <a href="?edit=<?php echo $state['id']; ?>" class="btn-icon btn-sm btn-warning"
                                            title="Edit">
                                            <i class="fa-solid fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?php echo $state['id']; ?>" class="btn-icon btn-sm btn-danger"
                                            title="Delete"
                                            onclick="return confirm('Delete <?php echo htmlspecialchars($state['name']); ?>?\n\nThis state has <?php echo $article_count; ?> linked article(s).')">
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
        border-color: #28a745;
        box-shadow: 0 0 0 3px rgba(40, 167, 69, 0.1);
    }

    .form-group label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2c3e50;
        font-size: 14px;
    }

    .form-group label i {
        color: #28a745;
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

    .order-badge-green {
        background: #d4edda;
        color: #155724;
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

    .badge-success {
        background: #d4edda;
        color: #155724;
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
        background: #28a745;
        color: white;
        border-color: #28a745;
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
    document.getElementById('name').addEventListener('input', function () {
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

    document.getElementById('slug').addEventListener('input', function () {
        this.dataset.auto = 'false';
    });
</script>

<?php require_once 'includes/admin_footer.php'; ?>