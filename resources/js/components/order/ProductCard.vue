<!-- Fichier : resources/js/components/order/ProductCard.vue -->
<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Plus } from 'lucide-vue-next';
import { useCart, type Product } from '@/composables/useCart';

// Ce composant reçoit un seul produit en prop
defineProps<{
    product: {
        id: number;
        name: string;
        price: number;
        image_url: string | null;
    }
}>();
// 2. On récupère la fonction addToCart depuis notre composable
const { addToCart } = useCart();

</script>

<template>
    <div class="group relative flex flex-col rounded-xl border border-cafe-noir/10 bg-white/70 shadow-sm transition-all hover:shadow-xl">
        <!-- Image du produit -->
        <div class="aspect-video overflow-hidden rounded-t-xl">
            <img
                v-if="product.image_url"
                :src="product.image_url"
                :alt="product.name"
                class="h-full w-full object-cover transition-transform group-hover:scale-105"
            >
            <div v-else class="h-full w-full bg-gray-200 flex items-center justify-center text-muted-foreground">
                Image
            </div>
        </div>

        <!-- Informations du produit -->
        <div class="flex flex-col p-4">
            <h3 class="text-lg font-bold text-cafe-noir">{{ product.name }}</h3>
            <p class="mt-2 text-xl font-semibold text-sinopia">{{ product.price.toFixed(2) }} €</p>
        </div>

        <!-- Bouton Ajouter -->
        <div class="mt-auto p-4 pt-0">
            <!-- 3. On connecte l'événement @click -->
            <Button class="w-full" @click="() => {
                addToCart(product);
                console.log(useCart().cart.value);
            }">
                <Plus class="mr-2 h-4 w-4" />
                Ajouter
            </Button>
        </div>
    </div>
</template>
