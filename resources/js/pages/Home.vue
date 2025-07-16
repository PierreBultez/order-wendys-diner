<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLogo from '@/components/AppLogo.vue';
</script>
<template>
    <Head title="Bienvenue chez Wendy's Diner" />
    <div class="flex flex-col items-center justify-center min-h-screen bg-floral-white p-6">
        <AppLogo />
        <h2 class="text-2xl text-cafe-noir/80 mt-4 mb-8">Voulez-vous vous connecter ?</h2>
        <div class="flex gap-4">
            <Link :href="route('login')" as="button" class="bg-sinopia text-floral-white font-bold py-3 px-8 rounded-lg shadow-md hover:bg-sinopia/90 transition-colors">
                OUI
            </Link>
            <Link :href="route('carte.index')" as="button" class="bg-gray-300 text-cafe-noir font-bold py-3 px-8 rounded-lg shadow-md hover:bg-gray-400 transition-colors">
                NON
            </Link>
        </div>
    </div>
</template>
