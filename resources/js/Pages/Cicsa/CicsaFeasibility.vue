<template>

    <Head title="CICSA Factibilidad" />

    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header>
            Factibilidad PINT y PEXT
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-end">
                <SelectCicsaComponent currentSelect="Factibilidad PINT y PEXT" />
            </div>
            <br>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre de Proyecto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Factibilidad
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Informe
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
                        <tr v-for="item in feasibility.data" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.project_name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.cicsa_feasibility?.feasibility_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_feasibility?.report }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_feasibility?.user_name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button class="text-blue-900"
                                        @click="openEditFeasibilityModal(item.id, item.cicsa_feasibility)">
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
                <pagination :links="feasibility.links" />
            </div>
        </div>

        <Modal :show="showAddEditModal" @close="closeAddFeasibilityModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ form.id ? 'Editar Factibilidad' : 'Nueva Factibilidad' }}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <InputLabel for="feasibility_date">Fecha de Factibilidad</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.feasibility_date" autocomplete="off"
                                    id="feasibility_date" />
                                <InputError :message="form.errors.feasibility_date" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="report">Informe</InputLabel>
                            <div class="mt-2">
                                <select id="report" v-model="form.report" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>Proceso</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.report" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <br>
                            <div class="flex gap-2 items-center">
                                <h2 class="text-base font-bold leading-6 text-gray-900 ">
                                    Añadir Materiales
                                </h2>
                                <button @click="modalFeasibility" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="indigo" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                    </svg>
                                </button>
                            </div>
                            <br>
                        </div>
                        <div class="sm:col-span-2">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr
                                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Nombre
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Unidad
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Cantidad
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in form.cicsa_feasibility_materials" :key="item.id"
                                        class="text-gray-700">
                                        <td class="w-1/3 border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 text-center">
                                                {{ item.name }}
                                            </p>
                                        </td>
                                        <td class="w-1/3 border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 text-center">
                                                {{ item.unit }}
                                            </p>
                                        </td>
                                        <td class="w-1/3 border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <TextInput class="text-center" type="number" min="0"
                                                @change="modifyQuantity(item.id, $event)" :value="item.quantity" />
                                            <!-- <p class="text-gray-900 text-center">
                                                {{ item.quantity }}
                                            </p> -->
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <button v-if="!item.id" type="button" @click="delete_material(item.name)"
                                                class="text-blue-900 whitespace-no-wrap">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor"
                                                    class="w-4 h-4 text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <br>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeAddFeasibilityModal"> Cancelar </SecondaryButton>
                        <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="showModalFeasibility">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Agregar un Factibilidad
                </h2>
                <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <InputLabel for="name">Nombre</InputLabel>
                        <div class="mt-2">
                            <TextInput required type="text" v-model="feasibilityObject.name" id="name" />
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <InputLabel for="unit">Unidad
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput required type="text" v-model="feasibilityObject.unit" id="unit" />
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <InputLabel for="quantity">Cantidad
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput required type="number" v-model="feasibilityObject.quantity" id="quantity" min="0" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="modalFeasibility"> Cerrar </SecondaryButton>
                    <PrimaryButton type="button" @click="addFeasibility"> Agregar </PrimaryButton>
                </div>
            </div>


        </Modal>

        <SuccessOperationModal :confirming="confirmFeasibility" :title="'Nueva Factibilidad creada'"
            :message="'La Factibilidad fue creada con éxito'" />
        <SuccessOperationModal :confirming="confirmUpdateFeasibility" :title="'Factibilidad Actualizada'"
            :message="'La Factibilidad fue actualizada'" />
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
import { formattedDate } from '@/utils/utils.js';
import TextInput from '@/Components/TextInput.vue';

const { feasibility, auth } = defineProps({
    feasibility: Object,
    auth: Object
})

const initialState = {
    user_id: auth.user.id,
    feasibility_date: '',
    report: 'Pendiente',
    user_name: auth.user.name,
    cicsa_feasibility_materials: [],
}

const form = useForm(
    { ...initialState }
);

const feasibilityObject = ref({
    id: '',
    name: '',
    unit: '',
    quantity: 0,
});
const showAddEditModal = ref(false);
const confirmFeasibility = ref(false);
const showModalFeasibility = ref(false);
const cicsa_assignation_id = ref(null);

function modalFeasibility() {
    showModalFeasibility.value = !showModalFeasibility.value
}

function closeAddFeasibilityModal() {
    showAddEditModal.value = false
    form.defaults({ ...initialState })
    form.reset()
}

const confirmUpdateFeasibility = ref(false);

function openEditFeasibilityModal(id, item) {
    cicsa_assignation_id.value = id;
    form.defaults({ ...item })
    form.reset()
    form.cicsa_feasibility_materials = item ? item.cicsa_feasibility_materials : []
    showAddEditModal.value = true

}

function submit() {
    let url = route('feasibilities.storeOrUpdate', { cicsa_assignation_id: cicsa_assignation_id.value })
    form.put(url, {
        onSuccess: () => {
            closeAddFeasibilityModal()
            confirmUpdateFeasibility.value = true
            setTimeout(() => {
                confirmUpdateFeasibility.value = false
            }, 1500)
        },
        onError: (e) => {
            console.error(e)
        }
    })
}

function addFeasibility() {
    if (feasibilityObject.value.name && feasibilityObject.value.unit && feasibilityObject.value.quantity) {
        const newFeasibility = {
            name: feasibilityObject.value.name,
            unit: feasibilityObject.value.unit,
            quantity: feasibilityObject.value.quantity
        };
        form.cicsa_feasibility_materials.push(newFeasibility);
        feasibilityObject.value.name = '';
        feasibilityObject.value.unit = '';
        feasibilityObject.value.quantity = '';
    } else {
        console.error('Por favor completa todos los campos del formulario.');
    }
}

function delete_material(materialName) {
    const index = form.cicsa_feasibility_materials.findIndex(material => material.name === materialName);
    if (index !== -1) {
        form.cicsa_feasibility_materials.splice(index, 1);
    } else {
        console.error(`No se encontró ningún material con el nombre '${materialName}'.`);
    }
}

function modifyQuantity(id, event) {
    form.cicsa_feasibility_materials = form.cicsa_feasibility_materials.map(item => {
        if (item.id === id) {
            return { ...item, quantity: event.target.value };
        }
        return item;
    });
}
</script>
