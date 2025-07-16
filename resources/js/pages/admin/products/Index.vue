<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

// Composants UI ----------------------------------------------------
import BulkEditDialog from '@/components/BulkEditDialog.vue';
import { Button } from '@/components/ui/button';
import { Badge } from '@/components/ui/badge';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/components/ui/table';
import {
    AlertDialog,
    AlertDialogAction,
    AlertDialogCancel,
    AlertDialogContent,
    AlertDialogDescription,
    AlertDialogFooter,
    AlertDialogHeader,
    AlertDialogTitle,
    AlertDialogTrigger,
} from '@/components/ui/alert-dialog';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/components/ui/dropdown-menu';

import { PlusCircle, Pencil, Trash2 } from 'lucide-vue-next';

/* -----------------------------------------------------------------
   Props
------------------------------------------------------------------*/
const props = defineProps<{
    products: Array<{
        id: number;
        name: string;
        price: number;
        is_available: boolean;
        category: string;
        image_url: string | null;
    }>;
}>();

/* -----------------------------------------------------------------
   Helpers
------------------------------------------------------------------*/
const formatPrice = (price: number) =>
    new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(price);

/* -----------------------------------------------------------------
   Sélection de lignes
------------------------------------------------------------------*/
const selectedProducts = ref<number[]>([]);

// Valeur que retourne le composant Checkbox shadcn
// true | false | 'indeterminate'
// On la réutilise pour le type du paramètre des callbacks
export type CheckboxValue = true | false | 'indeterminate';

// --- Case principale (tri‑state) ----------------------------------
const mainCheckbox = computed<CheckboxValue>({
    get: () => {
        if (selectedProducts.value.length === 0) return false;
        if (selectedProducts.value.length === props.products.length) return true;
        return 'indeterminate';
    },
    set: (value) => {
        if (value === true) {
            selectedProducts.value = props.products.map((p) => p.id);
        } else {
            // false ou "indeterminate" ⇒ on vide
            selectedProducts.value = [];
        }
    },
});

// --- Toggle d’une ligne -------------------------------------------
const toggleRow = (productId: number, value: CheckboxValue) => {
    // Pour une ligne, la valeur ne devrait jamais être 'indeterminate',
    // mais on la traite comme false par sécurité.
    const checked = value === true;

    if (checked) {
        if (!selectedProducts.value.includes(productId)) {
            selectedProducts.value.push(productId);
        }
    } else {
        selectedProducts.value = selectedProducts.value.filter((id) => id !== productId);
    }
};

/* -----------------------------------------------------------------
   Suppression d’un produit
------------------------------------------------------------------*/
const deleteForm = useForm({});
const productToDelete = ref<number | null>(null);

const deleteProduct = () => {
    if (!productToDelete.value) return;
    deleteForm.delete(route('dashboard.products.destroy', productToDelete.value), {
        preserveScroll: true,
        onFinish: () => (productToDelete.value = null),
    });
};

/* -----------------------------------------------------------------
   Suppression en masse
------------------------------------------------------------------*/
const bulkDeleteForm = useForm({
    ids: [] as number[], // On initialise un formulaire avec un tableau d'IDs
});

const confirmBulkDeletion = () => {
    // Cette fonction sera appelée par le dialogue de confirmation
    bulkDeleteForm.transform(() => ({
        ids: selectedProducts.value, // On s'assure que les données du formulaire sont bien à jour
    })).delete(route('dashboard.products.bulkDestroy'), {
        preserveScroll: true,
        onSuccess: () => {
            selectedProducts.value = []; // On vide la sélection après succès
        }
    });
};

/* -----------------------------------------------------------------
   Édition en masse
------------------------------------------------------------------*/
const editingInBulk = ref(false);

const openBulkEditDialog = () => {
    editingInBulk.value = true;
};

</script>

