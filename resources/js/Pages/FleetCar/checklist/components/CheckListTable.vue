<template>
    <TableStructure>
        <template #thead>
            <tr>
                <TableTitle>Fecha de Registro</TableTitle>
                <TableTitle>Zona</TableTitle>
                <TableTitle>Personal 1</TableTitle>
                <TableTitle>Personal 2</TableTitle>
                <TableTitle>Motivo</TableTitle>
                <TableTitle>Checklist</TableTitle>
                <TableTitle>Imágenes</TableTitle>
                <TableTitle>Observaciones</TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="item in checklist.data" :key="item.id">
                <TableRow>{{ formattedDate(item.created_at) }}</TableRow>
                <TableRow>{{ item.zone }}</TableRow>
                <TableRow>{{ item.user_name }}</TableRow>
                <TableRow>{{ item.additionalEmployees }}</TableRow>
                <TableRow>{{ item.reason }}</TableRow>
                <TableRow>
                    <button type="button">
                        <EyeIcon @click="openChecklist(item)" class="text-green-500 w-5" />
                    </button>
                </TableRow>
                <TableRow>
                    <button type="button">
                        <PhotoIcon @click="openImages(item.id)" class="text-indigo-600 w-5" />
                    </button>
                </TableRow>
                <TableRow width="w-[500px]">{{ item.observation }}</TableRow>
            </tr>
        </template>
    </TableStructure>
    <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
        <pagination :links="checklist.links" />
    </div>
</template>
<script setup>
import { formattedDate } from "@/utils/utils";
import { PhotoIcon, EyeIcon } from "@heroicons/vue/24/outline";
import Pagination from "@/Components/Pagination.vue";
import TableStructure from "@/Layouts/TableStructure.vue";
import TableTitle from "@/Components/TableTitle.vue";
import TableRow from "@/Components/TableRow.vue";

const { checklist, openImages, openChecklist } = defineProps({
    checklist: Object,
    openImages: Function,
    openChecklist: Function
})

</script>