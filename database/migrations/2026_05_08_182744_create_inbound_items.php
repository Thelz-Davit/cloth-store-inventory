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
        Schema::create('inbound_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('inbound_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('material_id')
                ->constrained()
                ->cascadeOnDelete();

            // quantity received
            $table->integer('quantity');

            $table->decimal('unit_price', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inbound_items');
    }
};
