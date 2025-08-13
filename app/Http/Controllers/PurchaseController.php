<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\InvalidCardException;
use App\Exceptions\InvalidSlotException;
use App\Exceptions\NotEnoughPointsException;
use App\Exceptions\ProductPriceMismatchException;
use App\Http\Requests\PurchaseRequest;
use App\Models\Card;
use App\Models\Slot;
use Illuminate\Contracts\Database\Query\Builder;

final class PurchaseController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(PurchaseRequest $request)
    {
        $cardNumber = $request->validated('card_number');
        $machineId = (int) $request->validated('machine_id');
        $slotNumber = (int) $request->validated('slot_number');
        $productPrice = (int) $request->validated('product_price');

        $card = Card::query()
            ->with('employee')
            ->where('number', $cardNumber)
            ->where('is_active', false)
            ->where(function (Builder $query) {
                $query->whereNull('expired_date')
                    ->orWhere('expired_date', '>=', now());
            })
            ->first();

        if (! $card) {
            throw new InvalidCardException;
        }

        $slot = Slot::query()
            ->where('machine_id', $machineId)
            ->where('number', $slotNumber)
            ->where('is_available', true)
            ->whereHas('machine', function (Builder $query) {
                $query->where('is_active', true);
            })
            ->first();

        if (! $slot) {
            throw new InvalidSlotException;
        }

        if ($slot->price !== $productPrice) {
            throw new ProductPriceMismatchException;
        }

        if ($card->employee->current_points < $productPrice) {
            throw new NotEnoughPointsException;
        }

        /**
         * Checks à faire :
         *
         * La card existe et est bien valide
         *
         * Check si on trouve un employé lié à la carte
         *
         * Le slot est actif et existe bien dans la machine
         *
         * Le price correspond bien au price du slot
         *
         * Le quota du product_id par rapport à la classification
         *
         * L'employé possède assez de points restants
         */

        /**
         * Si check ok :
         *
         * Insérer la transaction
         *
         * Update les points de l'employee
         *
         * Update le daily count de la catégorie de l'employee
         */
    }
}
