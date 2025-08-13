<?php

declare(strict_types=1);

namespace App\Exceptions;

use Illuminate\Http\Response;

final class InvalidSlotException extends PurchaseException
{
    public function __construct()
    {
        parent::__construct('This slot is invalid', Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}
