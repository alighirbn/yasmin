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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id_create')->nullable();
            $table->foreign('user_id_create')->references('id')->on('users');

            $table->unsignedBigInteger('user_id_update')->nullable();
            $table->foreign('user_id_update')->references('id')->on('users');


            $table->unsignedBigInteger('payment_contract_id');
            $table->foreign('payment_contract_id')->references('id')->on('contracts')->onDelete('cascade');

            $table->unsignedBigInteger('contract_installment_id')->nullable();
            $table->foreign('contract_installment_id')->references('id')->on('contract_installments')->onDelete('cascade');


            $table->unsignedBigInteger('cash_account_id')->nullable();
            $table->foreign('cash_account_id')->references('id')->on('cash_accounts')->onDelete('cascade');


            $table->string('url_address', '60')->unique();

            $table->decimal('payment_amount', 15, 0);
            $table->date('payment_date');
            $table->string('payment_note', '200')->nullable();
            $table->boolean('approved')->default(false); // Add the approved column, default to false

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
