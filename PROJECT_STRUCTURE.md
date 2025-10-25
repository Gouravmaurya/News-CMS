# 📁 Project Structure

## Complete File Tree

```
news-cms/
│
├── 📄 index.php                    # Homepage
├── 📄 article.php                  # Single article page
├── 📄 category.php                 # Category page
├── 📄 state.php                    # State page
├── 📄 live.php                     # Live news page
├── 📄 404.php                      # Error page
├── 📄 newsletter-subscribe.php     # Newsletter handler
├── 📄 .htaccess                    # Apache configuration
├── 📄 database.sql                 # Database schema
│
├── 📂 config/
│   └── 📄 database.php             # Database connection
│
├── 📂 includes/
│   ├── 📄 header.php               # Header component
│   ├── 📄 footer.php               # Footer component
│   └── 📄 functions.php            # Helper functions
│
├── 📂 admin/
│   ├── 📄 login.php                # Admin login
│   ├── 📄 logout.php               # Logout handler
│   ├── 📄 dashboard.php            # Dashboard
│   ├── 📄 articles.php             # Articles list
│   ├── 📄 article-form.php         # Create/Edit article
│   ├── 📄 categories.php           # Manage categories
│   ├── 📄 states.php               # Manage states
│   ├── 📄 settings.php             # Site settings
│   └── 📂 includes/
│       ├── 📄 admin_header.php     # Admin header
│       └── 📄 admin_footer.php     # Admin footer
│
├── 📂 assets/
│   ├── 📂 css/
│   │   ├── 📄 style.css            # Main styles
│   │   ├── 📄 admin.css            # Admin styles
│   │   ├── 📄 category.css         # Category page
│   │   ├── 📄 state.css            # State page
│   │   ├── 📄 article.css          # Article page
│   │   ├── 📄 404.css              # 404 page
│   │   └── 📄 live.css             # Live page
│   │
│   ├── 📂 js/
│   │   ├── 📄 main.js              # Main JavaScript
│   │   ├── 📄 admin.js             # Admin scripts
│   │   ├── 📄 category.js          # Category page
│   │   ├── 📄 state.js             # State page
│   │   ├── 📄 article.js           # Article page
│   │   ├── 📄 404.js               # 404 page
│   │   └── 📄 live.js              # Live page
│   │
│   └── 📂 uploads/
│       ├── 📂 articles/            # Article images
│       └── 📂 settings/            # Logo, etc.
│
├── 📂 images/
│   └── 📄 logo.jpg                 # Default logo
│
└── 📂 Documentation/
    ├── 📄 README.md                # Project overview
    ├── 📄 QUICK_START.md           # 5-minute setup
    ├── 📄 SETUP_GUIDE.md           # Detailed guide
    ├── 📄 BUILD_PROGRESS.md        # Development log
    ├── 📄 FRONTEND_COMPLETE.md     # Frontend docs
    ├── 📄 ADMIN_PANEL_COMPLETE.md  # Admin docs
    ├── 📄 DEPLOYMENT_CHECKLIST.md  # Deploy guide
    ├── 📄 CMS_SIMPLE_MVP_PLAN.md   # Project plan
    └── 📄 CMS_DEVELOPMENT_PLAN.md  # Full plan
```

---

## 📊 File Statistics

### By Type
- **PHP Files:** 18
- **CSS Files:** 7
- **JavaScript Files:** 7
- **Documentation:** 9
- **Configuration:** 2
- **Total:** 43 files

### By Category
- **Frontend Pages:** 7
- **Admin Pages:** 8
- **Components:** 4
- **Assets:** 14
- **Config:** 2
- **Docs:** 9

---

## 🎯 Key Files Explained

### Frontend Core
| File | Purpose | Features |
|------|---------|----------|
| `index.php` | Homepage | Hero slider, live news, categories |
| `article.php` | Article page | Full article, media, sharing |
| `category.php` | Category page | Articles by category, pagination |
| `state.php` | State page | Articles by state, pagination |
| `live.php` | Live news | YouTube embed, latest news |
| `404.php` | Error page | Custom 404, search, suggestions |

### Admin Core
| File | Purpose | Features |
|------|---------|----------|
| `login.php` | Authentication | Secure login, session |
| `dashboard.php` | Overview | Stats, quick actions |
| `articles.php` | Article list | View, filter, search |
| `article-form.php` | Create/Edit | Full form, media types |
| `categories.php` | Categories | CRUD operations |
| `states.php` | States | CRUD operations |
| `settings.php` | Settings | Site, social, live |

