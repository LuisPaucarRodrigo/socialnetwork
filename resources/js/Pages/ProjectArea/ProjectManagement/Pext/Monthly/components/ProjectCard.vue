<template>
    <div class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
        <div class="grid grid-cols-2">
            <h2 class="text-sm font-semibold mb-3">
                N° {{ item.code }}
            </h2>
            <div v-if="auth.user.role_id === 1" class="inline-flex justify-end items-start gap-x-2">
                <button @click="() => {
                    router.post(route('projectmanagement.liquidation'), { project_id: item.id }, {
                        onSuccess: () => router.visit(route('projectmanagement.pext.index'))
                    })
                }" v-if="item.status === null"
                    :class="`h-6 px-1 rounded-md bg-indigo-700 text-white text-sm  ${item.is_liquidable ? '' : 'opacity-60'}`"
                    :disabled="item.is_liquidable ? false : true">
                    Liquidar
                </button>
                <Link :href="route('projectmanagement.update', { project_id: item.id, type: 2 })"
                    class="flex items-start">
                <EditIcon />
                </Link>
            </div>
        </div>
        <h3 class="text-sm font-semibold text-gray-700 line-clamp-3 mb-2">
            {{ item.description }}
        </h3>
        <h3 class="text-sm font-semibold text-gray-700 line-clamp-3 mb-2">
            {{ item.cost_center.name }}
        </h3>
        <p v-if="item?.initial_budget === 0.00" class="text-red-500 text-sm">
            No se definió un presupuesto
        </p>
        <div class="text-sm ">
            <div class="grid grid-cols-1 gap-y-1">
                <template v-permission="'pro_pext_tasks'">
                    <Link v-if="item?.initial_budget > 0" :href="route('tasks.index', { id: item.id })"
                        class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Tareas
                    </Link>
                    <span v-else class="text-gray-400">Tareas</span>
                </template>
                <template v-permission="'pro_pext_one_calendar'">
                    <Link v-if="item?.initial_budget > 0" :href="route('projectscalendar.show', { project: item.id })"
                        class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Calendario
                    </Link>
                    <span v-else class="text-gray-400">Calendario</span>
                </template>
                <template v-permission="'pro_pext_purchase_expenses'">
                    <Link v-if="item?.initial_budget > 0"
                        :href="route('projectmanagement.pext.expenses.index', { project_id: item.id, fixedOrAdditional: false, status: item.status })"
                        class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Compras y
                    Gastos</Link>
                    <span v-else class="text-gray-400">Compras y Gastos</span>
                </template>
            </div>
        </div>
    </div>
</template>
<script setup>
import { EditIcon } from '@/Components/Icons';
import { router, Link } from '@inertiajs/vue3';

const { item, auth } = defineProps({
    item: Object,
    auth: Object
})

</script>