# Simple News CMS - MVP Plan

## Project Goal
Build a **basic, working CMS** for a news website with essential features only. No complexity, just core functionality.

---

## Core Features Only (MVP)

### ✅ What We're Building

1. **Admin Login** - Simple username/password
2. **Categories** - Add/Edit/Delete (Crime, Sports, Politics, etc.)
3. **Articles** - Create/Edit/Delete with:
   - Title
   - Description
   - Image upload
   - Category selection
   - Content (simple text editor)
   - Auto timestamp
4. **States** - Add/Edit/Delete Indian states
5. **Live News Link** - One YouTube URL (no history)
6. **Frontend** - Display articles by category and state
7. **Basic Settings** - Site name, logo, social links

### ❌ What We're NOT Building (Yet)

- ❌ Drag-and-drop reordering (use simple order numbers instead)
- ❌ Multiple templates (one default template only)
- ❌ Advanced filters/sorting (just show latest first)
- ❌ Newsletter system (just collect emails in database, no sending)
- ❌ Per-page SEO (global meta tags only)
- ❌ Dashboard analytics/graphs (just show counts)
- ❌ Complex navigation builder (categories auto-appear in menu)

---

## Simplified Project Structure

```
news-cms/
├── config/
│   └── database.php          # Database connection
├── includes/
│   ├── header.php             # Header component
│   ├── footer.php             # Footer component
│   └── functions.php          # Helper functions
├── admin/
│   ├── login.php              # Admin login
│   ├── dashboard.php          # Simple dashboard
│   ├── categories.php         # Manage categories
│   ├── articles.php           # Manage articles
│   ├── article-form.php       # Create/Edit article
│   ├── states.php             # Manage states
│   ├── settings.php           # Site settings + live link
│   └── logout.php             # Logout
├── assets/
│   ├── css/style.css
│   ├── js/main.js
│   └── uploads/               # Uploaded images
├── index.php                  # Homepage
├── category.php               # Category page
├── state.php                  # State page
├── article.php                # Single article
├── live.php                   # Live news page
└── 404.php                    # 404 Error page
```

---

## Simplified Database Schema

