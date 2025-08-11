<?php

use App\Models\Employee;
use App\Models\ProductCategory;
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
        Schema::create('employee_daily_product_limits', function (Blueprint $table) {
            $table->id();
			$table->date('date');
			$table->integer('count');
			$table->foreignIdFor(Employee::class)->constrained()->cascadeOnDelete();
			$table->foreignIdFor(ProductCategory::class)->constrained()->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employee_daily_product_limits');
    }
};
