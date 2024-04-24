<template>

    <Head title="Productos" />
    <AuthenticatedLayout :redirectRoute="'warehouses.warehouses'">
        <template #header>
            {{ products ? 'Productos' : 'Activos' }}
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <div class="flex justify-between items-center gap-4">
                <div v-if="products" class="flex items-center flex-grow min-w-0">
                    <Link :href="route('warehouses.createNormalProduct', { warehouse: props.warehouseId })"
                        type="button"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    + Agregar
                    </Link>
                </div>
                <Link :href="route('warehouses.resource.create')"
                    class="inline-flex items-center px-4 py-2 min-w-[115px] border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                + Agregar
                </Link>
                <PrimaryButton v-if="resources" @click="reentry" type="button">
                    {{ boolean == false ? "Sin Revisar" : "Revisados" }}
                </PrimaryButton>
            </div>
            <br>
            <div class="min-w-full overflow-x-auto rounded-lg shadow">
                <table class="w-full table-auto">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Código
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Unidad
                            </th>
                            <th v-if="products"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad Disponible
                            </th>
                            <th v-if="resources && !boolean"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Numero de Serie
                            </th>
                            <th v-if="resources && !boolean"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Precio de Entrada
                            </th>
                            <th v-if="resources && !boolean"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Condición
                            </th>
                            <th v-if="products || boolean"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in products ? products.data : resources.data" :key="item.id"
                            class="text-gray-700 border-b">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.purchase_product.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.purchase_product.code }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.purchase_product.unit }}</p>
                            </td>
                            <td v-if="products" class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.available_quantity }}</p>
                            </td>
                            <td v-if="resources && !boolean"
                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.serial_number }}</p>
                            </td>
                            <td v-if="resources && !boolean"
                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">S/. {{ item.entry_price }}</p>
                            </td>
                            <td v-if="resources && !boolean"
                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.condition }}</p>
                            </td>
                            <td v-if="products || boolean" class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div v-if="products" class="flex justify-center items-center space-x-3">
                                    <Link
                                        :href="route('warehouses.products.entries', { warehouse: warehouseId, inventory: item.id })"
                                        class="text-green-600 hover:underline">
                                    <EyeIcon class="h-4 w-4 ml-1" />
                                    </Link>
                                    <button v-if="auth.user.role_id == 1" @click="confirmDeleteProduct(item.id)"
                                        class="text-red-600 hover:underline">
                                        <svg width="15px" height="15px" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="12" cy="12" r="9" stroke="red" stroke-width="2" />
                                            <path d="M18 18L6 6" stroke="red" stroke-width="2" />
                                        </svg>
                                    </button>
                                </div>
                                <div v-if="boolean" class="flex justify-center items-center space-x-3">
                                    <button @click="add_serial_number(item.id)" class="text-gray-600 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="products ? products.links : resources.links" />
            </div>
        </div>
        <Modal :show="showModalAddSerialNumber">
            <form class="p-6" @submit.prevent="submit_add_serial_number">
                <h2 class="text-lg font-medium text-gray-900">
                    Agregar Numero de serie
                </h2>
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-2">
                        <InputLabel for="serial_number" class="font-medium" value="Numero de Serie" />
                        <div class="mt-2">
                            <TextInput required id="serial_number" type="text" v-model="form.serial_number" />
                        </div>
                        <InputError :message="form.errors.serial_number" class="mt-2" />
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <DangerButton type="button" @click="close_add_serial_number"> Cerrar </DangerButton>
                    <PrimaryButton type="submit"> Aceptar </PrimaryButton>
                </div>
            </form>
        </Modal>
        <ConfirmDisableModal :confirmingDeletion="confirmingDocDeletion" itemType="Producto"
            :deleteFunction="deleteProduct" @closeModal="closeModalDoc" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import ConfirmDisableModal from '@/Components/ConfirmDisableModal.vue';
import { ref } from 'vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import { EyeIcon } from '@heroicons/vue/24/outline';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import DangerButton from '@/Components/DangerButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    products: {
        type: Object,
        required: false
    },
    auth: Object,
    warehouseId: {
        type: Number,
        required: false
    },
    resources: {
        type: Object,
        required: false
    },
    boolean: {
        type: Boolean,
        required: false
    }
});

const docToDelete = ref(null);
const confirmingDocDeletion = ref(false);
const resource_id = ref(null);
const showModalAddSerialNumber = ref(false);

const form = useForm({
    serial_number: '',
    resource_id: ''
})

const confirmDeleteProduct = (productId) => {
    docToDelete.value = productId;
    confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};

const deleteProduct = () => {
    const docId = docToDelete.value;
    if (docId) {
        router.put(route('inventory.purchaseproducts.disable', { purchase_product: docId }), {
            onSuccess: () => closeModalDoc()
        });
    }
};

const reentry = () => {
    if (props.boolean == true) {
        router.get(route('warehouses.index.resource'))
    } else {
        router.get(route('warehouses.index.resource', { boolean: false }))
    }
};

function add_serial_number(id) {
    resource_id.value = id
    showModalAddSerialNumber.value = true
}

function close_add_serial_number() {
    showModalAddSerialNumber.value = false
}

function submit_add_serial_number() {
    form.resource_id = resource_id.value
    form.post(route('warehouses.resource.add_serial_number'), {
        onSuccess: () => {
            form.reset()
            close_add_serial_number()
        }
    })
}
</script>