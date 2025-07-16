<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        // On récupère la collection de modèles Eloquent
        $products = Product::all();

        // On ajoute un attribut "virtuel" à chaque modèle de la collection
        $products->each(function ($product) {
            $product->image_url = $product->image ? asset('storage/' . $product->image) : null;
        });

        // On passe la collection enrichie à Inertia
        return Inertia::render('admin/products/Index', [
            'products' => $products,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('admin/products/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation des données
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'is_available' => 'required|boolean',
            'image' => 'nullable|image|max:4096', // On valide l'image : optionnelle, doit être une image, max 1Mo
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // 2. Création du produit dans la base de données
        Product::create($validated);

        // 3. Redirection vers la page de liste avec un message de succès
        return redirect()->route('dashboard.products.index')->with('success', 'Produit créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        // On transforme l'objet pour qu'il contienne l'URL de l'image
        $product->image_url = $product->image ? asset('storage/' . $product->image) : null;

        return Inertia::render('admin/products/Edit', [ // chemin en minuscules
            'product' => $product,
        ]);
    }

    public function update(Request $request, Product $product)
    {
        // On valide toutes les données SAUF l'image pour le moment
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string',
            'is_available' => 'required|boolean',
        ]);

        // On valide et on traite l'image SÉPARÉMENT
        if ($request->hasFile('image')) {
            // On valide spécifiquement le fichier image
            $request->validate([
                'image' => 'required|image|max:4096',
            ]);

            // On supprime l'ancienne image si elle existe
            if ($product->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($product->image);
            }

            // On stocke la nouvelle et on ajoute le chemin au tableau des données à sauvegarder
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        // On met à jour le produit. Si aucune nouvelle image n'a été uploadée,
        // la clé 'image' n'existe pas dans $validated, et le champ en base
        // de données ne sera pas touché.
        $product->update($validated);

        return redirect()->route('dashboard.products.index')->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    // On type-hinte le modèle Product directement, Laravel le trouvera pour nous
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();

        return redirect()->route('dashboard.products.index')->with('success', 'Produit supprimé avec succès.');
    }

    // NOUVELLE MÉTHODE
    public function bulkDestroy(Request $request)
    {
        // 1. On valide que les données reçues sont bien un tableau d'IDs
        $validated = $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:products,id',
        ]);

        // 2. On supprime tous les produits dont l'ID est dans le tableau validé
        Product::whereIn('id', $validated['ids'])->delete();

        // 3. On redirige avec un message de succès
        return redirect()->route('dashboard.products.index')->with('success', 'Les produits sélectionnés ont été supprimés.');
    }

    public function bulkUpdate(Request $request)
    {
        // 1. On valide les données qui sont réellement envoyées
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:products,id',
            'price' => 'sometimes|required|numeric|min:0', // 'sometimes' : valider seulement si présent
            'category' => ['sometimes', 'required', Rule::in(['Menus', 'Burgers', 'Snacks', 'Desserts', 'Enfants', 'Boissons', 'Frites'])],
            'is_available' => 'sometimes|required|boolean',
        ]);

        // 2. On récupère toutes les données validées, SAUF le tableau des IDs
        $dataToUpdate = $request->except('ids');

        // 3. On exécute la mise à jour seulement s'il y a des données à mettre à jour
        if (!empty($dataToUpdate)) {
            Product::whereIn('id', $request->input('ids'))->update($dataToUpdate);
        }

        // 4. On redirige
        return redirect()->route('dashboard.products.index')->with('success', 'La sélection a été mise à jour.');
    }
}
