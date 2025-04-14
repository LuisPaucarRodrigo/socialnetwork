<template>
    <div class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
        <p class="text-sm mb-3">
            Mes: {{ item.month }}
        </p>
        <p class="text-sm mb-3">
            Monto Total: S/ {{ Number(item.total_amount).toFixed(2) }}
        </p>
        <Link :href="route('spreadsheets.index', { payroll_id: item.id })"
            class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
        Nomina
        </Link>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/vue3';

const { item } = defineProps({
    item: Object
})
</script>
