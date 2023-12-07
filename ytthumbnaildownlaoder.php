<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YouTube Thumbnail Downloader</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>YouTube Thumbnail Downloader</h1>

        <?php
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $videoUrl = $_POST['videoUrl'];
            $videoId = getVideoId($videoUrl);

            if ($videoId) {
                // Thumbnail URLs for different sizes
                $fullHdThumbnailUrl = "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
                $mediumThumbnailUrl = "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg";
                $smallThumbnailUrl = "https://img.youtube.com/vi/{$videoId}/sddefault.jpg";

                // Display thumbnails
                displayThumbnail($fullHdThumbnailUrl, 'Full HD Thumbnail');
                displayThumbnail($mediumThumbnailUrl, 'Medium Thumbnail');
                displayThumbnail($smallThumbnailUrl, 'Small Thumbnail');
            } else {
                echo '<p>Invalid YouTube Video URL.</p>';
            }
        }

        function displayThumbnail($url, $label) {
            $thumbnailData = file_get_contents($url);

            if ($thumbnailData !== false) {
                $thumbnailPath = "thumbnails/" . basename($url);
                file_put_contents($thumbnailPath, $thumbnailData);
                echo '<div class="thumbnail-container">';
                echo '<p>' . $label . '</p>';
                echo '<img src="' . $thumbnailPath . '" alt="Thumbnail Preview">';
                echo '<a href="' . $thumbnailPath . '" download style="display: block; padding: 10px; background-color: #3498db; color: #fff; text-align: center; text-decoration: none; margin-top: 10px;">Download</a>';
                echo '</div>';
            } else {
                echo '<p>Failed to fetch the thumbnail.</p>';
            }
        }

        function getVideoId($url) {
            $match = [];

            // Check if it's a YouTube share link
            if (strpos($url, 'youtu.be') !== false) {
                preg_match('/youtu.be\/([^\/\?]+)/', $url, $match);
            } else {
                // Extract video ID from regular YouTube URL
                preg_match('/[?&]v=([^&#]+)/', $url, $match);
            }

            return $match ? $match[1] : null;
        }
        ?>

        <form action="" method="post">
            <label for="videoUrl">Enter YouTube Video URL:</label>
            <input type="text" name="videoUrl" placeholder="e.g., https://www.youtube.com/watch?v=VIDEO_ID" required>
            <button type="submit">Download Thumbnails</button>
        </form>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
