<template>
    <Head title="Recursos del Proyecto para Liquidar" />
    <AuthenticatedLayout
        :redirectRoute="{
            route: 'huawei.projects',
            params: { status: backStatus, prefix: 'Claro' },
        }"
    >
        <template #header>
            Recursos del Proyecto {{ props.project_name }}
        </template>
        <Toaster richColors />

        <div class="min-w-full rounded-lg shadow">
            <div class="flex gap-4 items-center justify-between">
                <Link
                    :href="
                        route('huawei.projects.liquidations.history', {
                            huawei_project: props.huawei_project,
                        })
                    "
                    type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500"
                >
                    Historial
                </Link>
                <div
                    class="flex items-center justify-center mt-5 space-x-4 p-2 bg-gray-100 mb-5"
                >
                    <p class="text-sm font-semibold">Materiales</p>
                    <button
                        class="relative w-10 h-5 rounded-full transition-colors duration-200 ease-in-out focus:outline-none flex items-center"
                        :class="{
                            'bg-green-500': isActive,
                            'bg-red-600': !isActive,
                        }"
                        @click="toggleSwitch"
                    >
                        <span
                            class="w-5 h-5 bg-white border rounded-full shadow transform transition-transform duration-200 ease-in-out"
                            :class="{ 'translate-x-5': isActive }"
                        ></span>
                    </button>
                    <p class="text-sm font-semibold">Equipos</p>
                </div>
                <div></div>
            </div>

            <div>
                <div v-if="isActive">
                    <div class="flex">
                        <h2
                            class="text-lg font-medium leading-7 text-gray-900 m-5"
                        >
                            Equipos
                        </h2>
                        <div class="flex gap-4 items-center justify-between">
                            <button
                                type="button"
                                @click="openNuUpdateModal(true)"
                                class="px-2 py-2 text-center font-black text-blue-700 hover:underline"
                            >
                                Liquidación Masiva
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto mt-3 min-h-[40vh]">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                                >
                                    <th
                                        class="sticky left-0 z-10 border-b-2 border-r border-gray-200 bg-gray-100 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600 w-12"
                                    >
                                        <label
                                            :for="`check-all`"
                                            class="flex gap-3 justify-center w-full px-2 py-1"
                                        >
                                            <input
                                                @change="
                                                    handleCheckAllEquipments
                                                "
                                                :id="`check-all-1`"
                                                :checked="
                                                    equipmentForm.ids.length > 0
                                                "
                                                type="checkbox"
                                            />
                                            {{ equipmentForm.ids.length ?? "" }}
                                        </label>
                                    </th>
                                    <th
                                        class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        Descripción del Equipo
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        <TableAutocompleteFilter
                                            labelClass="text-[11px]"
                                            label="N° de Pedido"
                                            :options="
                                                props.data
                                                    .equipment_order_numbers
                                            "
                                            v-model="
                                                filterForm.selectedEquipmentOrderNumbers
                                            "
                                            width="w-64"
                                        />
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        <TableAutocompleteFilter
                                            labelClass="text-[11px]"
                                            label="N° de Serie"
                                            :options="props.data.series"
                                            v-model="filterForm.selectedSeries"
                                            width="w-64"
                                        />
                                    </th>
                                    <th
                                        class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        Precio
                                    </th>
                                    <th
                                        class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    ></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="item in dataToRender.equipments"
                                    :key="item.id"
                                    class="text-gray-700"
                                >
                                    <td
                                        class="sticky left-0 z-10 border-b border-r border-gray-200 bg-amber-100 text-center text-[13px] whitespace-nowrap tabular-nums"
                                    >
                                        <label
                                            :for="`check-${item.id}`"
                                            class="block w-full px-2 py-1"
                                        >
                                            <input
                                                v-model="equipmentForm.ids"
                                                :value="item.id"
                                                :id="`check-${item.id}`"
                                                type="checkbox"
                                            />
                                        </label>
                                    </td>
                                    <td
                                        class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        {{
                                            item.huawei_entry_detail
                                                .huawei_equipment_serie
                                                .huawei_equipment.name
                                        }}
                                    </td>
                                    <td
                                        class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        {{
                                            item.huawei_entry_detail
                                                .order_number
                                        }}
                                    </td>
                                    <td
                                        class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        {{
                                            item.huawei_entry_detail
                                                .huawei_equipment_serie
                                                .serie_number
                                        }}
                                    </td>
                                    <td
                                        class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap"
                                    >
                                        {{
                                            item.huawei_entry_detail.unit_price
                                                ? "S/. " +
                                                  item.huawei_entry_detail.unit_price.toFixed(
                                                      2
                                                  )
                                                : "-"
                                        }}
                                    </td>
                                    <td
                                        class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        <div
                                            v-if="
                                                item.quantity !== 0 &&
                                                props.projectState == 1
                                            "
                                            class="flex items-center justify-center"
                                        >
                                            <button
                                                @click.prevent="
                                                    openLiquidateModal(
                                                        item,
                                                        true
                                                    )
                                                "
                                                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap"
                                            >
                                                Liquidar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div v-else>
                    <div class="flex">
                        <h2
                            class="text-lg font-medium leading-7 text-gray-900 m-5"
                        >
                            Materiales
                        </h2>
                        <div class="flex gap-4 items-center justify-between">
                            <button
                                type="button"
                                @click="openNuUpdateModal(false)"
                                class="px-2 py-2 text-center font-black text-blue-700 hover:underline"
                            >
                                Liquidación Masiva
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto mt-3 min-h-[40vh]">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                                >
                                    <th
                                        class="sticky left-0 z-10 border-b-2 border-r border-gray-200 bg-gray-100 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600 w-12"
                                    >
                                        <label
                                            :for="`check-all`"
                                            class="flex gap-3 justify-center w-full px-2 py-1"
                                        >
                                            <input
                                                @change="
                                                    handleCheckAllMaterials
                                                "
                                                :id="`check-all-2`"
                                                :checked="
                                                    materialForm.ids.length > 0
                                                "
                                                type="checkbox"
                                            />
                                            {{ materialForm.ids.length ?? "" }}
                                        </label>
                                    </th>
                                    <th
                                        class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        Descripción del Material
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        <TableAutocompleteFilter
                                            labelClass="text-[11px]"
                                            label="N° de Pedido"
                                            :options="
                                                props.data
                                                    .material_order_numbers
                                            "
                                            v-model="
                                                filterForm.selectedMaterialOrderNumbers
                                            "
                                            width="w-64"
                                        />
                                    </th>
                                    <th
                                        class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        Cantidad Restante en Proyecto
                                    </th>
                                    <th
                                        class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    >
                                        Precio
                                    </th>
                                    <th
                                        class="w-1/4 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                                    ></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="item in dataToRender.materials"
                                    :key="item.id"
                                    class="text-gray-700"
                                >
                                    <td
                                        class="sticky left-0 z-10 border-b border-r border-gray-200 bg-amber-100 text-center text-[13px] whitespace-nowrap tabular-nums"
                                    >
                                        <label
                                            :for="`check-${item.id}`"
                                            class="block w-full px-2 py-1"
                                        >
                                            <input
                                                v-model="materialForm.ids"
                                                :value="item.id"
                                                :id="`check-${item.id}`"
                                                type="checkbox"
                                            />
                                        </label>
                                    </td>
                                    <td
                                        class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        {{
                                            item.huawei_entry_detail
                                                .huawei_material.name
                                        }}
                                    </td>
                                    <td
                                        class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        {{
                                            item.huawei_entry_detail
                                                .order_number
                                        }}
                                    </td>
                                    <td
                                        class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        {{ item.quantity }}
                                    </td>
                                    <td
                                        class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap"
                                    >
                                        {{
                                            item.huawei_entry_detail.unit_price
                                                ? "S/. " +
                                                  item.huawei_entry_detail.unit_price.toFixed(
                                                      2
                                                  )
                                                : "-"
                                        }}
                                    </td>
                                    <td
                                        class="w-1/4 border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        <div
                                            v-if="
                                                item.quantity !== 0 &&
                                                props.projectState == 1
                                            "
                                            class="flex items-center justify-center"
                                        >
                                            <button
                                                @click.prevent="
                                                    openLiquidateModal(
                                                        item,
                                                        false
                                                    )
                                                "
                                                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap"
                                            >
                                                Liquidar
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="liquidate_modal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Liquidar Recurso
                </h2>
                <form
                    @submit.prevent="liquidate"
                    class="grid grid-cols-2 gap-3"
                >
                    <!-- Tercera Fila -->
                    <div class="col-span-2 grid grid-cols-2 gap-3">
                        <div v-if="liquidate_material" class="col-span-2">
                            <InputLabel class="mb-1" for="quantity"
                                >Cantidad a Liquidar</InputLabel
                            >
                            <input
                                type="number"
                                min="0"
                                :max="liquidate_resource_id.quantity"
                                v-model="form.liquidated_quantity"
                                class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600"
                            />
                            <InputError
                                :message="form.errors.liquidated_quantity"
                            />
                        </div>
                        <div v-if="liquidate_material" class="col-span-2">
                            <InputLabel class="mb-1" for="quantity"
                                >Cantidad a devolver a Inventario</InputLabel
                            >
                            <input
                                type="number"
                                disabled
                                readonly
                                min="0"
                                v-model="refund_quantity"
                                class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600"
                            />
                        </div>
                        <div v-if="liquidate_equipment" class="col-span-2">
                            <div
                                class="inline-flex items-center p-2 mt-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
                                role="alert"
                            >
                                <svg
                                    class="flex-shrink-0 inline w-4 h-4 me-3"
                                    aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor"
                                    viewBox="0 0 20 20"
                                >
                                    <path
                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"
                                    />
                                </svg>
                                <span class="sr-only">Info</span>
                                <div>
                                    <span class="font-small"
                                        >El número de serie del equipo
                                        seleccionado no será devuelto a
                                        inventario y no podrá ser usado de
                                        nuevo.</span
                                    >
                                </div>
                            </div>
                        </div>
                        <div class="col-span-2">
                            <InputLabel class="mb-1" for="instalation_date"
                                >Fecha de Instalación/Liquidación</InputLabel
                            >
                            <input
                                type="date"
                                v-model="form.instalation_date"
                                class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600"
                            />
                            <InputError
                                :message="form.errors.instalation_date"
                            />
                        </div>
                    </div>

                    <!-- Botones de Acción -->
                    <div
                        class="col-span-2 mt-2 flex items-center justify-end gap-x-6"
                    >
                        <SecondaryButton @click="closeLiquidateModal"
                            >Cancelar</SecondaryButton
                        >
                        <PrimaryButton
                            type="submit"
                            :class="{ 'opacity-25': form.processing }"
                            >Aceptar</PrimaryButton
                        >
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="showOpNuDatModal" @close="closeOpNuDatModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Actualización Masiva
                </h2>
                <form @submit.prevent="massive_liquidation">
                    <div class="space-y-6">
                        <div
                            class="inline-flex items-center p-2 mt-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
                            role="alert"
                        >
                            <svg
                                class="flex-shrink-0 inline w-4 h-4 me-3"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"
                                />
                            </svg>
                            <span class="sr-only">Info</span>
                            <div>
                                <span class="font-small"
                                    >Los equipos o materiales marcados serán
                                    liquidados en su totalidad y no serán
                                    devueltos a almacén.</span
                                >
                            </div>
                        </div>
                        <div
                            class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12"
                        >
                            <div>
                                <InputLabel
                                    for="entry_date"
                                    class="font-medium leading-6 text-gray-900"
                                    >Fecha de Instalación
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        type="date"
                                        v-model="opNuDateForm.instalation_date"
                                        id="entry_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="
                                            opNuDateForm.errors.instalation_date
                                        "
                                    />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeOpNuDatModal">
                                Cancelar
                            </SecondaryButton>
                            <button
                                type="submit"
                                :disabled="isFetching"
                                :class="{ 'opacity-25': isFetching }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                            >
                                Guardar
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <SuccessOperationModal
            :confirming="showModal"
            :title="`Éxito`"
            :message="`La liquidación se realizó correctamente.`"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, router, useForm } from "@inertiajs/vue3";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Modal from "@/Components/Modal.vue";
