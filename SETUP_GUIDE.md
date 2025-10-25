# News CMS - Setup Guide

## 📋 Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server with mod_rewrite enabled
- phpMyAdmin or MySQL command line access

---

## 🚀 Installation Steps

### Step 1: Database Setup

1. **Create Database**
   - Open phpMyAdmin or MySQL command line
   - Create a new database named `news_cms`

2. **Import Database Schema**
   - Open the `database.sql` file
   - Execute it in your `news_cms` database
   - This will create all tables and insert sample data

3. **Default Admin Credentials**
   - Username: `admin`
   - Password: `admin123`
   - **⚠️ Change this password immediately after first login!**

### Step 2: Configure Database Connection

1. Open `config/database.php`
2. Update the database credentials:

```php
define('DB_HOST', 'localhost');     // Your database host
define('DB_NAME', 'news_cms');      // Your database name
define('DB_USER', 'root');          // Your database username
define('DB_PASS', '');              // Your database password
```

### Step 3: Set File Permissions

Make sure the uploads directory is writable:

**On Linux/Mac:**
```bash
chmod -R 755 assets/uploads
```

**On Windows:**
- Right-click `assets/uploads` folder
- Properties → Security → Edit
- Give "Full Control" to your web server user

### Step 4: Configure Web Server

#### For Apache (with .htaccess)
- The `.htaccess` file is already included
- Make sure `mod_rewrite` is enabled
- Restart Apache if needed

#### For Nginx
Add this to your nginx config:

```nginx
location / {
    try_files $uri $uri/ /index.php?$query_string;
}

location ~ ^/(article|category|state)/([a-zA-Z0-9-]+)$ {
    rewrite ^/article/(.*)$ /article.php?slug=$1 last;
    rewrite ^/category/(.*)$ /category.php?slug=$1 last;
    rewrite ^/state/(.*)$ /state.php?slug=$1 last;
}
```

### Step 5: Test the Installation

1. **Visit Homepage**
   - Open: `http://localhost/your-project-folder/`
   - You should see the homepage with sample categories

2. **Test Category Page**
   - Click on any category (Crime, Sports, etc.)
   - URL should be clean: `/category/crime`

3. **Test State Page**
   - Click on States dropdown
   - Select any state
   - URL should be clean: `/state/maharashtra`

4. **Test Live News**
   - Visit: `/live.php` or `/live`
   - Should show live news section

5. **Test 404 Page**
   - Visit any invalid URL: `/invalid-page`
   - Should show custom 404 page

---

## 📁 Project Structure

```
news-cms/
├── config/
│   └── database.php          # Database configuration
├── includes/
│   ├── header.php            # Header component
│   ├── footer.php            # Footer component
│   └── functions.php         # Helper functions
├── admin/                    # Admin panel (to be built)
├── assets/
│   ├── css/
│   │   ├── style.css         # Main styles
│   │   ├── category.css      # Category page styles
│   │   ├── state.css         # State page styles
│   │   ├── article.css       # Article page styles
│   │   ├── 404.css           # 404 page styles
│   │   └── live.css          # Live page styles
│   ├── js/
│   │   ├── main.js           # Main JavaScript
│   │   ├── category.js       # Category page scripts
│   │   ├── state.js          # State page scripts
│   │   ├── article.js        # Article page scripts
│   │   ├── 404.js            # 404 page scripts
│   │   └── live.js           # Live page scripts
│   └── uploads/              # Uploaded images
├── index.php                 # Homepage
├── category.php              # Category page
├── state.php                 # State page
├── article.php               # Single article page
├── live.php                  # Live news page
├── 404.php                   # 404 error page
├── newsletter-subscribe.php  # Newsletter handler
├── database.sql              # Database schema
├── .htaccess                 # Apache configuration
└── README.md                 # This file
```

---

## 🎨 Customization

### Change Site Name and Logo

1. **Via Database** (Temporary - until admin panel is built)
   ```sql
   UPDATE settings 
   SET site_name = 'Your Site Name',
       site_logo = 'path/to/logo.jpg'
   WHERE id = 1;
   ```

2. **Upload Logo**
   - Place your logo in `images/` folder
   - Update the path in settings table

### Add Social Media Links

```sql
UPDATE settings 
SET facebook_url = 'https://facebook.com/yourpage',
    instagram_url = 'https://instagram.com/yourpage',
    youtube_url = 'https://youtube.com/@yourchannel',
    twitter_url = 'https://twitter.com/yourhandle'
WHERE id = 1;
```

### Update Contact Information

```sql
UPDATE settings 
SET contact_email = 'your@email.com',
    contact_phone = '+91 1234567890'
WHERE id = 1;
```

