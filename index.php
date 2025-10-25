<?php
$page_title = 'Home';
require_once 'includes/header.php';

// Get latest articles
$latest_articles = get_articles(12);
$featured_articles = db_fetch_all("SELECT a.*, c.name as category_name, c.slug as category_slug 
                                    FROM articles a 
                                    LEFT JOIN categories c ON a.category_id = c.id 
                                    WHERE a.status = 'published' 
                                    ORDER BY a.views DESC LIMIT 5");
?>

<!-- Hero Slider Section -->
<div class="heroSlider">
    <div class="slider-container">
        <div class="slide active">
            <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=1200" alt="News 1">
            <div class="slide-content">
                <h1>Stay Updated with Latest News</h1>
                <p>Your trusted source for breaking news and top stories</p>
            </div>
        </div>
        <div class="slide">
            <img src="https://images.unsplash.com/photo-1495020689067-958852a7765e?w=1200" alt="News 2">
            <div class="slide-content">
                <h1>Global Coverage</h1>
                <p>News from around the world at your fingertips</p>
            </div>
        </div>
        <div class="slide">
            <img src="https://images.unsplash.com/photo-1585829365295-ab7cd400c167?w=1200" alt="News 3">
            <div class="slide-content">
                <h1>Real-Time Updates</h1>
                <p>Get the latest stories as they happen</p>
            </div>
        </div>
    </div>
    <button class="slider-btn prev" onclick="changeSlide(-1)">&#10094;</button>
    <button class="slider-btn next" onclick="changeSlide(1)">&#10095;</button>
    <div class="slider-dots">
        <span class="dot active" onclick="currentSlide(0)"></span>
        <span class="dot" onclick="currentSlide(1)"></span>
        <span class="dot" onclick="currentSlide(2)"></span>
    </div>
</div>

<!-- About Us Section -->
<div class="aboutUs">
    <div class="container">
        <div class="about-content">
            <div class="about-text">
                <h2>About Us</h2>
                <p>📺🇮🇳 <strong><?php echo $settings['site_name']; ?> – Real News. Real Voices. Real India.</strong> 🔥</p>
                <p>Welcome to <?php echo $settings['site_name']; ?>, where every video brings you closer to the truth, culture, and spirit of
                    India. 📰 From breaking news to bold opinions, from hidden heroes to hot debates – we cover the
                    stories that matter!</p>
                <p><strong>🎥 What You'll Get:</strong></p>
                <ul class="about-features">
                    <li>✅ Latest Indian News & Headlines</li>
                    <li>✅ Deep Dives into Politics & Current Affairs</li>
                    <li>✅ Inspiring Stories of Real Indians</li>
                    <li>✅ Culture, Traditions & Social Issues</li>
                    <li>✅ Powerful Interviews & Street Reactions</li>
                </ul>
                <div class="about-stats">
                    <div class="stat">
                        <h3><?php echo count_articles(); ?>+</h3>
                        <p>Articles Published</p>
                    </div>
                    <div class="stat">
                        <h3><?php echo count($categories); ?>+</h3>
                        <p>Categories</p>
                    </div>
                    <div class="stat">
                        <h3>24/7</h3>
                        <p>News Coverage</p>
                    </div>
                </div>
            </div>
            <div class="about-image">
                <img src="https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600" alt="About Us">
            </div>
        </div>
    </div>
</div>

<!-- Top Headlines Section -->
<div class="topHeadlines">
    <div class="left">
        <div class="title">
            <h2>🔴 Live News</h2>
        </div>
        <div class="youtube-live" id="breakingImg">
            <?php if (!empty($settings['live_youtube_url'])): 
                $video_id = extract_youtube_id($settings['live_youtube_url']);
            ?>
                <iframe width="560" height="315" 
                    src="https://www.youtube.com/embed/<?php echo $video_id; ?>" 
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
            <?php else: ?>
                <p>No live stream available at the moment.</p>
            <?php endif; ?>
        </div>
        <div class="text" id="breakingNews">
            <div class="title">
                <a href="<?php echo url('live.php'); ?>">
                    <h2><?php echo $settings['live_title'] ?? 'Watch Live News'; ?></h2>
                </a>
            </div>
            <div class="description">
                Stay updated with real-time news coverage. Breaking news, live updates, and in-depth analysis - all streaming live!
            </div>
        </div>
    </div>
    <div class="right">
        <div class="title">
            <h2>Top Headlines</h2>
        </div>
        <div class="topNews">
            <?php foreach (array_slice($latest_articles, 0, 5) as $article): ?>
            <div class="newsCard">
                <div class="img">
                    <img src="<?php echo url($article['image']); ?>" alt="<?php echo $article['title']; ?>">
                </div>
                <div class="text">
                    <div class="title">
                        <a href="<?php echo url('article.php?slug=' . $article['slug']); ?>" target="_blank">
                            <p><?php echo $article['title']; ?></p>
                        </a>
                    </div>
                    <span class="time"><?php echo get_article_display_date($article); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Latest Articles by Category -->
<div class="page2">
    <?php foreach (array_slice($categories, 0, 3) as $category): 
        $category_articles = get_articles(5, 0, $category['id']);
        if (empty($category_articles)) continue;
    ?>
    <div class="news" id="<?php echo $category['slug']; ?>News">
        <div class="title">
            <h2><?php echo $category['name']; ?> News</h2>
        </div>
        <div class="newsBox">
            <?php foreach ($category_articles as $article): ?>
            <div class="newsCard">
                <div class="img">
                    <img src="<?php echo url($article['image']); ?>" alt="<?php echo $article['title']; ?>">
                </div>
                <div class="text">
                    <div class="title">
                        <a href="<?php echo url('article.php?slug=' . $article['slug']); ?>" target="_blank">
                            <p><?php echo $article['title']; ?></p>
                        </a>
                    </div>
                    <span class="time"><?php echo get_article_display_date($article); ?></span>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
