<?php
$page_title = 'Article Form';
require_once 'includes/admin_header.php';

$edit_mode = false;
$article = null;
$article_states = [];
$error = '';
$success = '';

// Check if editing
if (isset($_GET['id'])) {
    $edit_mode = true;
    $article = get_article_by_id($_GET['id']);
    if (!$article) {
        header('Location: articles.php');
        exit;
    }
    $article_states = array_column(get_article_states($article['id']), 'id');
    $page_title = 'Edit Article';
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = sanitize($_POST['title'] ?? '');
    $slug = sanitize($_POST['slug'] ?? '');
    $description = sanitize($_POST['description'] ?? '');
    $content = $_POST['content'] ?? ''; // Don't sanitize content (allow HTML)
    $selected_categories = $_POST['categories'] ?? [];
    $category_id = !empty($selected_categories) ? (int)$selected_categories[0] : 0; // Use first category as primary
    $media_type = $_POST['media_type'] ?? 'image';
    $status = $_POST['status'] ?? 'draft';
    $use_custom_date = isset($_POST['use_custom_date']) ? 1 : 0;
    $custom_date = $_POST['custom_date'] ?? null;
    $selected_states = $_POST['states'] ?? [];
    
    // Validation
    if (empty($title) || empty($description) || empty($content) || empty($selected_categories)) {
        $error = 'Please fill all required fields and select at least one category';
    } else {
        // Handle image upload
        $image_path = $article['image'] ?? '';
        
        if ($media_type == 'image' && isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
            $upload = upload_image($_FILES['image'], 'articles');
            if ($upload['success']) {
                $image_path = $upload['filename'];
            } else {
                $error = $upload['message'];
            }
        } elseif ($media_type == 'video' && isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
            $upload = upload_image($_FILES['thumbnail'], 'articles');
            if ($upload['success']) {
                $image_path = $upload['filename'];
            }
        } elseif ($media_type == 'url' && isset($_FILES['thumbnail']) && $_FILES['thumbnail']['error'] == 0) {
            $upload = upload_image($_FILES['thumbnail'], 'articles');
            if ($upload['success']) {
                $image_path = $upload['filename'];
            }
        }
        
        if (!$error) {
            $video_url = $media_type == 'video' ? ($_POST['video_url'] ?? '') : null;
            $external_url = $media_type == 'url' ? ($_POST['external_url'] ?? '') : null;
            
            if ($edit_mode) {
                // Update article
                $sql = "UPDATE articles SET 
                        title = ?, slug = ?, description = ?, content = ?,
                        media_type = ?, image = ?, video_url = ?, external_url = ?,
                        category_id = ?, status = ?,
                        use_custom_date = ?, custom_date = ?
                        WHERE id = ?";
                
                db_execute($sql, [
                    $title, $slug, $description, $content,
                    $media_type, $image_path, $video_url, $external_url,
                    $category_id, $status,
                    $use_custom_date, $custom_date,
                    $article['id']
                ]);
                
                $article_id = $article['id'];
                $success = 'Article updated successfully';
            } else {
                // Create new article
                $sql = "INSERT INTO articles (
                        title, slug, description, content,
                        media_type, image, video_url, external_url,
                        category_id, status,
                        use_custom_date, custom_date
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                
                $article_id = db_insert($sql, [
                    $title, $slug, $description, $content,
                    $media_type, $image_path, $video_url, $external_url,
                    $category_id, $status,
                    $use_custom_date, $custom_date
                ]);
                
                $success = 'Article created successfully';
            }
            
            // Update states
            db_execute("DELETE FROM article_states WHERE article_id = ?", [$article_id]);
            foreach ($selected_states as $state_id) {
                db_execute("INSERT INTO article_states (article_id, state_id) VALUES (?, ?)", 
                          [$article_id, $state_id]);
            }
            
            // Redirect after success
            header("Location: articles.php");
            exit;
        }
    }
}

$categories = get_categories();
$states = get_states();
?>

<?php if ($error): ?>
<div class="alert alert-error">
    <i class="fa-solid fa-exclamation-circle"></i>
    <?php echo $error; ?>
</div>
<?php endif; ?>

<?php if ($success): ?>
<div class="alert alert-success">
    <i class="fa-solid fa-check-circle"></i>
    <?php echo $success; ?>
</div>
<?php endif; ?>

<div class="page-header">
    <h2><?php echo $edit_mode ? 'Edit Article' : 'Create New Article'; ?></h2>
    <a href="articles.php" class="btn btn-outline">
        <i class="fa-solid fa-arrow-left"></i> Back to Articles
    </a>
</div>

