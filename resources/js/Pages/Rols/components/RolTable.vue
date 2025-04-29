<template>
    <TableStructure>
        <template #thead>
            <tr>
                <TableTitle>Nombre</TableTitle>
                <TableTitle>Descripcion</TableTitle>
                <TableTitle></TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="rol in rols.data" :key="rol.id">
                <TableRow>{{ rol.name }}</TableRow>
                <TableRow>{{ rol.description }}</TableRow>
                <TableRow>
                    <div class="flex space-x-3 justify-center">
                        <button type="button" @click="showModal(rol.id)" class="text-blue-900 whitespace-no-wrap">
                            <EyeIcon class="w-6 h-6 text-teal-500" />
                        </button>
                        <button type="button" @click="editModalRol(rol)" class="text-blue-900 whitespace-no-wrap">
                            <PencilSquareIcon class="w-5 h-5 text-yellow-400" />
                        </button>
                        <button type="button" @click="confirmRolsDeletion(rol.id)"
                            class="text-blue-900 whitespace-no-wrap">
                            <TrashIcon class="w-5 h-5 text-red-500" />
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
import Pagination from '@/Components/Pagination.vue';
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import { EyeIcon, PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';

const { rols, showModal, editModalRol, confirmRolsDeletion } = defineProps({
    rols: Object,
    showModal: Function,
    editModalRol: Function,
    confirmRolsDeletion: Function
})
</script>
