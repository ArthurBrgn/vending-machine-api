<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

final class ClassificationLimit extends Pivot
{
    protected $table = 'classification_limits';
}
