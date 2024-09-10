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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('url_address', '60')->unique();
            $table->decimal('transaction_amount', 15, 0);
            $table->string('transaction_type'); // debit or credit
            $table->date('transaction_date');

            $table->unsignedBigInteger('user_id_create')->nullable();
            $table->foreign('user_id_create')->references('id')->on('users');

            $table->unsignedBigInteger('user_id_update')->nullable();
            $table->foreign('user_id_update')->references('id')->on('users');

            // Polymorphic columns
            $table->unsignedBigInteger('transactionable_id');
            $table->string('transactionable_type');

            $table->timestamps();

            $table->unsignedBigInteger('cash_account_id');
            $table->foreign('cash_account_id')->references('id')->on('cash_accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
