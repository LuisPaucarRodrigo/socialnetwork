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
                                    <a
                                        href="#"
                                    >
                                        <EyeIcon class="text-teal-500 w-5" />
                                    </a>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                    <a
                                        href="#"
                                    >
                                        <EyeIcon class="text-teal-500 w-5" />
                                    </a>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ item.observation }}
                                    </p>
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
        { name: "Carro anticaídas", value: item.km },
        { name: "Línea de vida", value: item.circulation },
        { name: "Lína de posicionamiento", value: item.technique },
        { name: "Arnés", value: item.soat },
    ];
    itemArrays.value.toolkitArray = [
        { name: "Mosquetón", value: item.hornState },
        { name: "Pelacable", value: item.brakesState },
        { name: "Crimpeador de RJ45", value: item.headlightsState },
        { name: "Crimpeador de terminales", value: item.intermitentlightState },
        { name: "Limas", value: item.indicatorsState },
        { name: "Llaves allen", value: item.mirrorsState },
        { name: "Kit redline", value: item.tiresState },
        { name: "Desarmadores de impacto", value: item.bumpersState },
        { name: "Desarmadores dieléctrico", value: item.temperatureGauge },
        { name: "Alicate de corte", value: item.oilGauge },
        { name: "Alicate de fuerza", value: item.fuelGauge },
        { name: "Alicate recto", value: item.vehicleCleanliness },
        { name: "Llaves francesas", value: item.doorsState },
        { name: "Sierra", value: item.windshieldState },
        { name: "Aplicador de silicona", value: item.engineState },
        { name: "Polea", value: item.batteryState },
        { name: "Wincha", value: item.batteryState },
        { name: "Eslinga", value: item.batteryState },
        { name: "Botiquín", value: item.batteryState },
        { name: "Juego de brocas", value: item.batteryState },
        { name: "Sacabocado", value: item.batteryState },
        { name: "Extractor de aceite", value: item.batteryState },
        { name: "Juego de llaves", value: item.batteryState },
        { name: "Juego de dados", value: item.batteryState },
        { name: "Cutter", value: item.batteryState },
        { name: "Llave thor", value: item.batteryState },
        { name: "Maleta grande", value: item.batteryState },
        { name: "Maleta mediana", value: item.batteryState },
    ];
    itemArrays.value.equipmentArray = [
        { name: "Hidrolavadora", value: item.extinguisher },
        { name: "Sopladora", value: item.extinguisher },
        { name: "Megómetro", value: item.extinguisher },
        { name: "Telurómetro", value: item.extinguisher },
        { name: "Pinza amperimétrica", value: item.extinguisher },
        { name: "Manómetro", value: item.extinguisher },
        { name: "Pirómetro", value: item.extinguisher },
        { name: "Laptop", value: item.extinguisher },
        { name: "Taladro", value: item.extinguisher },
        { name: "Brújula", value: item.extinguisher },
        { name: "Inclinometro", value: item.extinguisher },
        { name: "Linterna", value: item.extinguisher },
        { name: "Power Meter", value: item.extinguisher },
        { name: "Pistola de calor", value: item.extinguisher },
        { name: "Pistola de Estaño", value: item.extinguisher },
        { name: "Escalera tijera", value: item.extinguisher },
        { name: "Pulverizadora", value: item.extinguisher },
        { name: "Testeador RJ45", value: item.extinguisher },
        { name: "Cables de consola y red", value: item.extinguisher },
        { name: "Adaptador de red", value: item.extinguisher },
        { name: "Pértiga", value: item.extinguisher },
        { name: "Soga 75m", value: item.extinguisher },
        { name: "Escalera", value: item.extinguisher },
        { name: "Extensión", value: item.extinguisher },
        { name: "Cable largo", value: item.extinguisher },
        { name: "Candado", value: item.extinguisher },
        { name: "Cadenas", value: item.extinguisher },
        { name: "Manguera", value: item.extinguisher },
        { name: "Celular corporativo", value: item.extinguisher },
        
    ];
    showChecklistModal.value = true;
}
function closeChecklistModal() {
    showChecklistModal.value = false;
}

</script>
