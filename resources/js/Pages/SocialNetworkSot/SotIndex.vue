<template>

    <Head title="SocialNetwork SOT" />

    <AuthenticatedLayout :redirectRoute="'socialnetwork.sot'">
        <template #header>
            Proceso de SOT
        </template>

        
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-end">
                <SelectSNSotComponent currentSelect="Proceso" />
            </div>
            <br>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr
                            class="border-b bg-gray-700 text-xs font-semibold uppercase tracking-wide text-gray-100">
                            <th
                                class="border-b-2 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider" colspan="4">
                                Área Programación
                            </th>
                            <th
                                class="border-b-2  border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider" colspan="4">
                                Área de Operaciones
                            </th>
                            <th
                                class="border-b-2  border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider" colspan="7">
                                Área de Liquidación
                            </th>
                            <th
                                class="border-b-2  border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider" colspan="6">
                                Área de Cobranzas
                            </th>
                            <th
                                class="border-b-2 border-r-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider" colspan="3">
                                Área de Control
                            </th>
                            <th  v-if="auth.user.role_id === 1"
                                class="border-b-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider">
                            </th>
                        </tr>
                        <tr
                            class="border-b bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 sticky left-0 z-10 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                SOT
                            </th>
                            <th
                                class=" border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 ">
                                Descripción
                            </th>
                            <th
                                class="border-b-2  border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Asignación
                            </th>
                            <th
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cliente
                            </th>
                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Estado de Instalación
                            </th>
                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Adicionales
                            </th>
                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Reporte Fotográfico
                            </th>
                            <th
                                class="border-b-2 border-r border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Instalación Completada
                            </th>

                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Subir Actas
                            </th>
                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Liquidación
                            </th>
                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Descargo de Almacen
                            </th>
                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Liquidación
                            </th>
                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Status de SOT
                            </th>
                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Observaciones
                            </th>
                            <th
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Monto a Facturar
                            </th>
                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                SOT a facturar
                            </th>
                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha
                            </th>
                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Factura
                            </th>
                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha
                            </th>
                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cobranza
                            </th>
                            <th
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha
                            </th>
                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Penalidad Mala Instalación
                            </th>
                            <th
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Penalidad Falta RF
                            </th>
                            <th
                                class="border-b-2 border-r-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Penalidad Rechazos
                            </th>
                            <th  v-if="auth.user.role_id === 1"
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in sots.data" :key="item.id" class="text-gray-700">
                            <td class="border-b sticky left-0 z-10 border-gray-200 bg-amber-200 px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm ">
                                <p class="text-gray-900 text-center w-[200px]">{{ item.description }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ formattedDate(item.assigned_date) }}</p>
                            </td>
                            <td class="border-b border-r border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex items-center justify-center">
                                    <p class="text-gray-900">{{ item?.customer }}</p>
                                    <button @click="openCustomerDetails(item)" class="text-green-600">
                                        <EyeIcon class="h-4 w-4 ml-1" />
                                    </button>
                                </div>
                            </td>

                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_operation?.i_state }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_operation?.additionals }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_operation?.photo_report }}</p>
                            </td>
                            <td class="border-b border-r border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_operation?.ic_date }}</p>
                            </td>

                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_liquidation?.up_minutes }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_liquidation?.liquidation }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_liquidation?.down_warehouse }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_liquidation?.liquidation_date }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_liquidation?.sot_status }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_liquidation?.observations }}</p>
                            </td>
                            <td class="border-b border-r border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                                <p class="text-gray-900 text-center">S/. {{ item?.sot_liquidation?.bill_amount.toFixed(2) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_payment?.sot_bill }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_payment?.sot_bill_date }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_payment?.bill }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_payment?.bill_date }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_payment?.charge }}</p>
                            </td>
                            <td class="border-b border-r border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_payment?.charge_date }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_control?.p_bad_installation }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_control?.p_no_rf }}</p>
                            </td>
                            <td class="border-b border-r border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ item?.sot_control?.p_rejections }}</p>
                            </td>
                            
                            <td v-if="auth.user.role_id === 1" class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button type="button" @click="openSotDeleteModal(item.id)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="sots.links" />
            </div>
        </div>

        <Modal :show="showSotDeleteModal" @close="closeSotDeleteModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    ¿Estás seguro de que quieres eliminar la SOT?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Una vez que se elimine el registro, todos sus recursos y datos se eliminarán permanentemente.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeSotDeleteModal"> Cancelar </SecondaryButton>

                    <DangerButton class="ml-3" @click="deleteSot">
                        Eliminar
                    </DangerButton>
                </div>
            </div>
        </Modal>
        <Modal :show="showCustomerDetails" @close="showCustomerDetails = false" :maxWidth="'xl'">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-base font-medium leading-7">
                        Detalles del Cliente
                    </h2>
                    <button @click="closeCustomerDetails"
                        class="text-gray-500 hover:text-gray-700 focus:outline-none">
                        <!-- Puedes usar un símbolo de cierre, como una X -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
                <div class="mb-4">
                    <p class="font-medium text-gray-900">Cliente:
                        <span class="text-gray-600">{{ customer.customer }}</span>
                    </p>
                    <p class="font-medium text-gray-900">Plano del Cliente:
                        <span class="text-gray-600">{{ customer.customer_flat }}</span>
                    </p>
                    <p class="font-medium text-gray-900">Teléfono del Cliente:
                        <span class="text-gray-600">{{ customer.customer_phone }}</span>
                    </p>
                    <p class="font-medium text-gray-900">Dirección del Cliente:
                        <span class="text-gray-600">{{ customer.customer_address }}</span>
                    </p>
                    <p class="font-medium text-gray-900">Distrito del Cliente:
                        <span class="text-gray-600">{{ customer.customer_district }}</span>
                    </p>
                    <p class="font-medium text-gray-900">Referencia del Cliente:
                        <span class="text-gray-600">{{ customer.customer_ref }}</span>
                    </p>
                </div>
            </div>
        </Modal>
        <SuccessOperationModal :confirming="confirmSotDelete" :title="'SOT Eliminada'"
            :message="'La SOT fue eliminada y todos los registros de ella'" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import SelectSNSotComponent from '@/Components/SelectSNSotComponent.vue';
import { formattedDate } from '@/utils/utils';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import { EyeIcon } from '@heroicons/vue/24/outline';

const { sots, auth } = defineProps({
    sots: Object,
    auth: Object
})

const showSotDeleteModal = ref(false);
const sotToDelete = ref(null)
const confirmSotDelete = ref(false)
const showCustomerDetails = ref(false);
const customer = ref([]);

const openCustomerDetails = (item) => {
    showCustomerDetails.value = true
    customer.value = item 
}

const closeCustomerDetails = () => {
    customer.value = [];
    showCustomerDetails.value = false;
}

function openSotDeleteModal (id) {
    sotToDelete.value = id
    showSotDeleteModal.value = true
}
function closeSotDeleteModal () {
    sotToDelete.value = null
    showSotDeleteModal.value = false
}
function deleteSot () {
    router.delete(route('socialnetwork.sot.delete', {sot_id: sotToDelete.value}),{
        onSuccess: () => {
            closeSotDeleteModal()
            confirmSotDelete.value = true
            setTimeout(()=>{
                confirmSotDelete.value = false
            }, 1500)
        }
    })
}

</script>
