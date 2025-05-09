<template>
    <Modal :show="confirmingPurchasesDeletion">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Estas seguro de eliminar la solicitud?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Se eliminara toda la informacion relacionada con la solicitud.
            </p>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="toggleModal"> Cancel </SecondaryButton>
                <DangerButton class="ml-3" @click="deletePurchase()">Eliminar</DangerButton>
            </div>
        </div>
    </Modal>
</template>
<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';

const confirmingPurchasesDeletion = ref(false);
const purchaseToDelete = ref(null);

const toggleModal = () => {
    confirmingPurchasesDeletion.value = !confirmingPurchasesDeletion.value;
};

function confirmPurchasesDeletion(purchaseId) {
    purchaseToDelete.value = purchaseId;
    toggleModal()
};

const deletePurchase = () => {
    const purchaseId = purchaseToDelete.value;
    if (purchaseId) {
        router.delete(route('purchasesrequest.destroy', { id: purchaseId }), {
            onSuccess: () => closeModal()
        });
    }
};

defineExpose({ confirmPurchasesDeletion })
</script>