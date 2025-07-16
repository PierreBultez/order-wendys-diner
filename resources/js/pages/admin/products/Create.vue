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

// On utilise 'useForm' d'Inertia pour gérer l'état du formulaire.
// C'est un objet qui contient les données, les erreurs, et des méthodes utiles.
const form = useForm({
    name: '',
    description: '',
    price: 0.00,
    category: '',
    is_available: true,
    image: null as File | null,
});

// La fonction qui sera appelée à la soumission du formulaire
const submit = () => {
    // La route 'dashboard.products.store' est celle que Route::resource a créée pour nous
    form.post(route('dashboard.products.store'));
};
</script>

<template>
    <Head title="Ajouter un Produit" />

    <AppLayout>
        <div class="p-6 w-full">
            <h1 class="font-display text-3xl text-cafe-noir mb-6">Ajouter un nouveau produit</h1>

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

                    <div>
                        <Label for="image">Image du produit</Label>
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
