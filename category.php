<?php
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
$category = get_category_by_slug($slug);

if (!$category) {
    header("HTTP/1.0 404 Not Found");
    include '404.php';
    exit;
}

$page_title = $category['name'];

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

$articles = get_articles($per_page, $offset, $category['id']);
$total_articles = count_articles($category['id']);
$total_pages = ceil($total_articles / $per_page);

require_once 'includes/header.php';
?>

<link rel="stylesheet" href="<?php echo asset('css/category.css'); ?>">

<div class="category-page">
    <!-- Simple Header -->
    <div class="page-header">
        <div class="container">
            <h1><?php echo $category['name']; ?> News</h1>
        </div>
    </div>

    <!-- Articles Grid -->
    <div class="container">
        <?php if (empty($articles)): ?>
            <div class="empty-state">
                <p>No articles found in this category.</p>
                <a href="<?php echo url(); ?>">← Back to Home</a>
            </div>
        <?php else: ?>
            <div class="news-grid">
                <?php foreach ($articles as $article): ?>
                <article class="news-item">
                    <a href="<?php echo url('article.php?slug=' . $article['slug']); ?>" class="news-link">
                        <div class="news-image">
                            <img src="<?php echo url($article['image']); ?>" alt="<?php echo htmlspecialchars($article['title']); ?>">
                        </div>
                        <h3 class="news-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                    </a>
                </article>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

        <?php if ($total_pages > 1): ?>
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?slug=<?php echo $slug; ?>&page=<?php echo $page - 1; ?>" class="page-link">← Previous</a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($i == $page): ?>
                    <span class="page-link active"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?slug=<?php echo $slug; ?>&page=<?php echo $i; ?>" class="page-link"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($page < $total_pages): ?>
                <a href="?slug=<?php echo $slug; ?>&page=<?php echo $page + 1; ?>" class="page-link">Next →</a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$extra_js = '/assets/js/category.js';
require_once 'includes/footer.php';
?>
