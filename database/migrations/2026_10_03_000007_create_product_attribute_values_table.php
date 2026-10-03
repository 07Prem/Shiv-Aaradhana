<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->cascadeOnDelete();
            $table->foreignId('attribute_definition_id')->constrained('attribute_definitions')->cascadeOnDelete();
            $table->text('text_value')->nullable();
            $table->decimal('number_value', 12, 4)->nullable()->index();
            $table->json('json_value')->nullable();
            $table->boolean('boolean_value')->nullable();
            $table->timestamps();

            $table->unique(['product_id', 'attribute_definition_id'], 'prod_attr_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_attribute_values');
    }
};
