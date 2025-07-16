<script setup lang="ts">
import AppLogo from '@/components/AppLogo.vue';
import { Card, CardContent, CardDescription, CardHeader, CardTitle } from '@/components/ui/card';
import { Link } from '@inertiajs/vue3';

defineProps<{
    title?: string;
    description?: string;
}>();
</script>

<template>
    <!-- On change la couleur de fond générale de la page -->
    <div class="flex min-h-svh flex-col items-center justify-center gap-6 bg-floral-white p-6 md:p-10">
        <div class="flex w-full max-w-md flex-col gap-6"> <!-- max-w-md pour que la carte ne soit pas trop large -->

            <!-- Le logo : pour l'instant, on le cache, on mettra le vrai plus tard -->
            <Link :href="route('home')" class="self-center mb-2">
                <AppLogo />
            </Link>

            <div class="flex flex-col gap-6">
                <!-- On va styler la carte -->
                <Card class="rounded-xl border-cafe-noir/20 bg-floral-white shadow-lg">
                    <CardHeader class="px-10 pt-8 pb-4 text-center"> <!-- un peu moins de padding en bas -->

                        <!-- Le titre principal de la carte -->
                        <CardTitle class="font-display text-4xl text-cafe-noir">
                            {{ title }}
                        </CardTitle>

                        <!-- La description sous le titre -->
                        <CardDescription class="text-cafe-noir/80">
                            {{ description }}
                        </CardDescription>

                    </CardHeader>
                    <CardContent class="px-10 pb-8 pt-4">
                        <slot />
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
