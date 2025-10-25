<?php
require_once 'includes/functions.php';

$test_url = $_GET['url'] ?? '';
$video_id = '';
$is_valid = false;

if ($test_url) {
    $video_id = extract_youtube_id($test_url);
    $is_valid = $video_id !== false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube URL Tester</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 20px;
            padding: 40px;
            max-width: 800px;
            width: 100%;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }
        h1 {
            color: #333;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        h1 i { color: #ff0000; }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        input {
            width: 100%;
            padding: 15px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 16px;
            transition: all 0.3s;
        }
        input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        button {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s;
        }
        button:hover {
            transform: translateY(-2px);
        }
        .result {
            margin-top: 30px;
            padding: 20px;
            border-radius: 10px;
        }
        .result.success {
            background: #d4edda;
            border: 2px solid #28a745;
        }
        .result.error {
            background: #f8d7da;
            border: 2px solid #dc3545;
        }
        .result h3 {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .result.success h3 { color: #155724; }
        .result.error h3 { color: #721c24; }
        .video-preview {
            margin-top: 20px;
            position: relative;
            padding-bottom: 56.25%;
            height: 0;
            overflow: hidden;
            border-radius: 10px;
        }
        .video-preview iframe {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }
        .info-box {
            background: #e7f3ff;
            padding: 15px;
            border-radius: 10px;
            margin-top: 20px;
        }
        .info-box strong {
            display: block;
            margin-bottom: 10px;
            color: #0056b3;
        }
        .info-box ul {
            margin-left: 20px;
        }
        .info-box li {
            margin: 5px 0;
            color: #666;
        }
        code {
            background: #f8f9fa;
            padding: 3px 8px;
            border-radius: 4px;
            font-family: 'Courier New', monospace;
            color: #e83e8c;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>
            <i class="fa-brands fa-youtube"></i>
            YouTube URL Tester
        </h1>
        <p class="subtitle">Test if your YouTube URL is valid before adding it to Live Settings</p>
        
        <form method="GET">
            <div class="form-group">
                <label for="url">
                    <i class="fa-solid fa-link"></i> Enter YouTube URL
                </label>
                <input type="text" 
                       id="url" 
                       name="url" 
                       placeholder="https://www.youtube.com/watch?v=VIDEO_ID"
                       value="<?php echo htmlspecialchars($test_url); ?>"
                       required>
            </div>
            <button type="submit">
                <i class="fa-solid fa-check-circle"></i> Test URL
            </button>
        </form>
        
        <?php if ($test_url): ?>
            <?php if ($is_valid): ?>
                <div class="result success">
                    <h3>
                        <i class="fa-solid fa-check-circle"></i>
                        Valid YouTube URL!
                    </h3>
                    <p><strong>Video ID:</strong> <code><?php echo $video_id; ?></code></p>
                    <p><strong>Embed URL:</strong> <code>https://www.youtube.com/embed/<?php echo $video_id; ?></code></p>
                    
                    <div class="video-preview">
                        <iframe src="https://www.youtube.com/embed/<?php echo $video_id; ?>" 
                                frameborder="0" 
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                allowfullscreen></iframe>
                    </div>
                    
                    <p style="margin-top: 20px; color: #155724;">
                        ✅ This URL will work in your Live Settings!
                    </p>
                </div>
            <?php else: ?>
                <div class="result error">
                    <h3>
                        <i class="fa-solid fa-times-circle"></i>
                        Invalid YouTube URL
                    </h3>
                    <p style="color: #721c24;">The URL you entered is not a valid YouTube URL.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <div class="info-box">
            <strong>📋 Supported URL Formats:</strong>
            <ul>
                <li><code>https://www.youtube.com/watch?v=VIDEO_ID</code></li>
                <li><code>https://youtu.be/VIDEO_ID</code></li>
                <li><code>https://www.youtube.com/embed/VIDEO_ID</code></li>
                <li><code>https://www.youtube.com/live/VIDEO_ID</code></li>
            </ul>
            
            <strong style="margin-top: 15px;">🎯 Example URLs to Test:</strong>
            <ul>
                <li><a href="?url=https://www.youtube.com/watch?v=9Auq9mYxFEE" style="color: #007bff;">Test Example 1</a></li>
                <li><a href="?url=https://youtu.be/9Auq9mYxFEE" style="color: #007bff;">Test Example 2</a></li>
            </ul>
        </div>
    </div>
</body>
</html>
