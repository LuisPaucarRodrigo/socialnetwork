<template>

    <Head title="Gestion de Costos Adicionales" />
    <AuthenticatedLayout
      :redirectRoute="{ route: 'huawei.inventory.show'}">
      <template #header>
        Anticuamiento de Equipos
      </template>
      <div class="overflow-x-auto">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                </th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                De 0 a 3 meses Qty</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                De 0 a 3 meses S/</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                De 3 a 6 meses Qty</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                De 3 a 6 meses S/</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                De 6 meses a 1 año Qty</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-8 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                De 6 meses a 1 año S/</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Total Qty</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Total S/</th>
            </tr>
          </thead>
          <tbody>
            <tr class="text-gray-700">
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN DEVOLUCIÓN</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN DEVOLUCIÓN</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN DEVOLUCIÓN</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN DEVOLUCIÓN</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN DEVOLUCIÓN</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN DEVOLUCIÓN</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN DEVOLUCIÓN</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN DEVOLUCIÓN</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN DEVOLUCIÓN</td>
            </tr>
            <tr class="text-gray-700">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN STOCK</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN STOCK</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN STOCK</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN STOCK</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN STOCK</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN STOCK</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN STOCK</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN STOCK</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">EN STOCK</td>
            </tr>
            <tr class="text-gray-700">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/VALIDADO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/VALIDADO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/VALIDADO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/VALIDADO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/VALIDADO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/VALIDADO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/VALIDADO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/VALIDADO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/VALIDADO</td>
            </tr>
            <tr class="text-gray-700">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/GESTOR</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/GESTOR</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/GESTOR</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/GESTOR</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/GESTOR</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/GESTOR</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/GESTOR</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/GESTOR</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/GESTOR</td>
            </tr>
            <tr class="text-gray-700">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
            </tr>
            <tr class="text-gray-700">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">Grand Total</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">INSTALADO/FOLIO</td>
            </tr>
          </tbody>
        </table>
      </div>

    </AuthenticatedLayout>
  </template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { TrashIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import { formattedDate } from '@/utils/utils';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import Pagination from '@/Components/Pagination.vue';

const props = defineProps({
  equipments: Object,
  auth: Object,
  userPermissions: Array,
});

const hasPermission = (permission) => {
  return props.userPermissions.includes(permission);
};

</script>
