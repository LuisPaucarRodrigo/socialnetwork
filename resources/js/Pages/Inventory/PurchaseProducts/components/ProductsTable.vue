<template>
    <div class="min-w-full overflow-x-auto rounded-lg shadow">
        <table class="w-full table-auto">
            <thead>
                <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
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
                        Tipo/Tipo de Producto
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Descripción
                    </th>
                    <th v-if="hasPermission('InventoryManager')"
                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="item in products.data || products" :key="item.id" class="text-gray-700 border-b">
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ item.name }}</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ item.code }}</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ item.unit }}</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                        <p class="text-gray-900 whitespace-nowrap">
                            {{ item.type }}
                            {{ item.type === 'Producto' ? '/ ' + item.type_product : item.resource_type
                                ? '/ ' + item.resource_type.name
                                : '' }}</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                        <p class="text-gray-900 whitespace-no-wrap">{{ item.description }}</p>
                    </td>
                    <td v-if="hasPermission('InventoryManager')"
                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                        <div class="inline-flex justify-end gap-x-0">
                            <button v-if="auth.user.role_id == 1" @click="openEditProductModal(item)">
                                <EditIcon />
                            </button>
                            <button v-if="auth.user.role_id == 1" @click="confirmDeleteProduct(item.id)"
                                class="text-red-600 hover:underline">
                                <DisableIcon />
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div v-if="products.data" class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
        <pagination :links="products.links" />
    </div>
</template>
<script setup>
import { DisableIcon, EditIcon } from "@/Components/Icons/Index";
import Pagination from '@/Components/Pagination.vue'

const { products, userPermissions, auth, openEditProductModal, confirmDeleteProduct } = defineProps({
    products: Object,
    userPermissions: Object,
    auth: Object,
    openEditProductModal: Function,
    confirmDeleteProduct: Function
});

function hasPermission(permission) {
    return userPermissions.includes(permission);
}
</script>