<script setup lang="ts">
import { Method } from '@inertiajs/core';
import { Link } from '@inertiajs/vue3';

interface Props {
    href: string;
    tabindex?: number;
    method?: Method;
    as?: string;
}

defineProps<Props>();
</script>

<template>
    <Link
        :href="href"
        :tabindex="tabindex"
        :method="method"
        :as="as"
        class="text-gamboge hover:text-gamboge/80 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sinopia"
    >
        <slot />
    </Link>
</template>
