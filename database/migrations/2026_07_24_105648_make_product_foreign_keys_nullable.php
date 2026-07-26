<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Drop foreign keys
            $table->dropForeign(['material_id']);
            $table->dropForeign(['color_id']);
            $table->dropForeign(['size_id']);

            // Make columns nullable
            $table->foreignId('material_id')->nullable()->change();
            $table->foreignId('color_id')->nullable()->change();
            $table->foreignId('size_id')->nullable()->change();

            // Re-add foreign keys
            $table->foreign('material_id')->references('id')->on('materials')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('color_id')->references('id')->on('colors')->cascadeOnUpdate()->nullOnDelete();
            $table->foreign('size_id')->references('id')->on('sizes')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['material_id']);
            $table->dropForeign(['color_id']);
            $table->dropForeign(['size_id']);

            $table->foreignId('material_id')->nullable(false)->change();
            $table->foreignId('color_id')->nullable(false)->change();
            $table->foreignId('size_id')->nullable(false)->change();

            $table->foreign('material_id')->references('id')->on('materials')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('color_id')->references('id')->on('colors')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('size_id')->references('id')->on('sizes')->cascadeOnUpdate()->restrictOnDelete();
        });
    }
};
