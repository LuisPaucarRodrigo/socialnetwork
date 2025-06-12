<template>
    <TableStructure>
        <template #thead>
            <tr>
                <TableTitle>Título</TableTitle>
                <TableTitle>Tipo</TableTitle>
                <TableTitle>Códigos</TableTitle>
                <TableTitle v-permission-or="['edit_pro_title', 'delete_pro_title']"></TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="title in titles.data" :key="title.id">
                <TableRow>{{ title.title }}</TableRow>
                <TableRow>{{ title.type }}</TableRow>
                <TableRow>{{title.codes.map((item) => item.code).join(', ')}}</TableRow>
                <TableRow v-permission-or="['edit_pro_title', 'delete_pro_title']">
                    <div class="flex justify-center space-x-3">
                        <button v-permission="'edit_pro_title'" type="button" @click="openModal(title)">
                            <EditIcon />
                        </button>
                        <button v-permission="'delete_pro_title'" type="button" @click="confirmDeleteTitle(title.id)">
                            <DeleteIcon />
                        </button>
                    </div>
                </TableRow>
            </tr>
        </template>
    </TableStructure>

    <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <pagination :links="titles.links" />
    </div>
</template>
<script setup>

import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import { DeleteIcon, EditIcon } from "@/Components/Icons/Index";
import Pagination from '@/Components/Pagination.vue';

const { titles, openModal, confirmDeleteTitle } = defineProps({
    titles: Object,
    openModal: Function,
    confirmDeleteTitle: Function
})
</script>