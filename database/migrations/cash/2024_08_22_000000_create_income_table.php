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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->string('url_address', '60')->unique();

            $table->unsignedBigInteger('income_type_id');
            $table->foreign('income_type_id')->references('id')->on('income_types')->onDelete('cascade');

            $table->decimal('income_amount', 15, 2); // Amount spent on the income
            $table->date('income_date');     // Date the income occurred
            $table->text('income_note')->nullable(); // Optional notes about the income
            $table->boolean('approved')->default(false); // New field for approval status

            $table->unsignedBigInteger('cash_account_id')->nullable();
            $table->foreign('cash_account_id')->references('id')->on('cash_accounts')->onDelete('cascade');

            $table->unsignedBigInteger('user_id_create')->nullable();
            $table->foreign('user_id_create')->references('id')->on('users');

            $table->unsignedBigInteger('user_id_update')->nullable();
            $table->foreign('user_id_update')->references('id')->on('users');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incomes');
    }
};
