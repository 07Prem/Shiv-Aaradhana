<?php

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
        Schema::table('users', function (Blueprint $table) {
            // New fields for Customer Registration
            $table->string('username')->unique()->nullable()->after('name');
            $table->string('phone')->unique()->nullable()->after('email');
            $table->string('company_name')->nullable()->after('password');
            $table->text('address')->nullable()->after('company_name');
            $table->timestamp('last_login')->nullable()->after('updated_at');
            $table->timestamp('last_password_change')->nullable()->after('last_login');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'phone',
                'company_name',
                'address',
                'last_login',
                'last_password_change'
            ]);
        });
    }
};
