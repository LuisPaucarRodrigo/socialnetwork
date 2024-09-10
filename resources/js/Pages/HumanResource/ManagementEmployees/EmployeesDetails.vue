<template>

  <Head title="Informacion Personal" />
  <AuthenticatedLayout :redirectRoute="'management.employees'">
    <div>
      <div class="flex flex-1 items-center justify-center sm:items-stretch ">
        <div class="sm:ml-6 sm:block mb-6">
          <div class="flex space-x-4">
            <button @click="cambiarComponente(markRaw(PersonalInformation))" :class="clasesDinamic.PersonalInformation"
              class="text-gray-400 rounded-md px-3 py-2 text-sm font-medium">Informacion de Personal</button>
            <button @click="cambiarComponente(markRaw(PrivateInformation))" :class="clasesDinamic.PrivateInformation"
              class="text-gray-400 rounded-md px-3 py-2 text-sm font-medium">Informacion Privada</button>
            <button @click="cambiarComponente(markRaw(ResourceHumanInformation))"
              :class="clasesDinamic.ResourceHumanInformation"
              class="text-gray-400 rounded-md px-3 py-2 text-sm font-medium">Informacion de RRHH</button>
          </div>
        </div>
      </div>
      <component :is="componentDinamic" :details="details" />
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PersonalInformation from '@/Layouts/PersonalInformation.vue';
import PrivateInformation from '@/Layouts/PrivateInformation.vue';
import ResourceHumanInformation from '@/Layouts/ResourceHumanInformation.vue';
import { Head } from '@inertiajs/vue3';
import { ref, markRaw } from 'vue';

const props = defineProps({
  details: {
    type: Object,
    required: true,
  },
});

const componentDinamic = ref(markRaw(PersonalInformation));
const clasesDinamic = ref({
  PersonalInformation: 'text-white bg-gray-900',
  PrivateInformation: 'hover:bg-gray-700 hover:text-white',
  ResourceHumanInformation: 'hover:bg-gray-700 hover:text-white',
}); 

function cambiarComponente(component) {
  componentDinamic.value = component;
  clasesDinamic.value = {
    PersonalInformation: 'hover:bg-gray-700 hover:text-white',
    PrivateInformation: 'hover:bg-gray-700 hover:text-white',
    ResourceHumanInformation: 'hover:bg-gray-700 hover:text-white',
  };
  clasesDinamic.value[component] = 'text-white bg-gray-900';
}
</script>