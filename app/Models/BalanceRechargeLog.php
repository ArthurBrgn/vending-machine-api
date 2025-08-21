<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class BalanceRechargeLog extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'recharge_date' => 'datetime',
            'new_balance' => 'integer',
            'old_balance' => 'integer',
        ];
    }
}
