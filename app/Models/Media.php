<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Media Model
 */
final class Media extends Model
{
    protected string $table = 'media';
    protected array $fillable = [
        'filename', 'original_name', 'mime_type', 'size',
        'path', 'url', 'alt_text', 'caption', 'uploaded_by'
    ];
    protected array $casts = [
        'size' => 'int',
    ];

    public static function upload(array $file, string $directory = 'media'): ?self
    {
        $path = upload_file($file, $directory);
        
        if (!$path) {
            return null;
        }
        
        $media = new self();
        $media->create([
            'filename' => basename($path),
            'original_name' => $file['name'],
            'mime_type' => $file['type'],
            'size' => $file['size'],
            'path' => $path,
            'url' => upload($path),
            'uploaded_by' => auth_user()['id'] ?? null,
        ]);
        
        return $media;
    }

    public function formatBytes(): string
    {
        return format_bytes($this->size);
    }
}
