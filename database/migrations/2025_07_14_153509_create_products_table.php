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
            $table->id(); // Colonne d'identifiant unique (ex: 1, 2, 3...)
            $table->string('name'); // Le nom du produit (ex: "Classic Cheeseburger")
            $table->text('description')->nullable(); // Une description (peut être vide)
            $table->decimal('price', 8, 2); // Le prix, avec 8 chiffres au total et 2 après la virgule
            $table->string('image')->nullable(); // Le chemin vers une image (peut être vide)
            $table->boolean('is_available')->default(true); // Est-ce que le produit est disponible à la vente ?
            $table->string('category'); // Catégorie (ex: "Burgers", "Boissons", "Desserts")
            $table->timestamps(); // Ajoute les colonnes `created_at` et `updated_at`
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
