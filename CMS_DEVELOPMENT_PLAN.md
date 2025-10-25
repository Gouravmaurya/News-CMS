# News CMS Development Plan

## Project Overview
A clean, component-based Content Management System (CMS) for a news website using PHP and MySQL with modular architecture.

---

## 1. Project Structure

```
news-cms/
├── config/
│   ├── database.php          # Database connection
│   └── config.php             # Site configuration
├── includes/
│   ├── header.php             # Header component
│   ├── footer.php             # Footer component
│   ├── navigation.php         # Navigation menu
│   └── functions.php          # Helper functions
├── admin/
│   ├── index.php              # Admin dashboard
│   ├── login.php              # Admin login
│   ├── logout.php             # Logout handler
│   ├── articles/
│   │   ├── create.php         # Create article
│   │   ├── edit.php           # Edit article
│   │   ├── delete.php         # Delete article
│   │   └── list.php           # List all articles
│   ├── categories/
│   │   ├── manage.php         # Manage categories
│   │   └── actions.php        # Category CRUD
│   ├── navigation/
│   │   ├── manage.php         # Manage navigation menu
│   │   ├── create.php         # Add menu item
│   │   ├── edit.php           # Edit menu item
│   │   ├── reorder.php        # Drag-drop reordering
│   │   └── delete.php         # Delete menu item
│   ├── pages/
│   │   ├── list.php           # List dynamic pages
│   │   ├── create.php         # Create new page
│   │   ├── edit.php           # Edit page
│   │   └── delete.php         # Delete page
│   ├── states/
│   │   ├── manage.php         # Manage states
│   │   ├── create.php         # Add state
│   │   ├── edit.php           # Edit state
│   │   └── delete.php         # Delete state
│   ├── settings/
│   │   ├── site.php           # Site settings
│   │   ├── social.php         # Social media links
│   │   └── live.php           # Live news settings
│   └── includes/
│       ├── admin_header.php   # Admin header
│       └── admin_footer.php   # Admin footer
├── assets/
│   ├── css/
│   │   ├── style.css          # Frontend styles
│   │   └── admin.css          # Admin styles
│   ├── js/
│   │   ├── main.js            # Frontend scripts
│   │   └── admin.js           # Admin scripts
│   └── uploads/               # Uploaded images
├── index.php                  # Homepage
├── article.php                # Single article view
├── category.php               # Category page
├── state.php                  # State-wise news page
├── page.php                   # Dynamic page handler
├── search.php                 # Search results
└── live.php                   # Live news page
```

---

## 2. Database Schema

### Tables Required

#### `users` - Admin users
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- username (VARCHAR 50, UNIQUE)
- email (VARCHAR 100, UNIQUE)
- password (VARCHAR 255) - hashed
- role (ENUM: 'admin', 'editor', 'author')
- created_at (TIMESTAMP)
```

#### `categories` - News categories (Auto-creates dynamic pages)
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- name (VARCHAR 100)
- slug (VARCHAR 100, UNIQUE)
- description (TEXT)
- featured_image (VARCHAR 255)
- icon_class (VARCHAR 100) - Font Awesome icon
- show_in_menu (BOOLEAN, DEFAULT 1)
- menu_order (INT)
- is_active (BOOLEAN, DEFAULT 1)
- created_at (TIMESTAMP)
```

#### `articles` - News articles
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- title (VARCHAR 255)
- slug (VARCHAR 255, UNIQUE)
- description (TEXT) - Short description/excerpt
- content (LONGTEXT) - Full article content
- media_type (ENUM: 'image', 'video', 'url') - Type of featured media
- media_url (VARCHAR 500) - Image path, video URL, or external URL
- video_embed_code (TEXT) - For YouTube/Vimeo embed
- category_id (INT, FOREIGN KEY)
- author_id (INT, FOREIGN KEY)
- status (ENUM: 'draft', 'published')
- views (INT, DEFAULT 0)
- is_featured (BOOLEAN, DEFAULT 0)
- use_custom_date (BOOLEAN, DEFAULT 0) - Use manual date or auto
- custom_publish_date (DATETIME NULL) - Manual date for old posts
- created_at (TIMESTAMP DEFAULT CURRENT_TIMESTAMP) - Auto-generated
- updated_at (TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP) - Auto-updated
- published_at (TIMESTAMP NULL) - When article was published
```

#### `site_settings` - Site configuration
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- setting_key (VARCHAR 100, UNIQUE)
- setting_value (TEXT)
- updated_at (TIMESTAMP)
```

#### `live_news` - Live news/YouTube stream settings
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- title (VARCHAR 255)
- description (TEXT)
- youtube_url (VARCHAR 255) - Full YouTube URL
- youtube_embed_id (VARCHAR 100) - Extracted video/stream ID
- is_active (BOOLEAN, DEFAULT 1)
- created_at (TIMESTAMP DEFAULT CURRENT_TIMESTAMP)
- updated_at (TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP)
```

#### `social_links` - Social media links
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- platform (VARCHAR 50)
- url (VARCHAR 255)
- icon_class (VARCHAR 100)
- is_active (BOOLEAN, DEFAULT 1)
```

#### `sliders` - Hero slider content
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- title (VARCHAR 255)
- description (TEXT)
- image (VARCHAR 255)
- link (VARCHAR 255)
- order_position (INT)
- is_active (BOOLEAN, DEFAULT 1)
```

#### `newsletter_subscribers` - Email subscribers
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- email (VARCHAR 100, UNIQUE)
- subscribed_at (TIMESTAMP)
- is_active (BOOLEAN, DEFAULT 1)
```

#### `navigation_menu` - Dynamic navigation tabs
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- title (VARCHAR 100)
- slug (VARCHAR 100, UNIQUE)
- parent_id (INT, DEFAULT NULL) - for dropdown menus
- page_id (INT, FOREIGN KEY, NULL) - link to dynamic page
- external_url (VARCHAR 255, NULL) - for external links
- order_position (INT)
- is_active (BOOLEAN, DEFAULT 1)
- show_in_header (BOOLEAN, DEFAULT 1)
- show_in_footer (BOOLEAN, DEFAULT 1)
- created_at (TIMESTAMP)
```

#### `dynamic_pages` - Custom pages
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- title (VARCHAR 255)
- slug (VARCHAR 255, UNIQUE)
- content (LONGTEXT)
- meta_title (VARCHAR 255)
- meta_description (TEXT)
- template (ENUM: 'default', 'full-width', 'sidebar')
- is_active (BOOLEAN, DEFAULT 1)
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

#### `states` - Indian states management
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- name (VARCHAR 100)
- slug (VARCHAR 100, UNIQUE)
- description (TEXT)
- featured_image (VARCHAR 255)
- is_active (BOOLEAN, DEFAULT 1)
- order_position (INT)
- created_at (TIMESTAMP)
```

#### `state_articles` - Articles linked to states
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- article_id (INT, FOREIGN KEY)
- state_id (INT, FOREIGN KEY)
- created_at (TIMESTAMP)
- UNIQUE KEY (article_id, state_id)
```

#### `tags` - Article tags/keywords
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- name (VARCHAR 100)
- slug (VARCHAR 100, UNIQUE)
- description (TEXT)
- is_active (BOOLEAN, DEFAULT 1)
- created_at (TIMESTAMP DEFAULT CURRENT_TIMESTAMP)
```

#### `article_tags` - Articles linked to tags (Many-to-Many)
```sql
- id (INT, PRIMARY KEY, AUTO_INCREMENT)
- article_id (INT, FOREIGN KEY)
- tag_id (INT, FOREIGN KEY)
- created_at (TIMESTAMP)
- UNIQUE KEY (article_id, tag_id)
```

---

## 3. Core Features

