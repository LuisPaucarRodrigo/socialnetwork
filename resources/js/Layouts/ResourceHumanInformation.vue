<!-- ComponenteA.vue -->
<template>
  <div class="px-4 sm:px-0">
    <h3 class="text-base font-semibold leading-7 text-gray-900">Informacion del RRHH</h3>
  </div>
  <div class="mt-6 border-t border-gray-100">
    <div class="text-lg font-semibold mb-4">Contrato</div>
    <div class="sm:flex lg:justify-between lg:gap-8">
      <!-- Primera columna -->
      <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Linea de Gasto</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.contract?.cost_line?.name }}
          </dd>
        </div>
        <div v-if="details.contract?.state_travel_expenses == 1"
          class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Monto de Viaticos</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
            S/ {{ details.contract?.amount_travel_expenses.toFixed(2) }}
          </dd>
        </div>
      </div>
      <!-- Segunda columna -->
      <div class="sm:w-1/2 sm:pl-4">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Tipo de Contrato</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
            {{ details.contract?.type_contract }}
          </dd>
        </div>
      </div>
    </div>
    <div class="sm:flex lg:justify-between lg:gap-8">
      <!-- Primera columna -->
      <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Sistema Pensionario</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.contract.pension_type }}
          </dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Salario</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">S/ {{
            details.contract.basic_salary.toFixed(2) }}
          </dd>
        </div>
      </div>
      <!-- Segunda columna -->
      <div class="sm:w-1/2 sm:pl-4">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Fecha de Contratacion</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{
            formattedDate(details.contract.hire_date) }}</dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Descuento a Remuneracion</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
            {{ details.contract.discount_remuneration == 1 ? "Con Descuento" : "Sin Descuento" }}</dd>
        </div>
      </div>
    </div>

    <div class="sm:flex lg:justify-between lg:gap-8 mb-8">
      <!-- Primera columna -->
      <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Numero de Cuenta</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
            {{ details.contract.nro_cuenta ?? 'Sin Cuenta' }}
          </dd>
        </div>
      </div>
      <!-- Segunda columna -->
      <div class="sm:w-1/2 sm:pl-4">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">CUSPP</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
            {{ details.contract.cuspp ?? "Sin Cuspp"}}
          </dd>
        </div>
      </div>
    </div>
    
    <div class="text-lg font-semibold mb-4">Educacion</div>
    <div class="sm:flex lg:justify-between lg:gap-8">
      <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Nivel Educativo</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.education.education_level }}
          </dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Especialidad</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.education.specialization }}
          </dd>
        </div>
      </div>
      <div class="sm:w-1/2 sm:pl-4">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Estado Educativo</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.education.education_status
          }}
          </dd>
        </div>
      </div>
    </div>
    <div v-if="details.education.curriculum_vitae" class="sm:flex lg:justify-between lg:gap-8">
      <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Curriculum Vitae</dt>
          <dd class="mt-2 text-sm text-gray-900 sm:col-span-2 sm:mt-0">
            <ul role="list" class="divide-y divide-gray-100 rounded-md border border-gray-200">
              <li class="flex items-center justify-between py-4 pl-4 pr-5 text-sm leading-6">
                <div class="flex w-0 flex-1 items-center">
                  <svg class="h-5 w-5 flex-shrink-0 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
                    aria-hidden="true">
                    <path fill-rule="evenodd"
                      d="M15.621 4.379a3 3 0 00-4.242 0l-7 7a3 3 0 004.241 4.243h.001l.497-.5a.75.75 0 011.064 1.057l-.498.501-.002.002a4.5 4.5 0 01-6.364-6.364l7-7a4.5 4.5 0 016.368 6.36l-3.455 3.553A2.625 2.625 0 119.52 9.52l3.45-3.451a.75.75 0 111.061 1.06l-3.45 3.451a1.125 1.125 0 001.587 1.595l3.454-3.553a3 3 0 000-4.242z"
                      clip-rule="evenodd" />
                  </svg>
                  <div class="ml-4 flex min-w-0 flex-1 gap-2">
                    <span class="truncate font-medium">{{ formatoArchivo(details.education.curriculum_vitae) }}</span>
                  </div>
                </div>
                <div class="ml-4 flex-shrink-0">
                  <button @click="downloadDocument(details.id)" class="flex items-center text-blue-600 hover:underline">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                      stroke="currentColor" class="w-6 h-6">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                  </button>
                </div>
              </li>
            </ul>
          </dd>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link } from '@inertiajs/vue3';
import { formattedDate } from '@/utils/utils.js';
export default {
  props: {
    details: {
      type: Object,
      required: true
    }
  },
  components: {
    Link,
  },
  methods: {
    formatoArchivo(value) {
      const startIndex = value.indexOf('._');
      if (startIndex !== -1) {
        return value.substring(startIndex + 2);
      }
      return value;
    },

    downloadDocument(documentId) {
      const backendDocumentUrl = route('management.employees.information.details.download', { id: documentId });
      window.open(backendDocumentUrl, '_blank');
    },

    formattedDate(fecha) {
      return formattedDate(fecha);
    }
  },
}
</script>