### Components
| File | Purpose | Used By |
|------|---------|---------|
| `header.php` | Site header | All frontend pages |
| `footer.php` | Site footer | All frontend pages |
| `admin_header.php` | Admin header | All admin pages |
| `admin_footer.php` | Admin footer | All admin pages |
| `functions.php` | Helper functions | All pages |

### Configuration
| File | Purpose | Contains |
|------|---------|----------|
| `database.php` | DB connection | PDO setup, helpers |
| `.htaccess` | Apache config | Clean URLs, security |
| `database.sql` | DB schema | Tables, sample data |

---

## 🔄 Data Flow

### Frontend Request Flow
```
User Request
    ↓
index.php / article.php / category.php
    ↓
includes/header.php (loads functions.php)
    ↓
config/database.php (connects to DB)
    ↓
Query data from database
    ↓
Render page content
    ↓
includes/footer.php
    ↓
Response to user
```

### Admin Request Flow
```
Admin Login
    ↓
admin/login.php (verify credentials)
    ↓
Session created
    ↓
admin/dashboard.php
    ↓
admin/includes/admin_header.php (check session)
    ↓
Admin actions (CRUD operations)
    ↓
Database updates
    ↓
admin/includes/admin_footer.php
    ↓
Response to admin
```

---

## 📦 Dependencies

### External Libraries
- **Font Awesome 6.2.0** - Icons
- **None** - No jQuery, no Bootstrap (vanilla JS)

### PHP Extensions
- PDO (MySQL)
- GD or Imagick
- mbstring
- JSON

### Server Requirements
- PHP 7.4+
- MySQL 5.7+
- Apache/Nginx
- mod_rewrite (Apache)

---

## 🎨 Asset Organization

### CSS Structure
```
assets/css/
├── style.css       # Main styles (from original design)
├── admin.css       # Admin panel styles
├── category.css    # Category page specific
├── state.css       # State page specific
├── article.css     # Article page specific
├── 404.css         # 404 page specific
└── live.css        # Live page specific
```

### JavaScript Structure
```
assets/js/
├── main.js         # Main scripts (from original)
├── admin.js        # Admin functionality
├── category.js     # Category page scripts
├── state.js        # State page scripts
├── article.js      # Article page scripts
├── 404.js          # 404 page scripts
└── live.js         # Live page scripts
```

### Uploads Structure
```
assets/uploads/
├── articles/       # Article images
│   ├── image1.jpg
│   ├── image2.jpg
│   └── ...
└── settings/       # Site logo, etc.
    └── logo.jpg
```

---

## 🔐 Security Files

### Protected Directories
- `/config/` - Database credentials
- `/includes/` - PHP functions
- `/admin/includes/` - Admin components

### Security Measures
- `.htaccess` blocks direct access
- Session-based authentication
- Password hashing (bcrypt)
- SQL injection prevention
- XSS protection
- File upload validation

---

## 📝 Documentation Files

### User Guides
- `README.md` - Project overview
- `QUICK_START.md` - 5-minute setup
- `SETUP_GUIDE.md` - Detailed installation

### Developer Guides
- `BUILD_PROGRESS.md` - Development log
- `FRONTEND_COMPLETE.md` - Frontend documentation
- `ADMIN_PANEL_COMPLETE.md` - Admin documentation

### Planning Documents
- `CMS_SIMPLE_MVP_PLAN.md` - MVP plan
- `CMS_DEVELOPMENT_PLAN.md` - Full plan
- `DEPLOYMENT_CHECKLIST.md` - Deploy guide

---

## 🎯 File Sizes (Approximate)

| Category | Files | Total Size |
|----------|-------|------------|
| PHP | 18 | ~150 KB |
| CSS | 7 | ~80 KB |
| JavaScript | 7 | ~30 KB |
| Documentation | 9 | ~200 KB |
| Database | 1 | ~50 KB |
| **Total** | **42** | **~510 KB** |

*Note: Excludes uploaded images and external libraries*

---

## 🚀 Quick Navigation

### For Users
- Start here: `QUICK_START.md`
- Detailed setup: `SETUP_GUIDE.md`
- Admin guide: `ADMIN_PANEL_COMPLETE.md`

### For Developers
- Architecture: `CMS_SIMPLE_MVP_PLAN.md`
- Progress: `BUILD_PROGRESS.md`
- Frontend: `FRONTEND_COMPLETE.md`

### For Deployment
- Checklist: `DEPLOYMENT_CHECKLIST.md`
- Database: `database.sql`
- Config: `config/database.php`

---

**All files are organized, documented, and ready to use!** 📁✨
