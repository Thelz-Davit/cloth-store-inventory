<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->foreignId('material_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('color_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('size_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();

            $table->integer('stock');

            // true = active, false = inactive
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
