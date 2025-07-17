<script setup lang="ts">
import { ref } from 'vue';
import { Head, useForm, usePage } from '@inertiajs/vue3';
import AppLogo from '@/components/AppLogo.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import RevolutCheckout from '@revolut/checkout';
import InputError from '@/components/InputError.vue';

const props = defineProps<{
    revolut_public_id: string;
}>();

// Formulaire pour récupérer les infos du client
const customerForm = useForm({
    email: usePage().props.auth.user?.email || '', // On pré-remplit si l'utilisateur est connecté
    name: usePage().props.auth.user?.name || '',
});

const isProcessingPayment = ref(false);

const openRevolutPopup = () => {
    // --- DÉBUT DE LA CORRECTION ---

    // Validation manuelle simple côté client
    if (!customerForm.email) {
        customerForm.setError('email', 'Veuillez renseigner votre adresse e-mail.');
        return;
    }
    // On pourrait ajouter une vérification de format d'email ici si on voulait

    // On s'assure de vider les erreurs avant de continuer
    customerForm.clearErrors('email');

    // --- FIN DE LA CORRECTION ---

    isProcessingPayment.value = true;

    RevolutCheckout(props.revolut_public_id, 'sandbox').then((instance) => {
        instance.payWithPopup({
            // On passe les informations du client à la popup
            name: customerForm.name,
            email: customerForm.email,

            onSuccess() {
                isProcessingPayment.value = false;
                // TODO: Rediriger vers la page de succès finale
            },
            onError(error) {
                isProcessingPayment.value = false;
                console.error('Erreur de paiement:', error.message);
            },
            onCancel() {
                // L'utilisateur a fermé la pop-up
                isProcessingPayment.value = false;
            }
        });
    });
};

</script>

<template>
    <Head title="Finaliser la commande" />
    <div class="min-h-screen bg-floral-white flex items-center justify-center p-4">
        <div class="w-full max-w-md mx-auto">
            <div class="flex justify-center mb-6">
                <AppLogo size="text-4xl" />
            </div>

            <div class="bg-white/70 p-8 rounded-xl shadow-lg">
                <h2 class="text-2xl font-bold text-center text-cafe-noir mb-4">Finaliser votre commande</h2>

                <!-- On demande le nom et l'email -->
                <div class="space-y-4 mb-6">
                    <div>
                        <Label for="name">Nom</Label>
                        <Input
                            id="name"
                            type="text"
                            v-model="customerForm.name"
                            placeholder="Entrez votre nom complet"
                            required
                        />
                    </div>
                    <div>
                        <Label for="email">Adresse e-mail</Label>
                        <Input
                            id="email"
                            type="email"
                            v-model="customerForm.email"
                            placeholder="vous@exemple.com"
                            required
                        />
                        <InputError class="mt-2" :message="customerForm.errors.email" />
                        <p class="text-xs text-muted-foreground mt-1">Pour recevoir la confirmation de commande.</p>
                    </div>
                </div>

                <!-- Le bouton qui déclenche la pop-up -->
                <Button @click="openRevolutPopup" class="w-full text-lg py-6" :disabled="isProcessingPayment">
                    {{ isProcessingPayment ? 'Chargement...' : 'Payer par carte' }}
                </Button>
            </div>
        </div>
    </div>
</template>