### Frontend Features
- ✅ Dynamic homepage with latest articles
- ✅ Category-based article filtering
- ✅ State-wise news filtering
- ✅ Dynamic navigation menu (admin controlled)
- ✅ Dynamic custom pages
- ✅ Article search functionality
- ✅ Single article view with related articles
- ✅ **Live news section** (YouTube embed - admin controlled)
- ✅ Newsletter subscription
- ✅ Responsive design
- ✅ Hero slider management
- ✅ Social media integration
- ✅ Breadcrumb navigation
- ✅ **Auto-display article date/time** (human-readable format)

### Admin Panel Features
- ✅ Secure login system
- ✅ Dashboard with statistics
- ✅ **Article management** (CRUD with auto timestamp)
- ✅ **Quick article creation** (Title, Description, Image, Category)
- ✅ **Auto date/time tracking** (created_at, updated_at, published_at)
- ✅ Category management
- ✅ **Navigation menu builder** (add/edit/delete/reorder)
- ✅ **Dynamic page creator** (create custom pages)
- ✅ **State management** (add/edit/delete Indian states)
- ✅ **State-article linking** (tag articles with states)
- ✅ **Live news management** (YouTube link control)
- ✅ Image upload system with preview
- ✅ Site settings management
- ✅ Social media links management
- ✅ Slider content management
- ✅ Newsletter subscriber list
- ✅ User management
- ✅ Drag-and-drop menu ordering

---

## 4. Security Features

- Password hashing (bcrypt)
- SQL injection prevention (prepared statements)
- XSS protection (htmlspecialchars)
- CSRF token protection
- Session management
- File upload validation
- Admin authentication middleware

---

## 5. Component Architecture

### Header Component (`includes/header.php`)
- Site logo
- Navigation menu (dynamic from categories)
- Search bar
- Social media links (from database)
- Top header bar with contact info

### Footer Component (`includes/footer.php`)
- Footer columns (dynamic)
- Newsletter form
- Social links
- Copyright info
- Quick links

### Navigation Component (`includes/navigation.php`)
- **Fully dynamic menu from database** (`navigation_menu` table)
- **Multi-level dropdown support** (parent-child relationships)
- **States dropdown** (populated from `states` table)
- Active page highlighting
- Support for internal pages and external links
- Conditional display (header/footer)

---

## 6. Development Phases

### Phase 1: Setup & Database
1. Create database structure
2. Setup configuration files
3. Create database connection
4. Build helper functions

### Phase 2: Admin Panel
1. Admin login system
2. Dashboard layout
3. Article CRUD operations
4. Category management
5. **Navigation menu builder**
6. **Dynamic page creator**
7. **State management system**
8. Settings management

### Phase 3: Frontend
1. Convert HTML to PHP components
2. **Dynamic navigation system**
3. Homepage with dynamic content
4. Category pages
5. **State-wise news pages**
6. **Dynamic page handler**
7. Single article page
8. Search functionality with state filter

### Phase 4: Additional Features
1. Image upload system
2. Slider management
3. Newsletter system
4. Statistics tracking
5. SEO optimization

### Phase 5: Testing & Deployment
1. Security testing
2. Performance optimization
3. Cross-browser testing
4. Documentation
5. Deployment setup

---

## 7. Key PHP Functions to Build

### Database Functions
- `db_connect()` - Database connection
- `db_query()` - Execute queries safely
- `db_fetch()` - Fetch results
- `db_insert()` - Insert records
- `db_update()` - Update records
- `db_delete()` - Delete records

### Article Functions
- `get_articles($limit, $category, $status)`
- `get_article_by_slug($slug)`
- `get_featured_articles($limit)`
- `get_related_articles($category_id, $exclude_id)`
- `increment_article_views($id)`
- `get_article_media($article)` - Get media based on type (image/video/url)
- `render_article_card($article)` - Render card with appropriate media type

### Category Functions
- `get_categories()`
- `get_category_by_slug($slug)`
- `get_articles_by_category($category_id)`

### Navigation Functions
- `get_navigation_menu($location)` - Get menu items for header/footer
- `get_menu_item_by_id($id)`
- `get_child_menu_items($parent_id)`
- `update_menu_order($items_array)`
- `build_navigation_tree()` - Build hierarchical menu

### Dynamic Page Functions
- `get_page_by_slug($slug)`
- `get_all_pages($status)`
- `create_page($data)`
- `update_page($id, $data)`
- `delete_page($id)`

### State Functions
- `get_all_states($active_only)`
- `get_state_by_slug($slug)`
- `get_articles_by_state($state_id, $limit)`
- `link_article_to_state($article_id, $state_id)`
- `get_article_states($article_id)` - Get all states for an article
- `update_article_states($article_id, $state_ids)` - Update state associations

### Settings Functions
- `get_setting($key)`
- `update_setting($key, $value)`
- `get_social_links()`
- `get_live_news()` - Get active live stream details
- `update_live_news($data)` - Update live stream settings

### Helper Functions
- `sanitize_input($data)`
- `generate_slug($string)`
- `upload_image($file, $path)`
- `format_date($date)` - Convert timestamp to readable format
- `time_ago($timestamp)` - "2 hours ago", "3 days ago"
- `truncate_text($text, $length)`
- `extract_youtube_id($url)` - Extract video ID from YouTube URL
- `extract_vimeo_id($url)` - Extract video ID from Vimeo URL
- `generate_video_embed($url, $type)` - Generate embed code for video
- `optimize_image($file, $max_width, $max_height)` - Resize and compress
- `get_article_date($article)` - Get display date (auto or custom)
- `validate_external_url($url)` - Validate external URL format

---

## 8. Admin Panel Pages

### Dashboard (`admin/index.php`)
- Total articles count
- Total categories
- Total views
- Recent articles
- Quick actions

### Article Management (`admin/articles/`)

#### Create New Article (`create.php`)
**Form Fields:**
1. **Title** (Required)
   - Text input
   - Auto-generates slug on blur

2. **Description** (Required)
   - Textarea (200-300 characters)
   - Short summary for article cards

3. **Featured Media** (Required)
   - **Media Type Selection:**
     - ○ Image Upload
     - ○ Video URL (YouTube/Vimeo)
     - ○ External URL Link
   
   **If Image Selected:**
   - Image upload with preview
   - Drag-and-drop support
   - Auto-resize and optimize
   - Recommended size: 1200x630px
   - Formats: JPG, PNG, WebP
   
   **If Video Selected:**
   - YouTube URL input
   - Vimeo URL input
   - Auto-extract embed code
   - Video preview
   - Example: `https://www.youtube.com/watch?v=VIDEO_ID`
   
   **If URL Selected:**
   - External URL input
   - Link preview/thumbnail
   - Open in new tab option
   - Example: `https://example.com/news-source`

4. **Category** (Required)
   - Dropdown select (Crime, Politics, Sports, etc.)

5. **States** (Optional)
   - Multi-select checkboxes
   - Tag article with relevant states

6. **Content** (Required)
   - Rich text editor (TinyMCE/CKEditor)
   - Image insertion
   - Video embed
   - Text formatting

7. **Date & Time Settings**
   - **Time Mode Selection:**
     - ● Live Time (Use current date/time - Default)
     - ○ Manual Time (Set custom date/time for old posts)
   
   **If Live Time:**
   - System automatically uses current timestamp
   - Shows: "Will be published at: [Current Date/Time]"
   
   **If Manual Time:**
   - Date Picker: Select date
   - Time Picker: Select time (HH:MM)
   - Use case: Uploading old news articles
   - Example: Backdating article to "October 20, 2024 10:30 AM"

8. **Status**
   - Radio buttons: Draft / Published
   - If Published → Sets published_at timestamp

9. **Featured Article**
   - Checkbox: Mark as featured (shows on homepage)

