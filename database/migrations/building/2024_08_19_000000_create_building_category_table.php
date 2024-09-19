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
        Schema::create('building_category', function (Blueprint $table) {
            $table->id();

            $table->string('url_address', '60')->unique();
            $table->string('category_name', '10');
            $table->string('category_area', '10');
            $table->integer('category_cost');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_category');
    }
};
