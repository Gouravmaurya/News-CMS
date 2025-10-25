<?php
require_once 'includes/functions.php';

$slug = $_GET['slug'] ?? '';
$article = get_article_by_slug($slug);

if (!$article) {
    header("HTTP/1.0 404 Not Found");
    include '404.php';
    exit;
}

// Increment views
increment_article_views($article['id']);

// Get related articles
$related_articles = get_related_articles($article['category_id'], $article['id'], 3);

// Get article states
$article_states = get_article_states($article['id']);

$page_title = $article['title'];

require_once 'includes/header.php';
?>

<link rel="stylesheet" href="<?php echo asset('css/article.css'); ?>">

<div class="article-page">
    <div class="container">
        <div class="article-main">
            <article class="article-content">
                <!-- Breadcrumb -->
                <div class="breadcrumb">
                    <a href="<?php echo url(); ?>">Home</a> / 
                    <a href="<?php echo url('category.php?slug=' . $article['category_slug']); ?>"><?php echo $article['category_name']; ?></a> / 
                    <span><?php echo truncate_text($article['title'], 50); ?></span>
                </div>

                <!-- Article Header -->
                <header class="article-header">
                    <h1><?php echo $article['title']; ?></h1>
                    
                    <div class="article-meta">
                        <span class="category">
                            <i class="fa-solid fa-folder"></i>
                            <a href="<?php echo url('category.php?slug=' . $article['category_slug']); ?>">
                                <?php echo $article['category_name']; ?>
                            </a>
                        </span>
                        <span class="date">
                            <i class="fa-solid fa-calendar"></i>
                            <?php echo get_article_display_date($article); ?>
                        </span>
                        <span class="views">
                            <i class="fa-solid fa-eye"></i>
                            <?php echo $article['views']; ?> views
                        </span>
                    </div>

                    <?php if (!empty($article_states)): ?>
                    <div class="article-states">
                        <i class="fa-solid fa-map-marker-alt"></i>
                        <?php foreach ($article_states as $state): ?>
                            <a href="<?php echo url('state.php?slug=' . $state['slug']); ?>" class="state-tag">
                                <?php echo $state['name']; ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </header>

                <!-- Article Media -->
                <div class="article-media">
                    <?php if ($article['media_type'] == 'image'): ?>
                        <img src="<?php echo url($article['image']); ?>" alt="<?php echo $article['title']; ?>" class="featured-image">
                    
                    <?php elseif ($article['media_type'] == 'video'): ?>
                        <div class="video-container">
                            <?php 
                            $video_id = extract_youtube_id($article['video_url']);
                            if ($video_id): 
                            ?>
                                <iframe width="100%" height="500" 
                                    src="https://www.youtube.com/embed/<?php echo $video_id; ?>" 
                                    frameborder="0" allowfullscreen></iframe>
                            <?php else: ?>
                                <p>Video not available</p>
                            <?php endif; ?>
                        </div>
                    
                    <?php elseif ($article['media_type'] == 'url'): ?>
                        <div class="external-link-notice">
                            <i class="fa-solid fa-external-link-alt"></i>
                            <p>This article is hosted on an external website.</p>
                            <a href="<?php echo $article['external_url']; ?>" target="_blank" class="btn-external">
                                Read Full Article <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                        <?php if (!empty($article['image'])): ?>
                            <img src="<?php echo url($article['image']); ?>" alt="<?php echo $article['title']; ?>" class="featured-image">
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Article Description -->
                <div class="article-description">
                    <p><strong><?php echo $article['description']; ?></strong></p>
                </div>

                <!-- Article Body -->
                <div class="article-body">
                    <?php echo nl2br($article['content']); ?>
                </div>

                <!-- Share Buttons -->
                <div class="article-share">
                    <h4>Share this article:</h4>
                    <div class="share-buttons">
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                           target="_blank" class="share-btn facebook">
                            <i class="fa-brands fa-facebook-f"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode('http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>&text=<?php echo urlencode($article['title']); ?>" 
                           target="_blank" class="share-btn twitter">
                            <i class="fa-brands fa-twitter"></i> Twitter
                        </a>
                        <a href="https://wa.me/?text=<?php echo urlencode($article['title'] . ' - http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" 
                           target="_blank" class="share-btn whatsapp">
                            <i class="fa-brands fa-whatsapp"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </article>

            <!-- Related Articles -->
            <?php if (!empty($related_articles)): ?>
            <section class="related-articles">
                <h2>Related Articles</h2>
                <div class="related-grid">
                    <?php foreach ($related_articles as $related): ?>
                    <div class="related-card">
                        <a href="<?php echo url('article.php?slug=' . $related['slug']); ?>">
                            <img src="<?php echo url($related['image']); ?>" alt="<?php echo $related['title']; ?>">
                            <h3><?php echo $related['title']; ?></h3>
                            <span class="date"><?php echo get_article_display_date($related); ?></span>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
$extra_js = '/assets/js/article.js';
require_once 'includes/footer.php';
?>