import { ref, watch } from "vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import SuccessOperationModal from "@/Components/SuccessOperationModal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import TableAutocompleteFilter from "@/Components/TableAutocompleteFilter.vue";

const props = defineProps({
    equipments: Object,
    materials: Object,
    huawei_project: String,
    project_name: String,
    projectState: Number,
    data: Object,
});

const backStatus =
    props.projectState == 1 ? "1" : props.projectState == null ? "2" : "3";

const liquidate_modal = ref(false);
const showModal = ref(false);
const liquidate_material = ref(false);
const liquidate_equipment = ref(false);
const liquidate_resource_id = ref(null);
const refund_quantity = ref(null);
const isFetching = ref(false);
const showOpNuDatModal = ref(false);
const filterMode = ref(false);
const isActive = ref(false);

const dataToRender = ref({
    equipments: props.equipments,
    materials: props.materials,
});

const form = useForm({
    liquidated_quantity: "",
    huawei_project_resource_id: "",
    instalation_date: "",
});


const openLiquidateModal = (item, equipment) => {
    liquidate_resource_id.value = item;
    if (equipment) {
        liquidate_equipment.value = true;
    } else {
        liquidate_material.value = true;
    }
    liquidate_modal.value = true;
};

const closeLiquidateModal = () => {
    form.reset();
    form.clearErrors();
    liquidate_equipment.value = false;
    liquidate_material.value = false;
    (liquidate_resource_id.value = null), (liquidate_modal.value = false);
};

