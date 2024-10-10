<template>
    <Head title="Materiales Internos" />

    <AuthenticatedLayout :redirectRoute="{route: 'huawei.quickmaterials'}">
        <template #header> Detalles del Material: {{ material.description }}</template>

        <div class="min-w-full rounded-lg shadow">
            <PrimaryButton @click="addBacklogRow" type="button">
                + Agregar
            </PrimaryButton>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-500"
                        >
                        <th
                                class="border border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                            ></th>
                            <th class="border border-gray-300 bg-gray-100 px-3 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <p class="text-center w-[200px]">
                                    Fecha de Entrada
                                </p>
                            </th>
                            <th class="border border-gray-300 bg-gray-100 px-3 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <p class="text-center w-[200px]">
                                    Cantidad
                                </p>
                            </th>
                            <th class="border border-gray-300 bg-gray-100 px-3 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <p class="text-center w-[200px]">
                                    Precio Unitario
                                </p>
                            </th>
                            <th class="border border-gray-300 bg-gray-100 px-3 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <p class="text-center w-[200px]">
                                    Receptor
                                </p>
                            </th>
                            <th class="border border-gray-300 bg-gray-100 px-3 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                <p class="text-center w-[400px]">
                                    Observación
                                </p>
                            </th>
                            <th
                                class="border border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                            ></th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="(item, key) in backlogsToRender"
                        :key="key">
                        <tr

                            class="text-gray-700"
                        >
                        <td class="text-center border-b border-gray-200 bg-white px-2 py-1">
                            <button v-if="item.id && item.quick_materials_outputs" type="button" @click="toggleDetails(item)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <svg v-if="huaweiProject?.id !== item.id" xmlns="http://www.w3.org/2000/svg"
                                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                            class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                        </svg>
                                        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                        </svg>
                                    </button>
                        </td>
                            <td
                                v-for="field in ['entry_date', 'quantity', 'unit_price', 'employee', 'observation']"
                                :key="field"
                                :class="[
                                    'border border-gray-200 px-2 bg-white py-2 text-[12px]',
                                    item?.id ? '' : 'h-16',
                                    'cursor-pointer',
                                ]"
                                @dblclick="editCell(key, field)"
                            >
                                <template v-if="isEditing(key, field)">
                                    <input
                                        :type="field === 'entry_date' ? 'date' : field === 'quantity' || field === 'unit_price' ? 'number' : 'text'"
                                        :id="`${key}-${field}`"
                                        v-model="item[field]"
                                        @blur="saveEdit(key, field)"
                                        @keydown.enter.prevent="isEnterPressed = true; saveEdit(key, field)"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                </template>

                                <template v-else>
                                    <p class="text-gray-900 text-center">
                                        {{ field === 'entry_date' ? formattedDate(item[field]) : item[field] }}
                                    </p>
                                </template>
                            </td>

                            <td class="border-b border-gray-200 bg-white px-2 py-1">
                                <div class="flex space-x-3 justify-center">
                                    <button
                                        type="button"
                                        @click="item?.id ? openSotDeleteModal(item.id, key) : deleteBacklogRow(key)"
                                    >
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor"
                                            class="w-4 h-4 text-red-500"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                            />
                                        </svg>
                                    </button>

                                </div>
                            </td>
                        </tr>
                        <template v-if="huaweiProject?.id == item.id && item.id">
                            <tr class="text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <th
                                    class="text-center text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    <button type="button" @click="addToogleRow">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </th>
                                <th colspan="1" class="border border-gray-300 bg-gray-100 px-3 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                    Fecha de Salida
                                </th>
                                <th colspan="1" class="border border-gray-300 bg-gray-100 px-3 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                    Cantidad
                                </th>
                                <th colspan="1" class="border border-gray-300 bg-gray-100 px-3 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                    Encargado
                                </th>
                                <th colspan="1" class="border border-gray-300 bg-gray-100 px-3 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                    Observación
                                </th>
                                <th colspan="1" class="border border-gray-300 bg-gray-100 px-3 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                    Site
                                </th>
                                <th colspan="1" class="border border-gray-300 bg-gray-100 px-3 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                    Proyecto
                                </th>
                            </tr>
                            <template v-for="(output, key) in backlogToogle" :key="key">
                            <tr class="bg-gray-100 h-[35px]">
                                <td>
                                </td>
                                <td
                                v-for="field in ['output_date', 'quantity', 'employee', 'observation', 'site', 'project']"
                                :key="field"
                                :class="[
                                    'border border-gray-200 border-b-gray-800 px-2 bg-white py-2 text-[12px]',
                                    item?.id ? '' : 'h-16',
                                    'cursor-pointer',
                                ]"
                                @dblclick="editCell(key, field)"
                            >
                                <template v-if="isEditing(key, field)">
                                    <input
                                        :type="field === 'output_date' ? 'date' : field === 'quantity2' || field === 'unit_price' ? 'number' : 'text'"
                                        :id="`${key}-${field}`"
                                        v-model="item[field]"
                                        @blur="saveEdit(key, field, item.id)"
                                        @keydown.enter.prevent="isEnterPressed = true; saveEdit(key, field, item.id)"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                </template>

                                <template v-else>
                                    <p class="text-gray-900 text-center">
                                        {{ field === 'output_date' ? formattedDate(item[field]) : item[field] }}
                                    </p>
                                </template>
                            </td>

                            </tr>
                        </template>
                        </template>
                    </template>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="quickMaterials.links" />
            </div>
        </div>

        <Modal :show="showSotDeleteModal" @close="closeSotDeleteModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    ¿Estás seguro de que quieres eliminar el registro del backlog?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Una vez que se elimine el registro, todos sus recursos y datos se eliminarán permanentemente.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeSotDeleteModal">
                        Cancelar
                    </SecondaryButton>
                    <DangerButton class="ml-3" @click="deleteSot">
                        Eliminar
                    </DangerButton>
                </div>
            </div>
        </Modal>

        <SuccessOperationModal
            :confirming="confirmSotDelete"
            :title="'Registro Eliminado'"
            :message="'El registro del backlog fue eliminado con éxito'"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, nextTick } from "vue";
