<template>
    <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#" @click="showingInventory = !showingInventory">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75zM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-8.25zM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-2.25z" />
        </svg>
        <span class="mx-3">Inventario</span>
    </a>
    <MyTransition v-if="subModulePermission(submodules.iproduct_submodule, userSubModules)"
        :transitiondemonstration="showingInventory">
        <Link class="w-full" :href="route('inventory.purchaseproducts')">Productos</Link>
    </MyTransition>
    <!-- <MyTransition v-if="subModulePermission(submodules.iwarehouse_submodule, userSubModules)" :transitiondemonstration="showingInventory">
        <Link class="w-full" :href="route('warehouses.warehouses')">Almacenes</Link>
    </MyTransition> -->
</template>
<script setup>
import MyTransition from '@/Components/MyTransition.vue';
import { subModulePermission } from '@/utils/roles/roles';
import { Link, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

const showingInventory = ref(false)
const { submodules } = usePage().props
const { userSubModules } = usePage().props.auth

</script>