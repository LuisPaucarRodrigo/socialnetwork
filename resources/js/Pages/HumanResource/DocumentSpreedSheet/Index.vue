<template>
    <div>
  
      <Head title="Gestion de Secciones" />
      <AuthenticatedLayout redirectRoute="document.rrhh.status">
        <template #header>
            Estatus RRHH
        </template>
        <div class="min-w-full overflow-hidden rounded-lg shadow">

          <div class="overflow-x-auto h-[80vh]">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="sticky z-20 top-0">
                <tr>
                  <th scope="col" :colspan="principalData.length + 1"
                    class="px-6 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider">
                    Datos
                  </th>
                  <th scope="col" :colspan="personalData.length"
                    class="px-6 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider">
                    Datos Personales
                  </th>
                  <th v-for="sec in sections" :key="sec.id" scope="col" :colspan="sec.subdivisions.length"
                    class="px-6 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider">
                    {{ sec.name }}
                  </th>
                </tr>
                <tr>
                  <th scope="col"
                    class="px-1 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider">
                    Item
                  </th>
                  <th v-for="(da, i) in principalData" :key="i" scope="col"
                    :class="['px-6 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider',da.titleClass]">
                    {{ da.title }}
                  </th>
                  <th v-for="(pd, i) in personalData" :key="i" scope="col"
                    class="px-6 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider">
                    {{ pd.title }}
                  </th>
                  <template v-for="sec in sections">
                    <th v-for="(sub, i) in sec.subdivisions" :key="sub.id" scope="col"
                      :class="['px-6 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider']">
                      {{ sub.name }}
                    </th>
                  </template>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="emp,index in employees" :key="emp.id" class="whitespace-nowrap font-medium text-gray-900 text-sm">
                  <td class="px-2 py-2 text-center">
                    <div class="">
                      {{ index + 1 }}
                    </div>
                  </td>
                  <!-- principalData -->
                  <td v-for="da,i in principalData" :key="i" :class="['px-2 py-2', da.propClass]">
                    <div class="">
                      {{ getProp({obj:emp, path:da.propName, sep: ', '}) }}
                    </div>
                  </td>
                  <!-- personalData -->
                  <td v-for="pd,i in personalData" :key="i" :class="['px-2 py-2', pd.propClass]">
                    <div class="">
                      {{  getProp({obj:emp, path:pd.propName, type:pd.propType}) }} 
                    </div>
                  </td>
                  <!-- All Sections -->
                  <template v-for="sec in sections">
                    <td v-for="sub in sec.subdivisions" :key="sub.id" :class="['px-2 py-4', 'text-center border-2']">
                    <div class="min-w-[150px] flex items-center">

                      
                      <div v-if="emp[sub.id]?.sync_status === false" class="relative group">
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-fuchsia-500 cursor-pointer">
                          <div class="absolute -top-8 left-1/2 transform w-max -translate-x-1/2 p-1 text-xs text-white bg-black rounded opacity-0 group-hover:opacity-100 transition-opacity duration-100 z-50 whitespace-normal">
                            Documento No Sincronizado
                          </div>
                        </span>
                      </div>
        
                      <p class="w-3/4">
                        s{{ emp[sub.id]?.id }} 
                      </p>
                      <div class="w-1/4 justify-end flex gap-3">

                        <button type="button" 
                          @click="openDocModal(
                            {
                              emp_name: emp.name + ' ' + emp.lastname,
                              doc_name:sub.name,
                              id: emp[sub.id]?.id,
                              subdivision_id: sub.id,
                              document_id: emp[sub.id]?.document_id, 
                              employee_id: emp.id, 
                              exp_date: emp[sub.id]?.exp_date, 
                              state: emp[sub.id]?.state ? emp[sub.id]?.state : '', 
                              observations: emp[sub.id]?.observations, 
                            }
                          )"
                        >
                          <InformationCircleIcon class="h-6 w-6 text-blue-700 hover:text-blue-400" style="stroke-width: 2;"/>
                        </button>

                      </div>
                        
                    </div>
                  </td>
                  </template>
                </tr>
              </tbody>
            </table>
          </div>



  
        </div>
        <Modal :show="showDocModal" @close="closeDocModal">
          <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
              Documento: {{ docForm.doc_name }}
            </h2>
            <h2 class="text-base font-medium leading-7 text-gray-900">
              Colaborador: {{ docForm.emp_name }}
            </h2>
            <form @submit.prevent="submit" >
              <div class="pb-6 pt-3 border-t border-b border-gray-900/10 ">
                <div class=" grid sm:grid-cols-2 gap-6">
                  <div>
                    <InputLabel> Estado <span class="text-red-600 text-normal">*</span></InputLabel>
                    <div class="mt-2">
                      <select v-model="docForm.state" id="rols"
                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                          <option value="" disabled>Seleccionar Estado</option>
                          <option>Pendiente</option>
                          <option>En Proceso</option>
                          <option>Completado</option>
                      </select>
                      <InputError :message="docForm.errors.state" />
                    </div>
                  </div>
                  <div>
                    <InputLabel> Fecha de Vencimiento</InputLabel>
                    <div class="mt-2">
                      <TextInput type="date" v-model="docForm.exp_date"  autocomplete="off" />
                      <InputError :message="docForm.errors.exp_date" />
                    </div>
                  </div>
                  <div class="sm:col-span-2">
                    <InputLabel> Observaciones</InputLabel>
                    <div class="mt-2">
                      <textarea class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" rows="4" v-model="docForm.observations"  autocomplete="off" />
                      <InputError :message="docForm.errors.observations" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="mt-3 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeDocModal" > Cancelar </SecondaryButton>
                <PrimaryButton type="submit" class="text-xs uppercase tracking-wider">
                  Guardar
                </PrimaryButton>
              </div>
            </form>
          </div>
        </Modal>
        <!-- 
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

  import { PencilIcon, InformationCircleIcon } from '@heroicons/vue/24/outline';
  import { principalData, personalData, getProp } from './constants';
  
  const {employees, e_employees, sections, test} = defineProps({
    employees: Object, 
    e_employees: Object, 
    sections: Object,
    test: Object
  });

  const showDocModal = ref(false)
  const docForm = useForm({})

  function openDocModal (item) {
    showDocModal.value = true
    docForm.defaults({...item})
    docForm.reset()
  }
  function closeDocModal () {
    showDocModal.value = false
  }
  
  function submit () {
     console.log(docForm.data())
  }


  


  
  </script>
  