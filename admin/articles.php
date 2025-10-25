<?php
$page_title = 'Manage Articles';
require_once 'includes/admin_header.php';

// Handle delete
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    db_execute("DELETE FROM articles WHERE id = ?", [$id]);
    $success = 'Article deleted successfully';
}

// Get filter parameters
$category_filter = $_GET['category'] ?? '';
$status_filter = $_GET['status'] ?? '';
$search = $_GET['search'] ?? '';

// Build query
$sql = "SELECT a.*, c.name as category_name 
        FROM articles a 
        LEFT JOIN categories c ON a.category_id = c.id 
        WHERE 1=1";
$params = [];

if ($category_filter) {
    $sql .= " AND a.category_id = ?";
    $params[] = $category_filter;
}

if ($status_filter) {
    $sql .= " AND a.status = ?";
    $params[] = $status_filter;
}

if ($search) {
    $sql .= " AND a.title LIKE ?";
    $params[] = "%$search%";
}

$sql .= " ORDER BY a.created_at DESC";

$articles = db_fetch_all($sql, $params);
$categories = get_categories();
?>

<?php if (isset($success)): ?>
<div class="alert alert-success">
    <i class="fa-solid fa-check-circle"></i>
    <?php echo $success; ?>
</div>
<?php endif; ?>

<div class="page-header">
    <h2>All Articles</h2>
    <a href="article-form.php" class="btn btn-primary">
        <i class="fa-solid fa-plus"></i> New Article
    </a>
</div>

<!-- Filters -->
<div class="filters-bar">
    <form method="GET" class="filters-form">
        <input type="text" name="search" placeholder="Search articles..." 
               value="<?php echo htmlspecialchars($search); ?>">
        
        <select name="category">
            <option value="">All Categories</option>
            <?php foreach ($categories as $cat): ?>
            <option value="<?php echo $cat['id']; ?>" <?php echo $category_filter == $cat['id'] ? 'selected' : ''; ?>>
                <?php echo htmlspecialchars($cat['name']); ?>
            </option>
            <?php endforeach; ?>
        </select>
        
        <select name="status">
            <option value="">All Status</option>
            <option value="published" <?php echo $status_filter == 'published' ? 'selected' : ''; ?>>Published</option>
            <option value="draft" <?php echo $status_filter == 'draft' ? 'selected' : ''; ?>>Draft</option>
        </select>
        
        <button type="submit" class="btn btn-sm btn-primary">
            <i class="fa-solid fa-filter"></i> Filter
        </button>
        
        <?php if ($category_filter || $status_filter || $search): ?>
        <a href="articles.php" class="btn btn-sm btn-outline">
            <i class="fa-solid fa-times"></i> Clear
        </a>
        <?php endif; ?>
    </form>
</div>

<!-- Articles Table -->
<div class="card">
    <div class="table-responsive">
        <table class="data-table">
            <thead>
                <tr>
                    <th width="50">ID</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Views</th>
                    <th>Date</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($articles)): ?>
                <tr>
                    <td colspan="8" class="text-center">
                        <p class="no-data">
                            No articles found. <a href="article-form.php">Create your first article</a>
                        </p>
                    </td>
                </tr>
                <?php else: ?>
                    <?php foreach ($articles as $article): ?>
                    <tr>
                        <td><?php echo $article['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($article['title']); ?></strong>
                            <br>
                            <small class="text-muted"><?php echo truncate_text($article['description'], 60); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($article['category_name']); ?></td>
                        <td>
                            <?php if ($article['media_type'] == 'image'): ?>
                                <span class="badge badge-primary"><i class="fa-solid fa-image"></i> Image</span>
                            <?php elseif ($article['media_type'] == 'video'): ?>
                                <span class="badge badge-video"><i class="fa-solid fa-video"></i> Video</span>
                            <?php else: ?>
                                <span class="badge badge-external"><i class="fa-solid fa-link"></i> URL</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($article['status'] == 'published'): ?>
                                <span class="badge badge-success">Published</span>
                            <?php else: ?>
                                <span class="badge badge-warning">Draft</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo number_format($article['views']); ?></td>
                        <td><?php echo time_ago($article['created_at']); ?></td>
                        <td class="actions">
                            <a href="<?php echo BASE_URL; ?>/article.php?slug=<?php echo $article['slug']; ?>" target="_blank" 
                               class="btn-icon" title="View">
                                <i class="fa-solid fa-eye"></i>
                            </a>
                            <a href="article-form.php?id=<?php echo $article['id']; ?>" 
                               class="btn-icon" title="Edit">
                                <i class="fa-solid fa-edit"></i>
                            </a>
                            <a href="?delete=<?php echo $article['id']; ?>" 
                               class="btn-icon btn-danger" title="Delete"
                               onclick="return confirmDelete('Delete this article?')">
                                <i class="fa-solid fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
}

.page-header h2 {
    font-size: 1.5rem;
    color: #333;
}

.filters-bar {
    background: white;
    padding: 20px;
    border-radius: 10px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.filters-form {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.filters-form input,
.filters-form select {
    padding: 10px 15px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.9rem;
}

.filters-form input {
    flex: 1;
    min-width: 200px;
}

.filters-form select {
    min-width: 150px;
}

.card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
}

.text-muted {
    color: #999;
}

.badge-primary {
    background: rgba(102, 126, 234, 0.1);
    color: var(--primary);
}

.btn-danger {
    color: var(--danger);
}

.btn-danger:hover {
    background: var(--danger);
    color: white;
}

.alert-success {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #6ee7b7;
}
</style>

<?php require_once 'includes/admin_footer.php'; ?>
