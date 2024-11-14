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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable(false);
            $table->unsignedNBigInteger('warehouse_id')->nullable(false);
            $table->unsignedBigInteger('user_id')->nullable(false);
            $table->int('quantity_moved')->nullable(false);
            // replace entry and exit by IN and OUT
            $table->enum('movement_type', ['IN', 'OUT'])->nullable(false);
            $table->date('movement_date')->nullable(false);
            $table->enum('movement_status', ['IN_PROGRESS', 'COMPLETED', 'CANCELLED'])->nullable(false);
            $table->string('movement_source')->nullable(false);
            $table->timestamps();

            $table->foreign('product_id')->references('id')->on('product');
            $table->foreign('warehouse_id')->references('id')->on('warehouse');
            $table->foreign('user_id')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movement');
    }
};
