# 🚀 Deployment Checklist

## ✅ Pre-Deployment Checklist

### 1. Files Verification
- [x] All 35+ files created
- [x] Database schema ready
- [x] Config files present
- [x] Assets organized
- [x] Documentation complete

### 2. Database Setup
- [ ] MySQL database created
- [ ] `database.sql` imported
- [ ] Default admin user created
- [ ] Sample data loaded

### 3. Configuration
- [ ] `config/database.php` updated with credentials
- [ ] Database connection tested
- [ ] File permissions set (uploads folder)

### 4. Testing
- [ ] Frontend pages load
- [ ] Admin login works
- [ ] Article creation works
- [ ] Image upload works
- [ ] Clean URLs work

---

## 📋 Step-by-Step Deployment

### Step 1: Upload Files
```bash
# Upload all files to your web server
# Via FTP, cPanel File Manager, or Git

# Ensure these folders exist:
/assets/uploads/
/assets/uploads/articles/
/assets/uploads/settings/
```

### Step 2: Create Database
```sql
-- In phpMyAdmin or MySQL command line:
CREATE DATABASE news_cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### Step 3: Import Database
```bash
# Method 1: phpMyAdmin
1. Open phpMyAdmin
2. Select 'news_cms' database
3. Click 'Import'
4. Choose 'database.sql'
5. Click 'Go'

# Method 2: Command Line
mysql -u username -p news_cms < database.sql
```

### Step 4: Configure Database Connection
Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');      // Your host
define('DB_NAME', 'news_cms');       // Your database name
define('DB_USER', 'your_username');  // Your MySQL username
define('DB_PASS', 'your_password');  // Your MySQL password
```

### Step 5: Set Permissions
```bash
# Linux/Mac
chmod -R 755 assets/uploads
chmod -R 755 assets/uploads/articles
chmod -R 755 assets/uploads/settings

# Windows
Right-click folders → Properties → Security → Give write permissions
```

### Step 6: Test Installation
1. **Frontend Test:**
   - Visit: `http://yoursite.com/`
   - Should see homepage with categories

2. **Admin Test:**
   - Visit: `http://yoursite.com/admin/login.php`
   - Login: `admin` / `admin123`
   - Should see dashboard

### Step 7: Security (IMPORTANT!)
```sql
-- Change default admin password
-- Generate new hash at: https://bcrypt-generator.com/
UPDATE users 
SET password = '$2y$10$YOUR_NEW_HASH_HERE'
WHERE username = 'admin';
```

### Step 8: Configure Settings
1. Login to admin panel
2. Go to Settings
3. Update:
   - Site name
   - Logo
   - Contact info
   - Social media links
   - Live YouTube URL

---

## 🔧 Server Requirements

### Minimum Requirements
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache with mod_rewrite OR Nginx
- 50MB disk space
- 128MB PHP memory limit

### PHP Extensions Required
- PDO (MySQL)
- GD or Imagick (image processing)
- mbstring (string handling)
- JSON (data handling)

### Apache Configuration
```apache
# .htaccess already included
# Ensure mod_rewrite is enabled:
sudo a2enmod rewrite
sudo service apache2 restart
```

### Nginx Configuration
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

---

## 🌐 Domain Setup

### With Domain
1. Point domain to your server
2. Update site URL in settings
3. Enable HTTPS (recommended)
4. Update `.htaccess` for HTTPS redirect

### Subdomain
1. Create subdomain (e.g., news.yoursite.com)
2. Point to CMS folder
3. Follow same steps as domain

### Localhost (Development)
1. Use XAMPP/WAMP/MAMP
2. Place files in htdocs/www folder
3. Access via http://localhost/news-cms/

---

## 🔒 Security Hardening

### 1. Change Admin Password
```sql
UPDATE users SET password = '$2y$10$NEW_HASH' WHERE id = 1;
```

### 2. Enable HTTPS
Uncomment in `.htaccess`:
```apache
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### 3. Protect Config Files
Already protected in `.htaccess`:
```apache
RedirectMatch 403 ^/config/
RedirectMatch 403 ^/includes/
```

### 4. Update PHP Settings
In `php.ini` or `.htaccess`:
```ini
upload_max_filesize = 10M
post_max_size = 10M
max_execution_time = 300
```

### 5. Regular Backups
- Backup database weekly
- Backup uploads folder
- Keep backups off-server

---

## 📊 Post-Deployment Tasks

### 1. Create Content
- [ ] Add categories
- [ ] Add states (already included)
- [ ] Create first article
- [ ] Upload logo
- [ ] Set live YouTube URL

### 2. Customize Design
- [ ] Update colors in CSS
- [ ] Replace placeholder images
- [ ] Customize about section
- [ ] Update footer text

### 3. SEO Setup
- [ ] Add Google Analytics
- [ ] Submit sitemap
- [ ] Set up Google Search Console
- [ ] Add meta descriptions

### 4. Social Media
- [ ] Update all social links
- [ ] Share first articles
- [ ] Set up social sharing

---

## 🐛 Troubleshooting

### Issue: Blank Page
**Solution:**
```php
// Add to top of index.php temporarily
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

### Issue: Database Connection Error
**Solution:**
1. Check credentials in `config/database.php`
2. Verify MySQL is running
3. Check database exists
4. Verify user has permissions

### Issue: Clean URLs Not Working
**Solution:**
1. Check `.htaccess` exists
2. Enable mod_rewrite:
   ```bash
   sudo a2enmod rewrite
   sudo service apache2 restart
   ```
3. Check Apache config allows `.htaccess`

### Issue: Images Not Uploading
**Solution:**
1. Check folder permissions:
   ```bash
   chmod -R 755 assets/uploads
   ```
2. Check PHP upload limits
3. Verify folder exists

### Issue: 404 on Admin Pages
**Solution:**
1. Check files exist in `/admin/` folder
2. Clear browser cache
3. Check file permissions

---

## ✅ Final Verification

### Frontend Checklist
- [ ] Homepage loads
- [ ] Categories work
- [ ] States work
- [ ] Articles display
- [ ] Live news shows
- [ ] 404 page works
- [ ] Newsletter form works
- [ ] Mobile responsive

### Admin Checklist
- [ ] Login works
- [ ] Dashboard shows stats
- [ ] Can create article
- [ ] Can upload image
- [ ] Can edit article
- [ ] Can delete article
- [ ] Categories work
- [ ] States work
- [ ] Settings save

### Performance Checklist
- [ ] Pages load fast (<3 seconds)
- [ ] Images optimized
- [ ] Caching enabled
- [ ] Gzip compression on

---

## 🎉 Launch!

Once all checks pass:
1. ✅ Change admin password
2. ✅ Enable HTTPS
3. ✅ Create content
4. ✅ Share on social media
5. ✅ Monitor analytics

---

## 📞 Support Resources

- **Setup Guide:** `SETUP_GUIDE.md`
- **Quick Start:** `QUICK_START.md`
- **Admin Guide:** `ADMIN_PANEL_COMPLETE.md`
- **Frontend Guide:** `FRONTEND_COMPLETE.md`

---

## 🎯 Success Metrics

After 1 week:
- [ ] 10+ articles published
- [ ] All categories populated
- [ ] Social media connected
- [ ] First 100 visitors
- [ ] Newsletter subscribers

After 1 month:
- [ ] 50+ articles
- [ ] Regular posting schedule
- [ ] Growing audience
- [ ] SEO improvements
- [ ] User feedback collected

---

**Your News CMS is ready to launch! 🚀**
