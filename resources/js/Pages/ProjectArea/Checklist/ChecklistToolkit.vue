<template>
    <Head title="ChecklistVehicular" />
    <AuthenticatedLayout :redirectRoute="'checklist.index'">
        <template #header> Checklist Herramientas </template>
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
                                Motivo
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Checklist
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Herramientas Buenas
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Herramientas Malas
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
                                        {{ item.user?.name }}
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ item.additionalEmployees }}
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ item.reason }}
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
                                    <a v-if="item.goodTools"
                                        :href="route('checklist.toolkit.photo', {
                                            id: item.id, photoProp: 'goodTools'
                                        })"
                                    >
                                        <EyeIcon class="text-teal-500 w-5" />
                                    </a>
                                    <p v-else>
                                        -
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                    <a v-if="item.badTools"
                                    :href="route('checklist.toolkit.photo', {
                                            id: item.id, photoProp: 'badTools'
                                        })"
                                    >
                                        <EyeIcon class="text-teal-500 w-5" />
                                    </a>
                                    <p v-else>
                                        -
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ item.observation }}
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                <button
                                        @click.prevent="confirmDeleteAdditional(item.id)"
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
                    Checklist de Herramientas
                </h2>
                <br />
                <div class="mt-2">
                    <div class="flex space-x-5 w-full">
                        <div class="w-1/2 flex flex-col h-full space-y-5">
                            <div>
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
                                                Control EPPS
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, i
                                            ) in itemArrays.eppArray"
                                            :key="i"
                                            class="text-gray-700 bg-white text-xs"
                                        >
                                            <td
                                                class="border-b border-slate-300 px-2 py-2"
                                            >
                                                {{ item?.name }}
                                            </td>
                                            <td
                                                class="border-b border-slate-300 px-2 py-2"
                                            >
                                                {{ item?.value }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div>
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
                                                Kit de herramientas
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr
                                            v-for="(
                                                item, i
                                            ) in itemArrays.toolkitArray"
                                            :key="i"
                                            class="text-gray-700 bg-white text-xs"
                                        >
                                            <td
                                                class="border-b border-slate-300 px-2 py-2"
                                            >
                                                {{ item?.name }}
                                            </td>
                                            <td
                                                class="border-b border-slate-300 px-2 py-2"
                                            >
                                                {{ item?.value }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="w-1/2">
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
                                            Control de equipos
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(
                                            item, i
                                        ) in itemArrays.equipmentArray"
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
        <ConfirmDeleteModal
            :confirmingDeletion="confirmingDocDeletion"
            itemType="Checklist Unidad Móvil"
            :deleteFunction="deleteAdditional"
            @closeModal="closeModalDoc"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import { ref } from "vue";
import Modal from "@/Components/Modal.vue";
import { Head, router } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import { TrashIcon } from "@heroicons/vue/24/outline";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import { formattedDate } from "@/utils/utils";
import { EyeIcon } from "@heroicons/vue/24/outline";

const { checklists } = defineProps({
    checklists: Object,
    auth: Object,
    userPermissions: Array,
});

const showChecklistModal = ref(false);
const itemArrays = ref({ eppArray: [], toolkitArray: [], equipmentArray: [] });

function openChecklistModal(item) {
    itemArrays.value.eppArray = [
        { name: "Carro anticaídas", value: item.fallProtectionCar },
        { name: "Arnés", value: item.harness },
    ];
    itemArrays.value.toolkitArray = [
        { name: "Mosquetón", value: item.carabiner },
        { name: "Pelacable", value: item.wireStripper },
        { name: "Crimpeador de RJ45", value: item.crimper },
        { name: "Crimpeador de terminales", value: item.terminalCrimper },
        { name: "Limas", value: item.files },
        { name: "Llaves allen", value: item.allenKeys },
        { name: "Kit redline", value: item.readlineKit },
        { name: "Desarmadores de impacto", value: item.impactWrench },
        { name: "Desarmadores dieléctrico", value: item.dielectricTools },
        { name: "Alicate de corte", value: item.cuttingTools },
        { name: "Alicate de fuerza", value: item.forceps },
        { name: "Alicate recto", value: item.straightWrench },
        { name: "Llaves francesas", value: item.frenchWrench },
        { name: "Sierra", value: item.saw },
        { name: "Aplicador de silicona", value: item.silicone },
        { name: "Polea", value: item.pulley },
        { name: "Wincha", value: item.tapeMeasure },
        { name: "Eslinga", value: item.sling },
        { name: "Botiquín", value: item.kit },
        { name: "Juego de brocas", value: item.drillBits },
        { name: "Sacabocado", value: item.punch },
        { name: "Extractor de aceite", value: item.extractor },
        { name: "Juego de llaves", value: item.wrenchSet },
        { name: "Juego de dados", value: item.braveDices },
        { name: "Cutter", value: item.cutter },
        { name: "Llave thor", value: item.hammer },
        { name: "Maleta grande", value: item.largeToolBag },
        { name: "Maleta mediana", value: item.mediumToolBag },
    ];
    itemArrays.value.equipmentArray = [
        { name: "Hidrolavadora", value: item.pressureWasher },
        { name: "Sopladora", value: item.blower },
        { name: "Megómetro", value: item.megommeter },
        { name: "Telurómetro", value: item.earthTester },
        { name: "Pinza amperimétrica", value: item.perimeterMeter },
        { name: "Manómetro", value: item.manometer },
        { name: "Pirómetro", value: item.pyrometer },
        { name: "Laptop", value: item.laptop },
        { name: "Taladro", value: item.drill },
        { name: "Brújula", value: item.compass },
        { name: "Inclinometro", value: item.inclinometer },
        { name: "Linterna", value: item.flashlight },
        { name: "Power Meter", value: item.powerMeter },
        { name: "Pistola de calor", value: item.glueGun },
        { name: "Pistola de Estaño", value: item.solderingGun },
        { name: "Escalera tijera", value: item.stepLadder },
        { name: "Pulverizadora", value: item.sprayer },
        { name: "Testeador RJ45", value: item.rj45Connector },
        { name: "Cables de consola y red", value: item.networkConsole },
        { name: "Adaptador de red", value: item.networkAdapter },
        { name: "Pértiga", value: item.hotStick },
        { name: "Soga 75m", value: item.rope75 },
        { name: "Escalera", value: item.ladder },
        { name: "Extensión", value: item.extensionCord },
        { name: "Cable largo", value: item.longCable },
        { name: "Candado", value: item.padlock },
        { name: "Cadenas", value: item.chains },
        { name: "Manguera", value: item.hose },
        { name: "Celular corporativo", value: item.corporatePhone },
        
    ];
    showChecklistModal.value = true;
}
function closeChecklistModal() {
    showChecklistModal.value = false;
}

// delete
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
            route("checklist.toolkit.destroy", {
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
