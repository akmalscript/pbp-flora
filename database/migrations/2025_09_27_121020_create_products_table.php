<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 32);
            $table->string('description', 255);
            $table->integer('price');
            $table->unsignedInteger('stock')->default(0);
            $table->string('image_url');
            $table->integer('pre_order_duration');
            $table->boolean('active')->default(1);
            $table->timestamps();

            $table->uuid('category_id')->nullable();
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null')->onUpdate('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
