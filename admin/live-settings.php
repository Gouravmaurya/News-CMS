<?php
$page_title = 'Live Settings';
require_once 'includes/admin_header.php';

$error = '';
$success = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $live_youtube_url = trim($_POST['live_youtube_url'] ?? '');
    $live_title = sanitize($_POST['live_title'] ?? '');
    
    // Validate YouTube URL only if provided
    if (!empty($live_youtube_url)) {
        $video_id = extract_youtube_id($live_youtube_url);
        if (!$video_id) {
            $error = 'Invalid YouTube URL. Please check the URL and try again.<br><br>';
            $error .= '<strong>Supported formats:</strong><br>';
            $error .= '• https://www.youtube.com/watch?v=VIDEO_ID<br>';
            $error .= '• https://youtu.be/VIDEO_ID<br>';
            $error .= '• https://www.youtube.com/embed/VIDEO_ID<br>';
            $error .= '• https://www.youtube.com/live/VIDEO_ID';
        }
    }
    
    if (!$error) {
        // Update live settings
        $sql = "UPDATE settings SET 
                live_youtube_url = ?,
                live_title = ?
                WHERE id = 1";
        
        db_execute($sql, [$live_youtube_url, $live_title]);
        
        $success = 'Live settings updated successfully!';
        
        // Refresh settings
        $settings = get_settings();
    }
}

// Get current video ID for preview
$current_video_id = !empty($settings['live_youtube_url']) ? extract_youtube_id($settings['live_youtube_url']) : null;
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
    <div>
        <h2><i class="fa-solid fa-video"></i> Live Stream Settings</h2>
        <p>Manage your YouTube live stream that appears on the homepage and live page</p>
    </div>
    <div>
        <a href="<?php echo url('live.php'); ?>" target="_blank" class="btn btn-outline">
            <i class="fa-solid fa-eye"></i> View Live Page
        </a>
    </div>
</div>

<div class="live-settings-container">
    <div class="live-form-section">
        <form method="POST" class="live-form">
            <div class="card">
                <div class="card-header">
                    <h3><i class="fa-brands fa-youtube"></i> YouTube Live Stream</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="live_title">
                            <i class="fa-solid fa-heading"></i> Live Stream Title
                        </label>
                        <input type="text" 
                               id="live_title" 
                               name="live_title" 
                               class="form-control"
                               value="<?php echo htmlspecialchars($settings['live_title']); ?>"
                               placeholder="Watch Live News 24/7">
                        <small>This title will appear on the homepage and live page</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="live_youtube_url">
                            <i class="fa-brands fa-youtube"></i> YouTube Video/Live URL
                        </label>
                        <input type="url" 
                               id="live_youtube_url" 
                               name="live_youtube_url" 
                               class="form-control"
                               value="<?php echo htmlspecialchars($settings['live_youtube_url']); ?>"
                               placeholder="https://www.youtube.com/watch?v=VIDEO_ID">
                        <small>Paste your YouTube live stream or video URL here</small>
                    </div>
                    
                    <div class="url-formats">
                        <strong>✅ Supported URL formats:</strong>
                        <ul>
                            <li><code>https://www.youtube.com/watch?v=VIDEO_ID</code></li>
                            <li><code>https://youtu.be/VIDEO_ID</code></li>
                            <li><code>https://www.youtube.com/embed/VIDEO_ID</code></li>
                            <li><code>https://www.youtube.com/live/VIDEO_ID</code> (for live streams)</li>
                        </ul>
                        <div class="example-box">
                            <strong>📺 Example URLs to test:</strong>
                            <ul>
                                <li><code>https://www.youtube.com/watch?v=9Auq9mYxFEE</code></li>
                                <li><code>https://youtu.be/9Auq9mYxFEE</code></li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fa-solid fa-save"></i> Save Live Settings
                        </button>
                        <button type="button" class="btn btn-outline" onclick="clearLive()">
                            <i class="fa-solid fa-trash"></i> Clear Live Stream
                        </button>
                    </div>
                </div>
            </div>
        </form>
        
        <!-- Instructions -->
        <div class="card instructions-card">
            <div class="card-header">
                <h3><i class="fa-solid fa-info-circle"></i> How to Add Live Stream</h3>
            </div>
            <div class="card-body">
                <ol class="instructions-list">
                    <li>
                        <strong>Go to YouTube</strong>
                        <p>Open your YouTube channel or find the live stream you want to embed</p>
                    </li>
                    <li>
                        <strong>Copy the URL</strong>
                        <p>Copy the URL from your browser's address bar</p>
                    </li>
                    <li>
                        <strong>Paste Here</strong>
                        <p>Paste the URL in the "YouTube Video/Live URL" field above</p>
                    </li>
                    <li>
                        <strong>Add Title</strong>
                        <p>Enter a catchy title for your live stream</p>
                    </li>
                    <li>
                        <strong>Save & Preview</strong>
                        <p>Click "Save Live Settings" and check the preview on the right</p>
                    </li>
                </ol>
                
                <div class="tip-box">
                    <i class="fa-solid fa-lightbulb"></i>
                    <div>
                        <strong>Pro Tip:</strong>
                        <p>You can use any YouTube video URL, not just live streams. This is useful for featuring important news videos on your homepage.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Live Preview -->
    <div class="live-preview-section">
        <div class="card preview-card">
            <div class="card-header">
                <h3><i class="fa-solid fa-eye"></i> Live Preview</h3>
            </div>
            <div class="card-body">
                <?php if ($current_video_id): ?>
                    <div class="video-preview">
                        <iframe width="100%" height="315" 
                            src="https://www.youtube.com/embed/<?php echo $current_video_id; ?>" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                            allowfullscreen></iframe>
                    </div>
                    <div class="preview-info">
                        <p><strong>Title:</strong> <?php echo htmlspecialchars($settings['live_title']); ?></p>
                        <p><strong>Video ID:</strong> <code><?php echo $current_video_id; ?></code></p>
                        <p><strong>Status:</strong> <span class="badge badge-success">Active</span></p>
                    </div>
                <?php else: ?>
                    <div class="no-preview">
                        <i class="fa-solid fa-video-slash"></i>
                        <h4>No Live Stream Set</h4>
                        <p>Add a YouTube URL to see the preview here</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Quick Stats -->
        <div class="card stats-card">
            <div class="card-header">
                <h3><i class="fa-solid fa-chart-simple"></i> Quick Stats</h3>
            </div>
            <div class="card-body">
                <div class="stat-item">
                    <i class="fa-solid fa-video"></i>
                    <div>
                        <strong>Live Status</strong>
                        <p><?php echo $current_video_id ? 'Active' : 'Not Set'; ?></p>
                    </div>
                </div>
                <div class="stat-item">
                    <i class="fa-solid fa-clock"></i>
                    <div>
                        <strong>Last Updated</strong>
                        <p><?php echo date('M d, Y h:i A', strtotime($settings['updated_at'])); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
}

