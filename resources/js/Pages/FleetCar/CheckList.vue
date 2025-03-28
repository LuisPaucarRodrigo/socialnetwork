<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'fleet.cars.index'">
        <template #header> CheckList </template>
        <div class="flex flex-space gap-2">
            <span class="font-black">Dueño: </span><span>{{ props.car.user.name }}</span>
        </div>
        <div class="flex flex-space gap-2">
            <span class="font-black">Placa: </span><span>{{ props.car.plate }}</span>
        </div>

        <TableStructure>
            <template #thead>
                <tr>
                    <TableTitle>Fecha de Registro</TableTitle>
                    <TableTitle>Zona</TableTitle>
                    <TableTitle>Personal 1</TableTitle>
                    <TableTitle>Personal 2</TableTitle>
                    <TableTitle>Motivo</TableTitle>
                    <TableTitle>Checklist</TableTitle>
                    <TableTitle>Imágenes</TableTitle>
                    <TableTitle>Observaciones</TableTitle>
                </tr>
            </template>
            <template #tbody>
                <tr v-for="item in checklist.data" :key="item.id">
                    <TableRow>{{ formattedDate(item.created_at) }}</TableRow>
                    <TableRow>{{ item.zone }}</TableRow>
                    <TableRow>{{ item.user_name }}</TableRow>
                    <TableRow>{{ item.additionalEmployees }}</TableRow>
                    <TableRow>{{ item.reason }}</TableRow>
                    <TableRow>
                        <button type="button">
                            <EyeIcon @click="openChecklist(item)" class="text-green-500 w-5" />
                        </button>
                    </TableRow>
                    <TableRow>
                        <button type="button">
                            <PhotoIcon @click="openImages(item.id)" class="text-indigo-600 w-5" />
                        </button>
                    </TableRow>
                    <TableRow>{{ item.observation }}</TableRow>
                </tr>
            </template>
        </TableStructure>
        <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="checklist.links" />
        </div>
        <CarouselModal :images="images" :show="isModalOpen" @close="closeModal" />

        <Modal :show="checklist_modal" @close="openChecklist(null)" max-width="2xl" :closeable="true">
            <div class="py-6 px-3">
                <h2 class="px-2 text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Checklist Vehicular
                </h2>
                <div class="mt-2">
                    <div class="flex space-x-2 w-full">
                        <div class="w-1/2 flex flex-col h-full space-y-5">
                            <div>
                                <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                                    <thead>
                                        <tr
                                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 w-auto">
                                            <th colspan="2"
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                Documentación
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in first.filter(
                                            Boolean
                                        )" :key="index" class="text-gray-700 text-xs">
                                            <td class="border-b border-gray-200 bg-white px-5 py-2">
                                                <p class="text-gray-900">
                                                    {{ Object.values(item)[0] }}
                                                </p>
                                            </td>
                                            <td
                                                class="border-b border-gray-200 font-black bg-white text-center px-5 py-2">
                                                <p :class="show_checklist[
                                                    Object.keys(item)[0]
                                                ]
                                                    ? 'text-green-600'
                                                    : 'text-gray-900'
                                                    ">
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
                                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 w-auto">
                                        <th colspan="2"
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Equipamiento del Vehículo
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in second.filter(
                                        Boolean
                                    )" :key="index" class="text-gray-700 text-xs">
                                        <td class="border-b border-gray-200 bg-white px-5 py-2">
                                            <p class="text-gray-900">
                                                {{ Object.values(item)[0] }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 font-black bg-white text-center px-5 py-2">
                                            <p :class="show_checklist[
                                                Object.keys(item)[0]
                                            ]
                                                ? 'text-green-600'
                                                : 'text-gray-900'
                                                ">
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
import { Head } from "@inertiajs/vue3";
import axios from "axios";
import { ref } from "vue";
import CarouselModal from "@/Components/CarouselModal.vue";
import { formattedDate } from "@/utils/utils";
import { PhotoIcon, EyeIcon } from "@heroicons/vue/24/outline";
import Pagination from "@/Components/Pagination.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import TableStructure from "@/Layouts/TableStructure.vue";
import TableTitle from "@/Components/TableTitle.vue";
import TableRow from "@/Components/TableRow.vue";

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
