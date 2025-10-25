    <div class="footer">
        <div class="footer-top">
            <div class="footer-column">
                <div class="footer-logo">
                    <?php if (!empty($settings['site_logo'])): ?>
                        <img src="<?php echo url($settings['site_logo']); ?>" alt="<?php echo $settings['site_name']; ?>">
                    <?php else: ?>
                        <img src="<?php echo url('images/logo.jpg'); ?>" alt="<?php echo $settings['site_name']; ?>">
                    <?php endif; ?>
                    <p>Your trusted source for breaking news and top stories from around the world.</p>
                </div>
            </div>
            <div class="footer-column">
                <h3>Quick Links</h3>
                <ul>
                    <li><a href="<?php echo url(); ?>">Home</a></li>
                    <li><a href="<?php echo url('live.php'); ?>">Live News</a></li>
                    <?php 
                    $footer_categories = array_slice($categories, 0, 4);
                    foreach ($footer_categories as $category): 
                    ?>
                    <li><a href="<?php echo url('category.php?slug=' . $category['slug']); ?>"><?php echo $category['name']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Categories</h3>
                <ul>
                    <?php 
                    $remaining_categories = array_slice($categories, 4, 6);
                    foreach ($remaining_categories as $category): 
                    ?>
                    <li><a href="<?php echo url('category.php?slug=' . $category['slug']); ?>"><?php echo $category['name']; ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div class="footer-column">
                <h3>Contact Us</h3>
                <ul class="contact-info">
                    <li><i class="fa-solid fa-phone"></i> <?php echo $settings['contact_phone']; ?></li>
                    <li><i class="fa-solid fa-envelope"></i> <?php echo $settings['contact_email']; ?></li>
                    <li><i class="fa-solid fa-location-dot"></i> Mumbai, India</li>
                </ul>
                <div class="social-links">
                    <a href="<?php echo !empty($settings['instagram_url']) ? $settings['instagram_url'] : 'https://www.instagram.com/news9india_/'; ?>" target="_blank" title="Follow us on Instagram">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                    <a href="<?php echo !empty($settings['facebook_url']) ? $settings['facebook_url'] : 'https://facebook.com/N9india'; ?>" target="_blank" title="Like our Facebook page">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="<?php echo !empty($settings['youtube_url']) ? $settings['youtube_url'] : 'https://www.youtube.com/@N9India'; ?>" target="_blank" title="Subscribe to our YouTube channel">
                        <i class="fa-brands fa-youtube"></i>
                    </a>
                    <a href="<?php echo !empty($settings['snapchat_url']) ? $settings['snapchat_url'] : 'https://www.snapchat.com/add/news9india'; ?>" target="_blank" title="Add us on Snapchat">
                        <i class="fa-brands fa-snapchat"></i>
                    </a>
                </div>
            </div>
            <div class="footer-column newsletter-column">
                <h3>Newsletter</h3>
                <p>Subscribe to get the latest news updates</p>
                <form action="<?php echo url('newsletter-subscribe.php'); ?>" method="POST" class="newsletter-form">
                    <input type="email" name="email" placeholder="Enter your email" required>
                    <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> <?php echo $settings['site_name']; ?>. All Rights Reserved.</p>
            <div class="footer-links">
                <a href="<?php echo url('page.php?slug=privacy-policy'); ?>">Privacy Policy</a>
                <a href="<?php echo url('page.php?slug=terms-of-service'); ?>">Terms of Service</a>
                <a href="<?php echo url('page.php?slug=cookie-policy'); ?>">Cookie Policy</a>
            </div>
        </div>
    </div>

    <script src="<?php echo asset('js/main.js'); ?>"></script>
    <?php if (isset($extra_js)): ?>
        <script src="<?php echo $extra_js; ?>"></script>
    <?php endif; ?>
</body>
</html>
