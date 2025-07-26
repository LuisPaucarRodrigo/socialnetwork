<template>

    <Head title="ChecklistEPPS" />
    <AuthenticatedLayout :redirectRoute="'checklist.index'">
        <template #header> Checklist EPPS </template>
        <TableStructure :info="checklists">
            <template #thead>
                <tr>
                    <TableTitle>Fecha de Registro</TableTitle>
                    <TableTitle>Nombre</TableTitle>
                    <TableTitle>ChackList</TableTitle>
                    <TableTitle>Mas</TableTitle>
                </tr>
            </template>
            <template #tbody>
                <tr v-for="item in checklists.data" :key="item.id">
                    <TableRow>{{ formattedDate(item.created_at) }}</TableRow>
                    <TableRow>{{ item.user_name }}</TableRow>
                    <TableRow>
                        <button type="button" @click="openChecklistModal(item)">
                            <ShowIcon />
                        </button>
                    </TableRow>
                    <TableRow>
                        <button @click.prevent="confirmDeleteAdditional(item.id)">
                            <DeleteIcon />
                        </button>
                    </TableRow>
                </tr>
            </template>
        </TableStructure>
        <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="checklists.links" />
        </div>
        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Checklist Epps"
            :deleteFunction="deleteAdditional" @closeModal="closeModalDoc" />


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
