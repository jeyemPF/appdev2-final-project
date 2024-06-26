<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->timestamp('payment_date');
            $table->decimal('amount', 10, 2);
            $table->enum('payment_method', ['credit_card', 'paypal', 'bank_transfer', 'gcash']);
            $table->unsignedBigInteger('order_id'); // Add order_id column
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade'); // Add foreign key constraint
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['order_id']); // Drop foreign key constraint
        });
        Schema::dropIfExists('payments');
    }
}