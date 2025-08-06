<?php
// File debug untuk mengecek konfigurasi dan error
echo "<h2>🔍 Debug Information</h2>";

echo "<h3>📋 PHP Configuration</h3>";
echo "<ul>";
echo "<li>PHP Version: " . phpversion() . "</li>";
echo "<li>Upload Max Filesize: " . ini_get('upload_max_filesize') . "</li>";
echo "<li>Post Max Size: " . ini_get('post_max_size') . "</li>";
echo "<li>Max File Uploads: " . ini_get('max_file_uploads') . "</li>";
echo "<li>File Uploads: " . (ini_get('file_uploads') ? 'Enabled' : 'Disabled') . "</li>";
echo "</ul>";

echo "<h3>📁 Directory Permissions</h3>";
$assets_dir = 'assets/';
echo "<ul>";
echo "<li>Assets directory exists: " . (is_dir($assets_dir) ? 'Yes' : 'No') . "</li>";
echo "<li>Assets directory writable: " . (is_writable($assets_dir) ? 'Yes' : 'No') . "</li>";
echo "<li>Assets directory permissions: " . substr(sprintf('%o', fileperms($assets_dir)), -4) . "</li>";
echo "</ul>";

echo "<h3>🗄️ Database Connection</h3>";
try {
    require_once 'config/database.php';
    echo "<p style='color: green;'>✅ Database connection successful</p>";
    
    // Test query
    $stmt = $pdo->query("SELECT COUNT(*) FROM produk");
    $count = $stmt->fetchColumn();
    echo "<p>Total products in database: $count</p>";
    
} catch (Exception $e) {
    echo "<p style='color: red;'>❌ Database connection failed: " . $e->getMessage() . "</p>";
}

echo "<h3>📝 Recent Error Logs</h3>";
$error_log = ini_get('error_log');
if ($error_log && file_exists($error_log)) {
    $logs = file_get_contents($error_log);
    $recent_logs = array_slice(explode("\n", $logs), -20);
    echo "<pre style='background: #f5f5f5; padding: 10px; max-height: 300px; overflow-y: auto;'>";
    foreach ($recent_logs as $log) {
        if (strpos($log, 'proses_produk.php') !== false) {
            echo htmlspecialchars($log) . "\n";
        }
    }
    echo "</pre>";
} else {
    echo "<p>No error log found or error log not configured</p>";
}

echo "<h3>🔧 Test Upload</h3>";
echo "<form method='POST' enctype='multipart/form-data'>";
echo "<input type='file' name='test_file' accept='image/*'>";
echo "<button type='submit'>Test Upload</button>";
echo "<div class='form-text'>Format yang didukung: JPG, PNG, WebP. Maksimal 2MB</div>";
echo "</form>";

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['test_file'])) {
    echo "<h4>Upload Test Results:</h4>";
    echo "<pre>";
    print_r($_FILES['test_file']);
    echo "</pre>";
    
    if ($_FILES['test_file']['error'] === UPLOAD_ERR_OK) {
        $test_path = 'assets/test_' . time() . '.jpg';
        if (move_uploaded_file($_FILES['test_file']['tmp_name'], $test_path)) {
            echo "<p style='color: green;'>✅ Test upload successful: $test_path</p>";
            unlink($test_path); // Clean up test file
        } else {
            echo "<p style='color: red;'>❌ Test upload failed</p>";
        }
    } else {
        echo "<p style='color: red;'>❌ Upload error: " . $_FILES['test_file']['error'] . "</p>";
    }
}

echo "<hr>";
echo "<p><a href='admin/'>← Back to Admin Panel</a></p>";
echo "<p><a href='index.php'>← Back to Main Page</a></p>";
?> 