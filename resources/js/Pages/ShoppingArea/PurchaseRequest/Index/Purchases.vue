<template>

    <Head title="Solicitudes" />
    <AuthenticatedLayout :redirectRoute="'purchasesrequest.index'">
        <template #header>
            Solicitudes de compra
        </template>
        <Toaster richColors />
        <div class="min-w-full overflow-hidden">
            <TableHeader v-model:purchases="purchases" />
            <PurchasesTable :purchases="purchases" :confirmPurchasesDeletion="confirmPurchasesDeletion" />
        </div>
        <DeletionConfirmation ref="deletionConfirmation" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import TableHeader from './components/TableHeader.vue';
import PurchasesTable from './components/PurchasesTable.vue';
import DeletionConfirmation from './components/DeletionConfirmation.vue';
import { Toaster } from 'vue-sonner';


const props = defineProps({
    purchase: Object
});

const purchases = ref(props.purchase)
const deletionConfirmation = ref(null)

function confirmPurchasesDeletion(purchaseId) {
    deletionConfirmation.value.confirmPurchasesDeletion(purchaseId)
};

</script>
