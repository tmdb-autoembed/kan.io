<?php
declare(strict_types=1);

namespace ThemeHub\Models;

use ThemeHub\Core\Model;

/**
 * Coupon Model
 */
final class Coupon extends Model
{
    protected string $table = 'coupons';
    protected array $fillable = [
        'code', 'type', 'value', 'min_amount', 'max_amount',
        'starts_at', 'expires_at', 'usage_limit', 'usage_count',
        'status', 'created_by'
    ];
    protected array $casts = [
        'value' => 'float',
        'min_amount' => 'float',
        'max_amount' => 'float',
        'usage_limit' => 'int',
        'usage_count' => 'int',
    ];

    public function isValid(): bool
    {
        if ($this->status !== 'active') {
            return false;
        }
        
        $now = date('Y-m-d H:i:s');
        
        if ($this->starts_at && $now < $this->starts_at) {
            return false;
        }
        
        if ($this->expires_at && $now > $this->expires_at) {
            return false;
        }
        
        if ($this->usage_limit && $this->usage_count >= $this->usage_limit) {
            return false;
        }
        
        return true;
    }

    public function calculateDiscount(float $amount): float
    {
        if (!$this->isValid()) {
            return 0.0;
        }
        
        if ($this->min_amount && $amount < $this->min_amount) {
            return 0.0;
        }
        
        $discount = $this->type === 'percent' 
            ? ($amount * $this->value / 100)
            : $this->value;
        
        if ($this->max_amount) {
            $discount = min($discount, $this->max_amount);
        }
        
        return round($discount, 2);
    }

    public function incrementUsage(): bool
    {
        return Database::connection()->prepare("UPDATE {$this->table} SET usage_count = usage_count + 1 WHERE id = ?")->execute([$this->id]);
    }

    public static function findByCode(string $code): ?self
    {
        $instance = new self();
        $record = $instance->findBy('code', strtoupper($code));
        return $record ? $instance->setRawAttributes($record) : null;
    }

    private function setRawAttributes(array $attributes): self
    {
        foreach ($attributes as $key => $value) {
            $this->$key = $value;
        }
        return $this;
    }
}
