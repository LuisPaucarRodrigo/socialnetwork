<template>

    <Head title="Clientes" />

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
                    <tr v-for="item, i in dataToRender" :key="i" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <p class="text-gray-900 whitespace-no-wrap">{{ i+1 }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <p class="text-gray-900 whitespace-no-wrap">{{ item.name }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <p class="text-gray-900 whitespace-no-wrap">{{ item.percentage }}%</p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <div class="flex justify-center space-x-3">
                                <button @click="">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                                <button @click="">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                                <button type="button" @click="openCostCenterModal(item)"
                                    class="text-yellow-600 whitespace-no-wrap">
                                    <PencilIcon class="h-5 w-5 ml-1" />
                                </button>
                                <button type="button" @click="openCostCenterDestroyModal(item)"
                                    class="text-red-600 whitespace-no-wrap">
                                    <TrashIcon class="h-5 w-5 ml-1" />
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

        <ConfirmDeleteModal 
            :confirmingDeletion="confirmCostCenterDestroy" 
            itemType="Centro de Costos"
            :deleteFunction="deleteCostCenter" 
            @closeModal="closeCostCenterDestroyModal" 
            :processing="isFetching"
        />


    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/InputFile.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { TrashIcon, PencilIcon } from '@heroicons/vue/24/outline';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { setAxiosErrors } from '@/utils/utils';
import { notify, notifyError } from '@/Components/Notification';


const { costCenters, cost_line } = defineProps({
    costCenters: Array,
    cost_line: Object,
})
const dataToRender = ref(costCenters)



//Create and Update
const isFetching = ref(false)
const showCostCenterModal = ref(false)
const openCostCenterModal = (item=null) => {
    showCostCenterModal.value = true
    if (item){form.defaults({...item}); form.reset()}
}
const closeCostCenterModal = () => {
    showCostCenterModal.value = false;
    form.clearErrors()
    form.defaults({...initState})
    form.reset()
}
const initState = {name:'', percentage: '', id:'', cost_line_id: cost_line.id}
const form = useForm({...initState})
const submitCostCenterModal = () => {
    isFetching.value = true
    axios.post(route("finance.cost_center.store", {cc_id: form.id,}),form.data())
        .then((res)=>{
            if (form.id) {
                const index = dataToRender.value.findIndex((item) => item.id == form.id);
                dataToRender.value[index] = res.data
            } else {dataToRender.value.push(res.data);}
            closeCostCenterModal();
            notify("Centro de Costo Guardado");
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
    axios.delete(route("finance.cost_center.destroy", {cc_id: CostCenterToDelete.value.id}))
        .then((res)=>{
            const index = dataToRender.value.findIndex((item) => item.id == CostCenterToDelete.value.id);
            dataToRender.value.splice(index, 1);
            closeCostCenterDestroyModal();
            notify("Centro de Costo Eliminado");
        })
        .catch(e=>{
            if (e.response?.data?.errors) {setAxiosErrors(e.response.data.errors, form);} 
            else {notifyError("Server Error");}
        })
        .finally(()=>{
            isFetching.value = false;
        });
}



</script>
