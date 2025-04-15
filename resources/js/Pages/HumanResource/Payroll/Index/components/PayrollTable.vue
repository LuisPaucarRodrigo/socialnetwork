<template>
    <div class="rounded-lg shadow grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-6 gap-3">
        <PayrollCard v-for="item in payrolls.data || payrolls" :key="item.id" :item="item" />
    </div>
    <br>
    <div v-if="payrolls.data" class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
        <Pagination :links="payrolls.links" />
    </div>
</template>
<script setup>
import Pagination from '@/Components/Pagination.vue';
import PayrollCard from './PayrollCard.vue';

const { payrolls } = defineProps({
    payrolls: Object
})
</script>