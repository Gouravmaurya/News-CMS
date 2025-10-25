# ⚡ Quick Start - 5 Minutes Setup

## Step 1: Database (2 minutes)

1. Open phpMyAdmin
2. Create new database: `news_cms`
3. Import `database.sql`
4. Done! ✅

## Step 2: Configure (1 minute)

Edit `config/database.php`:
```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'news_cms');
define('DB_USER', 'root');        // Your MySQL username
define('DB_PASS', '');            // Your MySQL password
```

## Step 3: Permissions (1 minute)

**Windows:**
- Right-click `assets/uploads` → Properties → Security
- Give "Full Control" to your user

**Linux/Mac:**
```bash
chmod -R 755 assets/uploads
```

## Step 4: Test (1 minute)

Visit: `http://localhost/your-folder/`

You should see:
- ✅ Homepage with categories
- ✅ Sample categories (Crime, Sports, Politics)
- ✅ All 29 Indian states in dropdown

## Step 5: Login (Admin Panel - Coming Soon)

Default credentials:
- Username: `admin`
- Password: `admin123`

---

## 🎯 Quick Tests

### Test Category Page
Click "Crime" → Should go to `/category/crime`

### Test State Page
Click States → Select "Maharashtra" → Should go to `/state/maharashtra`

### Test 404 Page
Visit `/invalid-url` → Should show custom 404 page

### Test Live News
Visit `/live` → Should show live news section

---

## 🔧 Troubleshooting

### Problem: Blank page
**Solution:** Check `config/database.php` credentials

### Problem: Clean URLs not working
**Solution:** 
1. Check `.htaccess` exists
2. Enable mod_rewrite:
   ```bash
   sudo a2enmod rewrite
   sudo service apache2 restart
   ```

### Problem: Images not showing
**Solution:** Check folder permissions on `assets/uploads`

---

## 📝 Add Your First Article (Manual)

```sql
-- 1. Add article
INSERT INTO articles (
    title, slug, description, content, 
    media_type, image, category_id, status
) VALUES (
    'My First Article',
    'my-first-article',
    'This is a test article',
    'Full content of the article goes here...',
    'image',
    'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=600',
    1,
    'published'
);

-- 2. Link to state (optional)
INSERT INTO article_states (article_id, state_id) 
VALUES (LAST_INSERT_ID(), 14);  -- 14 = Maharashtra
```

Visit homepage → Your article should appear!

---

## 🎨 Customize Site

### Change Site Name
```sql
UPDATE settings SET site_name = 'Your News Site' WHERE id = 1;
```

### Add Your Logo
1. Upload logo to `images/` folder
2. Update database:
```sql
UPDATE settings SET site_logo = 'images/your-logo.jpg' WHERE id = 1;
```

### Update Contact Info
```sql
UPDATE settings 
SET contact_email = 'your@email.com',
    contact_phone = '+91 1234567890'
WHERE id = 1;
```

### Add Social Media
```sql
UPDATE settings 
SET facebook_url = 'https://facebook.com/yourpage',
    instagram_url = 'https://instagram.com/yourpage',
    youtube_url = 'https://youtube.com/@yourchannel'
WHERE id = 1;
```

### Add Live Stream
```sql
UPDATE settings 
SET live_youtube_url = 'https://www.youtube.com/watch?v=VIDEO_ID',
    live_title = 'Watch Live News 24/7'
WHERE id = 1;
```

---

## ✅ You're Done!

Your news website is now live and working!

**Next Steps:**
1. Add more articles (use SQL above)
2. Customize colors in `assets/css/style.css`
3. Wait for admin panel (coming soon!)

**Need Help?**
- Read `SETUP_GUIDE.md` for detailed instructions
- Check `README.md` for features overview
- Review `FRONTEND_COMPLETE.md` for testing checklist

---

**Total Setup Time: 5 minutes** ⚡
**Frontend Status: 100% Complete** ✅
**Admin Panel: Coming Soon** 🔄
