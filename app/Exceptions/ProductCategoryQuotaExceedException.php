<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;

final class ProductCategoryQuotaExceedException extends PurchaseException
{
    public function __construct()
    {
        parent::__construct('Quota exceeded for this product category', Response::HTTP_FORBIDDEN);
    }
}
