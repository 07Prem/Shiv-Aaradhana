<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_recommendations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('recommended_product_id')->constrained('products')->cascadeOnDelete();
            $table->enum('relation_type', ['curated', 'complementary', 'alternative', 'related'])->default('related');
            $table->integer('priority')->default(0);
            $table->timestamps();

            $table->unique(['source_product_id', 'recommended_product_id'], 'source_rec_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_recommendations');
    }
};
