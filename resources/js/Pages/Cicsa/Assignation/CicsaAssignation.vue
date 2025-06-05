<template>

    <Head title="CICSA Asignación" />

    <AuthenticatedLayout :redirectRoute="{ route: 'cicsa.index', params: { type } }">
        <template #header>
            {{ type == 1 ? 'Pint' : 'Pext' }} - Asignación
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <TableHeader :type="type" v-model:assignations="assignations" />
            <br>
            <AssignationTable :assignations="assignations" :updateAssignation="updateAssignation" />
        </div>
        <AssignationForm ref="assignationForm" :assignations="assignations" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import TableHeader from './components/TableHeader.vue';
import AssignationTable from './components/AssignationTable.vue';
import AssignationForm from './components/AssignationForm.vue';
import { Toaster } from 'vue-sonner';

const { assignation, auth, searchCondition, type } = defineProps({
    assignation: Object,
    auth: Object,
    searchCondition: {
        type: String,
        Required: false
    },
    type: String
})


const assignations = ref({ ...assignation });
const assignationForm = ref(null)
function updateAssignation(item) {
    assignationForm.value.updateAssignation(item)
}

// if (searchCondition) {
//     search(searchCondition)
// }

</script>
