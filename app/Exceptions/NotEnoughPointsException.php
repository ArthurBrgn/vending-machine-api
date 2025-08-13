<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;

final class NotEnoughPointsException extends PurchaseException
{
    public function __construct()
    {
        parent::__construct('You have not enough points to buy this product', Response::HTTP_PAYMENT_REQUIRED);
    }
}
