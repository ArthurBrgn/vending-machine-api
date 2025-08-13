<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

final class EmployeeDailyProductPurchase extends Pivot
{
    protected $table = 'employee_daily_product_purchases';

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'daily_count' => 0,
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'date' => 'date',
            'daily_count' => 'integer',
        ];
    }
}
