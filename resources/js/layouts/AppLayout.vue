<script setup lang="ts">
import AppLayout from '@/layouts/app/AppSidebarLayout.vue';
import FlashMessage from '@/components/FlashMessage.vue';
import type { BreadcrumbItemType } from '@/types';

interface Props {
    breadcrumbs?: BreadcrumbItemType[];
}

withDefaults(defineProps<Props>(), {
    breadcrumbs: () => [],
});
</script>

<template>
    <!-- On crée un conteneur principal -->
    <div>
        <!-- La notification est ici, au niveau le plus haut,
             elle "flottera" donc au-dessus de tout le reste -->
        <FlashMessage />

        <!-- Le layout de la page est placé juste après -->
        <AppLayout :breadcrumbs="breadcrumbs">
            <slot />
        </AppLayout>
    </div>
</template>
