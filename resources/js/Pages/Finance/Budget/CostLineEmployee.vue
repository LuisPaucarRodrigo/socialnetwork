<template>

    <Head title="ColaboradoresLineaDeGastos" />

    <AuthenticatedLayout :redirectRoute="'selectproject.index'">
        <template #header>
            {{ cost_line.name }} - Colaboradores
        </template>
        <Toaster richColors />
        <div class="mt-3 flex items-center justify-between gap-x-6">
            <button @click="openCostCenterModal" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                + Agregar
            </button>
        </div>

        <div class="overflow-x-auto rounded-lg shadow mt-2">
            <table class="w-full whitespace-no-wrap ">
                <thead>
                    <tr
                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Item
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            DNI
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Nombre
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Teléfono
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Correo personal
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Correo empresa
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item, i in dataToRender" :key="i" class="text-gray-700 hover:opacity-70">
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <p class="text-gray-900 whitespace-no-wrap">{{ i+1 }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <p class="text-gray-900 whitespace-no-wrap">{{ item.dni }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <p class="text-gray-900 whitespace-no-wrap">{{ item.name }} {{ item.lastname }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <p class="text-gray-900 whitespace-no-wrap">{{ item.phone1 }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <p class="text-gray-900 whitespace-no-wrap">{{ item.email }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <p class="text-gray-900 whitespace-no-wrap">{{ item.email_company }}</p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <div class="flex justify-center space-x-3">
                                <button type="button" @click="deleteEmployee(item)"
                                    class="text-red-600 whitespace-no-wrap">
                                    <DeleteIcon/>
                                </button>
                            </div>
                        </td>
                    </tr>
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
                                <InputLabel for="employee_id" class="font-medium leading-6 text-gray-900">
                                    Colaborador
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.employee_id" id="employee_id"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" >
                                        <option value="" disabled>Seleccione un colaborador</option>
                                        <option v-for="item,i in selectEmployees" :key="i" :value="item.id">
                                            {{ item.name }} {{ item.lastname }}
                                        </option>
                                        </select>
                                    <InputError :message="form.errors.employee_id" />
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

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { notify, notifyError } from '@/Components/Notification';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { setAxiosErrors } from '@/utils/utils';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Toaster } from 'vue-sonner';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';

const { currentEmployees, employees, cost_line } = defineProps({
    currentEmployees: Array,
    employees: Array,
    cost_line: Object,
})
const dataToRender = ref(currentEmployees)
const selectEmployees = ref(employees)

console.log(employees)

//Create and Update
const isFetching = ref(false)
const showCostCenterModal = ref(false)
const openCostCenterModal = (item=null) => {
    showCostCenterModal.value = true
    if (item){form.defaults({...item}); form.reset()}
}
const closeCostCenterModal = () => {
    showCostCenterModal.value = false;
    closeNextActions()
}
const closeNextActions = () =>{
    form.clearErrors()
    form.defaults({...initState})
    form.reset()
}
const initState = {employee_id: '', cost_line_id: cost_line.id}
const form = useForm({...initState})
const submitCostCenterModal = () => {
    isFetching.value = true
    axios.post(route("finance.cost_line.employee.store"),form.data())
        .then((res)=>{
           dataToRender.value.push(res.data)
            closeNextActions();
            notify("Colaborador añadido a la Línea de Gasto");
            reloadSelectEmployee()
        })
        .catch(e=>{
            if (e.response?.data?.errors) {setAxiosErrors(e.response.data.errors, form);} 
            else {notifyError("Server Error");}
        })
        .finally(()=>{
            isFetching.value = false;
        });
}



//Delete
const deleteEmployee = (item) => {
    isFetching.value = true
    axios.delete(route("finance.cost_line.employee.destroy", {emp_id: item.id}))
        .then((res)=>{
            const index = dataToRender.value.findIndex((item) => item.id == item.id);
            dataToRender.value.splice(index, 1);
            notify("Colaborador Desasignado de la Línea de Gasto");
            reloadSelectEmployee()
        })
        .catch(e=>{
            notifyError("Server Error");
        })
        .finally(()=>{
            isFetching.value = false;
        });
}

//reload select
const reloadSelectEmployee = () => {
    axios.get(route("finance.cost_line.employee.search"))
        .then(res=>{
            selectEmployees.value = res.data
        })
}



</script>
