<?php

declare(strict_types=1);

use App\Models\Card;
use App\Models\Employee;
use App\Models\Machine;
use App\Models\ProductCategory;
use App\Models\Slot;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('points_deducted');
            $table->foreignIdFor(Employee::class)->constrained();
            $table->foreignIdFor(Card::class)->constrained();
            $table->foreignIdFor(Machine::class)->constrained();
            $table->foreignIdFor(Slot::class)->constrained();
            $table->foreignIdFor(ProductCategory::class)->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
