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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->string('brand');
            $table->string('category');
            $table->text('description');
            $table->double('price', 10, 2);
            $table->integer('count_in_stock');
            $table->integer('rating')->default(0);
            $table->integer('num_reviews')->default(0);
            $table->foreignId('seller_id');
            $table->foreignId('order_item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
