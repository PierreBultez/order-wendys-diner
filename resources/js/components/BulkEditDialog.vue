<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import { Switch } from '@/components/ui/switch';
import InputError from '@/components/InputError.vue';

// On définit les props que ce composant reçoit.
// 'open' pour contrôler sa visibilité, et 'productIds' pour savoir quoi modifier.
const props = defineProps<{
    open: boolean;
    productIds: number[];
}>();

// On définit les événements que ce composant peut émettre vers son parent.
const emit = defineEmits(['update:open']);

// 1. On "aplatit" la structure du formulaire
const form = useForm({
    // Champs pour indiquer QUOI modifier
    apply_price: false,
    apply_category: false,
    apply_is_available: false,

    // Champs pour les NOUVELLES VALEURS
    price: 0.00,
    category: '',
    is_available: true,

    // Toujours les IDs des produits à modifier
    ids: props.productIds,
});

// 2. On modifie la fonction de soumission pour n'envoyer que les données nécessaires
const submit = () => {
    // On s'assure que les IDs sont à jour
    form.ids = props.productIds;

    // On utilise la méthode .transform() pour n'envoyer que ce qui est nécessaire.
    // C'est la méthode la plus propre.
    form.transform((data) => {
        const dataToSubmit: any = { ids: data.ids };
        if (data.apply_price) dataToSubmit.price = data.price;
        if (data.apply_category) dataToSubmit.category = data.category;
        if (data.apply_is_available) dataToSubmit.is_available = data.is_available;
        return dataToSubmit;
    }).put(route('dashboard.products.bulkUpdate'), {
        preserveScroll: true,
        onSuccess: () => {
            emit('update:open', false);
            form.reset();
        },
    });
};

</script>

<template>
    <Dialog :open="open" @update:open="(value) => emit('update:open', value)">
        <DialogContent class="sm:max-w-[425px]">
            <DialogHeader>
                <DialogTitle>Édition en masse</DialogTitle>
                <DialogDescription>
                    Modifier les champs pour les {{ productIds.length }} produits sélectionnés.
                    Cochez une case pour appliquer une modification.
                </DialogDescription>
            </DialogHeader>

            <!-- Notre formulaire d'édition -->
            <div class="grid gap-4 py-4">

                <!-- Champ Prix -->
                <div class="flex items-center gap-4">
                    <Checkbox id="apply_price" v-model="form.apply_price" />
                    <div class="grid grid-cols-3 items-center gap-2 flex-1">
                        <Label for="price" class="text-right">Prix</Label>
                        <Input id="price" v-model="form.price" type="number" step="0.1" class="col-span-2" :disabled="!form.apply_price" />
                    </div>
                </div>
                <InputError :message="form.errors.price" class="ml-10" />

                <!-- Champ Catégorie -->
                <div class="flex items-center gap-4">
                    <Checkbox id="apply_category" v-model="form.apply_category" />
                    <div class="grid grid-cols-3 items-center gap-2 flex-1">
                        <Label for="category" class="text-right">Catégorie</Label>
                        <Select v-model="form.category" :disabled="!form.apply_category">
                            <SelectTrigger class="col-span-2">
                                <SelectValue placeholder="Choisir..." />
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
                    </div>
                </div>
                <InputError :message="form.errors.category" class="ml-10" />

                <!-- Champ Disponibilité -->
                <div class="flex items-center gap-4">
                    <Checkbox id="apply_availability" v-model="form.apply_is_available" />
                    <div class="grid grid-cols-3 items-center gap-2 flex-1">
                        <Label for="availability_switch" class="text-right">Disponibilité</Label>
                        <div class="col-span-2 flex items-center gap-2">
                            <Switch class="data-[state=checked]:bg-yellow-green"
                                id="availability_switch"
                                v-model="form.is_available"
                                :disabled="!form.apply_is_available"
                            />
                            <span :class="!form.apply_is_available ? 'text-muted-foreground' : ''">
                {{ form.is_available ? 'Disponible' : 'Indisponible' }}
            </span>
                        </div>
                    </div>
                </div>
                <InputError :message="form.errors.is_available" class="ml-10" />
            </div>

            <DialogFooter>
                <Button type="button" @click="submit" :disabled="form.processing">
                    Appliquer les modifications
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>
