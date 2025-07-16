<!-- Fichier : resources/js/components/AppLogo.vue -->
<script setup lang="ts">
// On définit les props que le composant peut accepter.
// 'size' est optionnelle, et si elle n'est pas fournie,
// sa valeur par défaut sera 'text-5xl'.
withDefaults(defineProps<{
    size?: string;
}>(), {
    size: 'text-5xl',
});
</script>

<template>
    <!-- On utilise la prop 'size' dans la liste des classes -->
    <h1 class="font-display text-cafe-noir" :class="size">
        Wendy's Diner
    </h1>
</template>
