<?php
$page_title = '404 - Page Not Found';
require_once 'includes/header.php';

// Get recent articles for suggestions
$recent_articles = get_articles(5);
?>

<link rel="stylesheet" href="<?php echo asset('css/404.css'); ?>">

<div class="error-404">
    <div class="container">
        <div class="error-content">
            <div class="error-icon">
                <i class="fa-solid fa-exclamation-triangle"></i>
            </div>
            <h1>404</h1>
            <h2>Oops! Page Not Found</h2>
            <p>The page you're looking for doesn't exist or has been moved.</p>
            
            <!-- Search Box -->
            <div class="search-box">
                <form action="<?php echo url('search.php'); ?>" method="GET">
                    <input type="text" name="q" placeholder="Search for articles..." required>
                    <button type="submit">
                        <i class="fa-solid fa-search"></i> Search
                    </button>
                </form>
            </div>
            
            <!-- Quick Links -->
            <div class="quick-links">
                <h3>Try these instead:</h3>
                <div class="links-grid">
                    <a href="<?php echo url(); ?>" class="link-card">
                        <i class="fa-solid fa-home"></i>
                        <span>Homepage</span>
                    </a>
                    <?php foreach (array_slice($categories, 0, 3) as $category): ?>
                    <a href="<?php echo url('category.php?slug=' . $category['slug']); ?>" class="link-card">
                        <i class="fa-solid fa-newspaper"></i>
                        <span><?php echo $category['name']; ?></span>
                    </a>
                    <?php endforeach; ?>
                    <a href="<?php echo url('live.php'); ?>" class="link-card">
                        <i class="fa-solid fa-video"></i>
                        <span>Live News</span>
                    </a>
                </div>
            </div>
            
            <!-- Recent Articles -->
            <?php if (!empty($recent_articles)): ?>
            <div class="recent-articles">
                <h3>Recent Articles</h3>
                <div class="articles-list">
                    <?php foreach ($recent_articles as $article): ?>
                    <div class="article-item">
                        <img src="<?php echo url($article['image']); ?>" alt="<?php echo $article['title']; ?>">
                        <div class="article-info">
                            <a href="<?php echo url('article.php?slug=' . $article['slug']); ?>">
                                <?php echo $article['title']; ?>
                            </a>
                            <span class="date"><?php echo get_article_display_date($article); ?></span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$extra_js = '/assets/js/404.js';
require_once 'includes/footer.php';
?>
