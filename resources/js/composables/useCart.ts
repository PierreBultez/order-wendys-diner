// Fichier : resources/js/composables/useCart.ts
import { ref, computed } from 'vue';

// On définit les types pour plus de clarté et de sécurité
export interface Product {
    id: number;
    name: string;
    price: number;
    image_url: string | null;
}

export interface CartItem {
    product: Product;
    quantity: number;
}

// On crée une variable réactive 'cart' qui sera partagée
// C'est un singleton : il n'y aura qu'un seul panier dans toute l'application.
const cart = ref<CartItem[]>([]);

// C'est la fonction principale que nos composants utiliserons.
export function useCart() {

    // Fonction pour ajouter un produit au panier
    const addToCart = (product: Product) => {
        const existingItem = cart.value.find(item => item.product.id === product.id);

        if (existingItem) {
            // Si le produit est déjà dans le panier, on augmente juste la quantité
            existingItem.quantity++;
        } else {
            // Sinon, on l'ajoute avec une quantité de 1
            cart.value.push({ product, quantity: 1 });
        }
    };

    // Incrémenter la quantité d'un article
    const incrementQuantity = (productId: number) => {
        const item = cart.value.find(i => i.product.id === productId);
        if (item) {
            item.quantity++;
        }
    };

    // Décrémenter la quantité d'un article
    const decrementQuantity = (productId: number) => {
        const item = cart.value.find(i => i.product.id === productId);
        if (item && item.quantity > 1) {
            item.quantity--;
        } else if (item && item.quantity === 1) {
            // Si la quantité est 1, on retire l'article du panier
            removeFromCart(productId);
        }
    };

    // Retirer complètement un article du panier
    const removeFromCart = (productId: number) => {
        cart.value = cart.value.filter(i => i.product.id !== productId);
    };

    // Une "propriété calculée" qui nous donne le nombre total d'articles
    const totalItems = computed(() => {
        return cart.value.reduce((total, item) => total + item.quantity, 0);
    });

    // Une "propriété calculée" qui nous donne le prix total
    const totalPrice = computed(() => {
        return cart.value.reduce((total, item) => total + (item.product.price * item.quantity), 0);
    });

    // La fonction 'useCart' retourne toutes les données et fonctions
    // que les autres composants pourront utiliser.
    return {
        cart,
        addToCart,
        removeFromCart,
        incrementQuantity,
        decrementQuantity,
        totalItems,
        totalPrice,
    };
}
