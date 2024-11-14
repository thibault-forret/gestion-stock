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
        Schema::create('supplies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id')->nullabe(false);
            $table->unsignedBigInteger('product_id')->nullabe(false);
            $table->unsignedBigInteger('warehouse_id')->nullable(false);
            $table->date('supply_date')->nullable(false);
            $table->int('quantity_supplied')->nullable(false);
            $table->decimal('unit_price', 10, 2)->nullable(false);
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('warehouse_id')->references('id')->on('warehouses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('supplies');
    }
};
