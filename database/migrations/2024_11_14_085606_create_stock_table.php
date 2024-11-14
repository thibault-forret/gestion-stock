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
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable(false);
            $table->unsignedBigInteger('warehouse_id')->nullable(false);
            $table->int('quantity_available')->nullable(false);
            // remplace last_update_date (updated_at)
            $table->timestamps(); 

            $table->foreign('product_id')->references('id')->on('product')->onDelete('cascade');
            $table->foreign('warehouse_id')->references('id')->on('warehouse')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock');
    }
};
