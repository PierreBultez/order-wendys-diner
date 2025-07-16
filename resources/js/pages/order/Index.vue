<!-- Fichier : resources/js/Pages/Order/Index.vue -->
<script setup lang="ts">
import { Head, Link, usePage } from '@inertiajs/vue3';
import AppLogo from '@/components/AppLogo.vue';
import ProductCard from '@/components/order/ProductCard.vue';
import Cart from '@/components/order/Cart.vue';
import { Button } from '@/components/ui/button';
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

// On récupère les informations de l'utilisateur connecté
const user = usePage().props.auth.user;

</script>

<template>
    <Head title="Notre Carte" />
    <div class="flex h-screen bg-floral-white text-cafe-noir">

        <!-- Colonne de gauche : Panier -->
        <aside class="w-1/3 flex-shrink-0 bg-white/50 p-6 shadow-lg flex flex-col h-full">

            <!-- Section du haut (titres) -->
            <div class="flex-shrink-0">
                <div class="flex justify-center mb-6">
                    <AppLogo size="text-3xl" />
                </div>
                <h2 class="text-2xl font-bold mb-4">Votre Commande</h2>
            </div>

            <!-- Section du milieu (contenu du panier) qui grandit -->
            <div class="flex-grow overflow-y-auto">
                <Cart v-if="totalItems > 0" />
                <p v-else class="text-muted-foreground mt-4">Votre panier est vide.</p>
            </div>

            <!-- Section du bas (déconnexion) qui est fixe -->
            <div v-if="user" class="flex-shrink-0 border-t border-cafe-noir/10 pt-4 mt-4">
                <p class="text-sm text-muted-foreground text-center mb-2">Connecté en tant que {{ user.name }}</p>
                <Button variant="destructive" class="w-full destructive" as-child>
                    <Link :href="route('logout')" method="post" as="button">
                        Se déconnecter
                    </Link>
                </Button>
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
