<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 40)->unique();
            $table->enum('inquiry_type', ['general', 'quote'])->default('general')->index();
            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('full_name');
            $table->string('company_name')->nullable();
            $table->string('email')->index();
            $table->string('phone');
            $table->string('country')->index();
            $table->string('target_quantity')->nullable();
            $table->string('packaging_requirements')->nullable();
            $table->string('port_of_destination')->nullable();
            $table->string('subject')->nullable();
            $table->text('message');
            $table->enum('status', ['new', 'in_progress', 'responded', 'closed', 'spam'])->default('new')->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inquiries');
    }
};
