<template>

    <Head title="CICSA Asignación" />

    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header>
            Asignación
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-between">
                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <!-- <PrimaryButton @click="openAddAssignationModal" type="button">
                        + Agregar
                    </PrimaryButton> -->
                    <a :href="route('assignation.export') + '?' + uniqueParam"
                        class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">Exportar</a>
                </div>

                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <TextInput data-tooltip-target="search_fields" type="text" @input="search($event.target.value)"
                        placeholder="Buscar ..." />
                    <SelectCicsaComponent currentSelect="Asignación" />

                </div>
                <div id="search_fields" role="tooltip"
                    class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                    Nombre,Codigo,CPE
                    <div class="tooltip-arrow" data-popper-arrow></div>
                </div>
            </div>
            <br>
            <div class="overflow-x-auto h-[70vh]">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr
                            class="sticky top-0 z-20 border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre de Proyecto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Asignacion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cliente
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Centro de Costo
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Codigo de Proyecto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                CPE
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Zonas
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Gestor
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Encargado
                            </th>
                            <!-- <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th> -->
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in assignations.data ?? assignations" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.project_name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.assignation_date) }}
                                </p>
                            </td>

                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.customer }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.project.cost_center.name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.project_code }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.cpe }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.zone }} {{ item.zone2 }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.manager }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.user_name }}
                                </p>
                            </td>
                            <!-- <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <div class="flex space-x-3 justify-center">
                                    <button class="text-blue-900" @click="openEditSotModal(item)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-amber-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>
                                </div>
                            </td> -->
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="assignations.data"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="assignation.links" />
            </div>
        </div>

        <!-- <Modal :show="showAddEditModal" @close="closeAddAssignationModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ form.id ? 'Editar Asignación' : 'Nueva Asignación' }}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="">
                            <InputLabel for="manager">Gestor</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.manager" autocomplete="off" id="manager"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.manager" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="assignation_date">Fecha de Asignación</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.assignation_date" autocomplete="off"
                                    id="assignation_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.assignation_date" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel for="project_name">Nombre del Proyecto</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.project_name" autocomplete="off" id="project_name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.project_name" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="customer">Cliente</InputLabel>
                            <div class="mt-2">
                                <select id="customer" v-model="form.customer"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Cliente</option>
                                    <option>CICSA</option>
                                    <option>STL</option>
                                </select>
                                <InputError :message="form.errors.customer" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="cost_center">Centro de Costos</InputLabel>
                            <div class="mt-2">
                                <select id="cost_center" v-model="form.cost_center"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Centro de Costo</option>
                                    <option v-if="form.customer === 'CICSA'">Mantto Pext Claro</option>
                                    <option v-if="form.customer === 'CICSA'">Instalaciones GTD</option>
                                    <option v-if="form.customer === 'CICSA'">Mantto Pext GTD</option>
                                    <option v-if="form.customer === 'CICSA'">Densificacion</option>
                                    <option v-if="form.customer === 'CICSA'">Adicionales</option>
                                    <option v-if="form.customer === 'CICSA'">Instalaciones Claro</option>

                                    <option v-if="form.customer === 'STL'">TSS</option>
                                </select>
                                <InputError :message="form.errors.cost_center" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="project_code">Codigo de Proyecto</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.project_code" autocomplete="off" id="project_code"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.project_code" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="cpe">CPE</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.cpe" autocomplete="off" id="cpe"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.cpe" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="zone">Zona</InputLabel>
                            <div class="mt-2">
                                <select id="zone" v-model="form.zone" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Zona</option>
                                    <option>Arequipa</option>
                                    <option>Moquegua</option>
                                    <option>Tacna</option>
                                    <option>Cuzco</option>
                                    <option>Puno</option>
                                    <option>MDD</option>
                                </select>
                                <InputError :message="form.errors.zone" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="zone2">Zona2 (Opcional)</InputLabel>
                            <div class="mt-2">
                                <select id="zone2" v-model="form.zone2" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">Seleccionar Zona</option>
                                    <option>Arequipa</option>
                                    <option>Moquegua</option>
                                    <option>Tacna</option>
                                    <option>Cuzco</option>
                                    <option>Puno</option>
                                    <option>MDD</option>
                                </select>
                                <InputError :message="form.errors.zone2" />
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeAddAssignationModal"> Cancelar </SecondaryButton>
                        <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal> -->
        <SuccessOperationModal :confirming="confirmAssignation" :title="'Nueva Asignacion creada'"
            :message="'La Asignacion fue creada con éxito'" />
        <SuccessOperationModal :confirming="confirmUpdateAssignation" :title="'Asignacion Actualizada'"
            :message="'La Asignacion fue actualizada'" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectCicsaComponent from '@/Components/SelectCicsaComponent.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import { formattedDate } from '@/utils/utils.js';
