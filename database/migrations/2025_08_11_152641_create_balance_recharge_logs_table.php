<?php

use App\Models\Employee;
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
        Schema::create('balance_recharge_logs', function (Blueprint $table) {
            $table->id();
			$table->datetime('recharge_date');
			$table->integer('points_added');
			$table->foreignIdFor(Employee::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('balance_recharge_logs');
    }
};
