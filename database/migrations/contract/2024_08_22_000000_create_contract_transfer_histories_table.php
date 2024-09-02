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
        Schema::create('contract_transfer_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->onDelete('cascade');
            $table->foreignId('old_customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('new_customer_id')->constrained('customers')->onDelete('cascade');

            $table->string('url_address', '60')->unique();

            $table->integer('transfer_amount');
            $table->date('transfer_date');
            $table->string('transfer_note', '200')->nullable();
            $table->boolean('approved')->default(false);
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
        Schema::dropIfExists('contract_transfer_histories');
    }
};
