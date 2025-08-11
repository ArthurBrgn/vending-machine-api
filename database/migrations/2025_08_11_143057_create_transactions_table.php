<?php

use App\Models\Card;
use App\Models\Employee;
use App\Models\Machine;
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
			$table->foreignIdFor(Employee::class)->constrained();
			$table->foreignIdFor(Card::class)->constrained();
			$table->foreignIdFor(Machine::class)->constrained();
			$table->foreignIdFor(Slot::class)->constrained();
			$table->text('failure_reason')->nullable();
            $table->timestamps();
			$table->softDeletes();
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
