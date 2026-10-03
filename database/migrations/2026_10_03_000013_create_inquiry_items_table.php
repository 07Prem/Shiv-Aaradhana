<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiry_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inquiry_id')->constrained('inquiries')->cascadeOnDelete();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('product_name');
            $table->string('product_slug')->nullable();
            $table->string('hs_code', 50)->nullable();
            $table->string('quantity', 100);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['inquiry_id', 'product_id']);
        });

        // Backfill existing inquiries that have a product_id into inquiry_items
        $inquiries = \Illuminate\Support\Facades\DB::table('inquiries')
            ->whereNotNull('product_id')
            ->get();

        foreach ($inquiries as $inq) {
            $product = \Illuminate\Support\Facades\DB::table('products')->where('id', $inq->product_id)->first();
            \Illuminate\Support\Facades\DB::table('inquiry_items')->insert([
                'inquiry_id' => $inq->id,
                'product_id' => $inq->product_id,
                'product_name' => $product?->name ?? 'Quoted Product',
                'product_slug' => $product?->slug ?? null,
                'hs_code' => $product?->hs_code ?? null,
                'quantity' => $inq->target_quantity ?? '1 Unit/Consignment',
                'notes' => $inq->packaging_requirements ?? null,
                'created_at' => $inq->created_at,
                'updated_at' => $inq->updated_at,
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiry_items');
    }
};
