<template>

    <Head title="CentroDeCostos" />

    <AuthenticatedLayout :redirectRoute="'document.rrhh.status'">
        <template #header>
            Documentos Grupales
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
                            Archivo
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Fecha de Documento
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Observaciones
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
                            <p class="text-gray-900 whitespace-no-wrap">{{ item.type }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <p class="text-gray-900 whitespace-no-wrap">
                                <button @click="downloadDocument(item.id)" class="flex items-center text-blue-600 hover:underline">
                                    <ArrowDownIcon class="h-4 w-4 ml-1" />
                                </button>
                            </p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ formattedDate(item.date) }}
                            </p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <p class="text-gray-900 whitespace-no-wrap">
                                {{ item.observation }}
                            </p>
                        </td>
                        <td
                            class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <div class="flex justify-center space-x-3">
                                <!-- <button @click="">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button> -->
                                <!-- <button @click="assignUser(item.id, item.clc_employees)">
                                    <UserGroupIcon class="w-6 h-6 text-indigo-700"/>
                                </button> -->
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
        <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between"
        >
            <pagination :links="grupal_documents.links" />
        </div>

        <Modal :show="showCostCenterModal" @close="closeCostCenterModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900 mb-2">
                    {{ form.id ? "Editar" : "Agregar" }} Documento Grupal
                </h2>
                <form @submit.prevent="submitCostCenterModal">
                    <div class="space-y-12">
                        <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                            <div>
                                <InputLabel for="type" class="font-medium leading-6 text-gray-900">
                                    Nombre
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.type" id="type"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">
                                            Selecciona
                                        </option>
                                        <option v-for="op in types">
                                            {{ op }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.type" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="date" class="font-medium leading-6 text-gray-900">
                                    Fecha de Documento
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.date" id="date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.date" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="observation" class="font-medium leading-6 text-gray-900">
                                    Observaciones
                                </InputLabel>
                                <div class="mt-2">
                                    <textarea type="text" v-model="form.observation" id="observation"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.observation" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="archive" class="font-medium leading-6 text-gray-900">
                                    Archivo
                                </InputLabel>
                                <div class="mt-2">
                                    <InputFile type="file" v-model="form.archive" accept=".pdf, .xls, .xlsx"
                                        class="block w-full h-24 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.archive" />
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
            itemType="Documento"
            :deleteFunction="deleteCostCenter" 
            @closeModal="closeCostCenterDestroyModal" 
            :processing="isFetching"
        />


    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { TrashIcon, PencilIcon, UserGroupIcon, ArrowDownIcon } from '@heroicons/vue/24/outline';
import { notify, notifyError } from '@/Components/Notification';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { formattedDate, setAxiosErrors, toFormData } from '@/utils/utils';
import Modal from '@/Components/Modal.vue';
import { ref, watch } from 'vue';
import Pagination from "@/Components/Pagination.vue";
import InputFile from '@/Components/InputFile.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Toaster } from 'vue-sonner';



const { grupal_documents, types } = defineProps({
    grupal_documents: Object,
    types: Array,
})
const dataToRender = ref(grupal_documents.data)


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
const initState = {
    type: '',
    archive: undefined,
    date: '',
    observation: '',
}
const form = useForm({...initState})
const submitCostCenterModal = () => {
    isFetching.value = true
    const formToSend = toFormData(form.data());
    let url = form.id 
        ? route("document.grupal_documents.update", {gd_id: form.id,}) 
        : route("document.grupal_documents.store") 
    axios.post(url,formToSend)
        .then((res)=>{
            if (form.id) {
                const index = dataToRender.value.findIndex((item) => item.id == form.id);
                dataToRender.value[index] = res.data
            } else {dataToRender.value.push(res.data);}
            closeCostCenterModal();
            notify("Documento Guardado");
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
    axios.delete(route("document.grupal_documents.destroy", {gd_id: CostCenterToDelete.value.id}))
        .then((res)=>{
            const index = dataToRender.value.findIndex((item) => item.id == CostCenterToDelete.value.id);
            dataToRender.value.splice(index, 1);
            closeCostCenterDestroyModal();
            notify("Documento Grupal Eliminado");
        })
        .catch(e=>{
            if (e.response?.data?.errors) {setAxiosErrors(e.response.data.errors, form);} 
            else {notifyError("Server Error");}
        })
        .finally(()=>{
            isFetching.value = false;
        });
}

function downloadDocument(id) {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url = route("document.grupal_documents.download", { gd_id: id }) +
        "?" +
        uniqueParam;
    window.location.href = url;
};


</script>
