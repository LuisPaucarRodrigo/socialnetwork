<template>
    <Modal :show="showCotization">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Información de la Cotización
            </h2>
            <div class="mt-4">
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Codigo de Cotizacion:</span> {{ cotization.code }}
                </p>
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Monto:</span> S/ {{ cotization.amount.toFixed(2) }}
                </p>
                <p class="text-sm text-gray-600">
                    <span class="font-medium">Proveedor:</span> {{ cotization.provider
                    }}
                </p>
                <p class="text-sm text-gray-600"><span class="font-medium">Fecha de solicitud de compra:</span>
                    {{ formattedDate(cotization.quote_deadline) }}</p>
                <p class="text-sm text-gray-600"><span class="font-medium">Estado de Cotizacion:</span>
                    {{ cotization.response }}
                </p>
                <div class="flex items-center">
                    <p class="text-sm text-gray-600">
                        <span class="font-medium">Ver Documento de Cotización:</span>
                    </p>
                    <button @click="openPreviewDocumentQuote(cotization.purchase_quote_id)">
                        <ShowIcon />
                    </button>
                </div>

            </div>
            <div class="mt-6 flex justify-end">
                <PrimaryButton @click="closeCotizationModal">Cerrar</PrimaryButton>
            </div>
        </div>
    </Modal>
</template>
<script setup>
import {ShowIcon} from '@/Components/Icons/Index';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { formattedDate } from '@/utils/utils';
import { ref } from 'vue';

const showCotization = ref(false);
const quoteToOpen = ref(null);

const cotization = {
    code: '',
    amount: null,
    provider: '',
    quote_deadline: '',
    response: '',
    purchase_quote_id: '',
};

const openCotization = (order) => {
    quoteToOpen.value = JSON.parse(JSON.stringify(order.purchase_quote));
    cotization.code = quoteToOpen.value.code;
    cotization.amount = quoteToOpen.value.total_amount;
    cotization.provider = quoteToOpen.value.provider.company_name;
    cotization.quote_deadline = quoteToOpen.value.quote_deadline;
    cotization.response = quoteToOpen.value.state ? "Aceptado" : "Rechazado";
    cotization.purchase_quote_id = quoteToOpen.value.id;
    showCotization.value = true;
};

const closeCotizationModal = () => {
    showCotization.value = false;
}

function openPreviewDocumentQuote(documentId) {
    const routeToShow = route('purchasesrequest.show', { id: documentId });
    window.open(routeToShow, '_blank');
}

defineExpose({ openCotization })
</script>