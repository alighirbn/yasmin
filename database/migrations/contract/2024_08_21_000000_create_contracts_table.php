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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id_create')->nullable();
            $table->foreign('user_id_create')->references('id')->on('users');

            $table->unsignedBigInteger('user_id_update')->nullable();
            $table->foreign('user_id_update')->references('id')->on('users');

            $table->unsignedBigInteger('contract_customer_id');
            $table->foreign('contract_customer_id')->references('id')->on('customers')->onDelete('cascade');

            $table->unsignedBigInteger('contract_building_id');
            $table->foreign('contract_building_id')->references('id')->on('buildings')->onDelete('cascade');

            $table->unsignedBigInteger('contract_payment_method_id');
            $table->foreign('contract_payment_method_id')->references('id')->on('payment_method');


            $table->string('url_address', '60')->unique();

            $table->decimal('contract_amount', 15, 0);
            $table->date('contract_date');
            $table->string('contract_note', '200')->nullable();
            $table->enum('stage', ['temporary', 'accepted', 'Authenticated'])->default('temporary');
            $table->timestamp('temporary_at')->nullable()->useCurrent(); // Sets default to NOW()
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('Authenticated_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
