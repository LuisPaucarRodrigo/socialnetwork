<template>

    <Head title="CICSA Asignación" />

    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header>
            Instalación PINT y PEXT
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-end">
                <SelectCicsaComponent currentSelect="Instalación PINT y PEXT" />
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
                                Fecha PEXT
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha PINT
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Acta de Conformidad
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Informe
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Materiales Recibidos
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Materiales Liquidados
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Envío de Informe
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
                        <tr v-for="item in installations.data" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.project_name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.cicsa_installation?.pext_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.cicsa_installation?.pint_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_installation?.conformity }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_installation?.report }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                <button type="button" @click="openMaterialsModal(item.total_materials)">
                                    <EyeIcon class="w-5 h-5 text-green-600" />
                                </button>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                <button type="button" @click="openMaterialsModal(item.minute_materials)">
                                    <EyeIcon class="w-5 h-5 text-green-600" />
                                </button>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.cicsa_installation?.shipping_report_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.cicsa_installation?.user_name }}
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
                <pagination :links="installations.links" />
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
                            <InputLabel for="feasibility_date">Fecha de PEXT</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.pext_date" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.pext_date" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="feasibility_date">Fecha de PINT</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.pint_date" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.pint_date" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="feasibility_date">Acta de Conformidad</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.conformity" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.conformity" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="feasibility_date">Informe</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.report" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>En Proceso</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.report" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="feasibility_date">Fecha de envío de Informe</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.shipping_report_date" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.shipping_report_date" />
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



        <Modal :show="showMaterials" @close="closeMaterialsModal" max-width="md" :closeable="true">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Materiales en Acta
                </h2>
                <br>
                <div class="mt-2">
                    <div v-if="materials.length > 0" class="overflow-auto">
                        <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Material
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Unidad
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Cantidad
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, i) in materials" :key="i" class="text-gray-700 bg-white text-sm">
                                    <td class="border-b border-slate-300  px-4 py-4">
                                        {{ item?.name }}
                                    </td>
                                    <td class="border-b border-slate-300  px-4 py-4">
                                        {{ item?.unit }}
                                    </td>
                                    <td class="border-b border-slate-300  px-4 py-4">
                                        {{ item?.quantity }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-else>
                        No hay materiales asignados
                    </p>
                    <br>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeMaterialsModal"> Cerrar </SecondaryButton>

                    </div>
                </div>
            </div>
        </Modal>
<!-- 
        <Modal :show="showMaterials" @close="closeMaterialsModal" max-width="md" :closeable="true">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Materiales en Acta
                </h2>
                <br>
                <div class="mt-2">
                    <div v-if="materials.length > 0" class="overflow-auto">
                        <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Material
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Cantidad
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, i) in materials" :key="i" class="text-gray-700 bg-white text-sm">
                                    <td class="border-b border-slate-300  px-4 py-4">
                                        {{ item?.material }}
                                    </td>
                                    <td class="border-b border-slate-300  px-4 py-4">
                                        {{ item?.quantity }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <p v-else>
                        No hay materiales asignados
                    </p>
                    <br>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeMaterialsModal"> Cerrar </SecondaryButton>

                    </div>
                </div>
            </div>
        </Modal> 
        -->


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
import { TrashIcon, EyeIcon } from '@heroicons/vue/24/outline';

const { installations, auth } = defineProps({
    installations: Object,
    auth: Object
})


const initialState = {
    user_id: auth.user.id,
    user_name: auth.user.name,
    cicsa_assignation_id: '',
    pext_date: '',
    pint_date: '',
    conformity: '',
    report: '',
    shipping_report_date: '',
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


const showMaterials = ref(false)
const materials = ref([]);
function openMaterialsModal(arrayMaterials) {
    materials.value = arrayMaterials ? arrayMaterials : []
    showMaterials.value = true
}
function closeMaterialsModal() {
    showMaterials.value = false
}

</script>