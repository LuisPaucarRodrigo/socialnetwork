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
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-black}}">
                </th>
              <th
                class="border-b-2 bg-[#38ff1c] border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs uppercase tracking-wider text-black">
                De 0 a 3 meses Qty</th>
              <th
                class="border-b-2 bg-[#38ff1c] border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs uppercase tracking-wider text-black">
                De 0 a 3 meses S/</th>
              <th
                class="border-b-2 bg-[#fefe00] border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs uppercase tracking-wider text-black">
                De 3 a 6 meses Qty</th>
              <th
                class="border-b-2 bg-[#fefe00] border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs uppercase tracking-wider text-black">
                De 3 a 6 meses S/</th>
              <th
                class="border-b-2 bg-[#ff0101] border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs uppercase tracking-wider text-black">
                De 6 meses a 1 año Qty</th>
              <th
                class="border-b-2 bg-[#ff0101] border-gray-200 bg-gray-100 px-8 py-3 text-left text-xs uppercase tracking-wider text-black">
                De 6 meses a 1 año S/</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs uppercase tracking-wider text-black">
                Total Qty</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs uppercase tracking-wider text-black">
                Total S/</th>
            </tr>
          </thead>
          <tbody>
            <tr class="text-gray-700">
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-black">EN DEVOLUCIÓN</td>
              <td class="border-b bg-[#38ff1c] border-gray-200 bg-white px-5 py-5 text-sm text-black">EN DEVOLUCIÓN</td>
              <td class="border-b bg-[#38ff1c] border-gray-200 bg-white px-5 py-5 text-sm text-black">EN DEVOLUCIÓN</td>
              <td class="border-b bg-[#fefe00] border-gray-200 bg-white px-5 py-5 text-sm text-black">EN DEVOLUCIÓN</td>
              <td class="border-b bg-[#fefe00] border-gray-200 bg-white px-5 py-5 text-sm text-black">EN DEVOLUCIÓN</td>
              <td class="border-b bg-[#ff0101] border-gray-200 bg-white px-5 py-5 text-sm text-black">EN DEVOLUCIÓN</td>
              <td class="border-b bg-[#ff0101] border-gray-200 bg-white px-5 py-5 text-sm text-black">EN DEVOLUCIÓN</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-black">EN DEVOLUCIÓN</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-black">EN DEVOLUCIÓN</td>
            </tr>
            <tr class="text-gray-700">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-black">EN STOCK</td>
                <td class="border-b bg-[#38ff1c] border-gray-200 bg-white px-5 py-5 text-sm text-black">EN STOCK</td>
                <td class="border-b bg-[#38ff1c] border-gray-200 bg-white px-5 py-5 text-sm text-black">EN STOCK</td>
                <td class="border-b bg-[#fefe00] border-gray-200 bg-white px-5 py-5 text-sm text-black">EN STOCK</td>
                <td class="border-b bg-[#fefe00] border-gray-200 bg-white px-5 py-5 text-sm text-black">EN STOCK</td>
                <td class="border-b bg-[#ff0101] border-gray-200 bg-white px-5 py-5 text-sm text-black">EN STOCK</td>
                <td class="border-b bg-[#ff0101] border-gray-200 bg-white px-5 py-5 text-sm text-black">EN STOCK</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-black">EN STOCK</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-black">EN STOCK</td>
            </tr>
            <tr class="text-gray-700">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/VALIDADO</td>
                <td class="border-b bg-[#38ff1c] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/VALIDADO</td>
                <td class="border-b bg-[#38ff1c] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/VALIDADO</td>
                <td class="border-b bg-[#fefe00] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/VALIDADO</td>
                <td class="border-b bg-[#fefe00] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/VALIDADO</td>
                <td class="border-b bg-[#ff0101] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/VALIDADO</td>
                <td class="border-b bg-[#ff0101] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/VALIDADO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/VALIDADO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/VALIDADO</td>
            </tr>
            <tr class="text-gray-700">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/GESTOR</td>
                <td class="border-b bg-[#38ff1c] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/GESTOR</td>
                <td class="border-b bg-[#38ff1c] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/GESTOR</td>
                <td class="border-b bg-[#fefe00] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/GESTOR</td>
                <td class="border-b bg-[#fefe00] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/GESTOR</td>
                <td class="border-b bg-[#ff0101] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/GESTOR</td>
                <td class="border-b bg-[#ff0101] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/GESTOR</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/GESTOR</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/GESTOR</td>
            </tr>
            <tr class="text-gray-700">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/FOLIO</td>
                <td class="border-b bg-[#38ff1c] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/FOLIO</td>
                <td class="border-b bg-[#38ff1c] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/FOLIO</td>
                <td class="border-b bg-[#fefe00] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/FOLIO</td>
                <td class="border-b bg-[#fefe00] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/FOLIO</td>
                <td class="border-b bg-[#ff0101] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/FOLIO</td>
                <td class="border-b bg-[#ff0101] border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-black">INSTALADO/FOLIO</td>
            </tr>
            <tr class="text-gray-700">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm font-black text-black">Grand Total</td>
                <td class="border-b bg-[#38ff1c] border-gray-200 bg-white px-5 py-5 text-sm font-black text-black">INSTALADO/FOLIO</td>
                <td class="border-b bg-[#38ff1c] border-gray-200 bg-white px-5 py-5 text-sm font-black text-black">INSTALADO/FOLIO</td>
                <td class="border-b bg-[#fefe00] border-gray-200 bg-white px-5 py-5 text-sm font-black text-black">INSTALADO/FOLIO</td>
                <td class="border-b bg-[#fefe00] border-gray-200 bg-white px-5 py-5 text-sm font-black text-black">INSTALADO/FOLIO</td>
                <td class="border-b bg-[#ff0101] border-gray-200 bg-white px-5 py-5 text-sm font-black text-black">INSTALADO/FOLIO</td>
                <td class="border-b bg-[#ff0101] border-gray-200 bg-white px-5 py-5 text-sm font-black text-black">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm font-black text-black">INSTALADO/FOLIO</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm font-black text-black">INSTALADO/FOLIO</td>
            </tr>
          </tbody>
        </table>
      </div>

    </AuthenticatedLayout>
  </template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';

const props = defineProps({
  equipments: Object,
  auth: Object,
  userPermissions: Array,
});

const hasPermission = (permission) => {
  return props.userPermissions.includes(permission);
};

</script>
