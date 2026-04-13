<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * DB-001: Unique constraint on cart_items(cart_id, product_id) — prevents duplicate items
     * DB-003: Index on products.name for search queries
     * DB-004: Index on orders.user_id for order listing performance
     * DB-005: Composite index for password_reset_otps active OTP lookup
     */
    public function up(): void
    {
        // DB-001: Unique constraint on cart items — one entry per product per cart
        Schema::table('cart_items', function (Blueprint $table) {
            $table->unique(['cart_id', 'product_id']);
        });

        // DB-003: Index on products.name (standard index for leading-wildcard LIKE)
        // Note: For full-text search, a fulltext index would be needed on MySQL/PostgreSQL
        // SQLite doesn't support fulltext natively, so we use a standard index
        Schema::table('products', function (Blueprint $table) {
            $table->index('name');
        });

        // DB-004: Index on orders.user_id (foreign key already exists, but explicit index
        // ensures cross-database compatibility — SQLite doesn't auto-index FKs)
        Schema::table('orders', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('status');
            $table->index(['status', 'created_at']);
        });

        // DB-005: Composite index for password_reset_otps
        // Improves lookup performance for active OTP queries
        Schema::table('password_reset_otps', function (Blueprint $table) {
            $table->index(['email', 'used_at', 'expires_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropUnique(['cart_id', 'product_id']);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['name']);
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['status', 'created_at']);
        });

        Schema::table('password_reset_otps', function (Blueprint $table) {
            $table->dropIndex(['email', 'used_at', 'expires_at']);
        });
    }
};