10. **Auto-Generated Fields:**
    - **Created At** - Automatically set when article is created
    - **Updated At** - Automatically updated on every edit
    - **Published At** - Set based on Live/Manual time selection
    - **Author** - Current logged-in admin user

**Buttons:**
- Save as Draft
- Publish Now
- Preview
- Cancel

#### List Articles (`list.php`)
- Paginated table view
- Columns: Image, Title, Category, States, Views, Status, Date, Actions
- Filter by: Category, State, Status, Date Range
- Search by title
- Bulk actions: Delete, Change Status
- Sort by: Date, Views, Title

#### Edit Article (`edit.php`)
- Same form as create
- Pre-filled with existing data
- Shows "Last Updated" timestamp
- Update button

#### Delete Article (`delete.php`)
- Confirmation dialog
- Soft delete (mark as deleted, don't remove from DB)
- Option to permanently delete

### Category Management (`admin/categories/manage.php`)
- **Add new category** (automatically creates a dynamic page)
  - Category name (e.g., "Crime", "Politics", "Sports")
  - Auto-generated slug (e.g., "crime", "politics", "sports")
  - Category description
  - Upload featured image
  - Choose icon (Font Awesome)
  - Show in navigation menu (Yes/No)
  - Menu order position
- **Edit category** - Update details, image, description
- **Delete category** - Remove category (with warning if articles exist)
- **View articles per category** - See all articles in this category
- **Reorder categories** - Drag-and-drop menu ordering

**How it works:**
1. Admin creates "Crime" category
2. System automatically creates `/category/crime` page
3. Page displays all articles tagged with "Crime" category
4. If "Show in Menu" is enabled → "Crime" appears in navigation
5. Users click "Crime" → See all crime-related news

### Navigation Menu Builder (`admin/navigation/manage.php`)
- **Visual menu builder interface**
- Add new menu items (link to pages, categories, or external URLs)
- Edit menu items (title, link, icon)
- Delete menu items
- **Drag-and-drop reordering**
- Create dropdown menus (parent-child)
- Toggle visibility (header/footer)
- Set menu item status (active/inactive)

### Custom Static Page Management (`admin/pages/`)
**Note:** These are for static content pages like "About Us", "Privacy Policy", "Contact Us"

- **Create custom pages** with rich text editor
- Edit page content and settings
- Choose page template (default, full-width, sidebar)
- SEO settings (meta title, description)
- Page slug management
- Publish/Draft status
- Delete pages

**Difference from Category Pages:**
- **Category Pages** = Automatically show articles (Crime, Sports, Politics)
- **Static Pages** = Custom content written by admin (About Us, Privacy Policy)

### State Management (`admin/states/`)
- **Add Indian states** (name, slug, description)
- Upload state featured images
- Edit state information
- Delete states
- Reorder states
- Activate/Deactivate states
- View articles per state

### Article-State Linking
- **Multi-select states** when creating/editing articles
- Tag articles with one or multiple states
- Filter articles by state in admin panel
- Bulk state assignment

### Settings

#### Site Settings (`admin/settings/site.php`)
- Site name, logo, contact info
- About us content
- SEO settings (meta title, description)
- Contact email and phone
- Footer copyright text

#### Social Media Links (`admin/settings/social.php`)
- Add/Edit/Delete social media links
- Platforms: Instagram, Facebook, YouTube, Snapchat, Twitter, LinkedIn
- URL and icon management
- Show/Hide toggle

#### Live News Settings (`admin/settings/live.php`)
**Manage Live YouTube Stream**

**Form Fields:**
1. **Title** (e.g., "Watch N9 India Live 24/7")
2. **Description** (e.g., "Stay updated with real-time news coverage")
3. **YouTube URL** (Full URL)
   - Example: `https://www.youtube.com/watch?v=I1JL72pbRq4`
   - Example: `https://www.youtube.com/embed/I1JL72pbRq4`
   - System auto-extracts video ID
4. **Status** - Active/Inactive toggle
5. **Auto-Generated:**
   - Created At (timestamp)
   - Updated At (timestamp)

**Features:**
- Preview live stream before saving
- Test embed functionality
- Quick enable/disable without deleting
- History of previous live streams

**Frontend Display:**
- Shows on `/live.php` page
- Embedded YouTube player
- Title and description below player
- Auto-updates when admin changes link

---

## 9. Frontend Pages

### Homepage (`index.php`)
- Hero slider
- Featured articles
- Latest articles by category
- About section
- Live news section

### Category Page (`category.php`) - **AUTO-GENERATED**
**Automatically created when admin adds a category**

**Layout:**
1. **Category Header**
   - Category name (e.g., "Crime News")
   - Featured image banner
   - Category description
   - Total articles count

2. **Filter & Sort Options**
   - Sort by: Latest, Most Viewed, Trending
   - Date range filter
   - State filter (show crime news from specific state)

3. **Articles Grid**
   - All articles in this category
   - Card layout: Image, Title, Excerpt, Date, Views
   - Pagination (20 articles per page)

4. **Sidebar**
   - Other categories
   - Popular articles in this category
   - Related categories

**Examples:**
- Admin creates "Crime" → `/category/crime` shows all crime articles
- Admin creates "Politics" → `/category/politics` shows all politics articles
- Admin creates "Sports" → `/category/sports` shows all sports articles

### Article Page (`article.php`)
- Full article content
- Author info
- Related articles
- Social sharing

### Search Page (`search.php`)
- Search results
- Filters by category
- Filters by state
- Pagination

### State Page (`state.php`)
- **State-specific news listing**
- State information header (name, description, image)
- Articles filtered by selected state
- Pagination
- Related states sidebar
- Breadcrumb navigation

### Dynamic Page (`page.php`)
- **Render custom pages** created from admin
- Support multiple templates
- SEO meta tags
- Breadcrumb navigation
- Custom content layout

---

## 10. Best Practices

### Code Organization
- Separate logic from presentation
- Use includes for reusable components
- Follow PSR coding standards
- Comment complex logic

### Security
- Never trust user input
- Use prepared statements
- Validate and sanitize all inputs
- Implement CSRF protection
- Use HTTPS in production

### Performance
- Use indexes on database tables
- Implement pagination
- Optimize images
- Cache frequently accessed data
- Minimize database queries

### Maintainability
- Use meaningful variable names
- Keep functions small and focused
- Document code properly
- Use version control (Git)

---

## 11. Required PHP Extensions

- PDO (MySQL)
- GD or Imagick (image processing)
- mbstring (string handling)
- JSON (data handling)

---

## 12. Environment Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- mod_rewrite enabled (for clean URLs)

---

## 13. Media Types & Timestamp System

### Three Types of Featured Media

#### 1. Image (Default)
**Use Case:** Standard news articles with photos

**Admin Workflow:**
- Select "Image" radio button
- Upload image via drag-and-drop or file picker
- System auto-resizes to 1200x630px
- Image stored in `/assets/uploads/articles/`
- Path saved to `media_url` field

**Frontend Display:**
```html
<div class="article-card">
  <img src="/assets/uploads/articles/image.jpg" alt="Article Title">
  <h3>Article Title</h3>
  <p>Description...</p>
</div>
```

#### 2. Video (YouTube/Vimeo)
**Use Case:** Video news, interviews, live coverage highlights

**Admin Workflow:**
- Select "Video" radio button
- Paste YouTube or Vimeo URL
- System extracts video ID automatically
- Generates embed code
- Stores in `video_embed_code` field

**Supported URL Formats:**
```
https://www.youtube.com/watch?v=VIDEO_ID
https://youtu.be/VIDEO_ID
https://www.youtube.com/embed/VIDEO_ID
https://vimeo.com/VIDEO_ID
```

**Frontend Display:**
```html
<div class="article-card video">
  <div class="video-player">
    <iframe src="https://www.youtube.com/embed/VIDEO_ID"></iframe>
  </div>
  <h3>Article Title</h3>
  <p>Description...</p>
</div>
```

#### 3. External URL
**Use Case:** Link to partner sites, press releases, external sources

**Admin Workflow:**
- Select "External URL" radio button
- Enter external article URL
- Optionally upload thumbnail image
- Article card links to external site

**Frontend Display:**
```html
<div class="article-card external">
  <img src="thumbnail.jpg" alt="Article Title">
  <h3>Article Title</h3>
  <p>Description...</p>
  <a href="https://external-site.com/article" target="_blank">
    Read More <i class="fa-external-link"></i>
  </a>
</div>
```

---

### Timestamp System: Auto vs Manual

#### Option 1: Automatic Timestamp (Default)
**Use Case:** Current news articles

**How it works:**
- Admin selects "Use Current Date/Time"
- System automatically sets:
  - `created_at` = Current timestamp
  - `published_at` = Current timestamp (if published)
  - `use_custom_date` = 0

**Display:**
- "Published 2 hours ago"
- "Published yesterday"
- "Published 3 days ago"

#### Option 2: Custom/Manual Timestamp
**Use Case:** Backdated articles, historical content, imported old news

**How it works:**
- Admin selects "Set Custom Date/Time"
- Date picker appears
- Admin selects specific date and time
- System sets:
  - `use_custom_date` = 1
  - `custom_publish_date` = Selected date/time
  - `created_at` = Current timestamp (for tracking)

**Display:**
- "Published on October 15, 2023"
- "Published on January 1, 2020 at 10:30 AM"

**Example Use Cases:**
1. **Importing old articles** from previous website
2. **Historical content** (e.g., "Independence Day 1947")
3. **Backdating posts** for chronological order
4. **Scheduled content** that should appear as if published earlier

---

### Frontend Display Logic

```php
function get_article_date($article) {
    if ($article['use_custom_date'] == 1 && $article['custom_publish_date']) {
        // Use custom date for backdated posts
        return format_date($article['custom_publish_date']);
    } else {
        // Use published_at for current posts
        return time_ago($article['published_at']);
    }
}
```

**Result:**
- Recent articles: "2 hours ago", "Yesterday"
- Backdated articles: "October 15, 2023", "January 1, 2020"

---

## 14. Article Creation & Auto Timestamp System

### Article Creation Form - Detailed Breakdown

#### Admin Interface (`admin/articles/create.php`)

**Form Layout:**
```
┌─────────────────────────────────────────────────────┐
│  Create New Article                                  │
├─────────────────────────────────────────────────────┤
│                                                      │
│  Title: *                                            │
│  [_____________________________________________]     │
│                                                      │
│  Description: * (Short summary)                      │
│  [_____________________________________________]     │
│  [_____________________________________________]     │
│  [_____________________________________________]     │
│                                                      │
│  Featured Media Type: *                              │
│  ● Image    ○ Video    ○ External URL                │
│                                                      │
│  ┌─ If Image Selected ─────────────────────────┐    │
│  │  Upload Image:                               │    │
│  │  [Drag & Drop or Click to Upload]            │    │
│  │  [Preview Area]                              │    │
│  └──────────────────────────────────────────────┘    │
│                                                      │
│  ┌─ If Video Selected ─────────────────────────┐    │
│  │  Video URL (YouTube/Vimeo):                  │    │
│  │  [_____________________________________]     │    │
│  │  Example: https://www.youtube.com/watch?v=ID │    │
│  │  [Preview Player]                            │    │
│  └──────────────────────────────────────────────┘    │
│                                                      │
│  ┌─ If External URL Selected ──────────────────┐    │
│  │  External Article URL:                       │    │
│  │  [_____________________________________]     │    │
│  │  Example: https://example.com/article        │    │
│  │  Thumbnail Image (optional):                 │    │
│  │  [Upload]                                    │    │
│  └──────────────────────────────────────────────┘    │
│                                                      │
│  Category: *                                         │
│  [▼ Select Category]                                 │
│     - Crime                                          │
│     - Politics                                       │
│     - Sports                                         │
│     - Entertainment                                  │
│                                                      │
│  States: (Select all that apply)                     │
│  ☐ Andhra Pradesh    ☐ Karnataka    ☐ Maharashtra   │
│  ☐ Tamil Nadu        ☐ Gujarat      ☐ Delhi         │
│  ... (all states)                                    │
│                                                      │
│  Content: *                                          │
│  [Rich Text Editor - TinyMCE]                        │
│  [B] [I] [U] [Image] [Video] [Link]                 │
│  [_____________________________________________]     │
│  [_____________________________________________]     │
│                                                      │
│  Options:                                            │
│  ☐ Mark as Featured (Show on homepage)              │
│                                                      │
│  Publish Date & Time:                                │
│  ○ Use Current Date/Time (Automatic)                 │
│  ● Set Custom Date/Time (For old/backdated posts)   │
│                                                      │
│  ┌─ If Custom Date Selected ───────────────────┐    │
│  │  Date: [📅 24/10/2024]                       │    │
│  │  Time: [🕐 14:30]                            │    │
│  │  Note: Use this for importing old articles   │    │
│  └──────────────────────────────────────────────┘    │
│                                                      │
│  Status:                                             │
│  ○ Save as Draft    ● Publish Now                   │
│                                                      │
│  [Save Draft]  [Publish]  [Preview]  [Cancel]       │
│                                                      │
└─────────────────────────────────────────────────────┘
```

### Automatic Timestamp Handling

#### When Article is Created:
```php
// Backend automatically sets:
created_at = CURRENT_TIMESTAMP  // e.g., 2024-10-24 14:30:00
updated_at = CURRENT_TIMESTAMP  // e.g., 2024-10-24 14:30:00
published_at = NULL (if draft) OR CURRENT_TIMESTAMP (if published)
```

#### When Article is Edited:
```php
// Backend automatically updates:
updated_at = CURRENT_TIMESTAMP  // e.g., 2024-10-24 15:45:00
// created_at remains unchanged
// published_at remains unchanged (unless status changes)
```

#### When Draft is Published:
```php
// Backend automatically sets:
published_at = CURRENT_TIMESTAMP  // e.g., 2024-10-24 16:00:00
updated_at = CURRENT_TIMESTAMP
status = 'published'
```

### Frontend Display of Timestamps

#### Article Card (Homepage/Category Pages):
```html
<div class="article-card">
  <img src="featured-image.jpg">
  <h3>Major Bank Robbery in Chennai</h3>
  <p class="meta">
    <span class="category">Crime</span>
    <span class="time">2 hours ago</span>  ← Auto-calculated
  </p>
  <p class="description">A major bank robbery took place...</p>
</div>
```

#### Single Article Page:
```html
<article>
  <h1>Major Bank Robbery in Chennai</h1>
  <div class="article-meta">
    <span class="author">By Admin</span>
    <span class="date">Published: October 24, 2024 at 2:30 PM</span>
    <span class="updated">Last Updated: October 24, 2024 at 3:15 PM</span>
    <span class="views">1,234 views</span>
  </div>
  <img src="featured-image.jpg" class="featured-image">
  <div class="content">
    <!-- Full article content -->
  </div>
</article>
```

### Time Display Formats

#### Relative Time (for recent articles):
- Just now (< 1 minute)
- 5 minutes ago
- 2 hours ago
- Yesterday
- 3 days ago

#### Absolute Time (for older articles):
- October 24, 2024
- Oct 24, 2024 at 2:30 PM
- 24/10/2024

### PHP Helper Function Example:
```php
function time_ago($timestamp) {
    $time_ago = strtotime($timestamp);
    $current_time = time();
    $time_difference = $current_time - $time_ago;
    
    $seconds = $time_difference;
    $minutes = round($seconds / 60);
    $hours = round($seconds / 3600);
    $days = round($seconds / 86400);
    $weeks = round($seconds / 604800);
    $months = round($seconds / 2629440);
    $years = round($seconds / 31553280);
    
    if ($seconds <= 60) {
        return "Just now";
    } else if ($minutes <= 60) {
        return "$minutes minutes ago";
    } else if ($hours <= 24) {
        return "$hours hours ago";
    } else if ($days <= 7) {
        return "$days days ago";
    } else if ($weeks <= 4.3) {
        return "$weeks weeks ago";
    } else if ($months <= 12) {
        return "$months months ago";
    } else {
        return "$years years ago";
    }
}
```

---

## 14. Live News Management System

### Admin Interface (`admin/settings/live.php`)

**Form Layout:**
```
┌─────────────────────────────────────────────────────┐
│  Live News Settings                                  │
├─────────────────────────────────────────────────────┤
│                                                      │
│  Current Live Stream:                                │
│  ┌──────────────────────────────────────────────┐   │
│  │  [YouTube Player Preview]                    │   │
│  │  Watch N9 India Live 24/7                    │   │
│  │  Last Updated: 2 hours ago                   │   │
│  └──────────────────────────────────────────────┘   │
│                                                      │
│  Update Live Stream:                                 │
│                                                      │
│  Title: *                                            │
│  [_____________________________________________]     │
│                                                      │
│  Description:                                        │
│  [_____________________________________________]     │
│  [_____________________________________________]     │
│                                                      │
│  YouTube URL: *                                      │
│  [_____________________________________________]     │
│  Examples:                                           │
│  • https://www.youtube.com/watch?v=VIDEO_ID          │
│  • https://www.youtube.com/embed/VIDEO_ID            │
│  • https://youtu.be/VIDEO_ID                         │
│                                                      │
│  Status:                                             │
│  ● Active    ○ Inactive                              │
│                                                      │
│  [Preview]  [Save Changes]  [Cancel]                 │
│                                                      │
│  ─────────────────────────────────────────────────  │
│                                                      │
│  Previous Live Streams:                              │
│  • Cricket Match Live - 2 days ago                   │
│  • Breaking News Coverage - 1 week ago               │
│  • Election Results Live - 2 weeks ago               │
│                                                      │
└─────────────────────────────────────────────────────┘
```

### YouTube URL Processing

**Admin enters any YouTube URL format:**
```
https://www.youtube.com/watch?v=I1JL72pbRq4
https://www.youtube.com/embed/I1JL72pbRq4
https://youtu.be/I1JL72pbRq4
https://www.youtube.com/watch?v=I1JL72pbRq4&t=30s
```

**System automatically extracts video ID:**
```php
function extract_youtube_id($url) {
    preg_match('/[?&]v=([^&]+)/', $url, $matches);
    if (isset($matches[1])) {
        return $matches[1];
    }
    preg_match('/youtu\.be\/([^?]+)/', $url, $matches);
    if (isset($matches[1])) {
        return $matches[1];
    }
    preg_match('/embed\/([^?]+)/', $url, $matches);
    if (isset($matches[1])) {
        return $matches[1];
    }
    return false;
}
```

**Stores in database:**
```sql
youtube_url = "https://www.youtube.com/watch?v=I1JL72pbRq4"
youtube_embed_id = "I1JL72pbRq4"
```

### Frontend Display (`live.php`)

```php
<?php
$live_news = get_live_news();
if ($live_news && $live_news['is_active']) {
    $embed_url = "https://www.youtube.com/embed/" . $live_news['youtube_embed_id'];
?>
    <div class="live-section">
        <h2>🔴 <?php echo $live_news['title']; ?></h2>
        <div class="youtube-player">
            <iframe 
                width="100%" 
                height="500" 
                src="<?php echo $embed_url; ?>" 
                frameborder="0" 
                allowfullscreen>
            </iframe>
        </div>
        <p class="description"><?php echo $live_news['description']; ?></p>
        <p class="updated">Last Updated: <?php echo time_ago($live_news['updated_at']); ?></p>
    </div>
<?php } ?>
```

---

## 15. Understanding Dynamic Pages - Key Concept

### How Categories Create Dynamic Pages

**The Core Concept:**
When admin creates a **category** (like Crime, Politics, Sports), the system **automatically creates a page** that displays all articles in that category. This is NOT a static page with fixed content - it's a dynamic page that updates automatically as articles are added.

### Real-World Example:

#### Step 1: Admin Creates Categories
```
Admin Panel → Categories → Add New

1. Crime Category
   - Name: Crime
   - Slug: crime
   - Description: "Latest crime news"
   - Icon: fa-gavel
   - Show in Menu: Yes

2. Politics Category
   - Name: Politics
   - Slug: politics
   - Description: "Political news and updates"
   - Icon: fa-landmark
   - Show in Menu: Yes

3. Sports Category
   - Name: Sports
   - Slug: sports
   - Description: "Sports news and scores"
   - Icon: fa-futbol
   - Show in Menu: Yes
```

#### Step 2: System Auto-Creates Pages
```
✅ /category/crime → Shows all crime articles
✅ /category/politics → Shows all politics articles
✅ /category/sports → Shows all sports articles

Navigation menu automatically shows:
[Home] [Crime] [Politics] [Sports] [States ▼]
```

#### Step 3: Admin Creates Articles
```
Article 1:
- Title: "Bank Robbery in Mumbai"
- Category: Crime
- State: Maharashtra
→ Appears on /category/crime

Article 2:
- Title: "Election Results Announced"
- Category: Politics
- State: Uttar Pradesh
→ Appears on /category/politics

Article 3:
- Title: "India Wins Cricket Match"
- Category: Sports
- State: (National)
→ Appears on /category/sports
```

#### Step 4: Users Browse
```
User clicks "Crime" in menu
→ Goes to /category/crime
→ Sees ALL crime-related articles:
   - Bank Robbery in Mumbai
   - Cyber Crime Alert
   - Police Arrest Gang
   (All articles tagged with "Crime" category)

User clicks "Sports" in menu
→ Goes to /category/sports
→ Sees ALL sports-related articles:
   - India Wins Cricket Match
   - IPL Final Results
   - Olympic Medal Update
   (All articles tagged with "Sports" category)
```

### Two Types of Pages:

| Type | Purpose | Created By | Content |
|------|---------|------------|---------|
| **Category Pages** | Show articles by category | Auto-created when category is added | Dynamic - shows all articles in that category |
| **Static Pages** | Fixed content pages | Manually created by admin | Static - custom content written by admin |

**Category Pages Examples:**
- Crime, Politics, Sports, Entertainment, Business, Technology
- Content = All articles in that category

**Static Pages Examples:**
- About Us, Privacy Policy, Contact Us, Terms of Service
- Content = Fixed text written by admin

---

## 14. Navigation Menu Builder System

### Admin Interface Features

#### Menu Management Dashboard
- **Visual menu builder** with drag-and-drop interface
- Real-time preview of menu structure
- Add/Edit/Delete menu items
- Reorder menu items with drag-and-drop
- Create nested dropdowns (unlimited levels)

#### Menu Item Types
1. **Link to Dynamic Page** - Select from created pages
2. **Link to Category** - Auto-link to category pages
3. **Link to State** - Auto-link to state pages
4. **External URL** - Link to external websites
5. **Custom Link** - Any internal URL

#### Menu Item Properties
- Title (display name)
- Slug (URL identifier)
- Parent menu (for dropdowns)
- Icon class (Font Awesome icons)
- Order position
- Visibility (header/footer/both)
- Status (active/inactive)
- Open in new tab (for external links)

### Frontend Implementation
- Automatically generates navigation from database
- Supports multi-level dropdowns
- Active page highlighting
- Mobile-responsive hamburger menu
- Breadcrumb generation

---

## 14. Dynamic Page System (Two Types)

### Type 1: Category-Based Dynamic Pages (Automatic)

**How it works:**
When admin creates a category, the system automatically creates a dynamic page that displays all articles in that category.

#### Example Flow:
1. **Admin creates "Crime" category**
   - Name: Crime
   - Slug: crime (auto-generated)
   - Description: "Latest crime news from across India"
   - Featured Image: crime-banner.jpg
   - Show in Menu: Yes

2. **System automatically:**
   - Creates page accessible at `/category/crime`
   - Adds "Crime" to navigation menu
   - Displays all articles tagged with "Crime" category

3. **Frontend displays:**
   - Category header (name, description, image)
   - All crime-related articles in grid layout
   - Pagination
   - Filter options (sort by date, views, etc.)

#### Category Page (`category.php`)
```php
// URL: /category/crime

1. Get category slug from URL
2. Fetch category details from database
3. Get all articles where category_id = crime
4. Display category header
5. Show articles in grid/list layout
6. Add pagination
7. Show related categories sidebar
```

**Examples:**
- `/category/crime` → Shows all crime news
- `/category/politics` → Shows all politics news
- `/category/sports` → Shows all sports news
- `/category/entertainment` → Shows all entertainment news

---

### Type 2: Custom Static Pages (Manual)

**For pages like About Us, Privacy Policy, Contact Us, etc.**

#### Admin Creates Static Page
1. Click "Add New Page" in admin panel
2. Enter page title (auto-generates slug)
3. Add content using rich text editor (TinyMCE/CKEditor)
4. Choose page template
5. Set SEO meta tags
6. Publish or save as draft

#### Page Templates
- **Default** - Standard page with sidebar
- **Full Width** - No sidebar, full content width
- **Sidebar Left** - Content on right, sidebar on left

#### Page Features
- Rich text editor with media upload
- Custom CSS/JS per page (optional)
- Featured image support
- Publish date scheduling
- Page revisions (optional)
- SEO-friendly URLs

### Frontend Page Handler (`page.php`)
```php
// URL: /page.php?slug=about-us
// OR with mod_rewrite: /about-us

1. Get slug from URL
2. Query database for page content
3. Check if page is active
4. Load appropriate template
5. Render page with header/footer
6. Display breadcrumb navigation
```

---

## 15. State Management System

### State Administration

#### Add/Edit States
- State name (e.g., "Maharashtra", "Karnataka")
- Auto-generated slug (e.g., "maharashtra")
- State description
- Featured image upload
- Order position (for dropdown sorting)
- Activate/Deactivate state

#### State-Article Linking
When creating/editing an article:
- **Multi-select checkbox** for states
- Tag article with one or multiple states
- Example: Article about "Mumbai Traffic" → Tag with "Maharashtra"
- Example: Article about "Border Dispute" → Tag with "Karnataka" + "Maharashtra"

### Frontend State Features

#### States Dropdown in Navigation
```
States ▼
├── Andhra Pradesh
├── Arunachal Pradesh
├── Assam
├── Bihar
├── Chhattisgarh
├── ... (all active states from database)
```

#### State Page (`state.php?slug=maharashtra`)
**Layout:**
1. **State Header**
   - State name
   - Featured image banner
   - State description
   - Article count

2. **Filter Options**
   - Filter by category within state
   - Sort by (Latest, Most Viewed, Trending)
   - Date range filter

3. **Article Grid**
   - All articles tagged with this state
   - Pagination (20 articles per page)
   - Card layout with image, title, excerpt

4. **Sidebar**
   - Other states quick links
   - Popular articles in this state
   - State statistics

#### State Filter in Search
- Search page includes state filter dropdown
- Users can search: "keyword" + filter by "State"
- Example: Search "election" in "Uttar Pradesh"

---

## 16. Database Relationships

### Navigation Menu Relationships
```
navigation_menu
├── parent_id → navigation_menu.id (self-referencing)
├── page_id → dynamic_pages.id
└── (external_url for external links)
```

### State-Article Relationships
```
articles ←→ state_articles ←→ states
(Many-to-Many relationship)

One article can belong to multiple states
One state can have multiple articles
```

### Example Queries

#### Get all menu items with hierarchy
```sql
SELECT * FROM navigation_menu 
WHERE parent_id IS NULL AND is_active = 1 
ORDER BY order_position ASC
```

#### Get articles for a specific state
```sql
SELECT a.* FROM articles a
INNER JOIN state_articles sa ON a.id = sa.article_id
INNER JOIN states s ON sa.state_id = s.id
WHERE s.slug = 'maharashtra' AND a.status = 'published'
ORDER BY a.created_at DESC
```

#### Get all states for an article
```sql
SELECT s.* FROM states s
INNER JOIN state_articles sa ON s.id = sa.state_id
WHERE sa.article_id = 123
```

---

## 17. Admin Panel Workflow Examples

### Example 1: Creating a Navigation Menu
1. Admin logs in
2. Goes to "Navigation" → "Manage Menu"
3. Clicks "Add Menu Item"
4. Fills form:
   - Title: "About Us"
   - Type: "Link to Page"
   - Select Page: "About Us" (from dynamic pages)
   - Show in: Header + Footer
   - Order: 5
5. Saves → Menu item appears in navigation

### Example 2: Creating a Category (Auto-creates Dynamic Page)
1. Goes to "Categories" → "Add New"
2. Fills form:
   - Name: "Crime"
   - Description: "Latest crime news from India"
   - Upload featured image
   - Icon: fa-gavel
   - Show in Menu: Yes
   - Menu Order: 3
3. Saves → System automatically:
   - Creates `/category/crime` page
   - Adds "Crime" to navigation menu
   - Page displays all crime-related articles

### Example 2b: Creating a Static Page
1. Goes to "Pages" → "Add New"
2. Fills form:
   - Title: "Privacy Policy"
   - Content: (Rich text editor with custom content)
   - Template: Full Width
   - Meta Title: "Privacy Policy - News Website"
   - Meta Description: "Read our privacy policy..."
3. Publishes → Page accessible at `/privacy-policy`

### Example 3: Managing States
1. Goes to "States" → "Manage States"
2. Clicks "Add State"
3. Fills form:
   - Name: "Tamil Nadu"
   - Description: "News from Tamil Nadu"
   - Upload featured image
4. Saves → State appears in dropdown

### Example 4A: Creating Article with Image (Auto Timestamp)
1. Goes to "Articles" → "Create New"
2. Fills form:
   - **Title:** "Major Bank Robbery in Chennai"
   - **Description:** "A major bank robbery took place in Chennai today, police investigating the case"
   - **Media Type:** ● Image
   - **Upload Image:** Drag-drop image (auto-resized to 1200x630)
   - **Category:** Crime
   - **States:** ☑ Tamil Nadu
   - **Content:** (Full article with rich text editor)
   - **Publish Date:** ○ Use Current Date/Time (Automatic)
   - **Featured:** ☑ Yes
   - **Status:** Published

3. Clicks "Publish Now"

4. **System Automatically:**
   - Generates slug: "major-bank-robbery-in-chennai"
   - Sets `created_at`: 2024-10-24 14:30:00 (current timestamp)
   - Sets `published_at`: 2024-10-24 14:30:00
   - Sets `media_type`: 'image'
   - Saves image path to `media_url`
   - Sets `author_id`: Current logged-in admin
   - Links article to Tamil Nadu state

5. **Article appears with image thumbnail**

### Example 4B: Creating Article with Video
1. Goes to "Articles" → "Create New"
2. Fills form:
   - **Title:** "Live Coverage: Cricket Match Highlights"
   - **Description:** "Watch the exciting highlights from today's match"
   - **Media Type:** ● Video
   - **Video URL:** `https://www.youtube.com/watch?v=VIDEO_ID`
   - **Category:** Sports
   - **States:** (National)
   - **Content:** (Article text)
   - **Publish Date:** ○ Use Current Date/Time
   - **Status:** Published

3. **System Automatically:**
   - Extracts video ID from URL
   - Stores embed code in `video_embed_code`
   - Sets `media_type`: 'video'
   - Creates video player on article page

4. **Article displays with embedded video player**

### Example 4C: Creating Article with External URL
1. Goes to "Articles" → "Create New"
2. Fills form:
   - **Title:** "Breaking: International News Update"
   - **Description:** "Read the full story on our partner site"
   - **Media Type:** ● External URL
   - **External URL:** `https://partnernews.com/article-123`
   - **Thumbnail:** Upload preview image
   - **Category:** World News
   - **Publish Date:** ○ Use Current Date/Time
   - **Status:** Published

3. **Article card shows with "Read More" link to external site**

### Example 4D: Creating Backdated Article (Old News Import)
1. Goes to "Articles" → "Create New"
2. Fills form:
   - **Title:** "Historical Event: Independence Day 1947"
   - **Description:** "Remembering the historic day"
   - **Media Type:** ● Image
   - **Upload Image:** historical-photo.jpg
   - **Category:** History
   - **Publish Date:** ● Set Custom Date/Time
     - **Date:** 15/08/1947
     - **Time:** 00:00
   - **Status:** Published

3. **System:**
   - Sets `use_custom_date`: 1
   - Sets `custom_publish_date`: 1947-08-15 00:00:00
   - Uses custom date for display instead of created_at
   - Article shows "Published on August 15, 1947"

4. **Use Case:** Perfect for importing old articles or historical content

### Example 5: Managing Live News Link
1. Goes to "Settings" → "Live News"
2. Sees current live stream (if any)
3. Clicks "Update Live Stream"
4. Fills form:
   - **Title:** "Watch N9 India Live 24/7"
   - **Description:** "Stay updated with real-time news coverage from N9 India"
   - **YouTube URL:** `https://www.youtube.com/watch?v=I1JL72pbRq4`
   - **Status:** Active
5. System automatically:
   - Extracts video ID: `I1JL72pbRq4`
   - Creates embed URL: `https://www.youtube.com/embed/I1JL72pbRq4`
   - Sets `updated_at` timestamp
6. Clicks "Save"
7. Live stream updates on `/live.php` page immediately

### Example 6: User Browsing Flow
**Scenario:** User wants to read crime news

1. User visits homepage
2. Sees "Crime" in navigation menu (added when admin created category)
3. Clicks "Crime"
4. Lands on `/category/crime`
5. Sees all crime-related articles with timestamps:
   - "Bank Robbery in Chennai" - **2 hours ago**
   - "Cyber Crime Alert in Mumbai" - **1 day ago**
   - "Police Arrest Gang in Delhi" - **3 days ago**
6. Can filter by state: "Show only Tamil Nadu crime news"
7. Clicks article to read full story
8. Article shows:
   - Title
   - Featured image
   - **Published:** October 24, 2024 at 2:30 PM
   - **Last Updated:** October 24, 2024 at 3:15 PM
   - Full content
   - Related articles

---

## 18. URL Structure

### Clean URLs (with mod_rewrite)
```
Homepage:           /
Article:            /article/article-slug
Category:           /category/category-slug
State:              /state/state-slug
Dynamic Page:       /page-slug
Search:             /search?q=keyword&state=maharashtra
Live News:          /live
```

### .htaccess Configuration
```apache
RewriteEngine On
RewriteRule ^article/([a-z0-9-]+)$ article.php?slug=$1 [L]
RewriteRule ^category/([a-z0-9-]+)$ category.php?slug=$1 [L]
RewriteRule ^state/([a-z0-9-]+)$ state.php?slug=$1 [L]
RewriteRule ^([a-z0-9-]+)$ page.php?slug=$1 [L]
```

---

## Next Steps

1. Review and approve this plan
2. Set up local development environment
3. Create database and tables
4. Start with Phase 1 implementation
5. Iterate through each phase

---

## Quick Reference: How Everything Works Together

### Admin Workflow:
```
1. Create Categories (Crime, Politics, Sports)
   ↓
2. System auto-creates category pages
   ↓
3. Categories appear in navigation menu
   ↓
4. Add Live News Link (YouTube URL)
   ↓
5. Create Articles (Title, Description, Image, Category)
   ↓
6. System automatically sets timestamps (created_at, published_at)
   ↓
7. Articles automatically appear on category pages
   ↓
8. Frontend shows "2 hours ago" timestamps
```

### Quick Article Creation (Admin):
```
1. Click "Add New Article"
2. Enter Title: "Breaking News Story"
3. Enter Description: "Short summary of the news"
4. Upload Image (drag & drop)
5. Select Category: "Crime"
6. Select States: "Maharashtra"
7. Write Content (rich text editor)
8. Click "Publish Now"
9. ✅ Done! Article is live with auto timestamp
```

### Live News Update (Admin):
```
1. Go to Settings → Live News
2. Paste YouTube URL: https://www.youtube.com/watch?v=VIDEO_ID
3. Enter Title: "Watch Live Coverage"
4. Enter Description: "Live news updates"
5. Set Status: Active
6. Click "Save"
7. ✅ Done! Live stream appears on /live.php
```

### User Experience:
```
User visits website
   ↓
Sees navigation: [Home] [Crime] [Politics] [Sports] [States ▼]
   ↓
Clicks "Crime"
   ↓
Sees page with ALL crime articles
   ↓
Can filter by state: "Show only Maharashtra crime news"
   ↓
Clicks article to read full story
```

### Database Flow:
```
categories table
   ↓ (category_id)
articles table
   ↓ (article_id)
state_articles table
   ↓ (state_id)
states table
```

### URL Structure Summary:
```
/                           → Homepage
/category/crime             → All crime articles (auto-generated)
/category/politics          → All politics articles (auto-generated)
/category/sports            → All sports articles (auto-generated)
/state/maharashtra          → All Maharashtra articles
/article/bank-robbery       → Single article view
/about-us                   → Static page (manually created)
/privacy-policy             → Static page (manually created)
```

---

---

## Summary: Complete Admin Features List

### 1. Article Management ✅
- **Create Article:** Title, Description, Media, Category, States, Content
- **Three Media Types:**
  - 📷 **Image:** Upload photos (auto-resize)
  - 🎥 **Video:** Embed YouTube/Vimeo
  - 🔗 **External URL:** Link to external articles
- **Timestamp Options:**
  - ⏰ **Automatic:** Use current date/time (default)
  - 📅 **Manual:** Set custom date for backdated posts
- **Auto Timestamps:** created_at, updated_at, published_at (automatic)
- **Rich Text Editor:** Format text, add images, videos
- **Draft/Publish:** Save as draft or publish immediately
- **Featured Articles:** Mark for homepage display
- **Edit/Delete:** Update or remove articles

### 2. Category Management ✅
- **Create Category:** Auto-creates dynamic page
- **Category Details:** Name, Description, Icon, Featured Image
- **Menu Control:** Show/hide in navigation
- **Reorder:** Drag-and-drop menu ordering
- **Auto Pages:** `/category/crime`, `/category/sports`, etc.

### 3. State Management ✅
- **Add States:** Indian states with descriptions
- **State Images:** Upload featured images
- **Link to Articles:** Tag articles with multiple states
- **State Pages:** Auto-generated state-wise news pages
- **Reorder:** Drag-and-drop state ordering

### 4. Live News Management ✅
- **YouTube Integration:** Paste any YouTube URL
- **Auto Extract:** System extracts video ID automatically
- **Title & Description:** Customize live stream info
- **Active/Inactive:** Toggle without deleting
- **Auto Timestamps:** Track when live link was updated
- **Preview:** Test before publishing

### 5. Navigation Menu Builder ✅
- **Visual Builder:** Drag-and-drop interface
- **Menu Types:** Link to pages, categories, states, external URLs
- **Multi-level:** Create dropdown menus
- **Reorder:** Drag-and-drop positioning
- **Visibility:** Show in header/footer

### 6. Settings Management ✅
- **Site Settings:** Name, logo, contact info
- **Social Media:** Add/edit social links
- **Live News:** Manage YouTube stream
- **SEO Settings:** Meta tags, descriptions

### 7. Dashboard ✅
- **Statistics:** Total articles, views, categories
- **Recent Articles:** Quick access to latest posts
- **Quick Actions:** Create article, manage categories
- **Activity Log:** Recent admin actions

### 8. User Management ✅
- **Admin Users:** Add/edit/delete admin accounts
- **Roles:** Admin, Editor, Author
- **Permissions:** Role-based access control

---

## Media Types Comparison Table

| Feature | Image | Video | External URL |
|---------|-------|-------|--------------|
| **Use Case** | Standard news articles | Video content, interviews | Partner articles, press releases |
| **Admin Input** | Upload image file | Paste YouTube/Vimeo URL | Enter external URL |
| **Storage** | Server (uploads folder) | URL only (no file) | URL only (no file) |
| **Frontend Display** | `<img>` tag | `<iframe>` embed | Link with thumbnail |
| **Click Action** | Opens article page | Opens article with video | Opens external site |
| **File Size** | Optimized to 1200x630 | N/A | Thumbnail only |
| **Best For** | Photos, graphics | Live coverage, highlights | Syndicated content |

## Timestamp Options Comparison

| Feature | Automatic (Default) | Manual/Custom |
|---------|---------------------|---------------|
| **Use Case** | Current news | Old articles, historical content |
| **Admin Input** | None (automatic) | Select date & time |
| **Display Format** | "2 hours ago", "Yesterday" | "October 15, 2023" |
| **Database Field** | `published_at` | `custom_publish_date` |
| **Best For** | Real-time news | Importing archives, backdating |
| **Example** | "Breaking: News just in" | "Historical: Independence Day 1947" |

## Key Automatic Features (No Manual Work Required)

✅ **Timestamps:** System automatically tracks created_at, updated_at, published_at  
✅ **Slugs:** Auto-generated from titles (e.g., "Breaking News" → "breaking-news")  
✅ **Time Display:** Shows "2 hours ago", "3 days ago" on frontend  
✅ **Image Optimization:** Auto-resize and compress uploaded images  
✅ **Video Embed:** Auto-extracts video ID from YouTube/Vimeo URLs  
✅ **Media Type Detection:** Automatically handles image/video/URL display  
✅ **Category Pages:** Auto-created when category is added  
✅ **State Pages:** Auto-created when state is added  
✅ **Navigation Menu:** Auto-updated when categories are added  
✅ **View Counter:** Auto-increments when article is viewed  
✅ **Related Articles:** Auto-suggested based on category  
✅ **Date Formatting:** Auto-converts timestamps to readable format  

---

## Code Examples: Media & Timestamp Handling

### PHP: Render Article Card Based on Media Type

```php
function render_article_card($article) {
    $date = get_article_date($article);
    
    echo '<div class="article-card">';
    
    // Render media based on type
    switch($article['media_type']) {
        case 'image':
            echo '<img src="' . $article['media_url'] . '" alt="' . $article['title'] . '">';
            echo '<a href="/article/' . $article['slug'] . '">';
            break;
            
        case 'video':
            echo '<div class="video-embed">';
            echo $article['video_embed_code'];
            echo '</div>';
            echo '<a href="/article/' . $article['slug'] . '">';
            break;
            
        case 'url':
            echo '<img src="' . $article['media_url'] . '" alt="' . $article['title'] . '">';
            echo '<a href="' . $article['external_url'] . '" target="_blank">';
            echo '<span class="external-icon"><i class="fa-external-link"></i></span>';
            break;
    }
    
    echo '<h3>' . $article['title'] . '</h3>';
    echo '<p class="meta">';
    echo '<span class="category">' . $article['category_name'] . '</span>';
    echo '<span class="date">' . $date . '</span>';
    echo '</p>';
    echo '<p class="description">' . $article['description'] . '</p>';
    echo '</a>';
    echo '</div>';
}
```

### PHP: Get Article Date (Auto or Custom)

```php
function get_article_date($article) {
    // Check if custom date is used
    if ($article['use_custom_date'] == 1 && !empty($article['custom_publish_date'])) {
        // For backdated posts, show full date
        return date('F j, Y', strtotime($article['custom_publish_date']));
    } else {
        // For current posts, show relative time
        return time_ago($article['published_at']);
    }
}
```

### PHP: Extract Video ID and Generate Embed

```php
function generate_video_embed($url) {
    // YouTube
    if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
        $video_id = extract_youtube_id($url);
        return '<iframe width="100%" height="400" src="https://www.youtube.com/embed/' . $video_id . '" frameborder="0" allowfullscreen></iframe>';
    }
    
    // Vimeo
    if (strpos($url, 'vimeo.com') !== false) {
        $video_id = extract_vimeo_id($url);
        return '<iframe width="100%" height="400" src="https://player.vimeo.com/video/' . $video_id . '" frameborder="0" allowfullscreen></iframe>';
    }
    
    return false;
}

function extract_youtube_id($url) {
    preg_match('/[?&]v=([^&]+)/', $url, $matches);
    if (isset($matches[1])) return $matches[1];
    
    preg_match('/youtu\.be\/([^?]+)/', $url, $matches);
    if (isset($matches[1])) return $matches[1];
    
    preg_match('/embed\/([^?]+)/', $url, $matches);
    if (isset($matches[1])) return $matches[1];
    
    return false;
}

function extract_vimeo_id($url) {
    preg_match('/vimeo\.com\/(\d+)/', $url, $matches);
    return isset($matches[1]) ? $matches[1] : false;
}
```

### SQL: Insert Article with Media

```sql
INSERT INTO articles (
    title, 
    slug, 
    description, 
    content, 
    media_type, 
    media_url, 
    video_embed_code,
    category_id, 
    author_id, 
    use_custom_date,
    custom_publish_date,
    status, 
    is_featured
) VALUES (
    'Article Title',
    'article-title',
    'Short description',
    'Full content...',
    'video',  -- or 'image' or 'url'
    'https://www.youtube.com/watch?v=VIDEO_ID',
    '<iframe src="..."></iframe>',
    1,
    1,
    0,  -- 0 = auto date, 1 = custom date
    NULL,  -- or '2023-10-15 14:30:00' for custom
    'published',
    1
);
```

### JavaScript: Dynamic Form Based on Media Type

```javascript
// Show/hide fields based on media type selection
document.querySelectorAll('input[name="media_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        // Hide all media sections
        document.getElementById('image-section').style.display = 'none';
        document.getElementById('video-section').style.display = 'none';
        document.getElementById('url-section').style.display = 'none';
        
        // Show selected section
        if (this.value === 'image') {
            document.getElementById('image-section').style.display = 'block';
        } else if (this.value === 'video') {
            document.getElementById('video-section').style.display = 'block';
        } else if (this.value === 'url') {
            document.getElementById('url-section').style.display = 'block';
        }
    });
});

// Show/hide custom date picker
document.querySelectorAll('input[name="date_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const customDateSection = document.getElementById('custom-date-section');
        customDateSection.style.display = this.value === 'custom' ? 'block' : 'none';
    });
});
```

---

**Note:** This is a comprehensive plan. We'll build this step-by-step, starting with the core functionality and gradually adding features. Each component will be clean, well-documented, and follow PHP best practices.
