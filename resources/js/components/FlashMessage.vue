<!-- Fichier: resources/js/components/FlashMessage.vue -->
<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'; // 1. Importer onMounted
import { usePage } from '@inertiajs/vue3';
import { CheckCircle, XCircle } from 'lucide-vue-next';

// On utilise usePage() pour accéder aux props partagées, y compris les flash messages
const page = usePage();

// On crée une référence locale pour pouvoir masquer le message
const show = ref(false);

// On utilise une propriété 'computed' pour réagir aux changements du flash message
const message = computed(() => (page.props.flash as any)?.success);

// Fonction pour démarrer le timer, qu'on pourra réutiliser
const startTimer = () => {
    if (message.value) {
        show.value = true;
        setTimeout(() => {
            show.value = false;
        }, 3000);
    }
};

// 3. Utiliser onMounted pour démarrer le timer au chargement initial
onMounted(() => {
    startTimer();
});

// 4. Le watch reste pour les mises à jour futures sans rechargement de page
watch(message, () => {
    startTimer();
});
</script>

<template>
    <!-- On affiche le composant seulement si 'show' est vrai ET qu'il y a un message -->
    <div v-if="show && message" class="fixed top-4 right-4 z-50 rounded-md border-l-4 border-green-600 bg-floral-white p-4 shadow-lg">
        <div class="flex items-start">
            <div class="flex-shrink-0">
                <CheckCircle class="h-6 w-6 text-green-600" />
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium text-cafe-noir">
                    Succès !
                </p>
                <p class="mt-1 text-sm text-cafe-noir/80">
                    {{ message }}
                </p>
            </div>
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button @click="show = false" type="button" class="inline-flex rounded-md p-1.5 text-cafe-noir/50 hover:bg-cafe-noir/10 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-600">
                        <span class="sr-only">Fermer</span>
                        <XCircle class="h-5 w-5" />
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
