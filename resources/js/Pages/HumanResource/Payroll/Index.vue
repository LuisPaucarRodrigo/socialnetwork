<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="'payroll.index'">
        <template #header>
            Nomina
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 flex items-center justify-between gap-x-6">
                <div class="sm:flex sm:items-center sm:space-x-4">
                    <button v-if="hasPermission('HumanResourceManager')" @click="createOrEditModal" type="button"
                        class="whitespace-nowrap inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                        + Agregar
                    </button>
                </div>
                <!-- <input type="text" @input="search($event.target.value)" placeholder="Buscar..."> -->
            </div>
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-4 xl:grid-cols-6 gap-3">
                <div v-for="item in payrolls.data || payrolls" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <p class="text-sm mb-3">
                        Mes: {{ item.month }}
                    </p>
                    <p class="text-sm mb-3">
                        Monto Total: S/ {{ Number(item.total_amount).toFixed(2) }}
                    </p>
                    <Link :href="route('spreadsheets.index', { payroll_id: item.id })"
                        class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                    Nomina
                    </Link>
                </div>
            </div>
            <br>
            <div v-if="payrolls.data"
                class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="payrolls.links" />
            </div>
        </div>

        <Modal :show="showModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Crear Nomina
                </h2>
                <form @submit.prevent="submit">
                    <div class="grid sm:grid-cols-3 gap-6 pb-12">
                        <div class="col-span-1">
                            <InputLabel for="month" class="font-medium leading-6 text-gray-900">
                                Mes de Nomina
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="month" v-model="form.month" id="month" />
                                <InputError :message="form.errors.month" />
                            </div>
                        </div>
                        <div class="col-span-3">
                            <InputLabel for="month" class="font-medium leading-6 text-gray-900">
                                Sistema de Pensiones
                            </InputLabel>
                            <p class="mt-1 text-sm leading-6 text-gray-600">
                                Completa los valores para cada sistema de pensión.
                            </p>
                            <div class="grid sm:grid-cols-3 gap-4 mt-4">
                                <div v-for="(pension, index) in form.pension_system" :key="index">
                                    <InputLabel :for="'type-' + index">
                                        {{ pension.type }}
                                    </InputLabel>
                                    <div class="mt-2">
                                        <TextInput type="number" step="0.0001"
                                            v-model="form.pension_system[index].values" :id="'values-' + index"
                                            placeholder="Valor" />
                                        <InputError :message="form.errors['pension_system.' + index + '.values']" />
                                        <TextInput type="number" step="0.0001"
                                            v-model="form.pension_system[index].values_seg" :id="'values-seg-' + index"
                                            placeholder="Valor Seg." class="mt-2" />
                                        <InputError :message="form.errors['pension_system.' + index + '.values_seg']" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-3">
                            <p class="text-red-600">
                                Aviso Importante:<br>
                                Los Datos de los Empleados y el Sistema de Pensiones no se podran actualizar despues de crear la nomina.
                                
                            </p>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="createOrEditModal">
                            Cancelar
                        </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            Guardar
                        </PrimaryButton>
                    </div>

                </form>
            </div>
        </Modal>
        <!-- <ConfirmDeleteModal :confirmingDeletion="confirmingProjectDeletion" itemType="proyecto"
            :deleteFunction="delete_project" @closeModal="closeCreateOrEditModal" /> -->
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import Pagination from '@/Components/Pagination.vue'
import Dropdown from '@/Components/Dropdown.vue';
import axios from 'axios';
import { ref } from 'vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import { QueueListIcon, TrashIcon } from '@heroicons/vue/24/outline';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { setAxiosErrors } from '@/utils/utils';
import { stringify } from 'postcss';

const { payroll, userPermissions } = defineProps({
    payroll: Object,
    userPermissions: Array
})

const showModal = ref(false)
const payrolls = ref(payroll);

const initialState = {
    id: "",
    month: "",
    state: 0,
    pension_system: [
        { type: "Habitad", values: null, values_seg: "" },
        { type: "Integra", values: "", values_seg: "" },
        { type: "Prima", values: "", values_seg: "" },
        { type: "Profuturo", values: "", values_seg: "" },
        { type: "Habitadmx", values: "", values_seg: "" },
        { type: "Integramx", values: "", values_seg: "" },
        { type: "Profuturomx", values: "", values_seg: "" },
        { type: "Primamx", values: "", values_seg: "" },
        { type: "ONP", values: "", values_seg: "" },
    ],
}

const form = useForm({ ...initialState })

const hasPermission = (permission) => {
    return userPermissions.includes(permission);
}


// const confirmingProjectDeletion = ref(false);
// const projectToDelete = ref('');

// function editProject(pext) {
//     Object.assign(form, pext);
//     createOrEditModal()
// }

// const delete_project = () => {
//     const projectId = projectToDelete.value;
//     router.delete(route('projectmanagement.delete', { project_id: projectId }), {
//         onSuccess: () => {
//             createOrEditModal()
//             router.visit(route('projectmanagement.index'))
//         }
//     });
// }

// const confirmProjectDeletion = (employeeId) => {
//     projectToDelete.value = employeeId;
//     confirmingProjectDeletion.value = true;
// };

const createOrEditModal = () => {
    if (showModal.value) {
        form.clearErrors()
        form.defaults({ ...initialState })
        form.reset()
    }
    showModal.value = !showModal.value
};

// const search = async ($search) => {
//     try {
//         const response = await axios.post(route('projectmanagement.pext.index'), { searchQuery: $search });
//         projects.value = response.data;
//     } catch (error) {
//         console.error('Error searching:', error);
//     }
// };

async function submit() {
    try {
        const url = route('payroll.store')
        const response = await axios.post(url, form)
        updatePayroll(response.data, 'create')
    } catch (error) {
        console.log(error)
        if (error.response) {
            setAxiosErrors(error.response.data.errors, form)
        } else {
            console.error(error)
        }
    }
}

function updatePayroll(payroll, action) {
    const validations = payrolls.value.data || payrolls.value
    // const index = action === "create" ? null : validations.findIndex(item => item.id === payroll.id)
    if (action === "create") {
        validations.unshift(payroll);
        createOrEditModal()
    }

    if (validations.length > payrolls.value.per_page) {
        validations.pop();
    }
}
</script>
