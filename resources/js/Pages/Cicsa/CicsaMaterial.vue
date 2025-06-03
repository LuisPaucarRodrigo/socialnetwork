<template>

    <Head title="CICSA Material" />

    <AuthenticatedLayout :redirectRoute="{ route: 'cicsa.index', params: { type } }">
        <template #header>
            {{ type == 1 ? 'Pint' : 'Pext' }} - Materiales
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <div class="flex justify-end">
                <!-- <a :href="route('material.export') + '?' + uniqueParam"
                        class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">Exportar</a> -->
                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <TextInput data-tooltip-target="search_fields" type="text" @input="search($event.target.value)"
                        placeholder="Buscar ..." />
                    <SelectCicsaComponent currentSelect="Materiales" :type="type" />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Nombre,Codigo,CPE
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <br>
            <TableStructure>
                <template #thead>
                    <TableTitle>Nombre de Proyecto</TableTitle>
                    <TableTitle>Codigo de Proyecto</TableTitle>
                    <TableTitle>Centro de Costos</TableTitle>
                    <TableTitle>CPE</TableTitle>
                    <TableTitle>Lista de Materiales de Factibilidad</TableTitle>
                    <TableTitle>Acciones</TableTitle>
                </template>
                <template #tbody>
                    <template v-for="item in materials.data ?? materials" :key="item.id">
                        <tr class="text-gray-700">
                            <TableRow>{{ item.project_name }}</TableRow>
                            <TableRow>{{ item.project_code }}</TableRow>
                            <TableRow>{{ item.project?.cost_center?.name }}</TableRow>
                            <TableRow>{{ item.cpe }}</TableRow>
                            <TableRow>
                                <button v-if="item?.cicsa_feasibility?.cicsa_feasibility_materials?.length > 0"
                                    type="button"
                                    @click="openMaterialsModal(item?.cicsa_feasibility?.cicsa_feasibility_materials)">
                                    <ShowIcon/>
                                </button>
                            </TableRow>
                            <TableRow>
                                <div class="flex space-x-3 justify-center">
                                    <button
                                        @click="openCreateSotModal(item.id, item?.cicsa_feasibility?.cicsa_feasibility_materials, item.project_name, item.cpe)">
                                        <PlusCircleIcon/>
                                    </button>
                                    <button v-if="item.cicsa_materials.length > 0" type="button"
                                        @click="toggleDetails(item?.cicsa_materials)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <DownArrowIcon v-if="materialRow !== item.id"/>
                                        <UpArrowIcon v-else/>
                                    </button>
                                </div>
                            </TableRow>
                        </tr>
                        <template v-if="materialRow == item.id">
                            <tr>
                                <TableTitle :style="'bg-gray-200'"></TableTitle>
                                <TableTitle :style="'bg-gray-200'">Fecha de Recojo</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Numero de Guia</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Lista de Materiales Recibidos</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Acciones</TableTitle>
                                <TableTitle :style="'bg-gray-200'"></TableTitle>
                            </tr>
                            <tr v-for="materialDetail in item.cicsa_materials" :key="materialDetail.id"
                                class="bg-gray-100 text-center">
                                <TableRow></TableRow>
                                <TableRow>{{ formattedDate(materialDetail.pick_date) }}</TableRow>
                                <TableRow>{{ materialDetail.guide_number }}</TableRow>
                                <TableRow>
                                    <button v-if="materialDetail?.cicsa_material_items?.length > 0" type="button"
                                        @click="openMaterialsModal(materialDetail?.cicsa_material_items)">
                                        <ShowIcon/>
                                    </button>
                                </TableRow>
                                <TableRow>
                                    <button class="text-blue-900"
                                        @click="openEditSotModal(materialDetail, item.cicsa_feasibility?.cicsa_feasibility_materials, item.project_name, item.cpe)">
                                        <EditIcon/>
                                    </button>
                                </TableRow>
                                <TableRow>
                                    <button class="text-blue-900"
                                        @click="deleteMaterial(materialDetail.id, item)">
                                        <DeleteIcon/>
                                    </button>
                                </TableRow>
                            </tr>
                        </template>
                    </template>

                </template>
            </TableStructure>

            <div v-if="materials.data"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="materials.links" />
            </div>
        </div>

        <Modal :show="showAddEditModal" maxWidth="4xl">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ form.id ? 'Editar Material' : 'Nuevo Material' }} {{ ': ' + dateModal.project_name
                        + ' - '
                        + dateModal.cpe }}
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
                        <div class="sm:col-span-1">
                            <br>
                            <div class="flex gap-2 items-center">
                                <h2 class="text-base font-bold leading-6 text-gray-900 ">
                                    Materiales Recibidos
                                </h2>
                                <button @click="modalFeasibility" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="indigo" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                    </svg>
                                </button>

                                <button @click="modalImportMaterial" type="button">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="green" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 16.5V9.75m0 0 3 3m-3-3-3 3M6.75 19.5a4.5 4.5 0 0 1-1.41-8.775 5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752 3.752 0 0 1 18 19.5H6.75Z" />
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
                                            Codigo AX
                                        </th>
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
                                            Tipo
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Cantidad Total
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
                                    <tr v-for="(item, i) in form.cicsa_material_items" :key="i" class="text-gray-700">
                                        <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                            <p class="text-gray-900 text-center">
                                                {{ item.code_ax }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                            <p class="text-gray-900 text-center">
                                                {{ item.name }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                            <p class="text-gray-900 text-center">
                                                {{ item.unit }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                            <p class="text-gray-900 text-center">
                                                {{ item.type }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                            <p class="text-gray-900 text-center">
                                                {{ item.total_quantity ?? item.quantity }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                            <div class="flex justify-center">
                                                <input required type="number" min="0" step="0.01"
                                                    v-model="form.cicsa_material_items[i]['quantity']"
                                                    autocomplete="off"
                                                    class="block  text-center rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                            </div>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                            <button type="button" @click="delete_material(i)"
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
                        <SecondaryButton type="button" @click="closeAddMaterialModal"> Cancelar </SecondaryButton>
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
                    Agregar Material
                </h2>
                <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                    <div class="sm:col-span-1">
                        <InputLabel for="name">Codigo AX</InputLabel>
                        <div class="mt-2">
                            <TextInput required type="text" list="names" v-model="material_item.name"
                                placeholder="Codigo AX" @input="searchMaterial($event.target.value)"
                                @change="updateMaterialItem($event.target.value)" id="name" />
                            <datalist id="names">
                                <option v-for="item in arrayMaterials" :key="item.id" :value="item.code_ax">
                                    {{ item.code_ax }} || {{ item.name }} || {{ item.internal_reference }}
                                </option>
                            </datalist>
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <InputLabel for="unit">Unidad
                        </InputLabel>
                        <div class="mt-2">
                            <select id="unit" v-model="material_item.unit" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled value="">Seleccionar Unidad</option>
                                <option>UNIDAD</option>
                                <option>METROS</option>
                                <option>CAJA</option>
                                <option>GLB</option>
                                <option>METROS 2</option>
                                <option>METROS 3</option>
                                <option>PIEZA</option>
                                <option>LATA</option>
                                <option>PAQUETE</option>
                                <option>ROLLO</option>
                            </select>
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <InputLabel for="type">Tipo
                        </InputLabel>
                        <div class="mt-2">
                            <select id="type" v-model="material_item.type" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled value="">Seleccionar Tipo</option>
                                <option>Pint</option>
                                <option>Pext</option>
                            </select>
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <InputLabel for="quantity">Cantidad
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput required type="number" v-model="material_item.quantity" id="quantity"
                                step="0.01" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="modalFeasibility"> Cerrar </SecondaryButton>
                    <PrimaryButton type="button" @click="addFeasibility"> Agregar </PrimaryButton>
                </div>
            </div>
        </Modal>

        <Modal :show="showMaterials" @close="closeMaterialsModal" :closeable="true">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ material_title }}
                </h2>
                <br>
                <div class="mt-2">
                    <div v-if="feas_materials.length > 0" class="overflow-auto">
                        <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Codigo AX
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Nombre
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Unidad
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Tipo
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Cantidad
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, i) in feas_materials" :key="i" class="text-gray-700 bg-white text-sm">
                                    <td class="border-b border-slate-300  px-4 py-4">
                                        {{ item?.code_ax }}
                                    </td>
                                    <td class="border-b border-slate-300  px-4 py-4">
                                        {{ item?.name }}
                                    </td>
                                    <td class="border-b border-slate-300  px-4 py-4">
                                        {{ item?.unit }}
                                    </td>
                                    <td class="border-b border-slate-300  px-4 py-4">
                                        {{ item?.type }}
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
        <Modal :show="showModalImport">
            <div class="p-4 sm:p-8 md:p-12 lg:p-16">
                <h2 class="text-lg md:text-xl lg:text-2xl font-medium leading-7 text-gray-900 mb-4">
                    Importar Excel de Material
                </h2>
                <form @submit.prevent="submitImportExcel">
                    <div class="space-y-4 sm:space-y-6 lg:space-y-8">
                        <div class="border-b border-gray-900/10 pb-4 sm:pb-6 lg:pb-8">
                            <div class="mb-2">
                                <InputFile type="file" v-model="formImport.document" id="documentFile" accept=".xlsx" />
                                <InputError :message="formImport.errors.document" />
                            </div>
                        </div>
                        <div class="flex items-center justify-end gap-x-4 sm:gap-x-3 lg:gap-x-3">
                            <SecondaryButton @click="modalImportMaterial">Cancelar</SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': formImport.processing }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                Importar
                            </PrimaryButton>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>


        <!-- <SuccessOperationModal :confirming="confirmMaterial" :title="'Nueva Material creada'"
            :message="'La Material fue creada con éxito'" /> -->
        <!-- <SuccessOperationModal :confirming="confirmUpdateMaterial" :title="'Material Actualizada'"
            :message="'La Material fue actualizada'" /> -->
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import InputFile from '@/Components/InputFile.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectCicsaComponent from '@/Components/SelectCicsaComponent.vue';
import { formattedDate, setAxiosErrors } from '@/utils/utils.js';
import TextInput from '@/Components/TextInput.vue';
import { Toaster } from 'vue-sonner';
import { notify, notifyError } from '@/Components/Notification';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import ShowIcon from '@/Components/Icons/ShowIcon.vue';
import DownArrowIcon from '@/Components/Icons/DownArrowIcon.vue';
import UpArrowIcon from '@/Components/Icons/UpArrowIcon.vue';
import PlusCircleIcon from '@/Components/Icons/PlusCircleIcon.vue';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';

const { material, auth, searchCondition, type } = defineProps({
    material: Object,
    auth: Object,
    searchCondition: {
        type: String,
        required: false
    },
    type: Number
})

// const uniqueParam = ref(`timestamp=${new Date().getTime()}`);
const materials = ref(material)
const arrayMaterials = ref([])

const initialState = {
    id: '',
    cicsa_assignation_id: null,
    user_id: auth.user.id,
    pick_date: '',
    guide_number: '',
    cicsa_material_items: [],
    user_name: auth.user.name,
}

const form = useForm(
    { ...initialState }
);

const formImport = useForm({
    document: null
})

const showAddEditModal = ref(false);
const confirmMaterial = ref(false);
const cicsa_assignation_id = ref(null);
const cicsa_material_id = ref(null);
const materialRow = ref(0);
const showModalImport = ref(false);
const dateModal = ref({});

function closeAddMaterialModal() {
    cicsa_material_id.value = null
    cicsa_assignation_id.value = null;
    showAddEditModal.value = false
    form.defaults({ ...initialState })
    form.reset()
}

// const confirmUpdateMaterial = ref(false);

function openEditSotModal(item, feasibility_materials, project_name, cpe) {
    dateModal.value = { 'project_name': project_name, 'cpe': cpe }

    cicsa_material_id.value = item.id;
    let cicsa_material_items = []
    if (item?.id) {
        cicsa_material_items = item.cicsa_material_items?.length > 0 ? [...item.cicsa_material_items] : feasibility_materials ? feasibility_materials?.map(item => ({ ...item })) : []
    } else {
        cicsa_material_items = feasibility_materials ? feasibility_materials?.map(item => ({ ...item })) : []
    }
    form.defaults({ ...item, cicsa_material_items })
    form.reset()
    showAddEditModal.value = true
}


async function submit() {
    let url = cicsa_material_id.value ? route('material.update', { cicsa_material_id: cicsa_material_id.value }) : route('material.store')
    let action = cicsa_material_id.value ? false : true
    let method = cicsa_material_id.value ? 'put' : 'post'
    try {
        const response = await axios({
            method: method, // Dinámico según el caso
            url: url,
            data: form,
        });
        updateMaterial(action, response.data)
        closeAddMaterialModal()
        // confirmUpdateMaterial.value = true
        // setTimeout(() => {
        //     confirmUpdateMaterial.value = false
        // }, 1500)
    } catch (error) {
        console.error(error)
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form)
            } else {
                notifyError("Server error:", error.response.data)
            }
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

const showModalFeasibility = ref(false);
function modalFeasibility() {
    cleanArrayMaterial()
    showModalFeasibility.value = !showModalFeasibility.value
}

const material_item = ref({
    id: '',
    code_ax: '',
    name: '',
    unit: '',
    type: '',
    quantity: 0,
    total_quantity: null
});

function addFeasibility() {
    if (material_item.value.name && material_item.value.unit && material_item.value.quantity) {
        const newMaterials = {
            code_ax: material_item.value.code_ax,
            name: material_item.value.name,
            unit: material_item.value.unit,
            type: material_item.value.type,
            quantity: material_item.value.quantity,
            total_quantity: material_item.value.quantity
        };
        form.cicsa_material_items.push(newMaterials);
        cleanArrayMaterial()
    } else {
        console.error('Por favor completa todos los campos del formulario.');
    }
}

function cleanArrayMaterial() {
    material_item.value.code_ax = '';
    material_item.value.name = '';
    material_item.value.unit = '';
    material_item.value.type = '';
    material_item.value.quantity = '';
    material_item.value.total_quantity = '';
}


function delete_material(i) {
    form.cicsa_material_items.splice(i, 1);
}

const material_title = ref('')

//materiasls
const showMaterials = ref(false)
const feas_materials = ref([]);
function openMaterialsModal(arrayMaterials, title) {
    feas_materials.value = arrayMaterials ? arrayMaterials : []
    material_title.value = title
    showMaterials.value = true
}

function closeMaterialsModal() {
    showMaterials.value = false
}

const toggleDetails = (material) => {
    if (materialRow.value === material[0].cicsa_assignation_id) {
        materialRow.value = 0;
    } else {
        materialRow.value = material[0].cicsa_assignation_id;
    }
}

function openCreateSotModal(cicsa_assignation_id, cicsa_material_feasibility, project_name, cpe) {
    dateModal.value = { 'project_name': project_name, 'cpe': cpe }
    form.defaults({ ...initialState })
    form.cicsa_assignation_id = cicsa_assignation_id
    form.cicsa_material_items = cicsa_material_feasibility ? cicsa_material_feasibility.map(item => ({
        ...item,
        total_quantity: item.quantity,
    })) : [];
    showAddEditModal.value = true
}

function modalImportMaterial() {
    showModalImport.value = !showModalImport.value
}

function submitImportExcel() {
    axios.post(route('material.import'), formImport, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    })
        .then(response => {
            if (response.status === 200) {
                console.log(response.data)
                form.cicsa_material_items = response.data;
                modalImportMaterial();
            }
        })
        .catch(error => {
            console.log(error)
            if (error.response.status === 400) {
                formImport.errors.document = error.response.data.errorMessage
            } else {
                console.error('Error en el server:', error.errorMessage);
            }

        });
}

const search = async ($search) => {
    try {
        const response = await axios.post(route('material.index', { type }), { searchQuery: $search });
        materials.value = response.data.material;

    } catch (error) {
        console.error('Error searching:', error);
    }
};

const searchMaterial = async ($search) => {
    try {
        const response = await axios.post(route('material.search.material'), { searchQuery: $search });
        arrayMaterials.value = response.data.materials;
    } catch (error) {
        console.error('Error searching:', error);
    }
};

function updateMaterialItem(e) {
    const selectedItem = arrayMaterials.value.find(item => item.code_ax === e);
    if (selectedItem) {
        material_item.value.code_ax = selectedItem.code_ax
        material_item.value.name = selectedItem.name
        material_item.value.unit = selectedItem.unit
    }
}

function updateMaterial(item, material) {
    const validations = materials.value.data || materials.value;
    const index = validations.findIndex(item => item.id === material.cicsa_assignation_id);
    if (item) {
        validations[index].cicsa_materials.push(material)
        notify('Creacion Exitosa')
    } else {
        const indexMaterial = validations[index].cicsa_materials.findIndex(item => item.id === material.id);
        validations[index].cicsa_materials[indexMaterial] = material
        notify('Actualizacion Exitosa')
    }
}

if (searchCondition) {
    search(searchCondition)
}

async function deleteMaterial(id, item) {
    const res = await axios.delete(route('material.delete', {c_m_id:id}))
    if (res.status === 200) {
        notify('Material eliminado')
        item.cicsa_materials = item.cicsa_materials.filter((material) => material.id != id)
        if(item.cicsa_materials.length == 0 ) {materialRow.value = 0}
    } else {
        notifyError('SERVER ERROR')
    }
}
</script>
