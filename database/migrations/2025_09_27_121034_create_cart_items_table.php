<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->uuid('cart_id');
            $table->uuid('product_id');
            $table->integer('quantity');

            $table->primary(['cart_id', 'product_id']);

            $table->foreign('cart_id')
                  ->references('id')->on('carts')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');

            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('cart_items');
    }
};
