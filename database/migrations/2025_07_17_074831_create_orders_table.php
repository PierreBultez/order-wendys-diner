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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // On peut lier la commande à un utilisateur (s'il était connecté)
            // La clé étrangère peut être nulle pour les clients invités.
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');

            $table->string('customer_email'); // On stocke l'email du client
            $table->decimal('total_amount', 10, 2);

            // Le statut de la commande : 'pending', 'paid', 'preparing', 'ready', 'completed', 'cancelled'
            $table->string('status')->default('pending');

            // L'identifiant unique de la commande chez Revolut
            $table->string('revolut_order_id')->nullable()->unique();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
