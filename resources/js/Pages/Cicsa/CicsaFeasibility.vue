<template>

    <Head title="CICSA Asignación" />

    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header>
            Factibilidad PINT y PEXT
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-between">
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
                                Nombre Usuario
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
                                    {{ item.cicsa_feasibility?.user_name }}
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

        <Modal :show="showAddEditModal" @close="closeAddAssignationModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ form.id ? 'Editar Factibilidada' : 'Nueva Factibilidada' }}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <InputLabel for="feasibility_date">Fecha de Factibilidad</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.feasibility_date" autocomplete="off"
                                    id="feasibility_date"/>
                                <InputError :message="form.errors.feasibility_date" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="report">Informe</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.report" autocomplete="off" id="report"/>
                                <InputError :message="form.errors.report" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <div class="flex gap-2 items-center">
                                <h2 class="text-base font-bold leading-6 text-gray-900 ">
                                    Añadir Materiales
                                </h2>
                                <button @click="modalMaterial" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="indigo" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                    </svg>
                                </button>
                            </div>
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
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in materialArray" :key="item.id" class="text-gray-700">
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 text-center">
                                                {{ item.name }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 text-center">
                                                {{ item.unit }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 text-center">
                                                {{ item.quantity }}
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
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
        </Modal>

        <Modal :show="showModalMaterial">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Agregar un Material
                </h2>
                <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <InputLabel for="name">Nombre</InputLabel>
                        <div class="mt-2">
                            <TextInput required type="text" v-model="mateiralObject.name" id="name" />
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <InputLabel for="unit">Unidad
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput required type="text" v-model="mateiralObject.unit" id="unit" />
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <InputLabel for="quantity">Cantidad
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput required type="number" v-model="mateiralObject.quantity" id="quantity" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="modalMaterial"> Cerrar </SecondaryButton>
                    <PrimaryButton type="button" @click="addMaterial"> Agregar </PrimaryButton>
                </div>
            </div>


        </Modal>

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
    report: '',
    user_name: auth.user.name,
    material_feasibility: [],
    //Aqui lo ingreso desde el inicio por lo que solo tomara como id de assination 1
    cicsa_assignation_id: 1,
}

const form = useForm(
    { ...initialState }
);

const materialArray = ref([]);
const mateiralObject = ref({
    name: '',
    unit: '',
    quantity: 0,
    cicsa_feasibility_id: ''
});
const showAddEditModal = ref(false);
const confirmAssignation = ref(false);
const showModalMaterial = ref(false)

function modalMaterial() {
    showModalMaterial.value = !showModalMaterial.value
}

function openAddFeasibilityModal() {
    showAddEditModal.value = true
}
function closeAddAssignationModal() {
    showAddEditModal.value = false
    form.defaults({ ...initialState })
    form.reset()
}
function submitStore() {
    let url = route('feasibility.storeOrUpdate');
    form.put(url, {
        onSuccess: () => {
            closeAddAssignationModal()
            confirmAssignation.value = true
            setTimeout(() => {
                confirmAssignation.value = false
            }, 1500)
        },
        onError: (e) => {
            console.error(e)
        }
    })
}

const confirmUpdateAssignation = ref(false);

function openEditFeasibilityModal(cicsa_assignation_id, item) {
    console.log(item);
    form.cicsa_assignation_id = 1;
    form.defaults({ ...item })
    form.reset()
    showAddEditModal.value = true

}

function submitUpdate() {
    // let url = route('feasibility.storeOrUpdate', {cicsa_assignation_id:form.id})
    // form.put(url, {
    //     onSuccess: () => {
    //         closeAddAssignationModal()
    //         confirmUpdateAssignation.value = true
    //         setTimeout(() => {
    //             confirmUpdateAssignation.value = false
    //         }, 1500)
    //     }
    // })
}

function submit() {
    console.log(form)
    // form.id ? submitUpdate() : submitStore()
    form.material_feasibility = materialArray.value.map(material => ({
            name: material.name,
            unit: material.unit,
            quantity: material.quantity,
        }))
    let url = route('feasibilities.storeOrUpdate', { cicsa_assignation_id: form.cicsa_assignation_id })
    form.put(url, {
        onSuccess: () => {
            closeAddAssignationModal()
            confirmUpdateAssignation.value = true
            setTimeout(() => {
                confirmUpdateAssignation.value = false
            }, 1500)
        },
        onError: (e) => {
            console.error(e)
        }
    })
}

function addMaterial() {
    if (mateiralObject.value.name && mateiralObject.value.unit && mateiralObject.value.quantity) {
        const newMaterial = {
            name: mateiralObject.value.name,
            unit: mateiralObject.value.unit,
            quantity: mateiralObject.value.quantity
        };
        materialArray.value.push(newMaterial);

        mateiralObject.value.name = '';
        mateiralObject.value.unit = '';
        mateiralObject.value.quantity = '';
        
    } else {
        console.error('Por favor completa todos los campos del formulario.');
    }
}

</script>