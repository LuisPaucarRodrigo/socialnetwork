<template>
    <Head title="Informacion Personal" />
    <AuthenticatedLayout>
      <div>
        <div class="flex flex-1 items-center justify-center sm:items-stretch ">
          <div class="sm:ml-6 sm:block mb-6">
            <div class="flex space-x-4">
              <button @click="cambiarComponente('PersonalInformation')" :class="clasesDinamic.PersonalInformation" class="text-gray-400 rounded-md px-3 py-2 text-sm font-medium">Informacion de Personal</button>
              <button @click="cambiarComponente('PrivateInformation')" :class="clasesDinamic.PrivateInformation" class="text-gray-400 rounded-md px-3 py-2 text-sm font-medium">Informacion Privada</button>
              <button @click="cambiarComponente('ResourceHumanInformation')" :class="clasesDinamic.ResourceHumanInformation" class="text-gray-400 rounded-md px-3 py-2 text-sm font-medium">Informacion de RRHH</button>
            </div>
          </div>
        </div>
        <component :is="componentDinamic" :details="details"/>
      </div>
    </AuthenticatedLayout>
</template>
  
<script >
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PersonalInformation from '@/Layouts/PersonalInformation.vue'
import PrivateInformation from '@/Layouts/PrivateInformation.vue'
import ResourceHumanInformation from '@/Layouts/ResourceHumanInformation.vue'
import { Head } from '@inertiajs/vue3';
import { markRaw } from 'vue';

export default{
    props:{
        details: {
            type: Object, // El tipo de datos que esperas recibir
            required: true // Si es requerido o no
        }
    },
    data() {
        return {
            componentDinamic: markRaw(PersonalInformation),
            clasesDinamic: {
              PersonalInformation: 'text-white bg-gray-900',
              PrivateInformation: 'hover:bg-gray-700 hover:text-white',
              ResourceHumanInformation: 'hover:bg-gray-700 hover:text-white'
            }
        };
    },
    components: {
        AuthenticatedLayout,
        PersonalInformation,
        PrivateInformation,
        ResourceHumanInformation,
        Head
    },
    methods:{
        cambiarComponente(component) {
            this.componentDinamic = component
            this.clasesDinamic = {
              PersonalInformation: 'hover:bg-gray-700 hover:text-white',
              PrivateInformation: 'hover:bg-gray-700 hover:text-white',
              ResourceHumanInformation: 'hover:bg-gray-700 hover:text-white'
            };
            this.clasesDinamic[component] = 'text-white bg-gray-900';
        },
    }
}

</script>
  