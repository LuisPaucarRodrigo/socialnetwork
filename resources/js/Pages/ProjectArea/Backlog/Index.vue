<template>

    <Head title="Backlog" />

    <AuthenticatedLayout :redirectRoute="'socialnetwork.sot'">
        <template #header>
            Backlog
        </template>

        
        <div class="min-w-full rounded-lg shadow">
            <PrimaryButton @click="addBacklogRow" type="button">
                    + Agregar
                </PrimaryButton>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th v-for="(item, key) in backlogHeaders" :key="key" :ref="item.headerRef"
                                class="border-b-2 sticky left-0 z-10 border-gray-300 bg-gray-100 px-3 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                {{ item.headerName }}
                            </th>
                            <th  v-if="auth.user.role_id === 1"
                                class="border-b-2 border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, key) in backlogs.data" :key="key" class="text-gray-700">
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
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">{{ formattedDate(item?.sot_operation?.ic_date) }}</p>
                            </td>
                            <td class="border-b border-r border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                <button v-if="item?.sot_operation?.minute_materials" type="button" @click="openMaterialsModal(item.sot_operation.minute_materials)">
                                    <EyeIcon class="w-5 h-5 text-green-600" />
                                </button>
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
                                <p class="text-gray-900 text-center">{{ item.sot_liquidation ? 'S/. '+item.sot_liquidation.bill_amount.toFixed(2) : '' }}</p>
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

            <!-- <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="backlogs.links" />
            </div> -->
        </div>


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
import TestChildCompo from '@/Components/TestChildCompo.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';


import { backlogHeaders } from './BacklogConstants';

const { auth } = defineProps({
    auth: Object
})
const backlogs = ref({data:[]})






function addBacklogRow () {
    backlogs.value.data.unshift({})
}




const testProp = ref('')

</script>
