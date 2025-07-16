<!-- Fichier : resources/js/Pages/Order/Index.vue -->
<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AppLogo from '@/components/AppLogo.vue';
import ProductCard from '@/components/order/ProductCard.vue';
import Cart from '@/components/order/Cart.vue';
import { useCart } from '@/composables/useCart';

// On reçoit les produits groupés depuis le contrôleur
const props = defineProps<{
    products: Record<string, Array<{
        id: number;
        name: string;
        price: number; // On s'assure que c'est bien number ici aussi
        is_available: boolean;
        image_url: string | null;
    }>>;
}>();

// On récupère le nombre d'articles pour notre condition v-if
const { totalItems } = useCart();

</script>

<template>
    <Head title="Notre Carte" />
    <div class="flex h-screen bg-floral-white text-cafe-noir">

        <!-- Colonne de gauche : Panier (pour l'instant vide) -->
        <aside class="w-1/3 flex-shrink-0 bg-white/50 p-6 shadow-lg">
            <div class="flex justify-center mb-6">
                <AppLogo size="text-3xl" />
            </div>
            <h2 class="text-2xl font-bold mb-4">Votre Commande</h2>
            <div class="flex-1">
                <Cart v-if="totalItems > 0" />
                <p v-else class="text-muted-foreground mt-4">Votre panier est vide.</p>
            </div>
        </aside>

        <!-- Colonne de droite : Liste des produits -->
        <main class="flex-1 overflow-y-auto p-8">
            <h1 class="font-display text-5xl text-center mb-10">La Carte</h1>

            <!-- On boucle sur chaque catégorie -->
            <div v-for="(productsInCategory, category) in products" :key="category" class="mb-12">
                <h2 class="font-display text-4xl text-sinopia mb-6">{{ category }}</h2>

                <!-- On affiche les produits de la catégorie dans une grille -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Le composant ProductCard viendra ici -->
                    <ProductCard
                        v-for="product in productsInCategory"
                        :key="product.id"
                        :product="product"
                    />
                </div>
            </div>
        </main>

    </div>
</template>
