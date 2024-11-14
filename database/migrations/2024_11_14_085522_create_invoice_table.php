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
        Schema::create('invoices', function (Blueprint $table) {
            $table->integer('invoice_number')->nullable(false);
            $table->date('invoice_date')->nullable(false);
            $table->enum('invoice_status', ['paid', 'unpaid', 'partially_paid'])->nullable(false); 
            $table->unsignedBigInteger('order_id')->nullable(false); 
            $table->unsignedBigInteger('supply_id')->nullable(false);
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->foreign('supply_id')->references('id')->on('supply')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
