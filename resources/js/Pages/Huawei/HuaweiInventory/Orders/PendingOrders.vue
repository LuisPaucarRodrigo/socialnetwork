<template>
    <Head title="Huawei" />
    <AuthenticatedLayout :redirectRoute="{route: 'huawei.inventory.show', params: {warehouse: 1}}">
        <template #header> Pedidos Pendientes </template>
        <Toaster richColors />

        <div class="min-w-full rounded-lg shadow">
            <div>
                <div>
                    <div class="overflow-x-auto mt-3 h-[85vh]">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                                >
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                    >
                                        Fecha de Pedido
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        <TableAutocompleteFilter
                                            labelClass="text-[11px]"
                                            label="N° de Pedido"
                                            :options="props.order_numbers"
                                            v-model="
                                                filterForm.selectedOrderNumbers
                                            "
                                            width="w-100"
                                        />
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                    >
                                        Materiales
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                    >
                                        Equipos
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                    >
                                        Observación
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                    ></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="item in dataToRender.data"
                                    :key="item.id"
                                    class="text-gray-700"
                                >
                                    <td
                                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center min-w-[250px]"
                                    >
                                        {{ formattedDate(item.order_date) }}
                                    </td>
                                    <td
                                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        {{ item.order_number }}
                                    </td>
                                    <td
                                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        <div
                                            class="flex justify-center items-center"
                                        >
                                            <button
                                                type="button"
                                                @click="openMaterials(item)"
                                                class="text-green-600 hover:underline"
                                            >
                                                <EyeIcon class="h-5 w-5" />
                                            </button>
                                        </div>
                                    </td>
                                    <td
                                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        <div
                                            class="flex justify-center items-center"
                                        >
                                            <button
                                                type="button"
                                                @click="openEquipments(item)"
                                                class="text-green-600 hover:underline"
                                            >
                                                <EyeIcon class="h-5 w-5" />
                                            </button>
                                        </div>
                                    </td>

                                    <td
                                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        {{ item.observation }}
                                    </td>
                                    <td
                                        class="border-b border-gray-200 bg-white px-5 py-1 text-sm text-center whitespace-nowrap"
                                    >
                                        <div class="flex items-center">
                                            <button
                                                @click.prevent="
                                                    openGuideModal(item.id)
                                                "
                                                class="text-blue-600 hover:underline mr-2"
                                            >
                                                <svg
                                                    width="20px"
                                                    height="20px"
                                                    viewBox="0 0 16 16"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    fill="none"
                                                >
                                                    <g fill="#000000">
                                                        <path
                                                            d="M12.75 1a.75.75 0 000 1.5h.69l-1.97 1.97a.75.75 0 001.06 1.06l1.97-1.97v.69a.75.75 0 001.5 0v-2.5a.75.75 0 00-.75-.75h-2.5z"
                                                        />

                                                        <path
                                                            fill-rule="evenodd"
                                                            d="M1.25 2C.56 2 0 2.56 0 3.25v8.5C0 12.44.56 13 1.25 13H5c.896 0 1.475.205 1.809.448.317.23.441.51.441.802a.751.751 0 101.5 0c0-.292.124-.572.441-.802.334-.243.913-.448 1.809-.448h3.75c.69 0 1.25-.56 1.25-1.25v-4.5a.75.75 0 00-1.5 0v4.25H11c-.878 0-1.64.158-2.25.467v-6.55c0-.788.376-1.42 1.12-1.722a.75.75 0 00-.561-1.39 3.27 3.27 0 00-1.31.941A3.13 3.13 0 007.773 3C7.106 2.354 6.154 2 5 2H1.25zm6 3.417c0-.553-.187-1.015-.522-1.34C6.394 3.753 5.846 3.5 5 3.5H1.5v8H5c.878 0 1.64.158 2.25.467v-6.55z"
                                                            clip-rule="evenodd"
                                                        />
                                                    </g>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div
                        v-if="!props.search"
                        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between"
                    >
                        <pagination :links="props.pending_orders.links" />
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="materialModal" :closeable="true">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Materiales del Pedido
                </h2>
                <div class="overflow-x-auto mt-3 col-span-2">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                            >
                                <th
                                    class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                >
                                    N°
                                </th>
                                <th
                                    class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                >
                                    Código SAP
                                </th>
                                <th
                                    class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                >
                                    Material
                                </th>
                                <th
                                    class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                >
                                    Cantidad
                                </th>
                                <th
                                    class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                >
                                    Unidad
                                </th>
                                <th
                                    class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                >
                                    Precio Unitario
                                </th>
                                <th
                                    class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                >
                                    Observación
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in materials"
                                :key="index"
                                class="text-gray-700"
                            >
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap"
                                >
                                    {{ index + 1 }}
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap"
                                >
                                    {{ item.huawei_material.claro_code }}
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap"
                                >
                                    {{
                                        sanitizeText(item.huawei_material.name)
                                    }}
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap"
                                >
                                    {{ item.quantity }}
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap"
                                >
                                    {{ item.huawei_material.unit }}
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap text-right"
                                >
                                    {{
                                        item.unit_price
                                            ? "S/. " +
                                              item.unit_price.toFixed(2)
                                            : ""
                                    }}
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap"
                                >
                                    {{ item.observation }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    class="col-span-2 mt-6 flex items-center justify-end gap-x-6"
                >
                    <SecondaryButton @click="closeMaterials"
                        >Cerrar</SecondaryButton
                    >
                </div>
            </div>
        </Modal>

        <Modal :show="equipmentModal" :closeable="true">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Equipos del Pedido
                </h2>
                <div class="overflow-x-auto mt-3 col-span-2">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                            >
                                <th
                                    class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                >
                                    N°
                                </th>
                                <th
                                    class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                >
                                    Código SAP
                                </th>
                                <th
                                    class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                >
                                    Equipo
                                </th>
                                <th
                                    class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                >
                                    Serie
                                </th>
                                <th
                                    class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                >
                                    Precio Unitario
                                </th>
                                <th
                                    class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                >
                                    Observación
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in equipments"
                                :key="index"
                                class="text-gray-700"
                            >
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap"
                                >
                                    {{ index + 1 }}
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap"
                                >
                                    {{
                                        item.huawei_equipment_serie
                                            .huawei_equipment.claro_code
                                    }}
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap"
                                >
                                    {{
                                        sanitizeText(
                                            item.huawei_equipment_serie
                                                .huawei_equipment.name
                                        )
                                    }}
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap"
                                >
                                    {{
                                        item.huawei_equipment_serie.serie_number
                                    }}
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap text-right"
                                >
                                    {{
                                        item.unit_price
                                            ? "S/. " +
                                              item.unit_price.toFixed(2)
                                            : ""
                                    }}
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap"
                                >
                                    {{ item.observation }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div
                    class="col-span-2 mt-6 flex items-center justify-end gap-x-6"
                >
                    <SecondaryButton @click="closeEquipments"
                        >Cerrar</SecondaryButton
                    >
                </div>
            </div>
        </Modal>

        <Modal :show="guideModal" :closeable="true">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Asignar Guía
                </h2>
                <form
                    @submit.prevent="assignGuide"
                    class="grid grid-cols-2 gap-3"
                >
                    <!-- Tercera Fila -->
                    <div class="col-span-2 grid grid-cols-2 gap-3">
                        <div class="col-span-2">
                            <InputLabel class="mb-1" for="guide_number"
                                >N° de Guía</InputLabel
                            >
                            <input
                                type="text"
                                id="guide_number"
                                v-model="form.guide_number"
                                class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600"
                            />
                            <InputError
                                :message="form.errors.guide_number"
                            />
                        </div>
                        <div class="col-span-2">
                            <InputLabel class="mb-1" for="entry_date"
                                >Fecha de Guía</InputLabel
                            >
                            <input
                                type="date"
                                id="entry_date"
                                v-model="form.entry_date"
                                class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600"
                            />
                            <InputError
                                :message="form.errors.entry_date"
                            />
                        </div>
                        <div class="col-span-2">
                            <label for="archive" class="block text-sm font-medium text-gray-700">Guía</label>
                            <InputFile v-model="form.archive"
                            id="archive"
                            class="block w-full rounded-md border-0 mt-1 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            />
                            <InputError :message="form.errors.archive" />
                        </div>
                        <div class="col-span-2">
                            <InputLabel class="mb-1" for="observation"
                                >Observación</InputLabel
                            >
                            <textarea
                                id="observation"
                                v-model="form.observation"
                                class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600"
                            />
                            <InputError
                                :message="form.errors.observation"
                            />
                        </div>
                    </div>

                    <div
                        class="col-span-2 mt-6 flex items-center justify-end gap-x-6"
                    >
                        <SecondaryButton @click="closeGuideModal"
                            >Cancelar</SecondaryButton
                        >
                        <PrimaryButton
                            type="submit"
                            :class="{ 'opacity-25': form.processing }"
                            >Guardar</PrimaryButton
                        >
                    </div>
                </form>
            </div>
        </Modal>

        <SuccessOperationModal :confirming="successModal" :title="'Guía Asignada'"
        :message="'La guía se asignó correctamente.'" />

    </AuthenticatedLayout>
</template>

<script setup>
import { Head, useForm, router } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import { EyeIcon } from "@heroicons/vue/24/outline";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, watch } from "vue";
import { formattedDate } from "@/utils/utils";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TableAutocompleteFilter from "@/Components/TableAutocompleteFilter.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import InputFile from "@/Components/InputFile.vue";
import { notifyError } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import { setAxiosErrors } from "@/utils/utils";

const props = defineProps({
    pending_orders: Object,
    order_numbers: Array,
});

const dataToRender = ref(props.pending_orders);
const materials = ref([]);
const equipments = ref([]);
const materialModal = ref(false);
const equipmentModal = ref(false);
const filterMode = ref(false);
const guideModal = ref(false);
const orderToUpdate = ref(null);
const successModal = ref(false);

const openMaterials = (item) => {
    materials.value = item.huawei_entry_details.filter(
        (detail) => detail.huawei_equipment_serie_id == null
    );
    materialModal.value = true;
};

const closeMaterials = () => {
    materials.value = [];
    materialModal.value = false;
};

const openEquipments = (item) => {
    equipments.value = item.huawei_entry_details.filter(
        (detail) => detail.huawei_material_id == null
    );
    equipmentModal.value = true;
};

const closeEquipments = () => {
    equipments.value = [];
    equipmentModal.value = false;
};

const assignGuide = async () => {
    const url = route('huawei.inventory.pendingorders.assignguide', { order: orderToUpdate.value });

    const formData = new FormData();
    formData.append('guide_number', form.guide_number);
    formData.append('entry_date', form.entry_date);
    formData.append('archive', form.archive); // Asegúrate de que sea un archivo válido
    formData.append('observation', form.observation);

    try {
        let response = await axios.post(url, formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
        });

        dataToRender.value.data = response.data.orders;
        closeGuideModal();
        successModal.value = true;
        setTimeout(() => {
            successModal.value = false;
        }, 2000);
    } catch (error) {
        console.error(error);
        if (error.response?.data?.errors) {
            setAxiosErrors(error.response.data.errors, form);
        } else {
            notifyError('Server Error');
        }
    }
};


const filterForm = ref({
    selectedOrderNumbers: props.order_numbers,
});

watch(
    () => [filterForm.value.selectedOrderNumbers],
    () => {
        (filterMode.value = true), search_advance(filterForm.value);
    },
    { deep: true }
);

async function search_advance($data) {
    let url = route("huawei.inventory.pendingorders.searchadvance");
    try {
        let response = await axios.post(url, $data);
        dataToRender.value.data = response.data.orders;
    } catch (error) {
        console.error(error);
    }
}

function sanitizeText(text) {
    // Usa una expresión regular para eliminar el texto entre paréntesis al principio del texto y el espacio posterior
    return text.replace(/^\(.*?\)\s*/, "");
}

const form = useForm({
    guide_number: "",
    entry_date: "",
    archive: "",
    observation: "",
});

const openGuideModal = (id) => {
    orderToUpdate.value = id;
    guideModal.value = true;
};

const closeGuideModal = () => {
    orderToUpdate.value = null;
    guideModal.value = false;
};
</script>
