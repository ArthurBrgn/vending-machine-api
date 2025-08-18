<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

final class ProductCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected static function booted(): void
    {
        self::saving(function (ProductCategory $productCategory) {
            if ($productCategory->isDirty('name')) {
                // Set productCategory slug at saving hook
                $productCategory->slug = Str::slug($productCategory->name);
            }
        });
    }

    public function classifications(): BelongsToMany
    {
        return $this->belongsToMany(Classification::class, 'classification_limits')
            ->withPivot(['daily_limit'])
            ->withTimestamps();
    }
}
