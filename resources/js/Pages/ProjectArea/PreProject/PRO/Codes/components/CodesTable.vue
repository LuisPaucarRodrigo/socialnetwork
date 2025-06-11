<template>
    <TableStructure>
        <template #thead>
            <tr>
                <TableTitle>Código</TableTitle>
                <TableTitle>Descripción</TableTitle>
                <TableTitle
                    v-permission-or="['add_pro_code_images', 'see_pro_code_images', 'edit_pro_code', 'delete_pro_code', 'delete_pro_code_images']">
                    Acciones</TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="code in codes.data" :key="code.id" class="text-gray-700">
                <TableRow>{{ code.code }}</TableRow>
                <TableRow>{{ code.description }}</TableRow>
                <TableRow
                    v-permission-or="['add_pro_code_images', 'see_pro_code_images', 'edit_pro_code', 'delete_pro_code', 'delete_pro_code_images']">
                    <div class="flex justify-center space-x-3">
                        <button v-permission="'add_pro_code_images'" @click="openImagesForm(code.id)">
                            <PlusCircleIcon />
                        </button>
                        <button v-permission-or="['see_pro_code_images', 'delete_pro_code_images']"
                            @click="openImagesModal(code.id)">
                            <ShowIcon />
                        </button>
                        <button v-permission="'edit_pro_code'" type="button" @click="openModal(code)">
                            <EditIcon />
                        </button>
                        <button v-permission="'delete_pro_code'" type="button" @click="confirmDeleteCode(code.id)">
                            <DeleteIcon />
                        </button>
                    </div>
                </TableRow>
            </tr>
        </template>
    </TableStructure>
    <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <pagination :links="codes.links" />
    </div>
</template>
<script setup>
import Pagination from '@/Components/Pagination.vue';
import { EditIcon, DeleteIcon, PlusCircleIcon, ShowIcon } from '@/Components/Icons/Index';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';

const { codes, openImagesForm, openModal, openImagesModal, confirmDeleteCode } = defineProps({
    codes: Object,
    openImagesForm: Function,
    openModal: Function,
    openImagesModal: Function,
    confirmDeleteCode: Function
})
</script>