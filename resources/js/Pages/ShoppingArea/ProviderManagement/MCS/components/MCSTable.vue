<template>
    <TableStructure :info="dataToRender" :loading="loading">
        <template #thead>
            <tr>
                <TableTitle>Categoria</TableTitle>
                <TableTitle>Segmento</TableTitle>
                <TableTitle>Acciones</TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="item in dataToRender.data || dataToRender" :key="item.id">
                <TableRow>{{ item.name }}</TableRow>
                <TableRow :width="'w-[500px]'">
                    <p v-for="(i, index) in item.segment" :key="index" class="text-gray-900 inline">
                        {{ i.name }}
                        <span v-if="index < item.segment.length - 1">, </span>
                    </p>
                </TableRow>
                <TableRow>
                    <div class="flex space-x-3 justify-center">
                        <button type="button" @click="editCategoryForm(item)">
                            <EditIcon />
                        </button>
                        <button type="button" @click="confirmDeletion(item.id)">
                            <DeleteIcon />
                        </button>
                    </div>
                </TableRow>
            </tr>
        </template>
    </TableStructure>
    <div v-if="dataToRender.data"
        class="flex flex-col items-center border-t bg-white px-3 py-2 xs:flex-row xs:justify-between">
        <PaginationAxios :links="dataToRender.links" v-model:loading="loading" v-model:dataToRender="dataToRender" />
    </div>
</template>
<script setup>
import { DeleteIcon, EditIcon } from '@/Components/Icons';
import PaginationAxios from '@/Components/PaginationAxios.vue';
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';

const dataToRender = defineModel('dataToRender')
const loading = defineModel('loading')

const { confirmDeletion, editCategoryForm } = defineProps({
    confirmDeletion: Function,
    editCategoryForm: Function
})
</script>