<form method="POST" enctype="multipart/form-data" class="article-form">
    <div class="form-grid">
        <!-- Left Column -->
        <div class="form-main">
            <div class="card">
                <div class="card-header">
                    <h3>Article Details</h3>
                </div>
                <div class="card-body">
                    <!-- Title -->
                    <div class="form-group">
                        <label for="title">Title <span class="required">*</span></label>
                        <input type="text" id="title" name="title" required 
                               value="<?php echo htmlspecialchars($article['title'] ?? ''); ?>"
                               placeholder="Enter article title">
                    </div>
                    
                    <!-- Slug -->
                    <div class="form-group">
                        <label for="slug">Slug <span class="required">*</span></label>
                        <input type="text" id="slug" name="slug" required 
                               value="<?php echo htmlspecialchars($article['slug'] ?? ''); ?>"
                               placeholder="article-url-slug">
                        <small>URL-friendly version of the title</small>
                    </div>
                    
                    <!-- Description -->
                    <div class="form-group">
                        <label for="description">Short Description <span class="required">*</span></label>
                        <textarea id="description" name="description" rows="3" required 
                                  placeholder="Brief summary (150-200 characters)"><?php echo htmlspecialchars($article['description'] ?? ''); ?></textarea>
                    </div>
                    
                    <!-- Content -->
                    <div class="form-group">
                        <label for="content">Full Content <span class="required">*</span></label>
                        <textarea id="content" name="content" rows="15" required 
                                  placeholder="Write your article content here..."><?php echo htmlspecialchars($article['content'] ?? ''); ?></textarea>
                        <small>Full article content</small>
                    </div>
                </div>
            </div>
            
            <!-- Media Section -->
            <div class="card">
                <div class="card-header">
                    <h3>Featured Media</h3>
                </div>
                <div class="card-body">
                    <!-- Media Type -->
                    <div class="form-group">
                        <label>Media Type <span class="required">*</span></label>
                        <div class="radio-group">
                            <label class="radio-label">
                                <input type="radio" name="media_type" value="image" 
                                       <?php echo (!$article || $article['media_type'] == 'image') ? 'checked' : ''; ?>>
                                <i class="fa-solid fa-image"></i> Image
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="media_type" value="video"
                                       <?php echo ($article && $article['media_type'] == 'video') ? 'checked' : ''; ?>>
                                <i class="fa-solid fa-video"></i> Video
                            </label>
                            <label class="radio-label">
                                <input type="radio" name="media_type" value="url"
                                       <?php echo ($article && $article['media_type'] == 'url') ? 'checked' : ''; ?>>
                                <i class="fa-solid fa-link"></i> External URL
                            </label>
                        </div>
                    </div>
                    
                    <!-- Image Upload -->
                    <div id="image-field" class="media-field">
                        <div class="form-group">
                            <label for="image">Upload Image</label>
                            <input type="file" id="image" name="image" accept="image/*">
                            <?php if ($article && $article['image']): ?>
                                <img src="<?php echo BASE_URL . '/' . $article['image']; ?>" id="image-preview" 
                                     style="max-width: 300px; margin-top: 10px; display: block;">
                            <?php else: ?>
                                <img id="image-preview" style="max-width: 300px; margin-top: 10px; display: none;">
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Video URL -->
                    <div id="video-field" class="media-field" style="display: none;">
                        <div class="form-group">
                            <label for="video_url">YouTube/Vimeo URL</label>
                            <input type="url" id="video_url" name="video_url" 
                                   value="<?php echo htmlspecialchars($article['video_url'] ?? ''); ?>"
                                   placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                        </div>
                        <div class="form-group">
                            <label for="thumbnail">Video Thumbnail</label>
                            <input type="file" name="thumbnail" accept="image/*">
                            <small>Optional thumbnail for video preview</small>
                        </div>
                    </div>
                    
                    <!-- External URL -->
                    <div id="url-field" class="media-field" style="display: none;">
                        <div class="form-group">
                            <label for="external_url">External Article URL</label>
                            <input type="url" id="external_url" name="external_url" 
                                   value="<?php echo htmlspecialchars($article['external_url'] ?? ''); ?>"
                                   placeholder="https://example.com/article">
                        </div>
                        <div class="form-group">
                            <label for="thumbnail">Thumbnail Image</label>
                            <input type="file" name="thumbnail" accept="image/*">
                            <small>Thumbnail for external article</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Right Column (Sidebar) -->
        <div class="form-sidebar">
            <!-- Publish -->
            <div class="card">
                <div class="card-header">
                    <h3>Publish</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="status">Status</label>
                        <select id="status" name="status" class="form-control">
                            <option value="draft" <?php echo (!$article || $article['status'] == 'draft') ? 'selected' : ''; ?>>
                                Draft
                            </option>
                            <option value="published" <?php echo ($article && $article['status'] == 'published') ? 'selected' : ''; ?>>
                                Published
                            </option>
                        </select>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fa-solid fa-save"></i>
                            <?php echo $edit_mode ? 'Update Article' : 'Create Article'; ?>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Categories -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa-solid fa-folder"></i> Categories <span class="required">*</span></h3>
                    <small>Select one or more categories for this article</small>
                </div>
                <div class="card-body">
                    <div class="checkbox-grid">
                        <?php 
                        $article_categories = [];
                        if ($article) {
                            // Get primary category
                            $article_categories[] = $article['category_id'];
                        }
                        foreach ($categories as $cat): 
                        ?>
                        <label class="checkbox-label">
                            <input type="checkbox" name="categories[]" value="<?php echo $cat['id']; ?>"
                                   <?php echo in_array($cat['id'], $article_categories) ? 'checked' : ''; ?>>
                            <i class="fa-solid fa-folder"></i>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <?php if (empty($categories)): ?>
                        <p class="text-muted">No categories available. <a href="categories.php">Create one</a></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- States -->
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa-solid fa-map-marker-alt"></i> States (Optional)</h3>
                    <small>Tag this article with relevant Indian states</small>
                </div>
                <div class="card-body">
                    <div class="checkbox-grid">
                        <?php foreach ($states as $state): ?>
                        <label class="checkbox-label">
                            <input type="checkbox" name="states[]" value="<?php echo $state['id']; ?>"
                                   <?php echo in_array($state['id'], $article_states) ? 'checked' : ''; ?>>
                            <i class="fa-solid fa-map-marker-alt"></i>
                            <?php echo htmlspecialchars($state['name']); ?>
                        </label>
                        <?php endforeach; ?>
                    </div>
                    <?php if (empty($states)): ?>
                        <p class="text-muted">No states available. <a href="states.php">Create one</a></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Custom Date -->
            <div class="card">
                <div class="card-header">
                    <h3>Publish Date</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" id="use_custom_date" name="use_custom_date" value="1"
                                   <?php echo ($article && $article['use_custom_date']) ? 'checked' : ''; ?>>
                            Use custom date
                        </label>
                    </div>
                    
                    <div id="custom-date-field" style="display: none;">
                        <div class="form-group">
                            <label for="custom_date">Custom Date & Time</label>
                            <input type="datetime-local" id="custom_date" name="custom_date" 
                                   value="<?php echo $article && $article['custom_date'] ? date('Y-m-d\TH:i', strtotime($article['custom_date'])) : ''; ?>">
                            <small>For backdated posts</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
