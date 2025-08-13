<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;

final class InvalidCardException extends PurchaseException
{
    public function __construct()
    {
        parent::__construct('This card is invalid', Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
