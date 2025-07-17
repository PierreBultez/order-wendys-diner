<?php

use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\OrderController;
use App\Models\Product;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// La page racine affiche la question "Voulez-vous vous connecter ?"
Route::get('/', function () {
    return Inertia::render('Home');
})->name('home');

// La page de la carte, accessible à tous
Route::get('/carte', function () {
    $products = \App\Models\Product::query()
        ->where('is_available', true)
        ->get() // 1. On récupère d'abord les produits
        ->map(function ($product) { // 2. On utilise map() pour transformer chaque produit
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image_url' => $product->image ? asset('storage/' . $product->image) : null,
                'category' => $product->category, // On garde la catégorie pour le groupement
            ];
        })
        ->groupBy('category'); // 3. On groupe à la toute fin

    return Inertia::render('order/Index', [
        'products' => $products
    ]);
})->name('carte.index');

// NOUVELLE ROUTE POUR PRÉPARER LA COMMANDE
Route::post('/checkout/prepare', [OrderController::class, 'prepare'])->name('checkout.prepare');

// NOUVELLE ROUTE POUR AFFICHER LA PAGE DE PAIEMENT
Route::get('/checkout/{revolut_order_id}', [OrderController::class, 'showCheckout'])->name('checkout.show');

// Route du dashboard, accessible uniquement aux utilisateurs connectés et vérifiés
Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified', 'admin'])->name('dashboard');


// ----- NOTRE BLOC DE ROUTES POUR L'ADMINISTRATION -----
// Ce groupe entier est protégé par les mêmes middlewares que le dashboard.
Route::middleware(['auth', 'verified', 'admin'])->group(function () {

    // On préfixe les URLs par '/dashboard' et les noms par 'dashboard.'
    Route::prefix('dashboard')->name('dashboard.')->group(function() {

        // On déclare nos routes pour les produits ici.
        // Laravel va créer automatiquement les routes comme :
        // - URL: /dashboard/products, Nom: dashboard.products.index
        // - URL: /dashboard/products/create, Nom: dashboard.products.create
        // etc.
        Route::put('products/bulk-update', [ProductController::class, 'bulkUpdate'])->name('products.bulkUpdate');
        Route::delete('products', [ProductController::class, 'bulkDestroy'])->name('products.bulkDestroy');
        Route::resource('products', ProductController::class);
    });
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