.article-form {
    max-width: 1400px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 25px;
}

.card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}

.card-header {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
}

.card-header h3 {
    font-size: 1.1rem;
    color: #333;
    margin: 0;
}

.card-body {
    padding: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #555;
}

.form-group input[type="text"],
.form-group input[type="url"],
.form-group input[type="datetime-local"],
.form-group textarea,
.form-group select {
    width: 100%;
    padding: 10px 15px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.95rem;
    transition: all 0.3s;
}

.form-group input:focus,
.form-group textarea:focus,
.form-group select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

.form-group small {
    display: block;
    margin-top: 5px;
    color: #999;
    font-size: 0.85rem;
}

.required {
    color: var(--danger);
}

.radio-group {
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.radio-label {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 10px 15px;
    border: 2px solid #e5e7eb;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.3s;
}

.radio-label:hover {
    border-color: var(--primary);
    background: rgba(102, 126, 234, 0.05);
}

.radio-label input[type="radio"] {
    margin: 0;
}

.checkbox-group {
    max-height: 300px;
    overflow-y: auto;
}

.checkbox-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
    gap: 10px;
    max-height: 350px;
    overflow-y: auto;
    padding: 10px;
    background: #f8f9fa;
    border-radius: 8px;
}

.checkbox-label {
    display: flex;
    align-items: center;
    padding: 10px 12px;
    background: white;
    border: 2px solid #e9ecef;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s;
    font-size: 14px;
}

.checkbox-label:hover {
    border-color: #007bff;
    background: #f0f8ff;
}

.checkbox-label input[type="checkbox"] {
    margin-right: 8px;
    cursor: pointer;
    width: 16px;
    height: 16px;
}

.checkbox-label i {
    margin-right: 6px;
    color: #6c757d;
    font-size: 13px;
}

.card-header small {
    display: block;
    font-weight: normal;
    color: #6c757d;
    margin-top: 5px;
    font-size: 13px;
}

.text-muted {
    color: #6c757d;
    font-size: 14px;
}

.form-actions {
    margin-top: 20px;
}

@media (max-width: 1024px) {
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>

<?php require_once 'includes/admin_footer.php'; ?>