### 1. `users` - Admin users
```sql
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE,
    password VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 2. `categories` - News categories
```sql
CREATE TABLE categories (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    slug VARCHAR(100) UNIQUE,
    description TEXT,
    order_num INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 3. `articles` - News articles
```sql
CREATE TABLE articles (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255),
    slug VARCHAR(255) UNIQUE,
    description TEXT,
    content TEXT,
    media_type ENUM('image', 'video', 'url') DEFAULT 'image',
    image VARCHAR(255),
    video_url VARCHAR(500),
    external_url VARCHAR(500),
    category_id INT,
    status ENUM('draft', 'published') DEFAULT 'draft',
    views INT DEFAULT 0,
    use_custom_date TINYINT(1) DEFAULT 0,
    custom_date DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (category_id) REFERENCES categories(id)
);
```

### 4. `states` - Indian states
```sql
CREATE TABLE states (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    slug VARCHAR(100) UNIQUE,
    order_num INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

### 5. `article_states` - Link articles to states
```sql
CREATE TABLE article_states (
    article_id INT,
    state_id INT,
    PRIMARY KEY (article_id, state_id),
    FOREIGN KEY (article_id) REFERENCES articles(id) ON DELETE CASCADE,
    FOREIGN KEY (state_id) REFERENCES states(id) ON DELETE CASCADE
);
```

### 6. `settings` - Site settings
```sql
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    site_name VARCHAR(100),
    site_logo VARCHAR(255),
    contact_email VARCHAR(100),
    contact_phone VARCHAR(20),
    live_youtube_url VARCHAR(255),
    live_title VARCHAR(255),
    facebook_url VARCHAR(255),
    instagram_url VARCHAR(255),
    youtube_url VARCHAR(255),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

### 7. `newsletter` - Email subscribers (just collect, no sending)
```sql
CREATE TABLE newsletter (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(100) UNIQUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## Admin Panel - Simple Pages

### 1. Dashboard (`admin/dashboard.php`)
**Simple stats only:**
- Total Articles: 45
- Total Categories: 8
- Total Views: 12,345
- Recent 5 articles (table)

**No graphs, no complex analytics**

---

### 2. Categories (`admin/categories.php`)
**Simple table:**
```
ID | Name      | Slug      | Order | Actions
1  | Crime     | crime     | 1     | [Edit] [Delete]
2  | Politics  | politics  | 2     | [Edit] [Delete]
3  | Sports    | sports    | 3     | [Edit] [Delete]

[+ Add New Category]
```

**Add/Edit Form:**
- Name: [_______]
- Description: [_______]
- Order Number: [___] (manual number, no drag-drop)
- [Save] [Cancel]

---

### 3. Articles (`admin/articles.php`)
**Simple table:**
```
Image | Title              | Category | Status    | Date       | Actions
[img] | Bank Robbery...    | Crime    | Published | 2 hrs ago  | [Edit] [Delete]
[img] | Election News...   | Politics | Draft     | 1 day ago  | [Edit] [Delete]

[+ Add New Article]
```

---

### 4. Article Form (`admin/article-form.php`)
**Simple form:**
```
Title: [_________________________________]

Description (short):
[_____________________________________]
[_____________________________________]

Media Type:
● Image  ○ Video  ○ External URL

┌─ If Image ─────────────────────┐
│ Upload Image: [Choose File]    │
│ [Preview]                      │
└────────────────────────────────┘

┌─ If Video ─────────────────────┐
│ YouTube/Vimeo URL:             │
│ [____________________________] │
│ Thumbnail: [Choose File]       │
└────────────────────────────────┘

┌─ If External URL ──────────────┐
│ External Article URL:          │
│ [____________________________] │
│ Thumbnail: [Choose File]       │
└────────────────────────────────┘

Category: [▼ Select Category]

States: (checkboxes)
☐ Maharashtra  ☐ Tamil Nadu  ☐ Gujarat
☐ Karnataka    ☐ Delhi       ☐ Punjab
... (all states)

Content:
[Simple textarea or basic TinyMCE]
[_____________________________________]
[_____________________________________]

Publish Date:
○ Use Current Date/Time (Auto)
● Set Custom Date/Time

┌─ If Custom Date ───────────────┐
│ Date: [📅 24/10/2024]          │
│ Time: [🕐 14:30]               │
└────────────────────────────────┘

Status: ○ Draft  ● Published

[Save] [Cancel]
```

**Auto-generated:**
- Slug (from title)
- created_at (current timestamp)
- updated_at (current timestamp)
- Display date (custom_date if set, else created_at)

---

### 5. States (`admin/states.php`)
**Simple table:**
```
ID | Name          | Slug          | Order | Actions
1  | Maharashtra   | maharashtra   | 1     | [Edit] [Delete]
2  | Tamil Nadu    | tamil-nadu    | 2     | [Edit] [Delete]

[+ Add New State]
```

**Add/Edit Form:**
- Name: [_______]
- Order Number: [___]
- [Save] [Cancel]

---

### 6. Settings (`admin/settings.php`)
**One simple form:**
```
Site Settings:
- Site Name: [_______]
- Logo: [Choose File]
- Contact Email: [_______]
- Contact Phone: [_______]

Live News:
- YouTube URL: [_______________________________]
- Live Title: [_______________________________]

Social Media:
- Facebook: [_______]
- Instagram: [_______]
- YouTube: [_______]

[Save Settings]
```

---

## Frontend Pages - Simple Display

### 1. Homepage (`index.php`)
- Hero section (static or simple slider)
- Latest 10 articles (grid)
- Pagination (simple: Previous | Next)

### 2. Category Page (`category.php?slug=crime`)
- Category name and description
- All articles in this category
- Simple pagination

### 3. State Page (`state.php?slug=maharashtra`)
- State name
- All articles tagged with this state
- Simple pagination

### 4. Article Page (`article.php?slug=article-name`)
- Title
- Image
- Date ("2 hours ago")
- Content
- Related articles (same category, limit 3)

### 5. Live Page (`live.php`)
- YouTube embed (from settings)
- Title
- Simple description

### 6. 404 Error Page (`404.php`)
- "Page Not Found" message
- Search box
- Links to:
  - Homepage
  - Popular categories
  - Recent articles
- Friendly error message

---

## Development Phases (Simplified)

### Phase 1: Setup (Day 1)
1. Create database and tables
2. Setup config/database.php
3. Create basic folder structure

### Phase 2: Admin Panel (Days 2-3)
1. Login system
2. Dashboard (simple stats)
3. Categories CRUD
4. States CRUD
5. Settings page

### Phase 3: Articles (Days 4-5)
1. Article form (create/edit)
2. Image upload
3. Link articles to states
4. Article list with edit/delete

### Phase 4: Frontend (Days 6-7)
1. Convert HTML to PHP components (header/footer)
2. Homepage with articles
3. Category pages
4. State pages
5. Single article page
6. Live news page
7. 404 error page

### Phase 5: Polish (Day 8)
1. Test everything
2. Fix bugs
3. Add basic styling
4. Deploy

---

## Essential Functions Only

### Database Functions
```php
function db_connect() { /* PDO connection */ }
function db_query($sql, $params) { /* Execute query */ }
```

### Article Functions
```php
function get_articles($limit = 10, $category_id = null) { }
function get_article_by_slug($slug) { }
function create_article($data) { }
function update_article($id, $data) { }
function delete_article($id) { }
function get_article_display_date($article) { 
    // Returns custom_date if set, else created_at
}
function extract_youtube_id($url) { }
```

### Category Functions
```php
function get_categories() { }
function get_category_by_slug($slug) { }
```

### State Functions
```php
function get_states() { }
function get_article_states($article_id) { }
function link_article_to_states($article_id, $state_ids) { }
```

### Helper Functions
```php
function generate_slug($string) { }
function upload_image($file) { }
function time_ago($timestamp) { }
function format_date($date) { }
function sanitize($data) { }
function render_article_media($article) {
    // Renders image, video embed, or external link based on media_type
}
```

---

## What Makes This Simple?

✅ **No drag-and-drop** - Use order numbers (1, 2, 3...)
✅ **No multiple templates** - One default layout
✅ **No advanced filters** - Just show latest first
✅ **No navigation builder** - Categories auto-appear in menu
✅ **No newsletter sending** - Just collect emails
✅ **No per-page SEO** - Global meta tags only
✅ **No analytics graphs** - Just show numbers
✅ **No live stream history** - One current link only
✅ **Simple text editor** - Basic textarea or simple TinyMCE

### But We Include:
✅ **Media types** - Image, Video (YouTube/Vimeo), External URL
✅ **Manual dates** - Option to set custom publish date/time
✅ **Auto timestamps** - Default to current date/time

---

## MVP Feature Checklist

### Must Have ✅
- [x] Admin login
- [x] Create/Edit/Delete categories
- [x] Create/Edit/Delete articles
- [x] Upload images
- [x] **Media types: Image, Video, External URL**
- [x] **Manual date/time option**
- [x] Link articles to states
- [x] Display articles on frontend
- [x] Category pages
- [x] State pages
- [x] Single article page
- [x] Live news link
- [x] Basic settings
- [x] Auto timestamps (default)
- [x] **404 error page** with search and helpful links

### Can Add Later 🔄
- [ ] Drag-and-drop ordering
- [ ] Multiple templates
- [ ] Advanced filters
- [ ] Newsletter sending
- [ ] Per-page SEO
- [ ] Analytics dashboard
- [ ] User roles
- [ ] Comments system
- [ ] Article scheduling
- [ ] Image galleries

---

## Media Types - Simple Implementation

### 1. Image (Default)
**Admin:**
- Select "Image" radio button
- Upload image file
- System saves to `/assets/uploads/`

**Database:**
```sql
media_type = 'image'
image = 'uploads/article-123.jpg'
```

**Frontend:**
```html
<img src="/assets/uploads/article-123.jpg">
```

### 2. Video (YouTube/Vimeo)
**Admin:**
- Select "Video" radio button
- Paste YouTube URL: `https://www.youtube.com/watch?v=VIDEO_ID`
- Upload thumbnail image (for card preview)

**Database:**
```sql
media_type = 'video'
video_url = 'https://www.youtube.com/watch?v=VIDEO_ID'
image = 'uploads/video-thumb.jpg' (thumbnail)
```

**Frontend:**
```php
// On article card: Show thumbnail
<img src="/assets/uploads/video-thumb.jpg">

// On article page: Show video player
<iframe src="https://www.youtube.com/embed/VIDEO_ID"></iframe>
```

### 3. External URL
**Admin:**
- Select "External URL" radio button
- Enter external article URL: `https://partnernews.com/article`
- Upload thumbnail image

**Database:**
```sql
media_type = 'url'
external_url = 'https://partnernews.com/article'
image = 'uploads/external-thumb.jpg'
```

**Frontend:**
```html
<!-- Article card links to external site -->
<a href="https://partnernews.com/article" target="_blank">
  <img src="/assets/uploads/external-thumb.jpg">
  <h3>Article Title</h3>
  <span class="external-icon">🔗</span>
</a>
```

---

## Manual Date/Time - Simple Implementation

### Auto Date (Default)
**Admin:**
- Select "Use Current Date/Time"
- System automatically uses current timestamp

**Database:**
```sql
use_custom_date = 0
custom_date = NULL
created_at = '2024-10-24 14:30:00' (auto)
```

**Frontend Display:**
```
"2 hours ago"
"Yesterday"
"3 days ago"
```

### Custom Date (For Old Articles)
**Admin:**
- Select "Set Custom Date/Time"
- Pick date: 15/08/2023
- Pick time: 10:30 AM

**Database:**
```sql
use_custom_date = 1
custom_date = '2023-08-15 10:30:00'
created_at = '2024-10-24 14:30:00' (when actually created)
```

**Frontend Display:**
```
"Published on August 15, 2023"
```

**PHP Logic:**
```php
function get_article_display_date($article) {
    if ($article['use_custom_date'] == 1 && $article['custom_date']) {
        return date('F j, Y', strtotime($article['custom_date']));
    } else {
        return time_ago($article['created_at']);
    }
}
```

---

## Estimated Timeline

**Total: 8 days for basic working CMS**

- Day 1: Database setup
- Days 2-3: Admin panel basics
- Days 4-5: Article management
- Days 6-7: Frontend pages
- Day 8: Testing & polish

---

## Next Steps

1. ✅ Approve this simplified plan
2. Create database structure
3. Start with admin login
4. Build category management
5. Build article management
6. Build frontend pages
7. Test and deploy

---

## 404 Error Page - Simple Implementation

### Purpose
Handle invalid URLs gracefully and help users find content.

### When 404 is Triggered
- Article not found: `/article/non-existent-slug`
- Category not found: `/category/invalid-category`
- State not found: `/state/invalid-state`
- Any invalid URL

### 404 Page Layout (`404.php`)

```html
<!DOCTYPE html>
<html>
<head>
    <title>404 - Page Not Found</title>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="error-404">
        <div class="error-content">
            <h1>404</h1>
            <h2>Oops! Page Not Found</h2>
            <p>The page you're looking for doesn't exist or has been moved.</p>
            
            <!-- Search Box -->
            <div class="search-box">
                <form action="/search.php" method="GET">
                    <input type="text" name="q" placeholder="Search for articles...">
                    <button type="submit">Search</button>
                </form>
            </div>
            
            <!-- Quick Links -->
            <div class="quick-links">
                <h3>Try these instead:</h3>
                <ul>
                    <li><a href="/">← Back to Homepage</a></li>
                    <li><a href="/category/crime">Crime News</a></li>
                    <li><a href="/category/sports">Sports News</a></li>
                    <li><a href="/category/politics">Politics News</a></li>
                </ul>
            </div>
            
            <!-- Recent Articles -->
            <div class="recent-articles">
                <h3>Recent Articles</h3>
                <?php
                $recent = get_articles(5);
                foreach($recent as $article) {
                    echo '<div class="article-link">';
                    echo '<a href="/article/' . $article['slug'] . '">';
                    echo $article['title'];
                    echo '</a>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </div>
    
    <?php include 'includes/footer.php'; ?>
</body>
</html>
```

### .htaccess Configuration

```apache
# Enable mod_rewrite
RewriteEngine On

# Redirect all 404 errors to 404.php
ErrorDocument 404 /404.php

# Clean URLs
RewriteRule ^article/([a-z0-9-]+)$ article.php?slug=$1 [L]
RewriteRule ^category/([a-z0-9-]+)$ category.php?slug=$1 [L]
RewriteRule ^state/([a-z0-9-]+)$ state.php?slug=$1 [L]
```

### PHP Error Handling in Pages

**In article.php:**
```php
<?php
$slug = $_GET['slug'] ?? '';
$article = get_article_by_slug($slug);

if (!$article) {
    // Article not found, redirect to 404
    header("HTTP/1.0 404 Not Found");
    include '404.php';
    exit;
}

// Display article...
?>
```

**In category.php:**
```php
<?php
$slug = $_GET['slug'] ?? '';
$category = get_category_by_slug($slug);

if (!$category) {
    // Category not found, redirect to 404
    header("HTTP/1.0 404 Not Found");
    include '404.php';
    exit;
}

// Display category...
?>
```

**In state.php:**
```php
<?php
$slug = $_GET['slug'] ?? '';
$state = get_state_by_slug($slug);

if (!$state) {
    // State not found, redirect to 404
    header("HTTP/1.0 404 Not Found");
    include '404.php';
    exit;
}

// Display state...
?>
```

### 404 Page Features

✅ **User-Friendly Message** - Clear explanation of what happened
✅ **Search Box** - Let users search for what they need
✅ **Quick Links** - Homepage and popular categories
✅ **Recent Articles** - Show latest 5 articles
✅ **Proper HTTP Status** - Returns 404 status code
✅ **Same Header/Footer** - Consistent site design

---

## Quick Reference: Article Creation Examples

### Example 1: Standard Image Article
```
Title: "Bank Robbery in Mumbai"
Description: "Major bank robbery reported..."
Media Type: ● Image
Upload: robbery-photo.jpg
Category: Crime
States: ☑ Maharashtra
Content: Full article text...
Date: ○ Use Current Date/Time
Status: Published

Result: Shows "2 hours ago" on frontend
```

### Example 2: Video Article
```
Title: "Cricket Match Highlights"
Description: "Watch the exciting highlights..."
Media Type: ● Video
Video URL: https://www.youtube.com/watch?v=ABC123
Thumbnail: cricket-thumb.jpg
Category: Sports
Date: ○ Use Current Date/Time
Status: Published

Result: Card shows thumbnail, article page shows video player
```

### Example 3: External Link Article
```
Title: "Breaking International News"
Description: "Read full story on partner site..."
Media Type: ● External URL
External URL: https://partnernews.com/article-123
Thumbnail: news-thumb.jpg
Category: World
Date: ○ Use Current Date/Time
Status: Published

Result: Card links directly to external site
```

### Example 4: Backdated Article (Old News Import)
```
Title: "Historical Event from 2020"
Description: "Looking back at this event..."
Media Type: ● Image
Upload: historical-photo.jpg
Category: History
Date: ● Set Custom Date/Time
  Date: 15/03/2020
  Time: 10:00 AM
Status: Published

Result: Shows "Published on March 15, 2020"
```

---

## Summary: What We're Building

### ✅ Core Features (Simple but Complete)
1. **Admin Panel** - Login, dashboard, manage everything
2. **Categories** - Add/edit/delete with manual ordering
3. **Articles** - Full CRUD with 3 media types
4. **States** - Add/edit/delete, link to articles
5. **Media Types** - Image, Video, External URL
6. **Timestamps** - Auto (default) or Manual (for old posts)
7. **Live News** - One YouTube link
8. **Settings** - Site info, social links
9. **Frontend** - Homepage, category pages, state pages, article pages
10. **404 Page** - Custom error page with helpful links

### 📊 Database
- 7 simple tables
- No complex relationships
- Easy to understand

### ⏱️ Timeline
- 8-10 days for complete working CMS
- Can be extended later with advanced features

### 🎯 Result
A **functional, clean news CMS** that:
- Admin can easily manage content
- Supports images, videos, and external links
- Can import old articles with custom dates
- Displays beautifully on frontend
- Easy to maintain and extend

---

**This is a SIMPLE, WORKING CMS. No complexity. Just core features. We can add advanced features later once the basics work perfectly.**

