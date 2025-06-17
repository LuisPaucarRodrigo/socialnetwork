<template>

    <Head title="Ordenes de Compra" />
    <AuthenticatedLayout :redirectRoute="'purchaseorders.index'">
        <template #header>
            Ordenes
        </template>
        <div class="min-w-full overflow-hidden rounded-lg shadow">
            <TableHeader v-model:orders="orders" />
            <OrdersTable :orders="orders" :userPermissions="userPermissions" :openCotization="openCotization"
                :updateState="updateState" />
        </div>

        <OrderForm ref="showForm" :data="data" />
        <ShowCotization ref="showCotization" v-model:showModalUpdateSuccess="showModalUpdateSuccess" />
        <ConfirmateModal :showConfirm="showModalUpdateSuccess" tittle="Confirmar"
            text="El estado de la orden se cambiara" :actionFunction="updateStateOrder"
            @closeModal="closeUpdateModal" />

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import ConfirmateModal from '@/Components/ConfirmateModal.vue';
import TableHeader from './components/TableHeader.vue';
import OrdersTable from './components/OrdersTable.vue';
import ShowCotization from './components/ShowCotization.vue';
import OrderForm from './components/OrderForm.vue';
import { notify } from '@/Components/Notification';


const showForm = ref(false);
const showCotization = ref(false);

const state = ref(null);

const showModalUpdateSuccess = ref(false);
const message_order = ref('');

const data = ref({
    state: null,
    id: null
});

const props = defineProps({
    order: Object,
    userPermissions: Array
})

const orders = ref(props.order)


function updateState(stateid, newState, is_payments_completed) {
    showForm.value.updateState(stateid, newState, is_payments_completed)
}

function openCotization(order) {
    showCotization.value.openCotization(order)
};

async function updateStateOrder() {
    showModalUpdateSuccess.value = false
    let url = route('purchaseorders.state')
    try {
        let response = await axios.post(url, data.value)
        updateFrontEnd("Send", response.data)
    } catch (error) {
        console.error("Hubo un error al enviar la orden:", error);
    }
}

function updateFrontEnd(action, response) {
    let validations = orders.data || orders
    if (action === "Send") {
        let index = validations.findIndex(item => item.id === response.id)
        validations[index] = response
        message_order.value = state.value === "OC Enviada" ? "Orden enviada correctamente" : "Orden cambiada a Pendiente";
        notify(message_order.value)
    }
}

function closeUpdateModal() {
    showModalUpdateSuccess.value = false
}
</script>
