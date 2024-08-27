<template>

    <Head title="ChecklistDiarioHerramientas" />
    <AuthenticatedLayout :redirectRoute="'checklist.index'">
        <template #header> Checklist EPPS </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <div class="min-w-full overflow-x-auto">
                <table class="w-full table-auto">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Registro
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Checklist
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Más
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="item in checklists.data" :key="item.id">
                            <tr class="text-gray-700 border-b">
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ formattedDate(item.created_at) }}
                                    </p>
                                </td>

                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">
                                        {{ item.user.name }}
                                    </p>
                                </td>

                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <button type="button">
                                        <EyeIcon @click="openChecklistModal(item)" class="text-indigo-600 w-5" />
                                    </button>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
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
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="checklists.links" />
            </div>
        </div>
        <ConfirmDeleteModal
            :confirmingDeletion="confirmingDocDeletion"
            itemType="Checklist Unidad Móvil"
            :deleteFunction="deleteAdditional"
            @closeModal="closeModalDoc"
        />


        <Modal :show="showChecklistModal" @close="closeChecklistModal" max-width="2xl" :closeable="true">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Checklist EPPS
                </h2>
                <br />
                <div class="mt-2">
                    <div class="overflow-auto">
                        <table class="w-full whitespace-no-wrap border-collapse border border-slate-300">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">
                                    <th colspan="2"
                                        class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-gray-600">
                                        EPPS
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(
                                            item, i
                                        ) in itemArrays.stateArray" :key="i" class="text-gray-700 bg-white text-xs">
                                    <td class="border-b border-slate-300 px-2 py-2">
                                        {{ item.name }}
                                    </td>
                                    <td class="border-b border-slate-300 px-2 py-2">
                                        {{ item.value }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
        { name: "Casco", value: item.helmet },
        { name: "Barbiquejo", value: item.chin_strap },
        { name: "Tapasol/Cortavientos", value: item.windbreaker },
        { name: "Chaleco", value: item.vest },
        { name: "Zapatos de seguridad", value: item.safety_shoes },
        { name: "Polo manga larga", value: item.tshirt_ls },
        { name: "Camisa manga larga", value: item.shirt_ls },
        { name: "Pantalón jean con cinta reflexiva", value: item.jeans },
        { name: "Mameluco", value: item.coveralls },
        { name: "Casaca", value: item.jacket },
        { name: "Lentes Oscuros", value: item.dark_glasses },
        { name: "Lentes Claros", value: item.clear_glasses },
        { name: "Sobrelentes", value: item.overglasses },
        { name: "Mascarilla antipolvo", value: item.dust_mask },
        { name: "Tapones auditivos", value: item.earplugs },
        { name: "Bloqueador", value: item.sunscreen },
        { name: "Guantes de aceite/latex", value: item.latex_oil_gloves },
        { name: "Guantes de aceite/nitrilo", value: item.nitrile_oil_gloves },
        { name: "Guantes de badana", value: item.split_leather_gloves },
        { name: "Guantes de presición", value: item.precision_gloves },
        { name: "Guantes anticorte", value: item.cut_resistant_gloves },
        { name: "Doble línea de vida", value: item.double_lanyard },
        { name: "Arnés", value: item.harness },
        { name: "Línea de posicionamiento", value: item.positioning_lanyard },
        { name: "Mosquetones", value: item.carabiners },
        { name: "Carritos de ascenso", value: item.ascenders },
        { name: "CCIP", value: item.ccip },
        { name: "Claro", value: item.claro },
        { name: "Vericom", value: item.vericom },
    ];
    showChecklistModal.value = true;
}
function closeChecklistModal() {
    showChecklistModal.value = false;
}

//photos modal
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
            route("checklist.epp.destroy", {
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
