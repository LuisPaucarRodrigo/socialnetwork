<template>
    <Head title="Informacion Personal" />
    <AuthenticatedLayout>
      <div>
        <div class="flex flex-1 items-center justify-center sm:items-stretch ">
          <div class="sm:ml-6 sm:block mb-6">
            <div class="flex space-x-4">
              <button @click="cambiarComponente('GeneralDetails')" class="bg-gray-900 text-white rounded-md px-3 py-2 text-sm font-medium" aria-current="page">General</button>
              <button @click="cambiarComponente('EducationDetails')" class="text-gray-400 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Educacion</button>
              <button @click="cambiarComponente('EmergenciaDetails')" class="text-gray-400 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Emergencia</button>
              <button @click="cambiarComponente('DependientesDetails')" class="text-gray-400 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Dependientes</button>
              <button @click="cambiarComponente('SaludDetails')" class="text-gray-400 hover:bg-gray-700 hover:text-white rounded-md px-3 py-2 text-sm font-medium">Salud</button>
            </div>
          </div>
        </div>

        <div class="px-4 sm:px-0">
          <h3 class="text-base font-semibold leading-7 text-gray-900">Informacion del Personal</h3>
          <!-- <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Informacion Personal.</p> -->
        </div>
        <slot />
      </div>
    </AuthenticatedLayout>
</template>

<script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

</script>
