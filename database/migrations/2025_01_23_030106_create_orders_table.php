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
        Schema::create('orders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address');
            $table->decimal('shipping_fee', 10,2)->default(0);
            $table->decimal('total', 10,2);
            $table->string('note', 100)->nullable();
            $table->string('receiver_name', 200);
            $table->string('email', 100);
            $table->string('phone', 15);
            $table->string('status', 50);
            $table->float('VAT', 10,2)->default(0.1);
            $table->string('payment', 200); //Phuong thuc thanh toan
            $table->string('transaction_id', 50)->nullable();
            $table->unsignedInteger('customer_id');
            $table->foreign('customer_id')->references('id')->on('customers');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