import TextInput from '@/Components/TextInput.vue';
import { setAxiosErrors } from "@/utils/utils";

const { assignation, auth, searchCondition } = defineProps({
    assignation: Object,
    auth: Object,
    searchCondition: {
        type: String,
        Required: false
    }
})

const assignations = ref(assignation);
const uniqueParam = ref(`timestamp=${new Date().getTime()}`);

const initialState = {
    id: null,
    user_id: auth.user.id,
    assignation_date: '',
    project_name: '',
    cost_center: '',
    customer: '',
    project_code: '',
    cpe: '',
    zone: '',
    zone2: '',
    manager: '',
    user_name: auth.user.name,
}

const form = useForm(
    { ...initialState }
);

const showAddEditModal = ref(false);
const confirmAssignation = ref(false);

// function openAddAssignationModal() {
//     showAddEditModal.value = true
// }

// function closeAddAssignationModal() {
//     showAddEditModal.value = false
//     form.clearErrors()
//     form.defaults({ ...initialState })
//     form.reset()
// }

// async function submitStore() {
//     let url = route('assignation.storeOrUpdate');
//     try {
//         const response = await axios.put(url, form)
//         updateAssignation(null, response.data)
//         closeAddAssignationModal()
//         confirmAssignation.value = true
//         setTimeout(() => {
//             confirmAssignation.value = false
//         }, 1500)
//     } catch (error) {
//         if (error.response) {
//             if (error.response.data.errors) {
//                 setAxiosErrors(error.response.data.errors, form)
//             } else {
//                 console.error("Server error:", error.response.data)
//             }
//         } else {
//             console.error("Network or other error:", error)
//         }
//     }
// }

const confirmUpdateAssignation = ref(false);

function openEditSotModal(item) {
    form.defaults({ ...item })
    form.reset()
    showAddEditModal.value = true
}

// async function submitUpdate() {
//     let url = route('assignation.storeOrUpdate', { cicsa_assignation_id: form.id })
//     try {
//         const response = await axios.put(url, form)
//         updateAssignation(form.id, response.data)
//         closeAddAssignationModal()
//         confirmUpdateAssignation.value = true
//         setTimeout(() => {
//             confirmUpdateAssignation.value = false
//         }, 1500)
//     } catch (error) {
//         if (error.response) {
//             setAxiosErrors(error.response.data.errors, form)
//         } else {
//             console.error('Error desconocido:', error);
//         }
//     }
// }

// function submit() {
//     form.id ? submitUpdate() : submitStore()
// }

const search = async ($search) => {
    try {
        const response = await axios.post(route('assignation.index'), { searchQuery: $search });
        assignations.value = response.data.assignation;

    } catch (error) {
        console.error('Error searching:', error);
    }
};

// function updateAssignation(cicsa_assignation_id, assignation) {
//     const validations = assignations.value.data || assignations.value;
//     const index = validations.findIndex(item => item.id === cicsa_assignation_id)
//     if (cicsa_assignation_id) {
//         validations[index] = assignation
//     } else {
//         validations.unshift(assignation);
//     }

//     if (validations.length > assignations.value.per_page) {
//         validations.pop();
//     }
// }

if (searchCondition) {
    search(searchCondition)
}

</script>
