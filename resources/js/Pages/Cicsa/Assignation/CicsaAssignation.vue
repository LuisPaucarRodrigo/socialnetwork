<template>

    <Head title="CICSA Asignación" />

    <AuthenticatedLayout :redirectRoute="{ route: 'cicsa.index', params: { type } }">
        <template #header>
            {{ type == 1 ? 'Pint' : 'Pext' }} - Asignación
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <TableHeader :type="type" v-model:assignations="assignations" :searchCondition="searchCondition" />
            <br>
            <AssignationTable :assignations="assignations" :updateAssignation="updateAssignation" />
        </div>
        <SuspenseWrapper :when="showAssignationTable">
            <template #component>
                <AssignationForm ref="assignationForm" :assignations="assignations" :type="type" />
            </template>
        </SuspenseWrapper>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { defineAsyncComponent, ref } from 'vue';
import TableHeader from './components/TableHeader.vue';
import AssignationTable from './components/AssignationTable.vue';
import { Toaster } from 'vue-sonner';
import SuspenseWrapper from '@/Components/SuspenseWrapper.vue';
import { useLazyRefInvoker } from '@/utils/useLazyRefInvoker';

const AssignationForm = defineAsyncComponent(() => import('./components/AssignationForm.vue'));

const { assignation, auth, searchCondition, type } = defineProps({
    assignation: Object,
    auth: Object,
    searchCondition: {
        type: String,
        required: false
    },
    type: String,
})

const showAssignationTable = ref(false)
const assignations = ref({ ...assignation });
const assignationForm = ref(null)

const { invokeWhenReady: invokeAssignationForm } = useLazyRefInvoker(assignationForm, showAssignationTable);


function updateAssignation(item) {
    invokeAssignationForm('updateAssignation', item)
}
</script>
