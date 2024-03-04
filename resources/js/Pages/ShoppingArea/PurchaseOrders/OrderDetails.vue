<template>
    <Head title="Gestion de Miembro" />
    <AuthenticatedLayout :redirectRoute="'sections.cicsaSubSections'">
      <template #header>
        Orden de compra
      </template>
      <!-- Datos en formato de texto -->
      <div class="mt-5">
        <div class="p-4 text-md border rounded bg-white shadow mb-10">
            
            
            <div class="mb-14">
                <p class="text-lg font-bold pb-1 border-b-2 border-gray-200 mb-6 text-blue-900">Orden de Compra</p>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div class="grid grid-cols-2 gap-2">
                        <div class="font-semibold text-gray-600">Fecha de aprobación en finanzas:</div>
                      <div class="text-gray-500">
                          <span :class="`inline-flex items-center gap-x-1 py-0 px-3 text-sm rounded-full font-medium ${bgBadget} text-white`">    
                            {{ formattedDate(purchase_order.date_issue) }}
                            
                        </span>
                    </div>
                </div>
                    <div class="grid grid-cols-2 gap-2">
                      <div class="font-semibold text-gray-600">Estado:</div>
                      <div class="text-gray-500">
                        <span :class="`uppercase inline-flex items-center gap-x-1 py-0 px-3 text-sm rounded-full font-medium ${bgBadget} text-white`">
                            {{ purchase_order.state }}
                        </span>
                    </div>
                </div>
                <br>
                <div class="grid grid-cols-2 gap-2">
                    <div class="font-semibold text-gray-600">Monto:</div>
                    <div class="text-gray-500">S/. {{ purchase_order.purchase_quote.amount }}</div>
                </div>
            </div>
        </div>
        <div v-if="purchase_order.purchase_quote.purchasing_requests.project" class="mb-14">
            <p class="text-lg font-bold pb-1 border-b-2 border-gray-200 mb-6 text-blue-900">Proyecto</p>
            <div class="grid sm:grid-cols-3 gap-4">
                <div class="grid grid-cols-2 gap-2">
                  <div class="font-semibold text-gray-600">Código:</div>
                  <div class="text-gray-500">{{ purchase_order.purchase_quote.purchasing_requests.project?.code }}</div>
                </div>
                <div class="grid grid-cols-2 gap-2">
                  <div class="font-semibold text-gray-600">Cliente:</div>
                  <div class="text-gray-500">{{ purchase_order.purchase_quote.purchasing_requests.project?.preproject.customer  }}</div>
                </div>
            </div>
        </div>
        
        <div class="mb-14">
                <p class="text-lg font-bold pb-1 border-b-2 border-gray-200 mb-6 text-blue-900">Cotización</p>
                <div class="grid sm:grid-cols-3 gap-4">
                    <div class="grid grid-cols-2 gap-2">
                      <div class="font-semibold text-gray-600">Solicitud de compra:</div>
                      <div class="text-gray-500">{{ purchase_order.purchase_quote.purchasing_requests.title }}</div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                      <div class="font-semibold text-gray-600">Proveedor:</div>
                      <div class="text-gray-500">{{ purchase_order.purchase_quote.provider  }}</div>
                    </div>
                    <br>
                    <div class="grid grid-cols-2 gap-2">
                      <div class="font-semibold text-gray-600">Archivo:</div>
                      <div class="text-gray-500">
                        <a class="text-green-700 hover:text-green-500" :href="`/documents/quote/${purchase_order.purchase_quote.purchase_doc}`" target="_blank">
                            Previsualizar
                        </a>
                      </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                      <div class="font-semibold text-gray-600">Descripcion de la solicitud:</div>
                      <div class="text-gray-500">{{ purchase_order.purchase_quote.purchasing_requests.product_description  }}</div>
                    </div>
                </div>
            </div>

            






        </div>
        <div class="flex justify-center">
          <Link
            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
            :href="route('purchaseorders.index')">Ver todas las ordenes de compra</Link>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>
    
  <script setup>
  import { Head, Link } from '@inertiajs/vue3';
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import { ref } from 'vue';
  import { formattedDate } from '@/utils/utils';
  
  const {purchase_order} = defineProps({
    purchase_order: Object,
  });

  const bgBadget = ref (setBadgeColor(purchase_order.date_issue))

  function setBadgeColor (date) {
    const fechaString = date;
    const [year, month, day] = fechaString.split('-');
    const fecha = new Date(year, month - 1, day);
    const hoy = new Date()
    hoy.setUTCHours(hoy.getUTCHours() - 5)
    hoy.setHours(0, 0, 0, 0);
    const diferenciaEnMilisegundos = fecha - hoy ;
    const diferenciaEnDias = Math.floor(diferenciaEnMilisegundos / (1000 * 60 * 60 * 24));
    if(diferenciaEnDias<=3){
        return 'bg-red-600'
    } else if (4<=diferenciaEnDias && diferenciaEnDias <=7){
        return 'bg-yellow-500'
    } else{
        return ''
    }
  }
  
  </script>