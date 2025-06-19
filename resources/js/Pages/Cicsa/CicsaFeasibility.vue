<template>

    <Head title="CICSA Factibilidad" />

    <AuthenticatedLayout :redirectRoute="{ route: 'cicsa.index', params: { type } }">
        <template #header>
            {{ type == 1 ? 'Pint' : 'Pext' }} - Factibilidad PINT y PEXT
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <div class="flex justify-between">
                <a :href="route('feasibilities.export', { type }) + '?' + uniqueParam"
                    class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">Exportar</a>
                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <TextInput data-tooltip-target="search_fields" type="text" @input="search($event.target.value)"
                        placeholder="Buscar ..." />
                    <SelectCicsaComponent currentSelect="Factibilidad PINT y PEXT" :type="type" />
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
                    <TableTitle>Nombre del Proyecto</TableTitle>
                    <TableTitle>Codigo del Proyecto</TableTitle>
                    <TableTitle>Centro de Costos</TableTitle>
                    <TableTitle>CPE</TableTitle>
                    <TableTitle>Fecha de Factibilidad</TableTitle>
                    <TableTitle>Informe</TableTitle>
                    <TableTitle>Coordinador</TableTitle>
                    <TableTitle>Encargado</TableTitle>
                    <TableTitle></TableTitle>
                </template>
                <template #tbody>
                    <tr v-for="item in feasibilitys.data ?? feasibilitys" :key="item.id" class="text-gray-700">
                        <TableRow>{{ item.project_name }}</TableRow>
                        <TableRow>{{ item.project_code }}</TableRow>
                        <TableRow>{{ item.project.cost_center.name }}</TableRow>
                        <TableRow>{{ item.cpe }}</TableRow>
                        <TableRow>{{ formattedDate(item.cicsa_feasibility?.feasibility_date) }}</TableRow>
                        <TableRow>{{ item.cicsa_feasibility?.report }}</TableRow>
                        <TableRow>{{ item.cicsa_feasibility?.coordinator }}</TableRow>
                        <TableRow>{{ item.cicsa_feasibility?.user_name }}</TableRow>
                        <TableRow>
                            <button @click="openEditFeasibilityModal(item.id, item.cicsa_feasibility)">
                                <EditIcon />
                            </button>
                        </TableRow>
                    </tr>
                </template>
            </TableStructure>
            <div v-if="feasibilitys.data"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="feasibilitys.links" />
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
                            <InputLabel for="coordinator">Coordinador</InputLabel>
                            <div class="mt-2">
                                <select id="coordinator" v-model="form.coordinator" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccionar Coordinador</option>
                                    <option v-for="opt in (
                                        type == 2
                                            ? ['Valery Joana',
                                                'Maria Moscoso',
                                                'Angela Mayela']
                                            :
                                            type == 1
                                                ? ['Sheyla Rondón']
                                                : []
                                    )" :key="opt">
                                        {{ opt }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.coordinator" />
                            </div>
                        </div>
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
                                    <PlusCircleIcon />
                                </button>
                            </div>
                            <br>
                        </div>
                        <div class="sm:col-span-2 overflow-x-auto">
                            <table class="w-full whitespace-nowrap ">
                                <thead>
                                    <tr
                                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
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
                                        <td class="border-b border-slate-300  px-4 py-4">
                                            <p class="text-gray-900 text-center">
                                                {{ item?.code_ax }}
                                            </p>
                                        </td>
                                        <td class="w-1/3 border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                            <p class="text-gray-900 text-center">
                                                {{ item.name }}
                                            </p>
                                        </td>
                                        <td class="w-1/3 border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                            <p class="text-gray-900 text-center">
                                                {{ item.unit }}
                                            </p>
                                        </td>
                                        <td class="w-1/3 border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                            <TextInput class="text-center" type="number" min="0"
                                                @change="modifyQuantity(item.id, $event)" :value="item.quantity" />
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                            <button v-if="!item.id" type="button" @click="delete_material(item.name)"
                                                class="text-blue-900 whitespace-no-wrap">
                                                <DeleteIcon size="w-4 h-4" />
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
                            <TextInput required type="text" list="names" v-model="feasibilityObject.name"
                                placeholder="Codigo AX" @input="searchMaterial($event.target.value)"
                                @change="updateMaterialItem($event.target.value)" id="name" />
                            <datalist id="names">
                                <option v-for="item in arrayFeasibilitys" :key="item.id" :value="item.code_ax">
                                    {{ item.code_ax }} || {{ item.name }} || {{ item.internal_reference }}
                                </option>
                            </datalist>
                        </div>
                    </div>
                    <div class="sm:col-span-1">
                        <InputLabel for="unit">Unidad
                        </InputLabel>
                        <div class="mt-2">
                            <select id="unit" v-model="feasibilityObject.unit" required
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
                        <InputLabel for="quantity">Cantidad
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput required type="number" v-model="feasibilityObject.quantity" id="quantity"
                                min="0" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="modalFeasibility"> Cerrar </SecondaryButton>
                    <PrimaryButton type="button" @click="addFeasibility"> Agregar </PrimaryButton>
                </div>
            </div>


        </Modal>
        <!-- <SuccessOperationModal :confirming="confirmUpdateFeasibility" :title="'Factibilidad Actualizada'"
            :message="'La Factibilidad fue actualizada'" /> -->
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
import { formattedDate } from '@/utils/utils.js';
import TextInput from '@/Components/TextInput.vue';
import { setAxiosErrors } from "@/utils/utils";
import { notify, notifyError } from '@/Components/Notification';
import { Toaster } from 'vue-sonner';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import { DeleteIcon, PlusCircleIcon, EditIcon } from '@/Components/Icons/Index';

const { feasibility, auth, searchCondition, type } = defineProps({
    feasibility: Object,
    auth: Object,
    searchCondition: {
        type: String,
        required: false
    },
    type: Number
})

const uniqueParam = ref(`timestamp=${new Date().getTime()}`);
const feasibilitys = ref(feasibility)
const arrayFeasibilitys = ref([])

const initialState = {
    id: '',
    user_id: auth.user.id,
    coordinator: '',
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
    code_ax: '',
    name: '',
    unit: '',
    quantity: 0,
});

