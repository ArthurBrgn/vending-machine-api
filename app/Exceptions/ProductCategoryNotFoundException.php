<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;

final class ProductCategoryNotFoundException extends PurchaseException
{
    public function __construct()
    {
        parent::__construct('This product category does not exists', Response::HTTP_NOT_FOUND);
    }
}
