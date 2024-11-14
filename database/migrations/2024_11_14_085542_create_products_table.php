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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name', 100)->nullable(false);
            $table->text('product_description')->nullable(false);
            $table->decimal('reference_price', 10, 2)->nullable(false);
            $table->int('restock_threshold')->nullable(false);
            $table->int('alert_treshold')->nullable(false);
            $table->unsignedBigInteger('category_id')->nullable(false);
            $table->timestamps();

            $table->foreign('category_id')->references('id')->on('category')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product');
    }
};
