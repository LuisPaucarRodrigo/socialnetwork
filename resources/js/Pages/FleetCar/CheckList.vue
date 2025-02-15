<template>
    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'fleet.cars.index'">
        <template #header> CheckList </template>
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
                                Imágenes
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                            >
                                Observaciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="item in checklist.data" :key="item.id">
                            <tr class="text-gray-700 border-b">
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-center text-sm"
                                >
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ formattedDate(item.created_at) }}
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-center text-sm"
                                >
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ item.zone }}
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-center text-sm"
                                >
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ item.user_name }}
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-center text-sm"
                                >
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ item.additionalEmployees }}
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-center text-sm"
                                >
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ item.reason }}
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                    <div
                                        class="flex items-center justify-center"
                                    >
                                        <button type="button">
                                            <EyeIcon
                                                @click="openChecklist(item)"
                                                class="text-green-500 w-5"
                                            />
                                        </button>
                                    </div>
                                </td>
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-sm"
                                >
                                    <div
                                        class="flex items-center justify-center"
                                    >
                                        <button type="button">
                                            <PhotoIcon
                                                @click="openImages(item.id)"
                                                class="text-indigo-600 w-5"
                                            />
                                        </button>
                                    </div>
                                </td>

                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-5 text-center text-sm"
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
                <pagination :links="checklist.links" />
            </div>
        </div>

        <CarouselModal
            :images="images"
            :show="isModalOpen"
            @close="closeModal"
        />

        <Modal
            :show="checklist_modal"
            @close="openChecklist(null)"
            max-width="2xl"
            :closeable="true"
        >
            <div class="py-6 px-3">
                <h2
                    class="px-2 text-lg font-medium text-gray-800 border-b-2 border-gray-100"
                >
                    Checklist Vehicular
                </h2>
                <div class="mt-2">
                    <div class="flex space-x-2 w-full">
                        <div class="w-1/2 flex flex-col h-full space-y-5">
                            <div>
                                <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                                <thead>
                                    <tr
                                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 w-auto"
                                    >
                                        <th
                                            colspan="2"
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                        >
                                            Documentación
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(item, index) in first.filter(
                                            Boolean
                                        )"
                                        :key="index"
                                        class="text-gray-700 text-xs"
                                    >
                                        <td
                                            class="border-b border-gray-200 bg-white px-5 py-2"
                                        >
                                            <p class="text-gray-900">
                                                {{ Object.values(item)[0] }}
                                            </p>
                                        </td>
                                        <td
                                            class="border-b border-gray-200 font-black bg-white text-center px-5 py-2"
                                        >
                                            <p
                                                :class="
                                                    show_checklist[
                                                        Object.keys(item)[0]
                                                    ]
                                                        ? 'text-green-600'
                                                        : 'text-gray-900'
                                                "
                                            >
                                                {{
                                                    show_checklist[
                                                        Object.keys(item)[0]
                                                    ] ?? "N/A"
                                                }}
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            </div>

                        </div>

                        <div class="w-1/2">
                            <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                                <thead>
                                    <tr
                                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 w-auto"
                                    >
                                        <th
                                            colspan="2"
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                        >
                                            Equipamiento del Vehículo
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr
                                        v-for="(item, index) in second.filter(
                                            Boolean
                                        )"
                                        :key="index"
                                        class="text-gray-700 text-xs"
                                    >
                                        <td
                                            class="border-b border-gray-200 bg-white px-5 py-2"
                                        >
                                            <p class="text-gray-900">
                                                {{ Object.values(item)[0] }}
                                            </p>
                                        </td>
                                        <td
                                            class="border-b border-gray-200 font-black bg-white text-center px-5 py-2"
                                        >
                                            <p
                                                :class="
                                                    show_checklist[
                                                        Object.keys(item)[0]
                                                    ]
                                                        ? 'text-green-600'
                                                        : 'text-gray-900'
                                                "
                                            >
                                                {{
                                                    show_checklist[
                                                        Object.keys(item)[0]
                                                    ] ?? "N/A"
                                                }}
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="m-6 flex justify-end">
                <SecondaryButton type="button" @click="openChecklist(null)">
                    Cerrar
                </SecondaryButton>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import axios from "axios";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref } from "vue";
import CarouselModal from "@/Components/CarouselModal.vue";
import { formattedDate } from "@/utils/utils";
import { TrashIcon, PhotoIcon, EyeIcon } from "@heroicons/vue/24/outline";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
    car: Object,
    checklist: Object,
});

const images = ref([]);
const isModalOpen = ref(false);
const show_checklist = ref(null);
const checklist_modal = ref(false);

const first = [
    { beak: "Pico" },
    { shovel: "Pala" },
    { chocks: "Tacos" },
    { spareTire: "Llanta de repuesto" },
    { jack: "Gata y Accesorios" },
    { gps: "GPS" },
    { rollCage: "Jaula antivuelco" },
    { extinguisher: "Extintor" },
    { firstAidKit: "Botiquín" },
    { fogLights: "Luces neblineras" },
    { protectionCage: "Jaula de protección" },
    { reflector: "Cintas reflectantes" },
    { hoopInsurance: "Seguro de aros" },
    { headlightInsurance: "Seguro de faros" },
    { cardProtector: "Protector de carte" },
    { cones: "Conos de seguridad" },
    { safetyTriangle: "Triángulo de seguridad" },
    { batteryCable: "Cables de batería" },
    { wheelWrench: "Llave de ruedas" },
    { alarm: "Alarma de seguridad" },
    { ladderHolder: "Porta escalera" },
    { sidePlate: "Placa impresa laterales" },
];

const second = [
    { hornState: "Bocinas" },
    { brakesState: "Frenos" },
    { headlightsState: "Luces altas y bajas" },
    { intermitentlightState: "Luces intermitentes" },
    { indicatorsState: "Direccionales" },
    { mirrorsState: "Retrovisores" },
    { tiresState: "Estado de neumáticos" },
    { bumpersState: "Parachoques" },
    { temperatureGauge: "Marcadores de temperatura" },
    { oilGauge: "Marcador de aceite" },
    { fuelGauge: "Marcador de combustible" },
    { vehicleCleanliness: "Aseo general de vehículo" },
    { doorsState: "Estado de puertas" },
    { windshieldState: "Estado del parabrisas" },
    { engineState: "Estado del motor" },
    { batteryState: "Estado de la batería" },
];

async function openImages(id) {
    await axios
        .get(route("fleet.cars.show_checklist.send_images", { checklist: id }))
        .then((res) => {
            images.value = res.data;
            isModalOpen.value = true;
        })
        .catch((e) => {
            console.error(e);
        });
}

function closeModal() {
    images.value = [];
    isModalOpen.value = false;
}

function openChecklist(item) {
    show_checklist.value = item ?? null;
    checklist_modal.value = !checklist_modal.value;
}
</script>
