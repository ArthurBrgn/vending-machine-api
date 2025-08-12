<?php

declare(strict_types=1);

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
            $table->date('date');
            $table->integer('count');
            $table->foreignIdFor(Employee::class, 'employee_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ProductCategory::class, 'product_category_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['employee_id', 'product_category_id']);
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
