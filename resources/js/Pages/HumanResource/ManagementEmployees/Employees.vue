<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'management.employees'">
        <template #header>
            Empleados
        </template>

        <div>
            <EmployeesFilter :userPermission="userPermissions" v-model:form="formSearch" @reentry="reentry" />
            <EmployeesTable v-model:form="formSearch" :employees="employees" :costLine="cost_line"
                :userPermission="userPermissions" :employee_fired_date="employee_fired_date" :confirmFired="confirmFired"/>
        </div>
        <Modal :show="showModalReentry">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Reingreso del Empleado
                </h2>
                <form @submit.prevent="submit">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="mt-2">
                            <InputLabel for="reentry_date">Fecha de
                                Reingreso o
                                Recontratacion:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="reentry_date" v-model="form1.reentry_date" required />
                                <InputError :message="form.errors.reentry_date" />
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeReentryModal"> Cancel </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }"> Guardar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
        <Modal :show="showModalFired">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Baja del Empleado
                </h2>
                <form @submit.prevent="submit">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="mt-2">
                            <InputLabel for="fired_date">Fecha de Baja:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="fired_date" v-model="form.fired_date" />
                                <InputError :message="form.errors.fired_date" />
                            </div>
                        </div>
                        <!-- <div class="mt-6">
                            <InputLabel for="days_taken">Dias Tomados:
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="days_taken" v-model="form.days_taken" />
                                <InputError :message="form.errors.days_taken" />
                            </div>
                        </div> -->
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeFiredModal"> Cancel </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                                Guardar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { notifyError } from '@/Components/Notification';
import EmployeesFilter from '@/Layouts/Employees/EmployeesFilter.vue';
import EmployeesTable from '@/Layouts/Employees/EmployeesTable.vue';

// const confirmingUserDeletion = ref(false);
// const deleteButtonText = 'Eliminar';
// const employeeToDelete = ref(null);
const employeeToFired = ref(null);
const employeeReentry = ref(null);
const showModalFired = ref(false);
const showModalReentry = ref(false);

const props = defineProps({
    employee: Object,
    boolean: Boolean,
    userPermissions: Array,
    costLine: Object
})

const employees = ref(props.employee)

// const hasPermission = (permission) => {
//     return props.userPermissions.includes(permission);
// }

const form = useForm({
    fired_date: '',
    days_taken: '',
    state: 'Inactive'
})

const form1 = useForm({
    reentry_date: '',
})

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

function submit() {
    let url = employeeReentry.value ? route('management.employees.reentry', { id: employeeReentry.value }) : route('management.employees.fired', employeeToFired.value)
    let formData = employeeReentry.value ? form1 : form
    router.put(url, formData, {
        onSuccess: () => {
            if (employeeReentry.value) {
                closeReentryModal();
            } else {
                closeFiredModal();
            }
            closeReentryModal();
            router.visit(route('management.employees'));
        }
    })
}

// const confirmUserDeletion = (employeeId) => {
//     employeeToDelete.value = employeeId;
//     confirmingUserDeletion.value = true;
// };

const confirmFired = (firedId) => {
    employeeToFired.value = firedId
    showModalFired.value = true
}

const closeFiredModal = () => {
    showModalFired.value = false
}

// const deleteEmployee = () => {
//     const employeeId = employeeToDelete.value;
//     if (employeeId) {
//         router.delete(route('management.employees.destroy', { id: employeeId }), {
//             onSuccess: () => closeModal()
//         });
//     }
// };

// const closeModal = () => {
//     confirmingUserDeletion.value = false;
// };



const employee_fired_date = ($id) => {
    employeeReentry.value = $id
    showModalReentry.value = true
};

const closeReentryModal = () => {
    showModalReentry.value = false;
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