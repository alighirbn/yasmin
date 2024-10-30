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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('url_address', '60')->unique();

            $table->unsignedBigInteger('expense_type_id');
            $table->foreign('expense_type_id')->references('id')->on('expense_types')->onDelete('cascade');

            $table->decimal('expense_amount', 15, 2); // Amount spent on the expense
            $table->date('expense_date');     // Date the expense occurred
            $table->text('expense_note')->nullable(); // Optional notes about the expense
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
        Schema::dropIfExists('expenses');
    }
};
