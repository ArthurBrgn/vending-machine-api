<?php

use App\Models\Machine;
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
        Schema::create('slots', function (Blueprint $table) {
            $table->id();
			$table->string('name');
			$table->integer('number');
			$table->integer('price');
			$table->boolean('is_available')->default(true);
			$table->foreignIdFor(Machine::class)->constrained();
			$table->foreignIdFor(ProductCategory::class)->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('slots');
    }
};
