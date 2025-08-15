<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;

final class ProductPriceMismatchException extends PurchaseException
{
    public function __construct()
    {
        parent::__construct('The product price is invalid', Response::HTTP_CONFLICT);
    }
}
