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
            $table->id();
            $table->integer('invoice_number');
            $table->date('invoice_date');
            $table->enum('invoice_status', ['PAID', 'UNPAID', 'PARTIALLY_PAID']); 

            $table->unsignedBigInteger('order_id'); 
            $table->unsignedBigInteger('supply_id');
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('supply_id')->references('id')->on('supplies')->onDelete('cascade');
        });

        // Ajouter la contrainte CHECK, pour vérifier que order_id et supply_id ne sont pas tous les deux renseignés
        DB::statement("ALTER TABLE invoices 
                       ADD CONSTRAINT check_order_or_supply 
                       CHECK (
                           (order_id IS NOT NULL AND supply_id IS NULL) OR 
                           (order_id IS NULL AND supply_id IS NOT NULL)
                       )");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');

        // Supprimer la contrainte CHECK
        DB::statement("ALTER TABLE invoices DROP CONSTRAINT check_order_or_supply");
    }
};
