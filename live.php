<?php
$page_title = 'Live News';
require_once 'includes/header.php';

// Get latest articles
$latest_articles = get_articles(6);
?>

<link rel="stylesheet" href="<?php echo asset('css/live.css'); ?>">

<div class="live-page">
    <div class="container">
        <div class="live-header">
            <h1>🔴 Live News</h1>
            <p>Watch live news coverage 24/7</p>
        </div>

        <div class="live-content">
            <?php if (!empty($settings['live_youtube_url'])): 
                $video_id = extract_youtube_id($settings['live_youtube_url']);
                if ($video_id):
            ?>
            <div class="live-player">
                <iframe width="100%" height="600" 
                    src="https://www.youtube.com/embed/<?php echo $video_id; ?>?autoplay=1" 
                    title="Live News Stream" 
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin" 
                    allowfullscreen>
                </iframe>
            </div>
            
            <div class="live-info">
                <h2><?php echo $settings['live_title'] ?? 'Watch Live News'; ?></h2>
                <p>Stay updated with real-time news coverage from <?php echo $settings['site_name']; ?>. Breaking news, live updates, and in-depth analysis - all streaming live!</p>
                
                <div class="live-features">
                    <div class="feature">
                        <i class="fa-solid fa-video"></i>
                        <span>24/7 Live Coverage</span>
                    </div>
                    <div class="feature">
                        <i class="fa-solid fa-newspaper"></i>
                        <span>Breaking News</span>
                    </div>
                    <div class="feature">
                        <i class="fa-solid fa-comments"></i>
                        <span>Live Updates</span>
                    </div>
                    <div class="feature">
                        <i class="fa-solid fa-globe"></i>
                        <span>Global Coverage</span>
                    </div>
                </div>
            </div>
            
            <?php else: ?>
            <div class="no-live">
                <i class="fa-solid fa-video-slash"></i>
                <h3>No Live Stream Available</h3>
                <p>We're currently not streaming live. Check back soon for live news coverage!</p>
            </div>
            <?php endif; ?>
            
            <?php else: ?>
            <div class="no-live">
                <i class="fa-solid fa-video-slash"></i>
                <h3>No Live Stream Available</h3>
                <p>We're currently not streaming live. Check back soon for live news coverage!</p>
            </div>
            <?php endif; ?>
        </div>

        <!-- Latest News -->
        <?php if (!empty($latest_articles)): ?>
        <div class="latest-news">
            <h2>Latest News</h2>
            <div class="news-grid">
                <?php foreach ($latest_articles as $article): ?>
                <div class="news-card">
                    <a href="<?php echo url('article.php?slug=' . $article['slug']); ?>">
                        <img src="<?php echo url($article['image']); ?>" alt="<?php echo $article['title']; ?>">
                        <div class="news-content">
                            <span class="category"><?php echo $article['category_name']; ?></span>
                            <h3><?php echo $article['title']; ?></h3>
                            <span class="date"><?php echo get_article_display_date($article); ?></span>
                        </div>
                    </a>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php
$extra_js = '/assets/js/live.js';
require_once 'includes/footer.php';
?>
