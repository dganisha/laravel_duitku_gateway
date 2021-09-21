<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('amount');
            $table->string('customer_email');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('payment_method');
            $table->string('product');
            $table->longText('product_description')->nullable();
            $table->string('reff_number_to_duitku');
            $table->string('duitku_reff_number')->nullable();
            $table->string('virtual_account_number')->nullable();
            $table->string('duitku_payment_url')->nullable();
            $table->enum('status', ['Sukses', 'Pending', 'Failed'])->default('Pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
