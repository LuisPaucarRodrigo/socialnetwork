<template>
    <Head title="ChecklistDiarioHerramientas" />
    <AuthenticatedLayout :redirectRoute="'checklist.index'">
        <template #header> Checklist Diario de Herramientas </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <div class="min-w-full overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                        >
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Fecha de Registro
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Zona
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Personal 1
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Personal 2
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Checklist
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Observaciones
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Más
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template
                            v-for="item in checklists.data"
                            :key="item.id"
                        >
                            <tr class="text-gray-700 border-b">
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ formattedDate(item.created_at) }}
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ item.zone }}
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ item.user.name }}
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ item.personal_2 }}
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                    <button type="button">
                                        <EyeIcon
                                            @click="openChecklistModal(item)"
                                            class="text-indigo-600 w-5"
                                        />
                                    </button>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ item.observations }}
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                    <button
                                        @click.prevent="
                                            confirmDeleteAdditional(item.id)
                                        "
                                        class="text-red-600 hover:underline mr-2"
                                    >
                                        <TrashIcon class="h-5 w-5" />
                                    </button>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            <ConfirmDeleteModal
                :confirmingDeletion="confirmingDocDeletion"
                itemType="Checklist Unidad Móvil"
                :deleteFunction="deleteAdditional"
                @closeModal="closeModalDoc"
            />
            <div
                class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between"
            >
                <pagination :links="checklists.links" />
            </div>
        </div>

        <Modal
            :show="showChecklistModal"
            @close="closeChecklistModal"
            max-width="2xl"
            :closeable="true"
        >
            <div class="p-6">
                <h2
                    class="text-lg font-medium text-gray-800 border-b-2 border-gray-100"
                >
                    Checklist Diario de Herramientas
                </h2>
                <br />
                <div class="mt-2">
                    <div class="overflow-auto">
                        <table
                            class="w-full whitespace-no-wrap border-collapse border border-slate-300"
                        >
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500"
                                >
                                    <th
                                        colspan="2"
                                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-gray-600"
                                    >
                                        Estado de las Herramientas
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="(item, i) in itemArrays.stateArray"
                                    :key="i"
                                    class="text-gray-700 bg-white text-xs"
                                >
                                    <td
                                        class="border-b border-slate-300 px-2 py-2"
                                    >
                                        {{ item.name }}
                                    </td>
                                    <td
                                        class="border-b border-slate-300 px-2 py-2"
                                    >
                                        {{ item.value }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <br />
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton
                            type="button"
                            @click="closeChecklistModal"
                        >
                            Cerrar
                        </SecondaryButton>
                    </div>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import { Head } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { formattedDate } from "@/utils/utils";
import { EyeIcon } from "@heroicons/vue/24/outline";
import { TrashIcon } from "@heroicons/vue/24/outline";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";

const { checklists } = defineProps({
    checklists: Object,
    auth: Object,
    userPermissions: Array,
});

const showChecklistModal = ref(false);
const itemArrays = ref({ stateArray: [] });

function openChecklistModal(item) {
    itemArrays.value.stateArray = [
        {
            name: "Medidor Potencia p-series power meter",
            value: item.power_meter,
        },
        { name: "Pinza amperimétrica metrel", value: item.ammeter_clamp },
        { name: "Alicate de corte", value: item.cutting_pliers },
        { name: "Alicate de punta", value: item.nose_pliers },
        { name: "Alicate universal", value: item.universal_pliers },
        { name: "Cinta métrica 5m (wincha)", value: item.tape },
        { name: "Cutter", value: item.cutter },
        { name: "Desarmador perillero (6 piezas)", value: item.knob_driver },
        {
            name: "Juego de desarmadores 1000v (7 piezas)",
            value: item.screwdriver_set,
        },
        {
            name: "Juego 7 llaves allen standard en estuche",
            value: item.allenkeys_set,
        },
        { name: "Juego desarmador thor", value: item.thor_screwboard },
        { name: "Linterna p/casco 3AAA led", value: item.helmet_flashlight },
        { name: "Llave francesa de 6''", value: item.freanch_key },
        { name: "Pirómetro", value: item.pyrometer },
        { name: "Laptop", value: item.laptop },
        { name: "Cable de consola", value: item.console_cables },
        { name: "Adapatador de red", value: item.network_adapter },
    ];
    showChecklistModal.value = true;
}
function closeChecklistModal() {
    showChecklistModal.value = false;
}

//photos modal


const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const confirmDeleteAdditional = (additionalId) => {
    docToDelete.value = additionalId;
    confirmingDocDeletion.value = true;
};
const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};
const deleteAdditional = () => {
    const docId = docToDelete.value;
    if (docId) {
        router.delete(
            route("checklist.dailytoolkit.destroy", {
                id: docId,
            }),
            {
                onSuccess: () => {
                    closeModalDoc();
                },
                onError: (e) => {
                    console.log(e)
                }
            }
        );
    }
};
</script>
