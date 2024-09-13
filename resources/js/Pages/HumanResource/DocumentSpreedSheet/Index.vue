<template>
    <div>
  
      <Head title="Gestion de Secciones" />
      <AuthenticatedLayout redirectRoute="document.rrhh.status">
        <template #header>
            Estatus RRHH
        </template>
        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">

          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead>
                <tr>
                  <th scope="col" :colspan="principalData.length + 1"
                    class="px-6 py-3 bg-gray-50 text-center text-xs border-2 font-medium text-gray-600 uppercase tracking-wider">
                    Datos
                  </th>
                  <th scope="col" :colspan="personalData.length"
                    class="px-6 py-3 bg-gray-50 text-center text-xs border-2 font-medium text-gray-600 uppercase tracking-wider">
                    Datos Personales
                  </th>
                </tr>
                <tr>
                  <th scope="col"
                    class="px-1 py-3 bg-gray-50 text-center text-xs border-2 font-medium text-gray-600 uppercase tracking-wider">
                    Item
                  </th>
                  <th v-for="(da, i) in principalData" :key="i" scope="col"
                    class="px-6 py-3 bg-gray-50 text-center text-xs border-2 font-medium text-gray-600 uppercase tracking-wider">
                    {{ da.title }}
                  </th>
                  <th v-for="(pd, i) in personalData" :key="i" scope="col"
                    class="px-6 py-3 bg-gray-50 text-center text-xs border-2 font-medium text-gray-600 uppercase tracking-wider">
                    {{ pd.title }}
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="emp,index in employees" :key="emp.id" class="whitespace-nowrap font-medium text-gray-900 text-sm">
                  <td class="px-2 py-2 text-center">
                    <div class="">
                      {{ index + 1 }}
                    </div>
                  </td>
                  <td v-for="da,i in principalData" :key="i" :class="`px-2 py-2 ${da.propAlign}`">
                    <div class="">
                      {{ getProp({obj:emp, path:da.propName, sep: ', '}) }}
                    </div>
                  </td>
                  <td v-for="pd,i in personalData" :key="i" :class="`px-2 py-2 ${pd.propAlign}`">
                    <div class="">
                      {{  getProp({obj:emp, path:pd.propName, type:pd.propType}) }} 
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
  
        </div>
        <!-- <Modal :show="isCreateSubdivisionModalOpen || isUpdateSubdivisionModalOpen">
          <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
              {{ isCreateSubdivisionModalOpen ? 'Agregar Subdivisión' : 'Actualizar Subdivisión'}}
            </h2>
            <form @submit.prevent="isCreateSubdivisionModalOpen ? submit(false) : submit(true)">
              <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                  <div>
                    <InputLabel for="name">{{ isCreateSubdivisionModalOpen ? 'Agregar nueva Subdivisión:' : 'Actualizar Subdivisión' }}</InputLabel>
                    <div class="mt-2">
                      <TextInput type="text" v-model="form.name" id="name" autocomplete="off" />
                      <InputError :message="form.errors.name" />
                    </div>
                  </div>
                  <div class="mt-6 flex items-center justify-end gap-x-6">
                    <SecondaryButton @click="isCreateSubdivisionModalOpen ? closeCreateSubdivisionModal() : closeUpdateSubdivisionModal()"> Cancelar </SecondaryButton>
                    <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">{{ isCreateSubdivisionModalOpen ? 'Guardar' : 'Actualizar' }}</PrimaryButton>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </Modal>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Subdivisión de documentos" />
        <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Subdivisión de documentos" />
        <ConfirmDeleteModal :confirmingDeletion="create_subdivision" itemType="Subdivisión"
          :deleteFunction="deleteSubdivision" @closeModal="closeModalSubdivision" /> -->
      </AuthenticatedLayout>
    </div>
  </template>
  
  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
  import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
  import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
  import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import InputLabel from '@/Components/InputLabel.vue';
  import TextInput from '@/Components/TextInput.vue';
  import InputError from '@/Components/InputError.vue';
  import { Head, useForm, router } from '@inertiajs/vue3';
  import { TrashIcon, PencilSquareIcon, ArrowDownIcon } from '@heroicons/vue/24/outline';
  import { ref } from 'vue';
  import Modal from '@/Components/Modal.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';
  import { formattedDate } from '@/utils/utils';

  import { principalData, personalData, getProp } from './constants';
  
  const {employees, e_employees, sections, test} = defineProps({
    employees: Object, 
    e_employees: Object, 
    sections: Object,
    test: Object
  });
  
  console.log(employees)
  console.log(e_employees)

  
  


  
  </script>
  