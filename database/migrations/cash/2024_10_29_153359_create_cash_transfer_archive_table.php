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
        Schema::create('cash_transfer_archive', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id_create')->nullable();
            $table->foreign('user_id_create')->references('id')->on('users');

            $table->unsignedBigInteger('user_id_update')->nullable();
            $table->foreign('user_id_update')->references('id')->on('users');


            $table->unsignedBigInteger('cash_transfer_id');
            $table->foreign('cash_transfer_id')->references('id')->on('cash_transfers')->onDelete('cascade');

            $table->string('image_path'); // or 'image_url' if you're storing URLs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_transfer_archive');
    }
};
