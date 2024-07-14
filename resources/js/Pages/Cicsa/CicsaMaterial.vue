<template>

    <Head title="CICSA Material" />

    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header>
            Materiales
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-end">
                <SelectCicsaComponent currentSelect="Materiales" />
            </div>
            <br>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Proyecto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Recojo
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Guia
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Lista de Materiales Recibidos
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Lista de Materiales de Factibilidad
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Encargado
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in materials.data" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.project_name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.cicsa_materials?.pick_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_materials?.guide_number }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_materials?.received_materials }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p v-for="material in item.cicsa_feasibility?.cicsa_feasibility_materials" class="text-gray-900 text-center">
                                   - Nombre: {{ material.name }}, Cantidad: {{ material.quantity }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_materials?.user_name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button class="text-blue-900" @click="openEditSotModal(item.id,item)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-amber-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="materials.links" />
            </div>
        </div>

        <Modal :show="showAddEditModal" @close="closeAddMaterialModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{form.id ? 'Editar Material' : 'Nueva Material'}}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <InputLabel for="pick_date">Fecha de Recojo</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.pick_date" autocomplete="off" id="pick_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.pick_date" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="guide_number">Numero de Guia</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.guide_number" autocomplete="off" id="guide_number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.guide_number" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="received_materials">Lista de Materiales Recibidos</InputLabel>
                            <div class="mt-2">
                                <textarea type="text" v-model="form.received_materials" autocomplete="off" id="received_materials"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                <InputError :message="form.errors.received_materials" />
                            </div>
                        </div>
                        <div v-if="material_feasibility" class="sm:col-span-2">
                            <InputLabel for="received_materials">Lista de Materiales de Factibilidad</InputLabel>
                            <div class="mt-2">
                                <p v-for="material in material_feasibility" class="text-gray-900 text-center">
                                   - Nombre: {{ material.name }}, Cantidad: {{ material.quantity }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeAddMaterialModal"> Cancelar </SecondaryButton>
                        <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
        <SuccessOperationModal :confirming="confirmMaterial" :title="'Nueva Material creada'"
            :message="'La Material fue creada con éxito'" />
        <SuccessOperationModal :confirming="confirmUpdateMaterial" :title="'Material Actualizada'"
            :message="'La Material fue actualizada'" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectCicsaComponent from '@/Components/SelectCicsaComponent.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import {formattedDate} from '@/utils/utils.js';

const { materials, auth } = defineProps({
    materials: Object,
    auth: Object
})

const initialState = {
    user_id: auth.user.id,
    pick_date: '',
    guide_number: '',
    received_materials: '',
    user_name: auth.user.name,
}

const form = useForm(
    { ...initialState }
);

const showAddEditModal = ref(false);
const confirmMaterial = ref(false);
const cicsa_assignation_id = ref(null);
const material_feasibility = ref(null)

function closeAddMaterialModal() {
    showAddEditModal.value = false
    form.defaults({...initialState})
    form.reset()
}

const confirmUpdateMaterial = ref(false);

function openEditSotModal (id, item) {
    material_feasibility.value = item.cicsa_feasibility?.cicsa_feasibility_materials
    cicsa_assignation_id.value = id;
    form.defaults({...item?.cicsa_materials})
    form.reset()
    showAddEditModal.value = true
}


function submit() {
    let url = route('material.storeOrUpdate', {cicsa_assignation_id:cicsa_assignation_id.value})
    form.put(url, {
        onSuccess: () => {
            closeAddMaterialModal()
            confirmUpdateMaterial.value = true
            setTimeout(() => {
                confirmUpdateMaterial.value = false
            }, 1500)
        },
        onError: (e) => {
            console.error(e)
        }
    })
}

</script>
