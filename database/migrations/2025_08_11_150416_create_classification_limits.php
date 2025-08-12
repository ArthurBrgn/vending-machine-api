<?php

declare(strict_types=1);

use App\Models\Classification;
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
        Schema::create('classification_limits', function (Blueprint $table) {
            $table->foreignIdFor(Classification::class, 'classification_id')->constrained()->cascadeOnDelete();
            $table->foreignIdFor(ProductCategory::class, 'product_category_id')->constrained()->cascadeOnDelete();
            $table->integer('daily_limit');
            $table->timestamps();

            $table->unique(['classification_id', 'product_category_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classification_limits');
    }
};
