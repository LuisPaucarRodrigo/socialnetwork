<template>
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
                                    <tr v-for="(item, index) in first" :key="index" class="text-gray-700 text-xs">
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
</template>
<script setup>
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { ref } from "vue";

const { car } = defineProps({
    car: Object
})

const show_checklist = ref(null);
const checklist_modal = ref(false);

function openChecklist(item) {
    show_checklist.value = item ?? null;
    checklist_modal.value = !checklist_modal.value;
}

const first = [
    // { km: "Kilometraje" },
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

defineExpose({ openChecklist })
</script>