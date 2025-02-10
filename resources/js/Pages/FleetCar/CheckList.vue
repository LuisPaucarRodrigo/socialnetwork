<template>
    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'fleet.cars.index'">
        <template #header> CheckList </template>
        <PrimaryButton @click="openImages">Imágenes</PrimaryButton>
        <div class="min-w-full rounded-lg shadow">
            <div class="grid grid-cols-2 gap-4 p-4">
                <div class="overflow-x-auto min-h-[72vh] rounded-lg shadow">
                    <table class="w-full bg-white">
                        <thead class="sticky top-0 z-20">
                            <tr
                                class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 w-auto"
                            >
                                <th
                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                >
                                    Item
                                </th>
                                <th
                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                                >
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in first.filter(Boolean)"
                                :key="index"
                                class="text-gray-700"
                            >
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-2 text-sm"
                                >
                                    <p class="text-gray-900">
                                        {{ Object.values(item)[0] }}
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 font-black bg-white text-center px-5 py-2 text-sm"
                                >
                                    <p :class="checklist[Object.keys(item)[0]] ? 'text-green-600' : 'text-gray-900'">
                                        {{
                                            checklist[Object.keys(item)[0]] ? 'OK' : 'N/A'
                                        }}
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="overflow-x-auto min-h-[72vh] rounded-lg shadow">
                    <table class="w-full bg-white">
                        <thead class="sticky top-0 z-20">
                            <tr
                                class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500 w-auto"
                            >
                                <th
                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600"
                                >
                                    Item
                                </th>
                                <th
                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                                >
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(item, index) in second.filter(Boolean)"
                                :key="index"
                                class="text-gray-700"
                            >
                                <td
                                    class="border-b border-gray-200 bg-white px-5 py-2 text-sm"
                                >
                                    <p class="text-gray-900">
                                        {{ Object.values(item)[0] }}
                                    </p>
                                </td>
                                <td
                                    class="border-b border-gray-200 font-black bg-white text-center px-5 py-2 text-sm"
                                >
                                    <p :class="checklist[Object.keys(item)[0]] ? 'text-green-600' : 'text-gray-900'">
                                        {{
                                            checklist[Object.keys(item)[0]] ? 'OK' : 'N/A'
                                        }}
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <CarouselModal
            :images="images"
            :show="isModalOpen"
            @close="closeModal"
        />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head, router } from "@inertiajs/vue3";
import axios from "axios";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { ref } from "vue";
import CarouselModal from "@/Components/CarouselModal.vue";

const props = defineProps({
    car: Object,
    checklist: Object,
});

const images = ref([]);
const isModalOpen = ref(false);

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

async function openImages (){
    await axios.get(route('fleet.cars.show_checklist.send_images', {checklist: props.checklist.id}))
        .then(res => {
            images.value = res.data;
            isModalOpen.value = true;
        })
        .catch(e => {
            console.error(e)
        });
}

function closeModal() {
    images.value = [];
    isModalOpen.value = false;
}
</script>
