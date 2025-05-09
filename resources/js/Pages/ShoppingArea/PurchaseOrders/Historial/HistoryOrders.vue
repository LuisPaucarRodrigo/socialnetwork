<template>

    <Head title="Ordenes de Compra" />
    <AuthenticatedLayout :redirectRoute="'purchaseorders.index'">
        <template #header>
            Compras Completadas
        </template>
        <div class="min-w-full overflow-hidden">
            <TableHeader v-model:orders="orders" />
            <HIstoryTable :orders="orders" />
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import TableHeader from './components/TableHeader.vue';
import { ref } from 'vue';
import HIstoryTable from './components/HIstoryTable.vue';

const { order } = defineProps({
    order: Object
})
const orders = ref(order)
</script>
