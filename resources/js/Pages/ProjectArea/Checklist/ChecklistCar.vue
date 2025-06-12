<template>

    <Head title="ChecklistVehicular" />
    <AuthenticatedLayout :redirectRoute="'checklist.index'">
        <template #header> Checklist Vehicular </template>
        <TableStructure>
            <template #thead>
                <tr>
                    <TableTitle>Fecha de Registro</TableTitle>
                    <TableTitle>Zona</TableTitle>
                    <TableTitle>Personal 1</TableTitle>
                    <TableTitle>Personal 2</TableTitle>
                    <TableTitle>Motivo</TableTitle>
                    <TableTitle>Checklist</TableTitle>
                    <TableTitle>Fotos</TableTitle>
                    <TableTitle>Observaciones</TableTitle>
                    <TableTitle>Más</TableTitle>
                </tr>
            </template>
            <template #tbody>
                <tr v-for="item in checklists.data" :key="item.id">
                    <TableRow>{{ formattedDate(item.created_at) }}</TableRow>
                    <TableRow>{{ item.zone }}</TableRow>
                    <TableRow>{{ item.user_name }}</TableRow>
                    <TableRow>{{ item.additionalEmployees }}</TableRow>
                    <TableRow>{{ item.reason }}</TableRow>
                    <TableRow>
                        <button type="button" @click="openChecklistModal(item)">
                            <ShowIcon />
                        </button>
                    </TableRow>
                    <TableRow>
                        <button type="button" @click="openPhotosModal(item.id)">
                            <ShowIcon />
                        </button>
                    </TableRow>
                    <TableRow width="w-[500px]">
                        <p class="text-gray-900 whitespace-no-wrap">
                            {{ item.observation }}
                        </p>
                    </TableRow>
                    <TableRow>
                        <button type="button" @click.prevent="confirmDeleteAdditional(item.id)">
                            <DeleteIcon />
                        </button>
                    </TableRow>
                </tr>
            </template>
        </TableStructure>
        <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="checklists.links" />
        </div>

        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Checklist Unidad Móvil"
            :deleteFunction="deleteAdditional" @closeModal="closeModalDoc" />

        <Modal :show="showPhotosModal" @close="closePhotosModal" max-width="md" :closeable="true">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Fotos
                </h2>
                <br />
                <div class="mt-2">
                    <div class="overflow-auto">
                        <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Nombre
                                    </th>
                                    <th class="border-b-2 border-gray-200 bg-gray-100 px-4 py-2 text-gray-600">
                                        Foto
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(item, i) in itemPhotos.photos" :key="i"
                                    class="text-gray-700 bg-white text-sm">
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        {{ item?.name }}
                                    </td>
                                    <td class="border-b border-slate-300 px-4 py-4">
                                        <a v-if="item.value" :href="route('checklist.car.photo', {
                                            id: itemPhotos.id,
                                            photoProp: item.value,
                                        })
                                            " target="_blank" class="text-indigo-600 hover:underline">
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <br />
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closePhotosModal">
                            Cerrar
                        </SecondaryButton>
                    </div>
                </div>
            </div>
        </Modal>

        <Modal :show="showChecklistModal" @close="closeChecklistModal" max-width="2xl" :closeable="true">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Checklist Vehicular
                </h2>
                <br />
                <div class="mt-2">
                    <div class="flex space-x-5 w-full">
                        <div class="w-1/2 flex flex-col h-full space-y-5">
                            <div>
                                <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                                    <thead>
                                        <tr
                                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                            <th colspan="2"
                                                class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-gray-600">
                                                Documentación
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(
item, i
                                            ) in itemArrays.docArray" :key="i" class="text-gray-700 bg-white text-xs">
                                            <td class="border-b border-slate-300 px-2 py-2">
                                                {{ item?.name }}
                                            </td>
                                            <td class="border-b border-slate-300 px-2 py-2">
                                                {{ item.value }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div>
                                <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                                    <thead>
                                        <tr
                                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                            <th colspan="2"
                                                class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-gray-600">
                                                Equipamiento del vehículo
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(
item, i
                                            ) in itemArrays.equipementArray" :key="i"
                                            class="text-gray-700 bg-white text-xs">
                                            <td class="border-b border-slate-300 px-2 py-2">
                                                {{ item?.name }}
                                            </td>
                                            <td class="border-b border-slate-300 px-2 py-2">
                                                {{ item.value }}
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
                                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                        <th colspan="2"
                                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-gray-600">
                                            Estado del vehículo
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(
item, i
                                        ) in itemArrays.stateArray" :key="i" class="text-gray-700 bg-white text-xs">
                                        <td class="border-b border-slate-300 px-2 py-2">
                                            {{ item?.name }}
                                        </td>
                                        <td class="border-b border-slate-300 px-2 py-2">
                                            {{ item.value }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <br />
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeChecklistModal">
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
import { formattedDate } from "@/utils/utils";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import TableStructure from "@/Layouts/TableStructure.vue";
import TableTitle from "@/Components/TableTitle.vue";
import TableRow from "@/Components/TableRow.vue";
import { DeleteIcon, ShowIcon } from "@/Components/Icons/Index";


const { checklists } = defineProps({
    checklists: Object,
    auth: Object,
    userPermissions: Array,
});

const showChecklistModal = ref(false);
const itemArrays = ref({ docArray: [], stateArray: [], equipementArray: [] });

function openChecklistModal(item) {
    itemArrays.value.docArray = [
        { name: "Kilometraje", value: item.km },
    ];
    itemArrays.value.stateArray = [
        { name: "Bocinas", value: item.hornState },
        { name: "Frenos", value: item.brakesState },
        { name: "Luces altas y bajas", value: item.headlightsState },
        { name: "Luces intermitentes", value: item.intermitentlightState },
        { name: "Direccionales", value: item.indicatorsState },
        { name: "Retrovisores", value: item.mirrorsState },
        { name: "Estado de neúmaticos", value: item.tiresState },
        { name: "Parachoques", value: item.bumpersState },
        { name: "Marcadores de temperatura", value: item.temperatureGauge },
        { name: "Marcador de aceite", value: item.oilGauge },
        { name: "Marcador de combustible", value: item.fuelGauge },
        { name: "Aseo general de vehículo", value: item.vehicleCleanliness },
        { name: "Estado de puertas", value: item.doorsState },
        { name: "Estado del parabrisas", value: item.windshieldState },
        { name: "Estado del motor", value: item.engineState },
        { name: "Estado de la batería", value: item.batteryState },
    ];
    itemArrays.value.equipementArray = [
        { name: "Extintor", value: item.extinguisher },
        { name: "Botiquín", value: item.firstAidKit },
        { name: "Conos", value: item.cones },
        { name: "Gata", value: item.jack },
        { name: "Neumático de respuesto", value: item.spareTire },
        { name: "Cable de remolque", value: item.towCable },
        { name: "Cable de batería", value: item.batteryCable },
        { name: "Cinta reflectante", value: item.reflector },
        { name: "Kit de herramientas", value: item.emergencyKit },
        { name: "Alarma de seguridad", value: item.alarm },
        { name: "Tacos", value: item.chocks },
        { name: "Porta escalera", value: item.ladderHolder },
        { name: "Placa impresa laterales", value: item.sidePlate },
    ];
    showChecklistModal.value = true;
}
function closeChecklistModal() {
    showChecklistModal.value = false;
}

//photos modal
const showPhotosModal = ref(false);
const itemPhotos = ref({
    id: null,
    photos: [
        { name: "Herramientas de Mantenimento", value: "maintenanceTools" },
        { name: "Herramientas de Prevención", value: "preventionTools" },
        { name: "Llanta de Repuesto", value: "imageSpareTire" },
        { name: "Frontal", value: "front" },
        { name: "Lateral Izquierdo", value: "leftSide" },
        { name: "Lateral Derecho", value: "rightSide" },
        { name: "Interior", value: "interior" },
        { name: "Llanta Trasera Izquierda", value: "rearLeftTire" },
        { name: "Llanta Trasera Derecha", value: "rearRightTire" },
        { name: "LLanta Frontal Derecha", value: "frontRightTire" },
        { name: "Llanta Frontal Izquierda", value: "frontLeftTire" },
        { name: "Trasera", value: "back" },
        { name: "Tablero", value: "dashboard" },
        { name: "Asiento Trasero", value: "rearSeat" },
    ],
});
function openPhotosModal(id) {
    itemPhotos.value.id = id;
    showPhotosModal.value = true;
}
function closePhotosModal() {
    showPhotosModal.value = false;
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
            route("checklist.car.destroy", {
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
