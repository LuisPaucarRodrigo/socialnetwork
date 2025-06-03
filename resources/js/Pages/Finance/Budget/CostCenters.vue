<template>

    <Head title="CentroDeCostos" />

    <AuthenticatedLayout :redirectRoute="'selectproject.index'">
        <template #header>
            {{ cost_line.name }} - Centro de Costos
        </template>
        <Toaster richColors />
        <div class="mt-3 flex items-center justify-between gap-x-6">
            <button @click="openCostCenterModal" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                + Agregar
            </button>
        </div>

        <div class="overflow-x-auto rounded-lg shadow mt-2">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Item
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Nombre
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Porcentaje
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <template v-for="item, i in dataToRender" :key="item.id" class="text-gray-700">
                        <tr class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                                <p class="text-gray-900 whitespace-no-wrap">{{ i + 1 }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.percentage }}%</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                                <div class="flex justify-end space-x-3">
                                    <button @click="assignUser(item.id, item.clc_employees)">
                                        <GroupIcon />
                                    </button>
                                    <button type="button" @click="openCostCenterModal(item)">
                                        <EditIcon />
                                    </button>
                                    <button type="button" @click="openCostCenterDestroyModal(item)">
                                        <DeleteIcon />
                                    </button>
                                </div>
                            </td>
                            <td class="border-b border-gray-200 px-2 py-1 text-sm">
                                <button type="button" @click="toggleDetails(item.id, item.clc_employees)"
                                    :class="`flex items-center text-blue-900 rounded-full hover:bg-blue-300`">
                                    <svg v-if="row !== item.id" xmlns="http://www.w3.org/2000/svg" fill="none"
                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" :class="`w-4 h-4`">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                    </svg>
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                    </svg>
                                </button>
                            </td>
                        </tr>
                        <template v-if="row == item.id">
                            <tr class="bg-white">
                                <td colspan="5" class="py-1 px-2">
                                    <table class="w-full">
                                        <thead>
                                            <tr
                                                class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    Item
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    DNI
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    Nombre
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    Teléfono
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    Correo personal
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[9px] font-semibold uppercase tracking-wider text-gray-600">
                                                    Correo empresa
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="text-gray-700" v-for="(
item, i
                                                    ) in employeesFounded" :key="i">
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                    {{ i + 1 }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                    {{ item?.employee?.dni }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                    {{ item?.employee?.name }} {{ item?.employee?.lastname }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                    {{ item?.employee?.phone }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                    {{ item?.employee?.email }}
                                                </td>
                                                <td
                                                    class="border-b border-gray-200 bg-white px-1 py-1 text-center text-[12px]">
                                                    {{ item?.employee?.email_company }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                        </template>

                    </template>
                </tbody>
            </table>
        </div>

        <Modal :show="showCostCenterModal" @close="closeCostCenterModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900 mb-2">
                    {{ form.id ? "Editar" : "Agregar" }} Centro de Costo
                </h2>
                <form @submit.prevent="submitCostCenterModal">
                    <div class="space-y-12">
                        <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                            <div>
                                <InputLabel for="name" class="font-medium leading-6 text-gray-900">
                                    Nombre
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.name" id="name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.name" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="percentage" class="font-medium leading-6 text-gray-900">
                                    Porcentaje
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="number" v-model="form.percentage" id="percentage" max="100" min="1"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.percentage" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeCostCenterModal">
                                Cancelar
                            </SecondaryButton>
                            <button type="submit" :disabled="isFetching" :class="{ 'opacity-25': isFetching }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>




        <Modal :show="assignUserModal">
            <form class="p-6" @submit.prevent="submitAssignUser">
                <h2 class="text-lg font-medium text-gray-900">
                    Agregar Colaboradores
                </h2>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                    <div class="sm:col-span-3">
                        <InputLabel for="users" class="font-medium leading-6 text-gray-900">Colaboradores</InputLabel>
                        <div class="mt-2">
                            <select multiple v-model="assignUserForm.employees" id="users" size="20"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option v-for="item in selectEmployees" :key="item.id" :value="item.id">
                                    {{ item.name }} {{ item.lastname }}
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <InputLabel for="users" class="font-medium leading-6 text-gray-900">
                            Colaboradores en Centro de costos
                        </InputLabel>
                        <div class="mt-2">
                            <p v-for="item in assignUserForm.employeesArray" :key="user" class="text-sm">
                                - {{ item?.name }} {{ item?.lastname }}
                            </p>
                        </div>
                    </div>
                </div>
                <InputError :message="assignUserForm.errors.employees" class="mt-2" />
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="closeAssignUser">Cerrar</SecondaryButton>
                    <button type="submit" :disabled="isFetching" :class="{ 'opacity-25': isFetching }"
                        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                        Guardar
                    </button>
                </div>
            </form>
        </Modal>

        <ConfirmDeleteModal :confirmingDeletion="confirmCostCenterDestroy" itemType="Centro de Costos"
            :deleteFunction="deleteCostCenter" @closeModal="closeCostCenterDestroyModal" :processing="isFetching" />


    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { notify, notifyError, notifyWarning } from '@/Components/Notification';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { setAxiosErrors } from '@/utils/utils';
import Modal from '@/Components/Modal.vue';
import { ref, watch } from 'vue';
import { Toaster } from 'vue-sonner';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import GroupIcon from '@/Components/Icons/GroupIcon.vue';


const { costCenters, cost_line, employees } = defineProps({
    costCenters: Array,
    cost_line: Object,
    employees: Array,
})
const dataToRender = ref(costCenters)
const selectEmployees = ref(employees)
//Create and Update
const isFetching = ref(false)
const showCostCenterModal = ref(false)
const openCostCenterModal = (item = null) => {
    showCostCenterModal.value = true
    if (item) { form.defaults({ ...item }); form.reset() }
}
const closeCostCenterModal = () => {
    showCostCenterModal.value = false;
    form.clearErrors()
    form.defaults({ ...initState })
    form.reset()
}
const initState = { name: '', percentage: '', id: '', cost_line_id: cost_line.id }
const form = useForm({ ...initState })
const submitCostCenterModal = () => {
    isFetching.value = true
    axios.post(route("finance.cost_center.store", { cc_id: form.id, }), form.data())
        .then((res) => {
            console.log(res)
            if (form.id) {
                const index = dataToRender.value.findIndex((item) => item.id == form.id);
                dataToRender.value[index] = res.data
            } else { dataToRender.value.push(res.data); }
            closeCostCenterModal();
            notify("Centro de Costo Guardado");
        })
        .catch(e => {
            if (e.response?.data?.errors) { setAxiosErrors(e.response.data.errors, form); }
            else { notifyError("Server Error"); }
        })
        .finally(() => {
            isFetching.value = false;
        });
}


//Delete
const confirmCostCenterDestroy = ref(false)
const CostCenterToDelete = ref(null)
const openCostCenterDestroyModal = (item) => {
    CostCenterToDelete.value = item
    confirmCostCenterDestroy.value = true
}
const closeCostCenterDestroyModal = () => {
    CostCenterToDelete.value = null
    confirmCostCenterDestroy.value = false
}
const deleteCostCenter = () => {
    isFetching.value = true
    axios.delete(route("finance.cost_center.destroy", { cc_id: CostCenterToDelete.value.id }))
        .then((res) => {
            const index = dataToRender.value.findIndex((item) => item.id == CostCenterToDelete.value.id);
            dataToRender.value.splice(index, 1);
            closeCostCenterDestroyModal();
            notify("Centro de Costo Eliminado");
        })
        .catch(e => {
            if (e.response?.data?.errors) { setAxiosErrors(e.response.data.errors, form); }
            else { notifyError("Server Error"); }
        })
        .finally(() => {
            isFetching.value = false;
        });
}

//assign users

const initUsersFormState = { employees: [], employeesArray: [], cost_center_id: '' }
const assignUserModal = ref(false)
const assignUserForm = useForm({ ...initUsersFormState })
const assignUser = (id, clce) => {
    let usersIds = clce.map(item => item.employee.id)
    assignUserModal.value = true;
    assignUserForm.employees = [...usersIds];
    assignUserForm.cost_center_id = id
}
const closeAssignUser = () => {
    assignUserForm.reset();
    assignUserModal.value = false;
}

const submitAssignUser = () => {
    axios.post(route("finance.cost_center.employee.store"), { ...assignUserForm.data() })
        .then((res) => {
            console.log(res)
            const index = dataToRender.value.findIndex((item) => item.id == assignUserForm.cost_center_id);
            dataToRender.value[index] = res.data
            closeAssignUser();
            notify("Asignación de colaboradores al Centro de Costos");
        })
        .catch(e => {
            if (e.response?.data?.errors) { setAxiosErrors(e.response.data.errors, form); }
            else { notifyError("Server Error"); }
        })
        .finally(() => {
            isFetching.value = false;
        });
}

watch(() => assignUserForm.employees, (newVal) => {
    assignUserForm.employeesArray = newVal.map(id => {
        return selectEmployees.value.find(item => item.id == id)
    })
})
console.log(dataToRender.value)

const employeesFounded = ref([])

//toogle
const row = ref(0);
const toggleDetails = async (id, clc_employees) => {
    if (row.value == 0 || row.value != id) handleExpansible(clc_employees);
    if (row.value === employeesFounded.value[0]?.cost_center_id) {
        row.value = 0
    } else {
        row.value = 0
        if (employeesFounded.value?.length > 0) {
            row.value = employeesFounded.value[0]?.cost_center_id;
        }
    }
};

const handleExpansible = (clc_employees) => {
    employeesFounded.value = clc_employees;
    notifyWarning(`Trabajadores Encontrados ${clc_employees.length}`);
};




</script>
