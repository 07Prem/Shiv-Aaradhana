<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('product_type_id')->constrained('product_types')->restrictOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('hs_code', 30)->nullable()->index();
            $table->string('origin')->default('Gujarat, India');
            $table->text('short_description');
            $table->longText('description');
            $table->string('primary_image')->nullable();
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft')->index();
            $table->boolean('is_featured')->default(false)->index();
            $table->string('harvest_season')->nullable();
            $table->string('supply_capacity')->nullable();
            $table->string('minimum_order_qty')->nullable();
            $table->string('packaging_options')->nullable();
            $table->timestamp('published_at')->nullable()->index();
            $table->timestamps();

            if (DB::getDriverName() !== 'sqlite') {
                $table->fullText(['name', 'short_description']);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
