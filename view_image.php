<?php
// Handler untuk serve gambar dari assets dengan validasi MIME type
$allowed_ext = ['jpg', 'jpeg', 'png', 'webp'];
$allowed_mime = [
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'webp' => 'image/webp',
];

$file = $_GET['file'] ?? '';
if (!$file || strpos($file, '..') !== false || strpos($file, '/') !== false) {
    http_response_code(400);
    exit('Invalid file request');
}

$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
if (!in_array($ext, $allowed_ext)) {
    http_response_code(403);
    exit('Forbidden');
}

$path = __DIR__ . '/assets/' . $file;
if (!file_exists($path)) {
    http_response_code(404);
    exit('File not found');
}

$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime = finfo_file($finfo, $path);
finfo_close($finfo);
if ($mime !== $allowed_mime[$ext]) {
    http_response_code(403);
    exit('Invalid MIME type');
}

header('Content-Type: ' . $mime);
header('Content-Length: ' . filesize($path));
readfile($path);
exit; 