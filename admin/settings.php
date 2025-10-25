<?php
$page_title = 'Settings';
require_once 'includes/admin_header.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_name = sanitize($_POST['site_name'] ?? '');
    $contact_email = sanitize($_POST['contact_email'] ?? '');
    $contact_phone = sanitize($_POST['contact_phone'] ?? '');
    $live_youtube_url = sanitize($_POST['live_youtube_url'] ?? '');
    $live_title = sanitize($_POST['live_title'] ?? '');
    $facebook_url = sanitize($_POST['facebook_url'] ?? '');
    $instagram_url = sanitize($_POST['instagram_url'] ?? '');
    $youtube_url = sanitize($_POST['youtube_url'] ?? '');
    $twitter_url = sanitize($_POST['twitter_url'] ?? '');
    $snapchat_url = sanitize($_POST['snapchat_url'] ?? '');
    
    // Handle logo upload
    $site_logo = $settings['site_logo'] ?? '';
    if (isset($_FILES['site_logo']) && $_FILES['site_logo']['error'] == 0) {
        $upload = upload_image($_FILES['site_logo'], 'settings');
        if ($upload['success']) {
            $site_logo = $upload['filename'];
        } else {
            $error = $upload['message'];
        }
    }
    
    if (!$error) {
        // Update settings
        $sql = "UPDATE settings SET 
                site_name = ?,
                site_logo = ?,
                contact_email = ?,
                contact_phone = ?,
                live_youtube_url = ?,
                live_title = ?,
                facebook_url = ?,
                instagram_url = ?,
                youtube_url = ?,
                twitter_url = ?,
                snapchat_url = ?
                WHERE id = 1";
        
        db_execute($sql, [
            $site_name, $site_logo, $contact_email, $contact_phone,
            $live_youtube_url, $live_title,
            $facebook_url, $instagram_url, $youtube_url, $twitter_url, $snapchat_url
        ]);
        
        $success = 'Settings updated successfully';
        
        // Refresh settings
        $settings = get_settings();
    }
}
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
    <h2>Site Settings</h2>
</div>

<form method="POST" enctype="multipart/form-data" class="settings-form">
    <!-- General Settings -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-cog"></i> General Settings</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="site_name">Site Name</label>
                    <input type="text" id="site_name" name="site_name" 
                           value="<?php echo htmlspecialchars($settings['site_name']); ?>"
                           placeholder="Your News Site">
                </div>
                
                <div class="form-group">
                    <label for="site_logo">Site Logo</label>
                    <input type="file" id="site_logo" name="site_logo" accept="image/*">
                    <?php if ($settings['site_logo']): ?>
                        <img src="<?php echo BASE_URL . '/' . $settings['site_logo']; ?>" 
                             style="max-width: 200px; margin-top: 10px; display: block;">
                    <?php endif; ?>
                    <small>Recommended size: 200x60px</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Contact Information -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-address-book"></i> Contact Information</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="contact_email">Contact Email</label>
                    <input type="email" id="contact_email" name="contact_email" 
                           value="<?php echo htmlspecialchars($settings['contact_email']); ?>"
                           placeholder="contact@example.com">
                </div>
                
                <div class="form-group">
                    <label for="contact_phone">Contact Phone</label>
                    <input type="text" id="contact_phone" name="contact_phone" 
                           value="<?php echo htmlspecialchars($settings['contact_phone']); ?>"
                           placeholder="+91 1234567890">
                </div>
            </div>
        </div>
    </div>
    
    <!-- Live News Settings -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-video"></i> Live News Settings</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="live_title">Live Stream Title</label>
                <input type="text" id="live_title" name="live_title" 
                       value="<?php echo htmlspecialchars($settings['live_title']); ?>"
                       placeholder="Watch Live News 24/7">
            </div>
            
            <div class="form-group">
                <label for="live_youtube_url">YouTube Live URL</label>
                <input type="url" id="live_youtube_url" name="live_youtube_url" 
                       value="<?php echo htmlspecialchars($settings['live_youtube_url']); ?>"
                       placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                <small>Paste your YouTube live stream or video URL</small>
            </div>
            
            <?php if ($settings['live_youtube_url']): 
                $video_id = extract_youtube_id($settings['live_youtube_url']);
                if ($video_id):
            ?>
            <div class="live-preview">
                <strong>Preview:</strong>
                <div style="margin-top: 10px;">
                    <iframe width="100%" height="300" 
                        src="https://www.youtube.com/embed/<?php echo $video_id; ?>" 
                        frameborder="0" allowfullscreen></iframe>
                </div>
            </div>
            <?php endif; endif; ?>
        </div>
    </div>
    
    <!-- Social Media Links -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fa-solid fa-share-nodes"></i> Social Media Links</h3>
        </div>
        <div class="card-body">
            <div class="form-row">
                <div class="form-group">
                    <label for="facebook_url">
                        <i class="fa-brands fa-facebook"></i> Facebook URL
                    </label>
                    <input type="url" id="facebook_url" name="facebook_url" 
                           value="<?php echo htmlspecialchars($settings['facebook_url']); ?>"
                           placeholder="https://facebook.com/yourpage">
                </div>
                
                <div class="form-group">
                    <label for="instagram_url">
                        <i class="fa-brands fa-instagram"></i> Instagram URL
                    </label>
                    <input type="url" id="instagram_url" name="instagram_url" 
                           value="<?php echo htmlspecialchars($settings['instagram_url']); ?>"
                           placeholder="https://instagram.com/yourpage">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="youtube_url">
                        <i class="fa-brands fa-youtube"></i> YouTube URL
                    </label>
                    <input type="url" id="youtube_url" name="youtube_url" 
                           value="<?php echo htmlspecialchars($settings['youtube_url']); ?>"
                           placeholder="https://youtube.com/@yourchannel">
                </div>
                
                <div class="form-group">
                    <label for="twitter_url">
                        <i class="fa-brands fa-twitter"></i> Twitter URL
                    </label>
                    <input type="url" id="twitter_url" name="twitter_url" 
                           value="<?php echo htmlspecialchars($settings['twitter_url']); ?>"
                           placeholder="https://twitter.com/yourhandle">
                </div>
            </div>
            
            <div class="form-group">
                <label for="snapchat_url">
                    <i class="fa-brands fa-snapchat"></i> Snapchat URL
                </label>
                <input type="url" id="snapchat_url" name="snapchat_url" 
                       value="<?php echo htmlspecialchars($settings['snapchat_url']); ?>"
                       placeholder="https://snapchat.com/add/yourhandle">
            </div>
        </div>
    </div>
    
    <!-- Save Button -->
    <div class="form-actions">
        <button type="submit" class="btn btn-primary btn-lg">
            <i class="fa-solid fa-save"></i> Save All Settings
        </button>
        <a href="/" target="_blank" class="btn btn-outline btn-lg">
            <i class="fa-solid fa-eye"></i> View Website
        </a>
    </div>
</form>

<style>
.settings-form {
    max-width: 1000px;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 20px;
}

.form-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.btn-lg {
    padding: 15px 30px;
    font-size: 1rem;
}

.live-preview {
    margin-top: 20px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 10px;
}

.live-preview strong {
    display: block;
    margin-bottom: 10px;
    color: #333;
}
</style>

<?php require_once 'includes/admin_footer.php'; ?>
