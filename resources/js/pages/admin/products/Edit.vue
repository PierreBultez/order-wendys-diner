<!-- Fichier : resources/js/Pages/Admin/Products/Create.vue -->
<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

// On importe les composants UI nécessaires
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Textarea } from '@/components/ui/textarea';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import InputError from '@/components/InputError.vue';

// 1. MODIFIER LES PROPS
// On reçoit maintenant un objet produit complet
const props = defineProps<{
    product: {
        id: number;
        name: string;
        description: string;
        price: number;
        is_available: boolean;
        category: string;
        image: string | null; // L'image peut être nulle
        image_url: string | null;
    };
}>();

// 2. MODIFIER L'INITIALISATION DU FORMULAIRE
// On pré-remplit le formulaire avec les données du produit reçu en prop.
// On ajoute aussi un champ pour la nouvelle image, initialement vide.
const form = useForm({
    _method: 'PUT', // Astuce pour dire à Laravel qu'on fait une requête PUT
    name: props.product.name,
    description: props.product.description,
    price: props.product.price,
    category: props.product.category,
    is_available: props.product.is_available,
    image: null as File | null, // Pour le nouveau fichier image
});

// 3. MODIFIER LA FONCTION SUBMIT
const submit = () => {
    // On envoie le formulaire en POST (Inertia gère le _method: 'PUT')
    // à la route 'update' en passant l'ID du produit.
    form.post(route('dashboard.products.update', props.product.id));
};

</script>

<template>
    <Head :title="'Modifier : ' + product.name" />

    <AppLayout>
        <div class="p-6 w-full">
            <h1 class="font-display text-3xl text-cafe-noir mb-6">Modifier le produit</h1>

            <!-- Le formulaire pointe vers notre fonction 'submit' -->
            <form @submit.prevent="submit" class="max-w-2xl mx-auto bg-floral-white p-8 rounded-lg shadow-md">
                <div class="space-y-6">

                    <!-- Champ Nom -->
                    <div>
                        <Label for="name">Nom du produit</Label>
                        <Input id="name" v-model="form.name" type="text" class="mt-1 block w-full" required />
                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>

                    <!-- Champ Description -->
                    <div>
                        <Label for="description">Description</Label>
                        <Textarea id="description" v-model="form.description" class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="form.errors.description" />
                    </div>

                    <!-- Champ Prix -->
                    <div>
                        <Label for="price">Prix</Label>
                        <Input id="price" v-model="form.price" type="number" step="0.01" class="mt-1 block w-full" required />
                        <InputError class="mt-2" :message="form.errors.price" />
                    </div>

                    <!-- Champ Catégorie -->
                    <div>
                        <Label for="category">Catégorie</Label>
                        <Select v-model="form.category">
                            <SelectTrigger>
                                <SelectValue placeholder="Sélectionnez une catégorie" />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem value="Menus">Menus</SelectItem>
                                <SelectItem value="Burgers">Burgers seuls</SelectItem>
                                <SelectItem value="Snacks">Snacks</SelectItem>
                                <SelectItem value="Desserts">Desserts</SelectItem>
                                <SelectItem value="Enfants">Enfants</SelectItem>
                                <SelectItem value="Boissons">Boissons</SelectItem>
                                <SelectItem value="Frites">Frites</SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError class="mt-2" :message="form.errors.category" />
                    </div>

                    <!-- 4. AJOUTER LE CHAMP POUR L'IMAGE -->
                    <div>
                        <Label for="image">Image du produit</Label>
                        <!-- On affiche l'image actuelle si elle existe -->
                        <div v-if="props.product.image_url" class="my-2">
                            <img :src="props.product.image_url" alt="Image actuelle" class="w-32 h-32 object-cover rounded-md">
                        </div>
                        <Input id="image" type="file" @input="form.image = $event.target.files[0]" class="mt-1 block w-full" />
                        <InputError class="mt-2" :message="form.errors.image" />
                    </div>

                    <!-- Case à cocher Disponibilité -->
                    <div class="flex items-center gap-2">
                        <Checkbox id="is_available" v-model="form.is_available" :checked="form.is_available" />
                        <Label for="is_available">Produit disponible</Label>
                        <InputError class="mt-2" :message="form.errors.is_available" />
                    </div>

                    <!-- Bouton de soumission -->
                    <div class="flex items-center justify-end">
                        <Button :disabled="form.processing">
                            Enregistrer le produit
                        </Button>
                    </div>

                </div>
            </form>
        </div>
    </AppLayout>
</template>
