<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'employees.external.index'">
        <template #header>
            Empleados Externos
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <TableHeader v-model:employees="employees" :openExternal="openExternal" :formSearch="formSearch" />
            <ExternalTable :employees="employees" :formSearch="formSearch" :cost_line="cost_line"
                :openExternal="openExternal" :confirmUserDeletion="confirmUserDeletion" />
        </div>
        <ExternalForm ref="externalForm" :employees="employees" :costLines="costLines" />
        <ConfirmDeleteModal :confirmingDeletion="confirmingUserDeletion" itemType="empleado" deleteText="Eliminar"
            :deleteFunction="deleteEmployee" @closeModal="closeModal" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { Head } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import axios from 'axios';
import { notify, notifyError } from '@/Components/Notification';
import { Toaster } from 'vue-sonner';
import ExternalTable from './components/ExternalTable.vue';
import TableHeader from './components/TableHeader.vue';
import ExternalForm from './components/ExternalForm.vue';

const confirmingUserDeletion = ref(false);
const employeeToDelete = ref(null);


const props = defineProps({
    employee: Object,
    costLines: Object,
})

const employees = ref({ ...props.employee })
const cost_line = props.costLines.map(item => item.name)
const externalForm = ref(null)

const initialFormSearch = {
    searchQuery: '',
    cost_line: [...cost_line],
}

const formSearch = ref({ ...initialFormSearch })

watch(
    () => [
        formSearch.value.searchQuery,
        formSearch.value.cost_line,
    ],
    () => {
        search();
    },
    { deep: true }
);



const confirmUserDeletion = (employeeId) => {
    employeeToDelete.value = employeeId;
    confirmingUserDeletion.value = true;
};

async function deleteEmployee() {
    let employeeId = employeeToDelete.value;
    let url = route('employees.external.delete', { id: employeeId })
    try {
        await axios.delete(url)
        try {
            updateFrontEnd('delete', employeeId)
        } catch (frontendError) {
            console.error(frontendError);
            notifyError("Ocurrió un error al actualizar la interfaz. Por favor, actualice la página.");
        }

    } catch (error) {
        console.error(error)
    }
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
};

function openExternal(item) {
    externalForm.value.openExternal(item)
}

async function search() {
    let url = route('employees.external.search')
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

function updateFrontEnd(action, itemId) {
    let validations = employees.value.data || employees.value
    if (action === 'delete') {
        let index = validations.findIndex(item => item.id === itemId)
        validations.splice(index, 1)
        closeModal()
        notify("Eliminación exitosa")
    }
}
</script>