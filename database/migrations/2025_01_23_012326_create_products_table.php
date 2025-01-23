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
            $table->increments('id');
            $table->string('product_name', 150);
            $table->text('description')->nullable();
            $table->string('material')->nullable();
            $table->decimal('price', 10,3)->nullable();
            $table->text('short_description')->nullable();
            $table->string('slug')->nullable();
            $table->string('image')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('discount_id');
            $table->foreign('category_id')->references('id')->on('categories');
            $table->foreign('discount_id')->references('id')->on('discounts');
            $table->timestamps();
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
