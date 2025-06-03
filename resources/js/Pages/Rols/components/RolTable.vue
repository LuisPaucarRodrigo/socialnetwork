<template>
    <TableStructure>
        <template #thead>
            <tr>
                <TableTitle>Nombre</TableTitle>
                <TableTitle>Nombre</TableTitle>
                <TableTitle>Descripcion</TableTitle>
                <TableTitle></TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="rol in rols.data" :key="rol.id">
                <TableRow>{{ rol.id }}</TableRow>
                <TableRow>{{ rol.name }}</TableRow>
                <TableRow>{{ rol.description }}</TableRow>
                <TableRow>
                    <div class="flex space-x-3 justify-center">
                        <button type="button" @click="showModal(rol.id)" class="text-blue-900 whitespace-no-wrap">
                            <ShowIcon />
                        </button>
                        <button type="button" @click="editModalRol(rol)" class="text-blue-900 whitespace-no-wrap">
                            <EditIcon />
                        </button>
                        <button type="button" @click="confirmRolsDeletion(rol.id)"
                            class="text-blue-900 whitespace-no-wrap">
                            <DeleteIcon />
                        </button>
                    </div>
                </TableRow>
            </tr>
        </template>
    </TableStructure>
    <div v-if="rols.data" class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <Pagination :links="rols.links" />
    </div>
</template>
<script setup>
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import ShowIcon from '@/Components/Icons/ShowIcon.vue';
import Pagination from '@/Components/Pagination.vue';
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';

const { rols, showModal, editModalRol, confirmRolsDeletion } = defineProps({
    rols: Object,
    showModal: Function,
    editModalRol: Function,
    confirmRolsDeletion: Function
})
</script>
