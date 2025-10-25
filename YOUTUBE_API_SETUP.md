# YouTube API Setup Instructions

To display N9 India YouTube videos in the Top Headlines section, you need a YouTube Data API key.

## Steps to Get YouTube API Key:

1. **Go to Google Cloud Console**
   - Visit: https://console.cloud.google.com/

2. **Create a New Project** (or select existing)
   - Click "Select a project" at the top
   - Click "New Project"
   - Name it (e.g., "N9 India Website")
   - Click "Create"

3. **Enable YouTube Data API v3**
   - In the search bar, type "YouTube Data API v3"
   - Click on it
   - Click "Enable"

4. **Create API Credentials**
   - Click "Create Credentials" button
   - Select "API Key"
   - Copy the generated API key

5. **Restrict Your API Key** (Recommended for security)
   - Click on the API key you just created
   - Under "Application restrictions", select "HTTP referrers"
   - Add your website domain (e.g., `yourdomain.com/*`)
   - Under "API restrictions", select "Restrict key"
   - Choose "YouTube Data API v3"
   - Click "Save"

6. **Add API Key to Your Code**
   - Open `index.js`
   - Find the line: `const YOUTUBE_API_KEY = 'YOUR_YOUTUBE_API_KEY_HERE';`
   - Replace `YOUR_YOUTUBE_API_KEY_HERE` with your actual API key
   - Example: `const YOUTUBE_API_KEY = 'AIzaSyAbc123def456ghi789jkl012mno345pqr';`

## What This Does:

✅ Fetches latest 20 videos from N9 India YouTube channel
✅ Shows video thumbnails with YouTube play icon overlay
✅ Links directly to YouTube videos when clicked
✅ Updates automatically with your latest uploads

## Free Quota:

- YouTube Data API v3 provides 10,000 units per day for free
- Each video fetch costs ~3 units
- This allows ~3,000 requests per day (more than enough for a website)

## Note:

The channel ID is already set to N9 India: `UCiMVNsVU2JJbqJPVqYLOpGg`
