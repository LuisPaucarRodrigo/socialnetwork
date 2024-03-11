<template>
    <Head title="Gestion de Almacenes" />
    <AuthenticatedLayout :redirectRoute="'warehouses.warehouses'">
        <template #header>
            Gestión de Productos del Almacén
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <div class="flex justify-between items-center gap-4">
                <button @click="createProduct" type="button"
                    class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                    + Agregar
                </button>
                <div class="flex items-center">
                    <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                            stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <input type="text" placeholder="Buscar por nombre..."
                        class="block w-full ml-2 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        @input="updateFilteredProducts" />
                </div>
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
                                Proyecto Asignado
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                N° de Serie
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Acciones
                            </th>
                            <th v-if="Object.values(productStates).some(value => value)"
                                v-for="item in warehouseHeadersFiltered" :key="item.id"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                {{ item }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in filteredProducts" :key="item.id" class="text-gray-700 border-b">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <h2 class="text-sm font-semibold">{{ item.name }}</h2>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div v-for="productHeader in productHeaders" :key="productHeader.id">
                                    <h3 v-if="productHeader.header_id === 5 && productHeader.product_id === item.id"
                                        class="text-sm font-semibold text-gray-700 line-clamp-1">
                                        {{ productHeader.content }}
                                    </h3>
                                </div>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div v-for="productHeader in productHeaders" :key="productHeader.id">
                                    <h3 v-if="productHeader.header_id === 7 && productHeader.product_id === item.id"
                                        class="text-sm font-semibold text-gray-700 line-clamp-1">
                                        {{ formattedDate(productHeader.content) }}
                                    </h3>
                                </div>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div v-for="productHeader in productHeaders" :key="productHeader.id">
                                    <h3 v-if="productHeader.header_id === 12 && productHeader.product_id === item.id"
                                        class="text-sm font-semibold text-gray-700 line-clamp-1">
                                        {{ productHeader.content }}
                                    </h3>
                                </div>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="inline-flex justify-end gap-x-0">
                                    <button @click="toggleProductState(item.id)" class="text-indigo-600 hover:underline">
                                        <template v-if="!getProductState(item.id)">
                                            <EyeIcon class="h-4 w-4" />
                                        </template>
                                        <template v-else>
                                            <EyeSlashIcon class="h-4 w-4" />
                                        </template>
                                    </button>
                                    <Link
                                        :href="route('warehouses.showProduct', { warehouse: props.warehouse.id, product: item.id })"
                                        class="text-green-400 hover:underline mx-3">
                                    <EyeIcon class="h-4 w-4" />
                                    </Link>
                                    <button v-if="auth.user.role_id == 1" @click="confirmDeleteProduct(item.id)" class="text-red-600 hover:underline">
                                        <TrashIcon class="h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                            <template v-if="productStates[item.id]">
                                <td v-for="header in item.product_headers.filter(item => !excludedHeaderIds.includes(item.header_id))"
                                    :key="header.id" class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <h3 class="text-sm font-semibold text-gray-700 max-w-xl">{{ header.content }}</h3>
                                </td>
                            </template>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="products.links" />
            </div>
        </div>
        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Producto" :deleteFunction="deleteProduct"
            @closeModal="closeModalDoc" />
    </AuthenticatedLayout>
</template>
    
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { ref, computed } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { TrashIcon, EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/outline';
import { formattedDate } from '@/utils/utils';

const props = defineProps({
    products: Object,
    auth: Object,
    warehouse: Object,
});

const productStates = ref({});

const getProductState = (productId) => {
    return productStates.value[productId] ?? false; // Si no existe, devolvemos false
};

// Método para alternar el estado de expansión de un producto específico
const toggleProductState = (productId) => {
    const currentState = getProductState(productId);
    productStates.value = {
        ...productStates.value,
        [productId]: !currentState // Cambiamos el estado actual
    };
};

const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);

const confirmDeleteProduct = (productId) => {
    docToDelete.value = productId;
    confirmingDocDeletion.value = true;
};

const excludedHeaderIds = ref([5, 7, 12]); // IDs de headers que deseas excluir

const warehouseHeadersFiltered = computed(() => {
    if (!props.warehouse || !props.warehouse.headers) {
        return [];
    }

    const headers = props.warehouse.headers;
    const filteredHeaders = [];

    for (const key in headers) {
        if (headers.hasOwnProperty(key)) {
            const header = headers[key];
            if (!excludedHeaderIds.value.includes(header.id)) {
                filteredHeaders.push(header.name);
            }
        }
    }

    return filteredHeaders;
});


const productHeaders = computed(() => props.products.data.flatMap(product => product.product_headers));

const filteredHeaders = computed(() => {
    return productHeaders.value.filter(item => {
        return !excludedHeaderIds.value.includes(item.header_id) && productStates.value[item.product_id];
    });
});

const searchTerm = ref('');

let filteredProducts = props.products.data;

filteredProducts = computed(() => {
    const term = searchTerm.value.trim().toLowerCase();
    return props.products.data.filter(product => product.name.toLowerCase().includes(term));
});

const updateFilteredProducts = (event) => {
    const term = event.target.value.trim().toLowerCase();
    searchTerm.value = term;
};


const expanded = ref(false);

const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};

const createProduct = () => {
    router.visit(route('warehouses.createProduct', { warehouse: props.warehouse.id }));
};

const deleteProduct = () => {
    const docId = docToDelete.value;
    if (docId) {
        router.delete(route('warehouses.destroyProduct', { warehouse: props.warehouse.id, product: docId }), {
            onSuccess: () => closeModalDoc()
        });
    }
};

</script>
  
    