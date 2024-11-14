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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('warehouse_name', 50)->nullable(false);
            $table->string('warehouse_address')->nullable(false);
            $table->int('capacity')->nullable(false);
            $table->unsignedBigInteger('warehouse_manager_id')->nullable(false);
            $table->timestamps();

            $table->foreign('warehouse_manager_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
