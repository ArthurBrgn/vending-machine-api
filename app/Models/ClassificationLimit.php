<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

final class ClassificationLimit extends Pivot
{
    use HasFactory;

    protected $table = 'classification_limits';
}
