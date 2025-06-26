<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'management.employees'">
        <template #header>
            Empleados
        </template>
        <Toaster richColors />
        <EmployeesFilter v-model:form="formSearch" @reentry="reentry" />
        <EmployeesTable v-model:form="formSearch" :employees="employees" :costLine="cost_line"
            :openReentryModal="openReentryModal" :openFiredModal="openFiredModal" :openFotoCheck="openFotoCheck" />
        <ReentryForm v-if="showReentryForm" ref="reentryForm" :employees="employees" />
        <FiredForm v-if="showFiredForm" ref="firedForm" :employees="employees" />
        <FotoCheck v-if="showFotoCheck" ref="fotoCheck" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch, defineAsyncComponent } from 'vue';
import { notifyError } from '@/Components/Notification';
import EmployeesFilter from './components/EmployeesFilter.vue';
import EmployeesTable from './components/EmployeesTable.vue';
import { Toaster } from 'vue-sonner';

const ReentryForm = defineAsyncComponent(() => import('./components/ReentryForm.vue'));
const FiredForm = defineAsyncComponent(() => import('./components/FiredForm.vue'));
const FotoCheck = defineAsyncComponent(() => import('./components/FotoCheck.vue'));

const showReentryForm = ref(false)
const reentryForm = ref(null)
const showFiredForm = ref(false)
const firedForm = ref(null)
const showFotoCheck = ref(false)
const fotoCheck = ref(null)

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
    showFiredForm.value = true
    if (firedForm.value) {
        firedForm.value.openFiredModal(id);
        return;
    }
    const stop = watch(
        () => firedForm.value,
        (instance) => {
            if (instance) {
                instance.openFiredModal(id);
                stop();
            }
        }
    );
}

function openReentryModal(id) {
    showReentryForm.value = true
    if (reentryForm.value) {
        reentryForm.value.openReentryModal(id);
        return;
    }

    const stop = watch(
        () => reentryForm.value,
        (instance) => {
            if (instance) {
                instance.openReentryModal(id);
                stop();
            }
        }
    );
};

function openFotoCheck(item) {
    showFotoCheck.value = true
    if (fotoCheck.value) {
        fotoCheck.value.openFotoCheck(item);
        return;
    }
    const stop = watch(
        () => fotoCheck.value,
        (instance) => {
            if (instance) {
                instance.openFotoCheck(item);
                stop();
            }
        }
    );
}

// function openFotoCheck(item) {
//     showFotoCheck.value = true
//     nextTick(() => {
//         formCar.value.openFotoCheck(item)
//     });
// }

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