import { formattedDate } from "@/utils/utils";
import SuccessOperationModal from "@/Components/SuccessOperationModal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const { quickMaterials, material } = defineProps({
    quickMaterials: Object,
    material: Object
});


const backlogsToRender = ref(quickMaterials.data);
const huaweiProject = ref(null);
const backlogToogle = ref([]);

const toggleDetails = (item) => {
        if (huaweiProject.value && huaweiProject.value.id === item.id){
            backlogToogle.value = [];
            backlogToogle.value = huaweiProject.value.quick_materials_outputs;
            huaweiProject.value = null;
        }else{
            huaweiProject.value = item;
            backlogToogle.value = [];
            backlogToogle.value = huaweiProject.value.quick_materials_outputs;
        }
    }

function addBacklogRow() {
    backlogsToRender.value.unshift({});
}

function deleteBacklogRow(i) {
    backlogsToRender.value.splice(i, 1);
}

function addToogleRow() {
    backlogToogle.value.unshift({});
}

function deleteToogleRow (i) {
    backlogToogle.value.splice(i, 1)
}

// Modal de eliminación
const showSotDeleteModal = ref(false);
const sotToDelete = ref(null);
const backlogKey = ref(null);
const confirmSotDelete = ref(false);

function openSotDeleteModal(id, key) {
    sotToDelete.value = id;
    backlogKey.value = key;
    showSotDeleteModal.value = true;
}

function closeSotDeleteModal() {
    sotToDelete.value = null;
    showSotDeleteModal.value = false;
}

function deleteSot() {
    deleteBacklog();
}

async function deleteBacklog() {
    const res = await axios.delete(
        route("huawei.quickmaterials.details.delete", { quick_material: sotToDelete.value })
    );
    if (res.data.message === "success") {
        deleteBacklogRow(backlogKey.value);
        showSotDeleteModal.value = false;
        confirmSotDelete.value = true;
        setTimeout(() => {
            confirmSotDelete.value = false;
        }, 1500);
    }
}

// Edición de celdas
const editingCells = ref({});
const isEnterPressed = ref(false);

function isEditing(key, field) {
    return editingCells.value[`${key}-${field}`] === true;
}

function editCell(key, field) {
    editingCells.value[`${key}-${field}`] = true;
    nextTick(() => {
        const input = document.getElementById(`${key}-${field}`);
        if (input) {
            input.focus();
        }
    });
}

function saveEdit(key, field, id) {
    if (isEnterPressed.value) {
        isEnterPressed.value = false;
    } else {
        storeBacklog(key, id);
    }
    editingCells.value[`${key}-${field}`] = false;
}

async function storeBacklog(key, id) {
    if (!id) {
        const res = await axios.post(
            route("huawei.quickmaterials.details.store", {material_id: material.id}),
            backlogsToRender.value[key]
        );
        backlogsToRender.value[key] = res.data.quick_res;
    }else{
        const res = await axios.post(
            route('huawei.quickmaterials.details.output.store', {entry_id: id}),
            backlogToogle.value[key]
        );
        backlogToogle.value[key] = res.data.quick_res_out;
    }
}
</script>
