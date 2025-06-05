<template>

    <Head title="Productos" />
    <AuthenticatedLayout :redirectRoute="'warehouses.warehouses'">
        <template #header>
            Aprobar Ingreso de Productos por Compras
        </template>

        <div class="min-w-full p-3 rounded-lg shadow">
            <div class="min-w-full overflow-x-auto rounded-lg shadow">
                <table class="w-full table-auto">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Código
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Número de Serie
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Estado
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Monto Total
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Ver Detalles de Cotización
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Ver Documento de Cotización
                            </th>
                            <th v-if="hasPermission('InventoryManager')"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in purchase_orders ? purchase_orders.data : purchase_orders_resource.data"
                            :key="item.id" class="text-gray-700 border-b">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.code }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.serie_number }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.state }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ item.purchase_quote.currency === 'sol' ? 'S/. ' : '$ ' }}
                                    {{ item.purchase_quote.total_amount.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <button type="button" @click="showQuoteDetails(item)">
                                    <ShowIcon />
                                </button>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <button type="button" @click="showQuoteDoc(item)">
                                    <ShowIcon />
                                </button>
                            </td>
                            <td v-if="hasPermission('InventoryManager')"
                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <button @click="approve(item.id)"
                                    class="flex items-center text-blue-500 hover:underline">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="purchase_orders ? purchase_orders.links : purchase_orders_resource.links" />
            </div>
        </div>

        <Modal :show="approvating">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Agregar Observaciones
                </h2>
                <form @submit.prevent="submit">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <div>
                                <InputLabel for="observations" class="font-medium leading-6 text-gray-900">
                                    Observaciones:
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.observations" id="observations"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.observations" />
                                </div>
                            </div>
                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <SecondaryButton @click="closeApprove"> Cancelar </SecondaryButton>
                                <button type="submit" :class="{ 'opacity-25': form.processing }"
                                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Aprobar
                                    Ingreso</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="showModal" :maxWidth="'sm'">
            <div class="p-6">
                <div class="text-center">
                    <div class="bg-green-200 rounded-full h-12 w-12 flex mx-auto items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                        </svg>
                    </div>
                    <h2 class="pt-5 text-lg font-medium text-gray-900">
                        Aprobación de Ingreso de Producto por Compra exitosa.
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        ¡La orden se aprobó correctamente!
                    </p>
                </div>
            </div>
        </Modal>

        <Modal :show="showQuoteDetailsModal" @close="showQuoteDetailsModal = false" :maxWidth="'xl'">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-base font-medium leading-7">
                        Detalles de la Cotización
                    </h2>
                    <button @click="closeQuoteDetailsModal"
                        class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <!-- Puedes usar un símbolo de cierre, como una X -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mb-4">
                    <p class="font-medium text-gray-900">Fecha límite de cotización:
                        <span class="text-gray-600">{{ formattedDate(purchaseQuoteData.quote_deadline) }}</span>
                    </p>
                    <p class="font-medium text-gray-900">Tiempo de entrega:
                        <span class="text-gray-600">{{ purchaseQuoteData.deliverable_time }}</span>
                    </p>
                    <p class="font-medium text-gray-900">Tipo de pago:
                        <span class="text-gray-600">{{ purchaseQuoteData.payment_type }}</span>
                    </p>
                    <p class="font-medium text-gray-900">Moneda:
                        <span class="text-gray-600">{{ purchaseQuoteData.currency }}</span>
                    </p>
                    <p class="font-medium text-gray-900">Número de cuenta:
                        <span class="text-gray-600">{{ purchaseQuoteData.account_number }}</span>
                    </p>
                </div>
                <p class="font-medium">Productos:</p>
                <div class="overflow-x-auto mt-4">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Producto
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Cantidad
                                </th>
                                <th scope="col"
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Precio Unitario
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr v-for="(product, index) in purchaseQuoteData.products" :key="index">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ product.purchase_product.name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ product.quantity }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">
                                        {{ purchaseQuoteData.currency === 'sol' ? 'S/. ' : '$ ' }}
                                        {{ product.unitary_amount_no_igv.toFixed(2) }}
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, useForm, Link } from '@inertiajs/vue3';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { formattedDate } from '@/utils/utils';
import { ShowIcon } from "@/Components/Icons/Index";

const props = defineProps({
    purchase_orders: {
        type: Object,
        required: false
    },
    warehouseId: Number,
    purchase_orders_resource: {
        type: Object,
        required: false
    },
    userPermissions: Array
});

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const approvating = ref(false);
const showModal = ref(false);
const showQuoteDetailsModal = ref(false);

const approve = (purchase_order_id) => {
    approvating.value = true;
    form.purchase_order_id = purchase_order_id
}

const closeApprove = () => {
    form.reset();
    approvating.value = false;
}

const form = useForm({
    observations: '',
    purchase_order_id: null
});

let purchaseQuoteData = {
    quote_deadline: '',
    deliverable_time: null,
    payment_type: '',
    currency: '',
    account_number: '',
    products: []
};

const submit = () => {
    form.post(props.purchase_orders ? route('warehouses.purchaseorders.approve.post', { warehouse: props.warehouseId }) : route('warehouses.resource.approve'), {
        onSuccess: () => {
            closeApprove();
            form.reset();
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                router.visit(props.purchase_orders ? route('warehouses.purchaseorders.approve', { warehouse: props.warehouseId }) : route('warehouses.resource'))
            }, 2000);
        },
        onError: () => {
            closeApprove();
        }
    });
}

const showQuoteDoc = (purchase_order) => {
    const routeToShow = route('purchasesrequest.show', { id: purchase_order.purchase_quote.id });
    window.open(routeToShow, '_blank');
}



const showQuoteDetails = (purchase_order) => {
    purchaseQuoteData = {
        quote_deadline: purchase_order.purchase_quote.quote_deadline,
        deliverable_time: purchase_order.purchase_quote.deliverable_time,
        payment_type: purchase_order.purchase_quote.payment_type,
        currency: purchase_order.purchase_quote.currency,
        account_number: purchase_order.purchase_quote.account_number,
        products: purchase_order.purchase_quote.purchase_quote_products
    };

    showQuoteDetailsModal.value = true;
};

const closeQuoteDetailsModal = () => {
    showQuoteDetailsModal.value = false;
};

</script>