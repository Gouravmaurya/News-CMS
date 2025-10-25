<?php
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
$state = get_state_by_slug($slug);

if (!$state) {
    header("HTTP/1.0 404 Not Found");
    include '404.php';
    exit;
}

$page_title = $state['name'] . ' News';

// Pagination
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

$articles = get_articles($per_page, $offset, null, $state['id']);
$total_articles = count_articles(null, $state['id']);
$total_pages = ceil($total_articles / $per_page);

require_once 'includes/header.php';
?>

<link rel="stylesheet" href="<?php echo asset('css/state.css'); ?>">

<div class="state-page">
    <div class="state-header">
        <div class="container">
            <h1><?php echo $state['name']; ?> News</h1>
            <div class="state-meta">
                <span><i class="fa-solid fa-newspaper"></i> <?php echo $total_articles; ?> Articles</span>
                <span><i class="fa-solid fa-map-marker-alt"></i> <?php echo $state['name']; ?></span>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="articles-grid">
            <?php if (empty($articles)): ?>
                <div class="no-articles">
                    <i class="fa-solid fa-inbox"></i>
                    <h3>No articles found</h3>
                    <p>There are no articles for <?php echo $state['name']; ?> yet.</p>
                    <a href="<?php echo url(); ?>" class="btn">Back to Homepage</a>
                </div>
            <?php else: ?>
                <?php foreach ($articles as $article): ?>
                <div class="article-card">
                    <div class="article-image">
                        <a href="<?php echo url('article.php?slug=' . $article['slug']); ?>">
                            <?php if ($article['media_type'] == 'video'): ?>
                                <span class="video-badge"><i class="fa-solid fa-play"></i></span>
                            <?php elseif ($article['media_type'] == 'url'): ?>
                                <span class="external-badge"><i class="fa-solid fa-external-link"></i></span>
                            <?php endif; ?>
                            <img src="/<?php echo $article['image']; ?>" alt="<?php echo $article['title']; ?>">
                        </a>
                    </div>
                    <div class="article-content">
                        <div class="article-meta">
                            <span class="category"><?php echo $article['category_name']; ?></span>
                            <span class="date"><?php echo get_article_display_date($article); ?></span>
                        </div>
                        <h3 class="article-title">
                            <a href="<?php echo url('article.php?slug=' . $article['slug']); ?>">
                                <?php echo $article['title']; ?>
                            </a>
                        </h3>
                        <p class="article-description"><?php echo truncate_text($article['description'], 120); ?></p>
                        <div class="article-footer">
                            <span class="views"><i class="fa-solid fa-eye"></i> <?php echo $article['views']; ?> views</span>
                            <a href="<?php echo url('article.php?slug=' . $article['slug']); ?>" class="read-more">Read More →</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
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
$extra_js = '/assets/js/state.js';
require_once 'includes/footer.php';
?>
