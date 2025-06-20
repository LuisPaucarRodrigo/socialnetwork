<template>
    <TableStructure>
        <template #thead>
            <tr>
                <TableTitle>Nombre del Proyecto</TableTitle>
                <TableTitle>Fecha de Asignación</TableTitle>
                <TableTitle>Cliente</TableTitle>
                <TableTitle>Centro de Costo</TableTitle>
                <TableTitle>Codigo de Proyecto</TableTitle>
                <TableTitle>CPE</TableTitle>
                <TableTitle>Zonas</TableTitle>
                <TableTitle>Gestor</TableTitle>
                <TableTitle>Encargado</TableTitle>
                <TableTitle></TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="item in assignations.data || assignations" :key="item.id" class="text-gray-700">
                <TableRow>{{ item.project_name }}</TableRow>
                <TableRow>{{ formattedDate(item.assignation_date) }}</TableRow>
                <TableRow>{{ item.customer }}</TableRow>
                <TableRow>{{ item.project?.cost_center?.name }}</TableRow>
                <TableRow>{{ item.project_code }}</TableRow>
                <TableRow>{{ item.cpe }}</TableRow>
                <TableRow>{{ item.zone || "--" }} {{ item.zone2 }} {{ item.zone3 }}</TableRow>
                <TableRow>{{ item.manager }}</TableRow>
                <TableRow>{{ item.user_name }}</TableRow>
                <TableRow>
                    <button @click="updateAssignation(item)">
                        <EditIcon />
                    </button>
                </TableRow>
            </tr>
        </template>
    </TableStructure>

    <div v-if="assignations.data"
        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <pagination :links="assignations.links" />
    </div>
</template>
<script setup>
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import Pagination from '@/Components/Pagination.vue';
import { formattedDate } from '@/utils/utils.js';
import { EditIcon } from '@/Components/Icons/Index';

const { assignations, updateAssignation } = defineProps({
    assignations: Object,
    updateAssignation: Function,
})


</script>