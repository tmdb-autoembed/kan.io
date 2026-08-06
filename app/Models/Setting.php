<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

final class Setting extends Model
{
    protected string $table = 'settings';
    protected string $primaryKey = 'key';
    protected array $fillable = ['key', 'value'];
    protected bool $timestamps = false;

    public static function getValue(string $key, mixed $default = null): mixed
    {
        try {
            $setting = (new self())->find($key);
            return $setting ? $setting['value'] : $default;
        } catch (\Throwable) {
            return $default;
        }
    }

    public static function set(string $key, mixed $value, string $type = 'string'): void
    {
        if (is_array($value)) {
            $value = json_encode($value);
        }

        $setting = new self();
        $existing = $setting->find($key);

        if ($existing) {
            $setting->update($key, ['value' => $value]);
        } else {
            $setting->create(['key' => $key, 'value' => $value]);
        }
    }

    public static function getMany(array $keys): array
    {
        $settings = [];
        foreach ($keys as $key) {
            $settings[$key] = self::getValue($key);
        }
        return $settings;
    }

    public static function getAllSettings(): array
    {
        try {
            $rows = (new self())->all();
            $result = [];
            foreach ($rows as $row) {
                $result[$row['key']] = $row['value'];
            }
            return $result;
        } catch (\Throwable) {
            return [];
        }
    }

    public static function deleteKey(string $key): bool
    {
        return (new self())->delete($key);
    }
}
