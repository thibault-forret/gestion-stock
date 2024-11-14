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
        Schema::create('supply_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supply_id')->nullable(false);
            $table->unsignedBigInteger('product_id')->nullable(false);
            $table->int('quantity_supplied')->nullable(false);
            $table->timestamps();

            $table->foreign('supply_id')->references('id')->on('supplies');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supply_lines');
    }
};
