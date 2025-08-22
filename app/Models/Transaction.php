<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Transaction extends Model {
	
	public function productCategory(): BelongsTo {
		return $this->belongsTo(ProductCategory::class);
	}
}
