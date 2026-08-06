<?php
declare(strict_types=1);

namespace ThemeHub\Core;

/**
 * File Uploader
 */
final class Uploader
{
    public static function image(array $file, string $directory = 'images', array $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'], int $maxSize = 5 * 1024 * 1024): ?string
    {
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }
        
        if ($file['size'] > $maxSize) {
            return null;
        }
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);
        
        if (!in_array($mimeType, $allowedTypes, true)) {
            return null;
        }
        
        $extension = match ($mimeType) {
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/webp' => 'webp',
            'image/gif' => 'gif',
            default => 'bin'
        };
        
        $filename = uniqid() . '.' . $extension;
        $uploadDir = UPLOAD_PATH . '/' . $directory;
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Optimize image
        $image = match ($mimeType) {
            'image/jpeg', 'image/png', 'image/webp' => match ($mimeType) {
                'image/png' => imagecreatefrompng($file['tmp_name']),
                'image/webp' => imagecreatefromwebp($file['tmp_name']),
                default => imagecreatefromjpeg($file['tmp_name'])
            },
            default => null
        };
        
        if ($image) {
            $maxWidth = 1920;
            $maxHeight = 1080;
            $width = imagesx($image);
            $height = imagesy($image);
            
            if ($width > $maxWidth || $height > $maxHeight) {
                $ratio = min($maxWidth / $width, $maxHeight / $height);
                $newWidth = (int)($width * $ratio);
                $newHeight = (int)($height * $ratio);
                
                $resized = imagecreatetruecolor($newWidth, $newHeight);
                imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                imagedestroy($image);
                $image = $resized;
            }
            
            match ($mimeType) {
                'image/png' => imagepng($image, $uploadDir . '/' . $filename, 8),
                'image/webp' => imagewebp($image, $uploadDir . '/' . $filename, 80),
                default => imagejpeg($image, $uploadDir . '/' . $filename, 85)
            };
            
            imagedestroy($image);
        } else {
            move_uploaded_file($file['tmp_name'], $uploadDir . '/' . $filename);
        }
        
        return $directory . '/' . $filename;
    }
}
