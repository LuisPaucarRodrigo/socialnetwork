<template>

    <Head title="Sot Operación" />

    <AuthenticatedLayout :redirectRoute="'socialnetwork.sot'">
        <template #header>
            Área de Operación
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-between">
                <PrimaryButton @click="openAddSotOperationModal" type="button">
                    + Agregar
                </PrimaryButton>
                <SelectSNSotComponent currentSelect="Área de Operaciones" />
            </div>
            <br>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                SOT
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Estado de Instalación
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Adicionales
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Reporte Fotográfico
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Instalación Completada
                            </th>

                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Materiales en Acta
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in sotsOperation.data" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.sot.name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.i_state }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.additionals }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.photo_report }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.ic_date) }}
                                </p>
                            </td>

                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                <button type="button" @click="openMaterialsModal(item.minute_materials)">
                                    <EyeIcon class="w-5 h-5 text-green-600" />
                                </button>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button class="text-blue-900" @click="openEditSotOperationModal(item)">
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
                <pagination :links="sotsOperation.links" />
            </div>
        </div>

        <Modal :show="showAddEditModal" @close="closeAddSotOperationModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ formSot.id ? 'Editar Operación' : 'Nueva Operación' }}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div v-if="!formSot.id">
                            <InputLabel>
                                SOTS
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="formSot.sot_id"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccionar</option>
                                    <option v-for="item in sots" :key="item.id" :value="item.id">
                                        {{ item.name }}
                                    </option>

                                </select>
                                <InputError :message="formSot.errors.sot_id" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel>Estado de Instalación</InputLabel>
                            <div class="mt-2">
                                <select v-model="formSot.i_state" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Instalado">Instalado</option>
                                    <option value="Rechazado">Rechazado</option>
                                    <option value="Reprogramado">Reprogramado</option>
                                </select>
                                <InputError :message="formSot.errors.i_state" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel>Adicionales</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="formSot.additionals" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="formSot.errors.additionals" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel>Reporte Fotográfico</InputLabel>
                            <div class="mt-2">
                                <select v-model="formSot.photo_report" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Completo">Completo</option>
                                    <option value="Incompleto">Incompleto</option>
                                </select>
                                <InputError :message="formSot.errors.photo_report" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel>Fecha de Instalación Completada</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="formSot.ic_date" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="formSot.errors.ic_date" />
                            </div>
                        </div>

                    </div>
                    <br>


                    <template v-if="!formSot.id">
                        <div class="ring-1 p-3 text-sm ring-gray-300 rounded-md">


                            <div class="flex gap-2 items-center">
                                <b>Materiales usados en Acta:</b>
                                <button @click="oppenAddMaterialModal" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        class="text-blue-500 hover:text-purple-500 w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </div>
                            <br>
                            <div v-if="formSot.minute_materials.length > 0" class="overflow-auto">
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
                                            <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, i) in formSot.minute_materials" :key="i"
                                            class="text-gray-700 bg-white text-sm">
                                            <td class="border-b border-slate-300  px-4 py-4">
                                                {{ item?.material }}
                                            </td>
                                            <td class="border-b border-slate-300  px-4 py-4">
                                                {{ item?.quantity }}
                                            </td>
                                            <td class="border-b border-slate-300  px-4 py-4">
                                                <button @click="deleteMaterial(i)" type="button">
                                                    <TrashIcon class="text-red-500 w-5 h-5" />
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </template>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeAddSotOperationModal"> Cancelar </SecondaryButton>

                        <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                            :class="{ 'opacity-25': formSot.processing }" :disabled="formSot.processing" type="submit">
                            {{ formSot.id ? 'Actualizar' : 'Guardar' }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>


        <Modal :show="showAddMaterialModal" @close="closeAddMaterialModal" max-width="md">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Añadir material
                </h2>
                <form @submit.prevent="addMaterial" class="mt-2">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4">
                        <div>
                            <InputLabel class="font-medium leading-6 text-gray-900">
                                Nombre
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="materialItem.material" required @change="handleMaterial"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione</option>
                                    <option>Coaxial c/mens RG6</option>
                                    <option>Coaxial s/mens RG6</option>
                                    <option>Cable telefónico</option>
                                    <option>Cable UTP</option>
                                    <option>Cable Fibra DROP</option>
                                    <option>Conector RJ11</option>
                                    <option>Conector RJ45</option>
                                    <option>Conector RG6</option>
                                    <option>Conector OPT</option>
                                    <option>Cable SC/APC</option>
                                    <option>Control Remoto</option>
                                    <option>Cable HDMI</option>
                                    <option>Roseta Telef.</option>
                                    <option>Roseta Óptica</option>
                                    <option>Roseta Óptica</option>
                                    <option>Anclaje p</option>
                                    <option>Teléfono</option>
                                    <option>Chapa Q</option>
                                    <option>Divisor</option>
                                    <option>Otro</option>
                                </select>
                                <div v-if="materialItem.material === 'Otro'" class="mt-4">
                                    <input required autocomplete="off" placeholder="Especifique"
                                        v-model="materialItem.other_material"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />

                                </div>
                            </div>
                        </div>
                        <div class="">
                            <InputLabel>Cantidad</InputLabel>
                            <div class="mt-2">
                                <input autocomplete="off" required v-model="materialItem.quantity"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />

                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeAddMaterialModal"> Cancelar </SecondaryButton>
                        <PrimaryButton class="ml-3 tracking-widest uppercase text-xs" type="submit">
                            Agregar
                        </PrimaryButton>
                    </div>

                </form>
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


        <ErrorOperationModal :showError="errorModal" :title="'Error'" :message="'El material ya fue añadido'" />
        <SuccessOperationModal :confirming="confirmSot" :title="'Nueva operación de SOT creada'"
            :message="'La operación de la SOT fue creada con éxito'" />
        <SuccessOperationModal :confirming="confirmUpdateSot" :title="'Operación de SOT Actualizada'"
            :message="'La operación de SOT fue actualizada'" />
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
import SelectSNSotComponent from '@/Components/SelectSNSotComponent.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import { TrashIcon, EyeIcon } from '@heroicons/vue/24/outline';
import { formattedDate } from '@/utils/utils.js';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';

const { sotsLiquidation, sots, auth } = defineProps({
    sotsOperation: Object,
    sots: Object,
    auth: Object
})

const initialState = {
    id: '',
    sot_id: '',
    i_state: '',
    additionals: '',
    photo_report: '',
    ic_date: '',
    minute_materials: []
}

const formSot = useForm(
    { ...initialState }
);

//Add SOT
const showAddEditModal = ref(false);
const confirmSot = ref(false);
function openAddSotOperationModal() {
    showAddEditModal.value = true
}
function closeAddSotOperationModal() {
    showAddEditModal.value = false
    formSot.defaults({ ...initialState })
    formSot.reset()
}
function submitStore() {
    let url = route('socialnetwork.sot.operation.store')
    formSot.post(url, {
        onSuccess: () => {
            closeAddSotOperationModal()
            confirmSot.value = true
            setTimeout(() => {
                confirmSot.value = false
            }, 1500)
        }
    })
}

//Edit SOT
const confirmUpdateSot = ref(false);
function openEditSotOperationModal(item) {
    formSot.defaults({ ...item })
    formSot.reset()
    showAddEditModal.value = true
}
function submitUpdate() {
    let url = route('socialnetwork.sot.operation.update', { sot_operation_id: formSot.id })
    formSot.put(url, {
        onSuccess: () => {
            closeAddSotOperationModal()
            confirmUpdateSot.value = true
            setTimeout(() => {
                confirmUpdateSot.value = false
            }, 1500)
        }
    })
}

//Submit
function submit() {
    formSot.id ? submitUpdate() : submitStore()
}



//Add Material
const showAddMaterialModal = ref(false)
const errorModal = ref(false)
const initialItemMaterial = {
    material: '',
    other_material: '',
    quantity: ''
}
const materialItem = ref({ ...initialItemMaterial });
const closeAddMaterialModal = () => {
    showAddMaterialModal.value = false
    materialItem.value = { ...initialItemMaterial }
}
const oppenAddMaterialModal = () => {
    showAddMaterialModal.value = true
}
const handleMaterial = () => {
    materialItem.value.other_material = ''
}
const isInMaterialList = (name) => {
    return formSot.minute_materials.some(i => i.material === name)
}

function addMaterial() {
    let material = materialItem.value.material === 'Otro' ? materialItem.value.other_material : materialItem.value.material
    let quantity = materialItem.value.quantity
    if (isInMaterialList(material)) {
        errorModal.value = true
        setTimeout(() => {
            errorModal.value = false
        }, 2000)
        return
    }
    formSot.minute_materials.push({
        material, quantity
    })
    closeAddMaterialModal()
}

function deleteMaterial(i) {
    formSot.minute_materials.splice(i, 1)
}


//Materials Modal
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
