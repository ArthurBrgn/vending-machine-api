<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Exceptions\InvalidCardException;
use App\Exceptions\InvalidSlotException;
use App\Exceptions\NotEnoughPointsException;
use App\Exceptions\ProductCategoryNotFoundException;
use App\Exceptions\ProductCategoryQuotaExceedException;
use App\Exceptions\ProductPriceMismatchException;
use App\Http\Requests\PurchaseRequest;
use App\Models\Card;
use App\Models\ClassificationLimit;
use App\Models\EmployeeDailyProductPurchase;
use App\Models\Slot;
use App\Models\Transaction;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

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
            ->where('is_active', true)
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

        $employee = $card->employee;

        $dailyProductCategoryLimit = ClassificationLimit::query()
            ->where('classification_id', $employee->classification_id)
            ->where('product_category_id', $slot->product_category_id)
            ->value('daily_limit');

        if ($dailyProductCategoryLimit === null) {
            throw new ProductCategoryNotFoundException;
        }

        $employeeDailyProductPurchase = EmployeeDailyProductPurchase::firstOrNew([
            'employee_id' => $employee->id,
            'product_category_id' => $slot->product_category_id,
            'date' => now()->format('Y-m-d'),
        ]);

        if ($employeeDailyProductPurchase->daily_count >= $dailyProductCategoryLimit) {
            throw new ProductCategoryQuotaExceedException;
        }

        DB::transaction(function () use ($employee, $card, $slot, $employeeDailyProductPurchase, $productPrice) {
            $employee->decrement('current_points', $productPrice);

            if ($employeeDailyProductPurchase->exists) {
                $employeeDailyProductPurchase->increment('daily_count');
            } else {
                $employeeDailyProductPurchase->daily_count += 1;

                $employeeDailyProductPurchase->save();
            }

            $transaction = Transaction::create([
                'employee_id' => $employee->id,
                'card_id' => $card->id,
                'machine_id' => $slot->machine_id,
                'slot_id' => $slot->id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Purchase successful',
                'data' => [
                    'transaction_id' => $transaction->id,
                    'points_deducted' => $productPrice,
                    'remaining_points' => $employee->current_points,
                ],
            ]);
        });
    }
}