<template>
    <Head title="Gestion des Produits" />

    <AppLayout>
        <div class="p-6 w-full">
            <!-- En‑tête ---------------------------------------------------- -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="font-display text-3xl text-cafe-noir">Gestion des Produits</h1>
                    <p class="text-cafe-noir/80">Liste de tous les produits du restaurant.</p>
                </div>

                <div class="flex items-center gap-2">
                    <!-- Dropdown d’actions groupées -->
                    <DropdownMenu v-if="selectedProducts.length">
                        <DropdownMenuTrigger as-child>
                            <Button variant="outline">Actions groupées ({{ selectedProducts.length }})</Button>
                        </DropdownMenuTrigger>
                        <DropdownMenuContent align="end">
                            <!-- On enveloppe l'item du menu dans un dialogue d'alerte -->
                            <AlertDialog>
                                <AlertDialogTrigger as-child>
                                    <DropdownMenuItem
                                        class="text-destructive focus:bg-destructive/10 focus:text-destructive"
                                        @select.prevent
                                    >
                                        Supprimer la sélection
                                    </DropdownMenuItem>
                                </AlertDialogTrigger>
                                <AlertDialogContent>
                                    <AlertDialogHeader>
                                        <AlertDialogTitle>Êtes-vous absolument sûr ?</AlertDialogTitle>
                                        <AlertDialogDescription>
                                            Cette action est irréversible. Les {{ selectedProducts.length }} produits sélectionnés seront supprimés.
                                        </AlertDialogDescription>
                                    </AlertDialogHeader>
                                    <AlertDialogFooter>
                                        <AlertDialogCancel>Annuler</AlertDialogCancel>
                                        <AlertDialogAction @click="confirmBulkDeletion">
                                            Oui, supprimer
                                        </AlertDialogAction>
                                    </AlertDialogFooter>
                                </AlertDialogContent>
                            </AlertDialog>
                            <DropdownMenuItem @click="openBulkEditDialog">
                                Modifier la sélection
                            </DropdownMenuItem>
                        </DropdownMenuContent>
                    </DropdownMenu>

                    <Button as-child>
                        <Link :href="route('dashboard.products.create')">
                            <PlusCircle class="h-4 w-4 mr-2" />
                            Ajouter un produit
                        </Link>
                    </Button>
                </div>
            </div>

            <!-- Tableau ---------------------------------------------------- -->
            <div class="bg-floral-white shadow-md rounded-lg">
                <Table>
                    <TableHeader>
                        <TableRow>
                            <TableHead class="w-[40px]">
                                <Checkbox v-model="mainCheckbox" />
                            </TableHead>
                            <TableHead>ID</TableHead>
                            <TableHead class="w-[80px]">Image</TableHead>
                            <TableHead>Nom</TableHead>
                            <TableHead>Catégorie</TableHead>
                            <TableHead>Prix</TableHead>
                            <TableHead>Disponibilité</TableHead>

                            <TableHead class="text-right">Actions</TableHead>
                        </TableRow>
                    </TableHeader>

                    <TableBody>
                        <TableRow v-if="props.products.length === 0">
                            <TableCell :colspan="8" class="p-4 text-center text-muted-foreground">
                                Aucun produit trouvé.
                            </TableCell>
                        </TableRow>

                        <TableRow v-for="product in props.products" :key="product.id">
                            <TableCell>
                                <Checkbox
                                    :model-value="selectedProducts.includes(product.id)"
                                    @update:model-value="(value: CheckboxValue) => toggleRow(product.id, value)"
                                />
                            </TableCell>

                            <TableCell>{{ product.id }}</TableCell>
                            <TableCell>
                                <img v-if="product.image_url" :src="product.image_url" :alt="product.name" class="w-16 h-16 object-cover rounded-md">
                                <div v-else class="w-16 h-16 bg-gray-200 rounded-md flex items-center justify-center text-xs text-gray-500">
                                    Pas d'image
                                </div>
                            </TableCell>
                            <TableCell class="font-medium">{{ product.name }}</TableCell>
                            <TableCell>{{ product.category }}</TableCell>
                            <TableCell>{{ formatPrice(product.price) }}</TableCell>
                            <TableCell>
                                <Badge
                                    :variant="product.is_available ? 'default' : 'destructive'"
                                    :class="{
                                        'bg-yellow-green/20 text-cafe-noir': product.is_available,
                                        'bg-sinopia/20 text-sinopia': !product.is_available,
                                    }"
                                >
                                    {{ product.is_available ? 'Disponible' : 'Indisponible' }}
                                </Badge>
                            </TableCell>

                            <TableCell class="text-right">
                                <div class="flex justify-end gap-2">
                                    <!-- Modifier -->
                                    <Button variant="outline" size="icon" as-child>
                                        <Link :href="route('dashboard.products.edit', product.id)">
                                            <Pencil class="h-4 w-4" />
                                        </Link>
                                    </Button>

                                    <!-- Supprimer -->
                                    <AlertDialog>
                                        <AlertDialogTrigger as-child>
                                            <Button
                                                variant="destructive"
                                                size="icon"
                                                @click="productToDelete = product.id"
                                            >
                                                <Trash2 class="h-4 w-4" />
                                            </Button>
                                        </AlertDialogTrigger>
                                        <AlertDialogContent>
                                            <AlertDialogHeader>
                                                <AlertDialogTitle>Êtes-vous absolument sûr ?</AlertDialogTitle>
                                                <AlertDialogDescription>
                                                    Cette action est irréversible. Le produit sera supprimé définitivement.
                                                </AlertDialogDescription>
                                            </AlertDialogHeader>
                                            <AlertDialogFooter>
                                                <AlertDialogCancel>Annuler</AlertDialogCancel>
                                                <AlertDialogAction @click="deleteProduct">Supprimer</AlertDialogAction>
                                            </AlertDialogFooter>
                                        </AlertDialogContent>
                                    </AlertDialog>
                                </div>
                            </TableCell>
                        </TableRow>
                    </TableBody>
                </Table>
            </div>
        </div>
        <!-- NOTRE NOUVELLE MODALE D'ÉDITION -->
        <BulkEditDialog
            :open="editingInBulk"
            :product-ids="selectedProducts"
            @update:open="editingInBulk = $event"
        />
    </AppLayout>
</template>