const liquidate = () => {
    form.huawei_project_resource_id = liquidate_resource_id.value.id;
    const url = liquidate_equipment.value
        ? route("huawei.projects.liquidations.post", {
              huawei_project: props.huawei_project,
              equipment: 1,
          })
        : route("huawei.projects.liquidations.post", {
              huawei_project: props.huawei_project,
          });
    form.post(url, {
        onSuccess: () => {
            closeLiquidateModal();
            showModal.value = true;
            setTimeout(() => {
                showModal.value = false;
            }, 2000);
        },
    });
};

watch(
    [liquidate_resource_id, () => form.liquidated_quantity],
    ([resource, liquidatedQuantity]) => {
        if (resource && liquidatedQuantity !== "") {
            const remainingQuantity =
                resource.quantity - parseFloat(liquidatedQuantity);
            refund_quantity.value =
                remainingQuantity >= 0 ? remainingQuantity : 0;
        } else {
            refund_quantity.value = null;
        }
    }
);

const materialForm = ref({
    ids: [],
});

const equipmentForm = ref({
    ids: [],
});

const handleCheckAllEquipments = (e) => {
    if (e.target.checked) {
        equipmentForm.value.ids = dataToRender.value.equipments.map(
            (item) => item.id
        );
    } else {
        equipmentForm.value.ids = [];
    }
};
const handleCheckAllMaterials = (e) => {
    if (e.target.checked) {
        materialForm.value.ids = dataToRender.value.materials.map(
            (item) => item.id
        );
    } else {
        materialForm.value.ids = [];
    }
};

