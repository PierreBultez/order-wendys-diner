<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function prepare(Request $request)
    {
        // 1. Valider les données reçues du panier
        $validatedCart = $request->validate([
            'cart' => 'required|array|min:1',
            'cart.*.product.id' => 'required|integer|exists:products,id',
            'cart.*.quantity' => 'required|integer|min:1',
        ]);

        // 2. Récupérer les IDs des produits demandés
        $productIds = collect($validatedCart['cart'])->pluck('product.id');

        // 3. Récupérer les produits correspondants depuis la BDD pour avoir des données fiables
        $productsFromDB = Product::whereIn('id', $productIds)->get()->keyBy('id');

        // 4. Calculer le total côté serveur (SÉCURITÉ)
        $totalAmount = 0;
        foreach ($validatedCart['cart'] as $item) {
            $product = $productsFromDB->get($item['product']['id']);
            if (!$product || !$product->is_available) {
                // Si un produit n'existe pas ou n'est plus dispo, on rejette la commande
                throw ValidationException::withMessages(['cart' => 'Un produit de votre panier n\'est plus disponible.']);
            }
            $totalAmount += $product->price * $item['quantity'];
        }

        // 5. On appelle l'API Revolut pour créer la commande de paiement
        $revolutApiUrl = config('services.revolut.base_uri');
        $revolutSecretKey = config('services.revolut.secret_key');

        try {
            $response = Http::withToken($revolutSecretKey)->post("{$revolutApiUrl}/orders", [
                'amount' => $totalAmount * 100, // Revolut attend le montant en centimes
                'currency' => 'EUR', // Ou une autre devise
            ]);

            // Si l'appel échoue, on lève une exception
            $response->throw();

            // On récupère le vrai "public_id" (token) de la réponse
            $revolutPublicId = $response->json('public_id');

        } catch (\Exception $e) {
            // En cas d'erreur avec l'API Revolut, on log l'erreur et on informe le client
            Log::error('Erreur API Revolut: ' . $e->getMessage());
            throw ValidationException::withMessages(['cart' => 'Impossible de contacter le service de paiement. Veuillez réessayer.']);
        }

        // 6. On retourne le vrai token au frontend
        return response()->json([
            'revolut_public_id' => $revolutPublicId,
        ]);
    }

    // NOUVELLE MÉTHODE POUR AFFICHER LA PAGE DE PAIEMENT
    public function showCheckout(string $revolut_order_id): Response
    {
        return Inertia::render('checkout/Show', [
            'revolut_public_id' => $revolut_order_id,
        ]);
    }
}
