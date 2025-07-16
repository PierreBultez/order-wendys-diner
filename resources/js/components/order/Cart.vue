<!-- Fichier : resources/js/components/order/Cart.vue -->
<script setup lang="ts">
import { useCart } from '@/composables/useCart';
import { Button } from '@/components/ui/button';
import { Plus, Minus, Trash2 } from 'lucide-vue-next';

const { cart, totalPrice, incrementQuantity, decrementQuantity, removeFromCart } = useCart();

const formatPrice = (price: number) =>
    new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(price);
</script>

<template>
    <!-- Le conteneur principal du panier est maintenant un flex-col -->
    <div class="flex flex-col h-full">

        <!-- 1. La liste des articles est dans un conteneur qui grandit et qui scrolle -->
        <div class="overflow-y-auto space-y-4">
            <div v-for="item in cart" :key="item.product.id" class="flex items-center gap-4">
                <img v-if="item.product.image_url" :src="item.product.image_url" class="h-16 w-16 rounded-md object-cover">
                <div v-else class="h-16 w-16 rounded-md bg-gray-200"></div>

                <div class="flex-1">
                    <p class="font-bold">{{ item.product.name }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <Button @click="decrementQuantity(item.product.id)" variant="outline" size="icon" class="h-6 w-6">
                            <Minus class="h-3 w-3" />
                        </Button>
                        <span>{{ item.quantity }}</span>
                        <Button @click="incrementQuantity(item.product.id)" variant="outline" size="icon" class="h-6 w-6">
                            <Plus class="h-3 w-3" />
                        </Button>
                    </div>
                </div>

                <div class="text-right">
                    <p class="font-semibold">{{ formatPrice(item.product.price * item.quantity) }}</p>
                    <Button @click="removeFromCart(item.product.id)" variant="ghost" size="icon" class="h-8 w-8 text-muted-foreground hover:text-destructive">
                        <Trash2 class="h-4 w-4" />
                    </Button>
                </div>
            </div>
        </div>

        <!-- 2. Le pied du panier (total + bouton) est séparé et fixe en bas DE CETTE SECTION -->
        <div class="flex-shrink-0 mt-6 border-t border-cafe-noir/10 pt-6">
            <div class="flex justify-between font-bold text-lg">
                <span>Total</span>
                <span>{{ formatPrice(totalPrice) }}</span>
            </div>
            <Button class="w-full mt-4 text-lg py-6">
                Valider la commande
            </Button>
        </div>
    </div>
</template>
