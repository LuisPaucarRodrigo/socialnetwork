<template>

    <Head title="Productos" />
    <AuthenticatedLayout :redirectRoute="'warehouses.warehouses'">
        <template #header>
            Productos
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <div class="flex justify-between items-center gap-4">
                <Link
                    :href="route('warehouses.createNormalProduct', { warehouse: props.warehouseId })" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                + Agregar
                </Link>
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
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad Disponible
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in products.data" :key="item.id" class="text-gray-700 border-b">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.purchase_product.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.purchase_product.code }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.purchase_product.unit }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.available_quantity }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex justify-center items-center space-x-3">
                                    <Link
                                        :href="route('warehouses.products.entries', { warehouse: warehouseId, inventory: item.id })">
                                    <ShowIcon />
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
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="products.links" />
            </div>
        </div>
        <ConfirmDisableModal :confirmingDeletion="confirmingDocDeletion" itemType="Producto"
            :deleteFunction="deleteProduct" @closeModal="closeModalDoc" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import ConfirmDisableModal from '@/Components/ConfirmDisableModal.vue';
import { ref } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { ShowIcon } from "@/Components/Icons";

const props = defineProps({
    products: {
        type: Object,
        required: false
    },
    auth: Object,
    warehouseId: {
        type: Number,
        required: false
    }
});

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const docToDelete = ref(null);
const confirmingDocDeletion = ref(false);

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

</script>