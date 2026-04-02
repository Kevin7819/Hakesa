<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Convert all existing roles to 'admin'
        DB::table('admin_users')->update(['role' => 'admin']);

        // Change enum to only allow 'admin'
        Schema::table('admin_users', function (Blueprint $table) {
            $table->enum('role', ['admin'])->default('admin')->change();
        });
    }

    public function down(): void
    {
        Schema::table('admin_users', function (Blueprint $table) {
            $table->enum('role', ['super-admin', 'admin', 'editor'])->default('editor')->change();
        });
    }
};
