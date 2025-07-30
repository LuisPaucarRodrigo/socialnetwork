<template>
    <TableStructure :info="rols">
        <template #thead>
            <tr>
                <TableTitle>Nombre</TableTitle>
                <TableTitle>Descripcion</TableTitle>
                <TableTitle v-permission-or="[
                    'see_role',
                    'edit_role',
                    'delete_role',
                ]">Acciones</TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="rol in rols.data" :key="rol.id">
                <TableRow>{{ rol.name }}</TableRow>
                <TableRow>{{ rol.description }}</TableRow>
                <TableRow v-permission-or="[
                    'see_role',
                    'edit_role',
                    'delete_role',
                ]">
                    <div class="flex space-x-3 justify-center">
                        <button v-permission="'see_role'" type="button" @click="showModal(rol.id)">
                            <ShowIcon />
                        </button>
                        <button v-permission="'edit_role'" type="button" @click="editModalRol(rol)">
                            <EditIcon />
                        </button>
                        <button v-permission="'delete_role'" type="button" @click="confirmRolsDeletion(rol.id)">
                            <DeleteIcon />
                        </button>
                    </div>
                </TableRow>
            </tr>
        </template>
    </TableStructure>
    <div v-if="rols.data" class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <PaginationAxios :links="rols.links" v-model:loading="loading" v-model:dataToRender="rols" />
    </div>
</template>
<script setup>
import { DeleteIcon, EditIcon, ShowIcon } from '@/Components/Icons';
import PaginationAxios from '@/Components/PaginationAxios.vue';
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';

const { showModal, editModalRol, confirmRolsDeletion } = defineProps({
    showModal: Function,
    editModalRol: Function,
    confirmRolsDeletion: Function
})

const rols = defineModel('rols')
const loading = defineModel('loading')

</script>
