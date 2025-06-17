<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'management.employees'">
        <template #header>
            Empleados 
        </template>
        <Toaster richColors />
        <EmployeesFilter v-model:form="formSearch" @reentry="reentry" />
        <EmployeesTable v-model:form="formSearch" :employees="employees" :costLine="cost_line"
            :openReentryModal="openReentryModal" :openFiredModal="openFiredModal" />
        <ReentryForm ref="reentryForm" :employees="employees"/>
        <FiredForm ref="firedForm" :employees="employees" />

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import { notifyError } from '@/Components/Notification';
import EmployeesFilter from './components/EmployeesFilter.vue';
import EmployeesTable from './components/EmployeesTable.vue';
import ReentryForm from './components/ReentryForm.vue';
import FiredForm from './components/FiredForm.vue';
import { Toaster } from 'vue-sonner';

const reentryForm = ref(null)
const firedForm = ref(null)

const props = defineProps({
    employee: Object,
    costLine: Object
})

const employees = ref({ ...props.employee })

const cost_line = props.costLine.map(item => item.name)

const initialFormSearch = {
    state: 'Active',
    cost_line: [...cost_line],
    search: ''
}

const formSearch = ref({ ...initialFormSearch })

function reentry() {
    if (formSearch.value.state === 'Active') {
        formSearch.value.state = 'Inactive'
    } else {
        formSearch.value.state = 'Active'
    }
}

watch(
    () => [
        formSearch.value.state,
        formSearch.value.cost_line,
        formSearch.value.search,
    ],
    () => {
        search();
    },
    { deep: true }
);

function openFiredModal(id) {
    firedForm.value.openFiredModal(id)
}

function openReentryModal(id) {
    reentryForm.value.openReentryModal(id)
};

async function search() {
    let url = route('management.employees.search')
    try {
        let response = await axios.post(url, formSearch.value)
        employees.value = response.data
    } catch (error) {
        if (error.response.data) {
            notifyError('Server error', error.response.data)
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

</script>