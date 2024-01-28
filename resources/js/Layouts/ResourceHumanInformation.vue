<!-- ComponenteA.vue -->
<template>
  <div class="px-4 sm:px-0">
    <h3 class="text-base font-semibold leading-7 text-gray-900">Informacion del RRHH</h3>
  </div>
  <div class="mt-6 border-t border-gray-100">
    <div class="text-lg font-semibold mb-4">Contrato</div>
    <div class="sm:flex lg:justify-between lg:gap-8 mb-8">
      <!-- Primera columna -->
      <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Sstema Pensionario</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.contract.pension.type }}
          </dd>
        </div>
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Fecha de Contratacion</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.contract.hire_date }}</dd>
        </div>
      </div>

      <!-- Segunda columna -->
      <div class="sm:w-1/2 sm:pl-4">
        <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
          <dt class="text-sm font-medium leading-6 text-gray-900">Salario</dt>
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.contract.basic_salary }}</dd>
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
          <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.education.education_status }}
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
                  <a :href="'/management_employees/information_additional/details/download/' + details.education.curriculum_vitae"
                    class="font-medium text-indigo-600 hover:text-indigo-500">
                    Download
                  </a>
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
      const startIndex = value.indexOf(' ');
      if (startIndex !== -1) {
        return value.substring(startIndex);
      }
      return value;
    },
  },
}
</script>
  