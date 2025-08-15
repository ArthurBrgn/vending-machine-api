<?php

declare(strict_types=1);

use App\Http\Controllers\PurchaseController;
use Illuminate\Support\Facades\Route;

Route::post('/purchase', PurchaseController::class)->name('purchase');
