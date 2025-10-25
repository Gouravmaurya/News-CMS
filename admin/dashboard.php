<?php
$page_title = 'Dashboard';
require_once 'includes/admin_header.php';

// Get statistics
$total_articles = db_fetch_one("SELECT COUNT(*) as count FROM articles")['count'];
$published_articles = db_fetch_one("SELECT COUNT(*) as count FROM articles WHERE status = 'published'")['count'];
$draft_articles = db_fetch_one("SELECT COUNT(*) as count FROM articles WHERE status = 'draft'")['count'];
$total_categories = db_fetch_one("SELECT COUNT(*) as count FROM categories")['count'];
$total_states = db_fetch_one("SELECT COUNT(*) as count FROM states")['count'];
$total_views = db_fetch_one("SELECT SUM(views) as total FROM articles")['total'] ?? 0;
$newsletter_subscribers = db_fetch_one("SELECT COUNT(*) as count FROM newsletter")['count'];

// Get recent articles
$recent_articles = db_fetch_all("SELECT a.*, c.name as category_name 
                                 FROM articles a 
                                 LEFT JOIN categories c ON a.category_id = c.id 
                                 ORDER BY a.created_at DESC LIMIT 10");
?>

<div class="dashboard">
    <!-- Stats Cards -->
    <div class="stats-grid">
        <div class="stat-card stat-primary">
            <div class="stat-icon">
                <i class="fa-solid fa-newspaper"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $total_articles; ?></h3>
                <p>Total Articles</p>
                <small><?php echo $published_articles; ?> Published, <?php echo $draft_articles; ?> Draft</small>
            </div>
        </div>
        
        <div class="stat-card stat-success">
            <div class="stat-icon">
                <i class="fa-solid fa-eye"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo number_format($total_views); ?></h3>
                <p>Total Views</p>
                <small>Across all articles</small>
            </div>
        </div>
        
        <div class="stat-card stat-info">
            <div class="stat-icon">
                <i class="fa-solid fa-folder"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $total_categories; ?></h3>
                <p>Categories</p>
                <small><?php echo $total_states; ?> States</small>
            </div>
        </div>
        
        <div class="stat-card stat-warning">
            <div class="stat-icon">
                <i class="fa-solid fa-envelope"></i>
            </div>
            <div class="stat-content">
                <h3><?php echo $newsletter_subscribers; ?></h3>
                <p>Subscribers</p>
                <small>Newsletter</small>
            </div>
        </div>
    </div>
    
    <!-- Quick Actions -->
    <div class="quick-actions">
        <h2>Quick Actions</h2>
        <div class="action-buttons">
            <a href="article-form.php" class="action-btn btn-primary">
                <i class="fa-solid fa-plus"></i>
                <span>New Article</span>
            </a>
            <a href="categories.php" class="action-btn btn-success">
                <i class="fa-solid fa-folder-plus"></i>
                <span>New Category</span>
            </a>
            <a href="settings.php" class="action-btn btn-info">
                <i class="fa-solid fa-cog"></i>
                <span>Settings</span>
            </a>
            <a href="<?php echo BASE_URL; ?>/" target="_blank" class="action-btn btn-secondary">
                <i class="fa-solid fa-eye"></i>
                <span>View Site</span>
            </a>
        </div>
    </div>
    
    <!-- Recent Articles -->
    <div class="recent-section">
        <div class="section-header">
            <h2>Recent Articles</h2>
            <a href="articles.php" class="btn btn-sm btn-outline">View All</a>
        </div>
        
        <div class="table-responsive">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Category</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recent_articles)): ?>
                    <tr>
                        <td colspan="6" class="text-center">
                            <p class="no-data">No articles yet. <a href="article-form.php">Create your first article</a></p>
                        </td>
                    </tr>
                    <?php else: ?>
                        <?php foreach ($recent_articles as $article): ?>
                        <tr>
                            <td>
                                <strong><?php echo htmlspecialchars($article['title']); ?></strong>
                                <?php if ($article['media_type'] == 'video'): ?>
                                    <span class="badge badge-video"><i class="fa-solid fa-video"></i></span>
                                <?php elseif ($article['media_type'] == 'url'): ?>
                                    <span class="badge badge-external"><i class="fa-solid fa-external-link"></i></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars($article['category_name']); ?></td>
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
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require_once 'includes/admin_footer.php'; ?>
