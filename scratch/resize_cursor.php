<?php
$src_path = 'images/favicon.png';
$dst_path = 'images/favicon_cursor.png';

$src_img = imagecreatefrompng($src_path);
if (!$src_img) {
    die("Failed to load source image");
}

$src_w = imagesx($src_img);
$src_h = imagesy($src_img);

// Desired size for cursor (max 32x32)
$max_w = 32;
$max_h = 32;

// Calculate scale to maintain aspect ratio
$scale = min($max_w / $src_w, $max_h / $src_h);
$new_w = (int)($src_w * $scale);
$new_h = (int)($src_h * $scale);

// Create transparent destination image
$dst_img = imagecreatetruecolor($new_w, $new_h);
imagealphablending($dst_img, false);
imagesavealpha($dst_img, true);

$transparent = imagecolorallocatealpha($dst_img, 0, 0, 0, 127);
imagefill($dst_img, 0, 0, $transparent);

// Resize
imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $new_w, $new_h, $src_w, $src_h);

// Save as PNG
if (imagepng($dst_img, $dst_path)) {
    echo "Resized cursor saved to $dst_path ($new_w x $new_h)";
} else {
    echo "Failed to save resized cursor";
}

imagedestroy($src_img);
imagedestroy($dst_img);