const showAddEditModal = ref(false);
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

async function submit() {
    let url = route('feasibilities.storeOrUpdate', { cicsa_assignation_id: cicsa_assignation_id.value })
    try {
        const response = await axios.put(url, form);
        updateFeasibility(cicsa_assignation_id.value, response.data)
        closeAddFeasibilityModal()
    } catch (error) {
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

function addFeasibility() {
    if (feasibilityObject.value.name && feasibilityObject.value.unit && feasibilityObject.value.quantity) {
        const newFeasibility = {
            code_ax: feasibilityObject.value.code_ax,
            name: feasibilityObject.value.name,
            unit: feasibilityObject.value.unit,
            quantity: feasibilityObject.value.quantity
        };
        form.cicsa_feasibility_materials.push(newFeasibility);
        feasibilityObject.value.code_ax = '';
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
        form.cicsa_feasibility_materials.splice(index, 1)
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

const search = async ($search) => {
    try {
        const response = await axios.post(route('feasibilities.index', { type }), { searchQuery: $search });
        feasibilitys.value = response.data.feasibility;
    } catch (error) {
        console.error('Error searching:', error);
    }
};

const searchMaterial = async ($search) => {
    try {
        const response = await axios.post(route('material.search.material'), { searchQuery: $search });
        arrayFeasibilitys.value = response.data.materials;
    } catch (error) {
        console.error('Error searching:', error);
    }
};

function updateMaterialItem(e) {
    const selectedItem = arrayFeasibilitys.value.find(item => item.code_ax === e);
    if (selectedItem) {
        feasibilityObject.value.code_ax = selectedItem.code_ax
        feasibilityObject.value.name = selectedItem.name
        feasibilityObject.value.unit = selectedItem.unit
    }
}

function updateFeasibility(cicsa_assignation_id, feasibility) {
    const validations = feasibilitys.value.data || feasibilitys.value;
    const index = validations.findIndex(item => item.id === cicsa_assignation_id)
    validations[index].cicsa_feasibility = feasibility
    notify('Actualización Exitosa')
}

if (searchCondition) {
    search(searchCondition)
}
</script>