### Add Live YouTube Stream

```sql
UPDATE settings 
SET live_youtube_url = 'https://www.youtube.com/watch?v=VIDEO_ID',
    live_title = 'Watch Live News 24/7'
WHERE id = 1;
```

---

## 📝 Adding Content (Manual - Until Admin Panel is Built)

### Add a Category

```sql
INSERT INTO categories (name, slug, description, order_num) 
VALUES ('Technology', 'technology', 'Latest tech news', 7);
```

### Add a State

```sql
INSERT INTO states (name, slug, order_num) 
VALUES ('Goa', 'goa', 30);
```

### Add an Article (Image Type)

```sql
INSERT INTO articles (
    title, slug, description, content, 
    media_type, image, category_id, status
) VALUES (
    'Breaking News Title',
    'breaking-news-title',
    'Short description of the article',
    'Full article content goes here...',
    'image',
    'assets/uploads/articles/image.jpg',
    1,
    'published'
);
```

### Add an Article (Video Type)

```sql
INSERT INTO articles (
    title, slug, description, content, 
    media_type, video_url, image, category_id, status
) VALUES (
    'Video News Title',
    'video-news-title',
    'Short description',
    'Full article content...',
    'video',
    'https://www.youtube.com/watch?v=VIDEO_ID',
    'assets/uploads/articles/thumbnail.jpg',
    1,
    'published'
);
```

### Link Article to States

```sql
-- Get article ID first
SELECT id FROM articles WHERE slug = 'your-article-slug';

-- Link to states (replace 123 with actual article_id)
INSERT INTO article_states (article_id, state_id) VALUES
(123, 14),  -- Maharashtra
(123, 23);  -- Tamil Nadu
```

---

## 🔒 Security Recommendations

1. **Change Default Admin Password**
   ```sql
   -- Generate new password hash (use online bcrypt generator)
   UPDATE users 
   SET password = '$2y$10$NEW_HASH_HERE'
   WHERE username = 'admin';
   ```

2. **Protect Config Files**
   - The `.htaccess` already blocks access to `/config/` and `/includes/`
   - Verify this is working by trying to access: `http://yoursite.com/config/database.php`

3. **Enable HTTPS**
   - Uncomment HTTPS redirect in `.htaccess`
   - Get SSL certificate (Let's Encrypt is free)

4. **Regular Backups**
   - Backup database regularly
   - Backup uploaded images in `assets/uploads/`

---

## 🐛 Troubleshooting

### Issue: Clean URLs not working

**Solution:**
1. Check if `mod_rewrite` is enabled:
   ```bash
   # On Ubuntu/Debian
   sudo a2enmod rewrite
   sudo service apache2 restart
   ```

2. Check `.htaccess` file exists in root directory

3. Verify Apache config allows `.htaccess` overrides:
   ```apache
   <Directory /var/www/html>
       AllowOverride All
   </Directory>
   ```

### Issue: Images not uploading

**Solution:**
1. Check folder permissions:
   ```bash
   chmod -R 755 assets/uploads
   ```

2. Check PHP upload limits in `php.ini`:
   ```ini
   upload_max_filesize = 10M
   post_max_size = 10M
   ```

### Issue: Database connection error

**Solution:**
1. Verify database credentials in `config/database.php`
2. Check if MySQL service is running
3. Verify database name exists
4. Check user has proper permissions

### Issue: Blank page or errors

**Solution:**
1. Enable error reporting temporarily:
   ```php
   // Add to top of index.php
   error_reporting(E_ALL);
   ini_set('display_errors', 1);
   ```

2. Check PHP error logs
3. Verify all files are uploaded correctly

---

## 📞 Support

For issues or questions:
1. Check this guide first
2. Review `BUILD_PROGRESS.md` for implementation details
3. Check PHP error logs
4. Verify database structure matches `database.sql`

---

## ✅ Next Steps

1. **Test all frontend pages** - Make sure everything works
2. **Add sample content** - Use SQL queries above
3. **Customize design** - Update colors, fonts, etc.
4. **Build admin panel** - Coming next!
5. **Go live** - Deploy to production server

---

## 🎉 You're All Set!

Your News CMS frontend is now ready. The admin panel will be built next to make content management easier.

**Current Features:**
- ✅ Dynamic homepage
- ✅ Category pages
- ✅ State pages
- ✅ Article pages (image/video/url support)
- ✅ Live news page
- ✅ 404 error page
- ✅ Newsletter subscription
- ✅ Clean URLs
- ✅ Responsive design
- ✅ SEO friendly

**Coming Soon:**
- 🔄 Admin panel for easy content management
- 🔄 User authentication
- 🔄 Rich text editor
- 🔄 Image upload interface