const opNuDateForm = useForm({
    ids: [],
    instalation_date: "",
});

const openNuUpdateModal = (equipment) => {
    if (equipment) {
        if (equipmentForm.value.ids.length === 0) {
            notifyWarning("No hay registros seleccionados");
            return;
        }
        opNuDateForm.ids = equipmentForm.value.ids;
    } else {
        if (materialForm.value.ids.length === 0) {
            notifyWarning("No hay registros seleccionados");
            return;
        }
        opNuDateForm.ids = materialForm.value.ids;
    }
    showOpNuDatModal.value = true;
};

const closeOpNuDatModal = () => {
    isFetching.value = false;
    showOpNuDatModal.value = false;
    opNuDateForm.reset();
};

const filterForm = ref({
    search: "",
    selectedSeries: props.data.series,
    selectedEquipmentOrderNumbers: props.data.equipment_order_numbers,
    selectedMaterialOrderNumbers: props.data.material_order_numbers,
});

watch(
    () => [
        filterForm.value.search,
        filterForm.value.selectedSeries,
        filterForm.value.selectedEquipmentOrderNumbers,
        filterForm.value.selectedMaterialOrderNumbers,
    ],
    () => {
        (filterMode.value = true), search_advance(filterForm.value);
    },
    { deep: true }
);

async function search_advance($data) {
    materialForm.value.ids = [];
    equipmentForm.value.ids = [];
    let url = route("huawei.projects.liquidations.searchadvance", {
        huawei_project: props.huawei_project,
    });
    try {
        let response = await axios.post(url, $data);
        dataToRender.value.equipments = response.data.equipments;
        dataToRender.value.materials = response.data.materials;
    } catch (error) {
        console.error(error);
    }
}

const massive_liquidation = (equipment) => {
    if (equipment) {
        opNuDateForm.post(
            route("huawei.projects.liquidations.massiveliquidation", {
                equipment: 1,
            }),
            {
                onSuccess: () => {
                    closeOpNuDatModal();
                    opNuDateForm.reset();
                    showModal.value = true;
                    setTimeout(() => {
                        showModal.value = false;
                        router.visit(
                            route("huawei.projects.liquidations", {
                                huawei_project: props.huawei_project,
                            })
                        );
                    }, 2000);
                },
                onError: (e) => {
                    console.error(e);
                },
            }
        );
    } else {
        opNuDateForm.post(
            route("huawei.projects.liquidations.massiveliquidation"),
            {
                onSuccess: () => {
                    closeOpNuDatModal();
                    opNuDateForm.reset();
                    showModal.value = true;
                    setTimeout(() => {
                        showModal.value = false;
                        router.visit(
                            route("huawei.projects.liquidations", {
                                huawei_project: props.huawei_project,
                            })
                        );
                    }, 2000);
                },
                onError: (e) => {
                    console.error(e);
                },
            }
        );
    }
};

const toggleSwitch = () => {
        isActive.value = !isActive.value;
    };
</script>