.page-header p {
    color: #666;
    margin-top: 5px;
}

.live-settings-container {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 30px;
}

.live-form {
    margin-bottom: 30px;
}

.form-control {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-size: 14px;
}

.form-control:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 3px rgba(0,123,255,0.1);
}

.url-formats {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-top: 15px;
}

.url-formats strong {
    display: block;
    margin-bottom: 10px;
    color: #333;
}

.url-formats ul {
    margin: 0;
    padding-left: 20px;
}

.url-formats li {
    margin: 5px 0;
    color: #666;
}

.url-formats code {
    background: #fff;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 12px;
    color: #e83e8c;
}

.example-box {
    margin-top: 15px;
    padding: 12px;
    background: #e7f3ff;
    border-left: 4px solid #007bff;
    border-radius: 4px;
}

.example-box strong {
    color: #0056b3;
}

.example-box ul {
    margin-top: 8px;
}

.example-box code {
    background: #fff;
    color: #007bff;
}

.instructions-card {
    margin-top: 20px;
}

.instructions-list {
    padding-left: 20px;
}

.instructions-list li {
    margin-bottom: 20px;
}

.instructions-list strong {
    display: block;
    color: #333;
    margin-bottom: 5px;
}

.instructions-list p {
    color: #666;
    margin: 0;
}

.tip-box {
    display: flex;
    gap: 15px;
    background: #fff3cd;
    padding: 15px;
    border-radius: 8px;
    border-left: 4px solid #ffc107;
    margin-top: 20px;
}

.tip-box i {
    color: #ffc107;
    font-size: 24px;
}

.tip-box strong {
    display: block;
    color: #856404;
    margin-bottom: 5px;
}

.tip-box p {
    color: #856404;
    margin: 0;
}

.preview-card .card-body {
    padding: 0;
}

.video-preview {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
}

.video-preview iframe {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

.preview-info {
    padding: 20px;
    background: #f8f9fa;
}

.preview-info p {
    margin: 10px 0;
    color: #666;
}

.no-preview {
    text-align: center;
    padding: 60px 20px;
    color: #999;
}

.no-preview i {
    font-size: 64px;
    margin-bottom: 20px;
    opacity: 0.3;
}

.no-preview h4 {
    margin: 0 0 10px 0;
    color: #666;
}

.stats-card {
    margin-top: 20px;
}

.stat-item {
    display: flex;
    gap: 15px;
    padding: 15px;
    border-bottom: 1px solid #eee;
}

.stat-item:last-child {
    border-bottom: none;
}

.stat-item i {
    font-size: 24px;
    color: #007bff;
}

.stat-item strong {
    display: block;
    color: #333;
    margin-bottom: 5px;
}

.stat-item p {
    margin: 0;
    color: #666;
    font-size: 14px;
}

@media (max-width: 1200px) {
    .live-settings-container {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function clearLive() {
    if (confirm('Are you sure you want to clear the live stream? This will remove it from your homepage and live page.')) {
        document.getElementById('live_youtube_url').value = '';
        document.getElementById('live_title').value = '';
    }
}
</script>

<?php require_once 'includes/admin_footer.php'; ?>
