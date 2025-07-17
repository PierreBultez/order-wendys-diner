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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            // Lien vers la commande
            $table->foreignId('order_id')->constrained()->onDelete('cascade');

            // Lien vers le produit
            $table->foreignId('product_id')->nullable()->constrained()->onDelete('set null');

            $table->integer('quantity');
            $table->decimal('price', 10, 2); // Prix du produit AU MOMENT de la commande
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
