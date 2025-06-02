<template>
  <div>
    <Head title="Gestion de Secciones" />
    <AuthenticatedLayout redirectRoute="document.rrhh.status">
      <template #header>
        Estatus RRHH
      </template>
      <div class="flex gap-x-3">
        <FilterProcess :options="sectionsArray" v-model="selectedOptions" :width="'w-[230px]'" />
        <select @change="filterExpenseLine($event.target.value)"
          class="block pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
          <option value="">Todos</option>
          <option v-for="item, i in costLines" :key="i">{{ item.name }}</option>
        </select>
        <PrimaryButton
          v-permission="'manage_grupal_documents_hr'"
          type="button"
          @click="()=>router.visit(route('document.grupal_documents.index'))"
        >
          Documentos Grupales 
        </PrimaryButton>
      </div>
      <br>
      <Toaster richColors class="z-1000" />
      <div class="min-w-full overflow-hidden rounded-lg shadow">
        <div class="overflow-x-auto h-[75vh]">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="sticky z-20 top-0">
              <tr>
                <th scope="col" :colspan="principalData.length + 2"
                  class="px-6 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider">
                  Datos
                </th>
                <th scope="col" :colspan="personalData.length" v-if="sectionIsVisible('Datos Personales')"
                  class="px-6 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider">
                  Datos Personales
                </th>
                <template v-for="sec in sections" :key="sec.id">
                  <th v-if="sectionIsVisible(sec.name)" scope="col"
                    :colspan="sec.id === 9 ? sec.subdivisions.length + 2 : sec.subdivisions.length"
                    class="px-6 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider">
                    {{ sec.name }}
                  </th>
                </template>
              </tr>
              <tr>
                <th scope="col"
                  class="px-1 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider">
                  Item
                </th>
                <th scope="col"
                  class="px-1 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider">
                  Detalles
                </th>
                <th v-for="(da, i) in principalData" :key="i" scope="col"
                  :class="['px-6 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider', da.titleClass]">
                  {{ da.title }}
                </th>
                <th v-if="sectionIsVisible('Datos Personales')" v-for="(pd, i) in personalData" :key="i" scope="col"
                  class="px-6 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider">
                  {{ pd.title }}
                </th>
                <template v-for="sec in sections">
                  <th v-if="sectionIsVisible(sec.name) && sec.id === 9" scope="col"
                    :class="['relative px-6 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider']">
                    SCTR
                    <button @click="openInsuranceModal('SCTR')" type="button" class="absolute top-50 right-4">
                      <svg fill="#697475" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="25px" height="25px" viewBox="0 0 512 512"
                        enable-background="new 0 0 512 512" xml:space="preserve">
                        <path
                          d="M256,0C114.609,0,0,114.609,0,256s114.609,256,256,256s256-114.609,256-256S397.391,0,256,0z M256,472
                            c-119.297,0-216-96.703-216-216S136.703,40,256,40s216,96.703,216,216S375.297,472,256,472z" />
                        <g>
                          <rect x="144" y="336" width="224" height="32" />
                          <rect x="144" y="272" width="224" height="32" />
                          <rect x="144" y="208" width="224" height="32" />
                          <rect x="144" y="144" width="224" height="32" />
                        </g>
                      </svg>
                    </button>
                  </th>
                  <th v-if="sectionIsVisible(sec.name) && sec.id === 9" scope="col"
                    :class="['relative px-6 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider']">
                    Póliza
                    <button @click="openInsuranceModal('Póliza')" type="button" class="absolute top-50 right-4">
                      <svg fill="#697475" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                        xmlns:xlink="http://www.w3.org/1999/xlink" width="25px" height="25px" viewBox="0 0 512 512"
                        enable-background="new 0 0 512 512" xml:space="preserve">
                        <path
                          d="M256,0C114.609,0,0,114.609,0,256s114.609,256,256,256s256-114.609,256-256S397.391,0,256,0z M256,472
                            c-119.297,0-216-96.703-216-216S136.703,40,256,40s216,96.703,216,216S375.297,472,256,472z" />
                        <g>
                          <rect x="144" y="336" width="224" height="32" />
                          <rect x="144" y="272" width="224" height="32" />
                          <rect x="144" y="208" width="224" height="32" />
                          <rect x="144" y="144" width="224" height="32" />
                        </g>
                      </svg>
                    </button>
                  </th>
                  <th v-if="sectionIsVisible(sec.name)" v-for="(sub, i) in sec.subdivisions" :key="sub.id" scope="col"
                    :class="['px-6 py-3 bg-gray-50 text-center text-xs shadow-header-gray-300 font-medium text-gray-600 uppercase tracking-wider']">
                    {{ sub.name }}
                  </th>
                </template>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="emp, index in employeesData" :key="emp.id"
                class="whitespace-nowrap font-medium text-gray-900 text-sm hover:opacity-60">
                <td class="px-2 py-2 text-center">
                  <div class="">
                    {{ index + 1 }}
                  </div>
                </td>
                <td class="px-2 py-2 text-center">
                  <a :href="route('employee.document.rrhh.status', { emp_id: emp.id, type:'employees' })"
                    class="text-green-700 hover:underline hover:text-green-500 cursor-pointer">
                    Ver detalles
                  </a>
                </td>
                <!-- principalData -->
                <td v-for="da, i in principalData" :key="i" :class="['px-2 py-2', { [da.propClass] : da.title == 'Personal' }]">
                  <div>
                    {{ getProp({ obj: emp, path: da.propName, sep: ', ' }) }}
                  </div>
                </td>
                <!-- personalData -->

                <td v-if="sectionIsVisible('Datos Personales')" v-for="pd, i in personalData" :key="i"
                  :class="['px-2 py-2', pd.propClass]">
                  <div class="">
                    {{ getProp({ obj: emp, path: pd.propName, type: pd.propType }) }}
                  </div>
                </td>


                <!-- All Sections -->
                <template v-for="sec in sections">
                  <td v-if="sectionIsVisible(sec.name) && sec.id === 9" :class="['px-2 py-2', 'text-center border-2',
                    (emp.contract?.discount_sctr && emp.sctr_exp_date === null) && 'bg-red-100'
                  ]">
                    <div class="min-w-[170px] flex items-center">

                      <p :class="['w-3/4 text-sm', emp.sctr_about_to_expire && 'text-red-600']">
                        {{ emp.sctr_exp_date ?
                          formattedDate(emp.sctr_exp_date)
                          : ''
                        }}
                      </p>
                    </div>
                  </td>
                  <td v-if="sectionIsVisible(sec.name) && sec.id === 9" :class="['px-2 py-2', 'text-center border-2',
                    (emp.l_policy && emp.policy_exp_date === null) && 'bg-red-100'
                  ]">
                    <div class="min-w-[170px] flex items-center">

                      <p :class="['w-3/4 text-sm', emp.policy_about_to_expire && 'text-red-600']">
                        {{ emp.policy_exp_date ?
                          formattedDate(emp.policy_exp_date)
                          : ''
                        }}
                      </p>
                    </div>
                  </td>


                  <td v-if="sectionIsVisible(sec.name)" v-for="sub in sec.subdivisions" :key="sub.id" :class="['px-2 py-2', 'text-center border-2',
                    emp.document_registers[sub.id] === undefined && 'bg-red-100',
                    emp.document_registers[sub.id]?.state === 'En Proceso' && 'bg-amber-100',
                    emp.document_registers[sub.id]?.state === 'Completado' && 'bg-green-100',
                    emp.document_registers[sub.id]?.state === 'No Corresponde' && 'bg-white-100',
                  ]">
                    <div class="min-w-[170px] flex items-center">
                      <div v-if="emp.document_registers[sub.id]?.sync_status === false" class="relative group">
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-fuchsia-500 cursor-pointer">
                        </span>
                      </div>
                      <p :class="['w-3/4 text-sm', emp.document_registers[sub.id]?.display && 'text-red-600']">
                        {{
                          formattedDate(emp.document_registers[sub.id]?.exp_date)

                        }}
                      </p>
                      <div class="w-1/4 justify-end flex gap-3">
                        <button 
                          v-permission="'modify_document_status'" 
                          type="button" 
                          @click="openDocModal(
                          {
                            emp_name: emp.name + ' ' + emp.lastname,
                            doc_name: sub.name,
                            id: emp.document_registers[sub.id]?.id,
                            subdivision_id: sub.id,
                            document_id: emp.document_registers[sub.id]?.document_id,
                            employee_id: emp.id,
                            exp_date: emp.document_registers[sub.id]?.exp_date,
                            state: emp.document_registers[sub.id]?.state ? emp.document_registers[sub.id]?.state : '',
                            observations: emp.document_registers[sub.id]?.observations,
                            has_exp_date: emp.document_registers[sub.id]?.exp_date ? 1 : 0,
                            document: null
                          }
                        )">
                          <InformationCircleIcon class="h-6 w-6 text-blue-700 hover:text-blue-400"
                            style="stroke-width: 2;" />
                        </button>

                      </div>

                    </div>
                  </td>
                </template>
              </tr>

              <tr v-for="emp, index in e_employeesData" :key="emp.id"
                class="whitespace-nowrap font-medium text-gray-900 text-sm hover:opacity-60">
                <td class="px-2 py-2 text-center">
                  <div class="">
                    {{ index + employeesData.length + 1 }}
                  </div>
                </td>
                <td class="px-2 py-2 text-center">
                  <a :href="route('employee.document.rrhh.status', { emp_id: emp.id, type:'external' })" 
                    class="text-green-700 hover:underline hover:text-green-500 cursor-pointer">
                    Ver detalles
                  </a>
                </td>
                <!-- principalData -->
                <td v-for="da, i in principalData" :key="i"
                  :class="['px-2 py-2 ', { 'bg-indigo-100 sticky left-0 z-10': da.title === 'Personal'}]"
                  
                  >
                  <div class="">
                    {{ getProp({ obj: emp, path: da.propName, sep: ', ' }) }}
                  </div>
                </td>
                <!-- personalData -->

                <td v-if="sectionIsVisible('Datos Personales')" v-for="pd, i in personalData" :key="i"
                  :class="['px-2 py-2', pd.propClass]">
                  <div class="">
                    {{ getProp({ obj: emp, path: pd.propName, type: pd.propType }) }}
                  </div>
                </td>


                <!-- All Sections -->
                <template v-for="sec in sections">
                  <td v-if="sectionIsVisible(sec.name) && sec.id === 9" :class="['px-2 py-2', 'text-center border-2',
                    (emp.sctr && emp.sctr_exp_date === null) && 'bg-red-100'
                  ]">
                    <div class="min-w-[170px] flex items-center">

                      <p :class="['w-3/4 text-sm', emp.sctr_about_to_expire && 'text-red-600']">
                        {{ emp.sctr_exp_date ?
                          formattedDate(emp.sctr_exp_date)
                          : ''
                        }}
                      </p>
                    </div>
                  </td>
                  <td v-if="sectionIsVisible(sec.name) && sec.id === 9" :class="['px-2 py-2', 'text-center border-2',
                    (emp.l_policy && emp.policy_exp_date === null) && 'bg-red-100'
                  ]">
                    <div class="min-w-[170px] flex items-center">

                      <p :class="['w-3/4 text-sm', emp.policy_about_to_expire && 'text-red-600']">
                        {{ emp.policy_exp_date ?
                          formattedDate(emp.policy_exp_date)
                          : ''
                        }}
                      </p>
                    </div>
                  </td>


                  <td v-if="sectionIsVisible(sec.name)" v-for="sub in sec.subdivisions" :key="sub.id" :class="['px-2 py-2', 'text-center border-2',
                    emp.document_registers[sub.id] === undefined && 'bg-red-100',
                    emp.document_registers[sub.id]?.state === 'En Proceso' && 'bg-amber-100',
                    emp.document_registers[sub.id]?.state === 'Completado' && 'bg-green-100',
                    emp.document_registers[sub.id]?.state === 'No Corresponde' && 'bg-white-100',
                  ]">
                    <div class="min-w-[170px] flex items-center">
                      <div v-if="emp.document_registers[sub.id]?.sync_status === false" class="relative group">
                        <span class="relative inline-flex rounded-full h-3 w-3 bg-fuchsia-500 cursor-pointer">
                        </span>
                      </div>
                      <p :class="['w-3/4 text-sm', emp.document_registers[sub.id]?.display && 'text-red-600']">
                        {{
                          formattedDate(emp.document_registers[sub.id]?.exp_date)

                        }}
                      </p>
                      <div class="w-1/4 justify-end flex gap-3">
                        <button 
                          v-permission="'modify_document_status'" 
                          type="button" 
                          @click="openDocModal(
                          {
                            emp_name: emp.name + ' ' + emp.lastname,
                            doc_name: sub.name,
                            id: emp.document_registers[sub.id]?.id,
                            subdivision_id: sub.id,
                            document_id: emp.document_registers[sub.id]?.document_id,
                            e_employee_id: emp.id,
                            exp_date: emp.document_registers[sub.id]?.exp_date,
                            state: emp.document_registers[sub.id]?.state ? emp.document_registers[sub.id]?.state : '',
                            observations: emp.document_registers[sub.id]?.observations,
                            has_exp_date: emp.document_registers[sub.id]?.exp_date ? 1 : 0,
                            document: null
                          }
                        )">
                          <InformationCircleIcon class="h-6 w-6 text-blue-700 hover:text-blue-400"
                            style="stroke-width: 2;" />
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
          <form @submit.prevent="submit">
            <div class="pb-6 pt-3 border-t border-b border-gray-900/10 ">
              <div class=" grid sm:grid-cols-2 gap-6">
                <div class="sm:col-span-2">
                  <InputLabel> Estado <span class="text-red-600 text-normal">*</span></InputLabel>
                  <div class="mt-2">
                    <select v-model="docForm.state" id="rols" required
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                      <option value="" disabled>Seleccionar Estado</option>
                      <option>En Proceso</option>
                      <option>Completado</option>
                      <option>No Corresponde</option>
                    </select>
                    <InputError :message="docForm.errors.state" />
                  </div>
                </div>
                <template v-if="docForm.state === 'Completado'">
                  <div class="sm:col-span-2">
                      <InputLabel for="documentFile"
                          >Documento <span class="text-red-600 text-normal">*</span> </InputLabel
                      >
                      <div class="mt-2">
                          <InputFile
                              type="file"
                              v-model="docForm.document"
                              id="documentFile"
                              class="w-full ring-1 ring-gray-300"
                              accept=".pdf, .png, .jpeg, .jpg"
                          />
                          <InputError :message="docForm.errors.document" />
                      </div>
                  </div>
                  <div class="sm:col-span-2">
                    <div class=" flex items-center gap-4 text-normal">
                      <InputLabel>¿Tiene Fecha de Vencimiento?</InputLabel>
                      <label
                          class="flex gap-2 items-center text-xs"
                          for="hasExpDateYes"
                      >
                          Si
                          <input
                              id="hasExpDateYes"
                              type="radio"
                              :value="1"
                              v-model="docForm.has_exp_date"
                              class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                          />
                      </label>
                      <label
                          class="flex gap-2 items-center text-xs"
                          for="hasExpDateNo"
                      >
                          No
                          <input
                              id="hasExpDateNo"
                              type="radio"
                              :value="0"
                              v-model="docForm.has_exp_date"
                              class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6"
                          />
                      </label>
                      <span class="text-red-600 text-normal">*</span>
                  </div>
                    <div  v-if="docForm.has_exp_date" class="mt-2 ">
                      <TextInput type="date" v-model="docForm.exp_date"
                         autocomplete="off" />
                      <InputError :message="docForm.errors.exp_date" />
                    </div>
                  </div>
                </template>
                <div class="sm:col-span-2">
                  <InputLabel> Observaciones</InputLabel>
                  <div class="mt-2">
                    <textarea
                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                      rows="4" v-model="docForm.observations" autocomplete="off" />
                    <InputError :message="docForm.errors.observations" />
                  </div>
                </div>
              </div>
              <InputError :message="errMsg" />
            </div>
            <div class="mt-3 flex items-center justify-end gap-x-6">
              <button
                class="inline-flex items-center px-4 text-xs py-2 bg-transparent border border-red-400 rounded-md font-semibold text-red-500  uppercase tracking-widest hover:bg-red-100/50 transition ease-in-out duration-150"
                v-if="docForm.id" type="button" @click="destroy">
                Eliminar
              </button>
              <SecondaryButton @click="closeDocModal"> Cancelar </SecondaryButton>
              <PrimaryButton type="submit" :disabled="isLoading"
                :class="['text-xs uppercase tracking-widest font-semibold', isLoading && 'opacity-25']">
                Guardar
              </PrimaryButton>
            </div>
          </form>
        </div>
      </Modal>




      <Modal :show="showInsuranceModal" @close="closeInsuranceModal">
        <div class="p-6">
          <h2 class="text-base font-medium leading-7 text-gray-900">
            {{ insuranceForm.title }}
          </h2>
          <form @submit.prevent="submitInsurance">
            <div class="pb-6 pt-3 border-t border-b border-gray-900/10 ">
              <div class=" grid  gap-6">
                <div>
                  <InputLabel> Fecha de Vencimiento</InputLabel>
                  <div class="mt-2">
                    <TextInput type="date" v-model="insuranceForm.exp_date" />
                  </div>
                </div>
              </div>
            </div>
            <div class="mt-3 flex items-center justify-end gap-x-6">
              <SecondaryButton @click="closeInsuranceModal"> Cancelar </SecondaryButton>
              <PrimaryButton type="submit" :disabled="isLoading"
                :class="['text-xs uppercase tracking-widest font-semibold', isLoading && 'opacity-25']">
                Guardar
              </PrimaryButton>
            </div>
          </form>
        </div>
      </Modal>
    </AuthenticatedLayout>
  </div>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/InputFile.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { formattedDate } from '@/utils/utils';
import { InformationCircleIcon } from '@heroicons/vue/24/outline';
import { principalData, personalData, getProp } from './constants';
import { Toaster } from 'vue-sonner';
import { notify, notifyError } from '@/Components/Notification';
import FilterProcess from '@/Components/FilterProcess.vue';
import { toFormData } from '@/utils/utils';

const { employees, e_employees, sections, costLines } = defineProps({
  employees: Object,
  e_employees: Object,
  sections: Object,
  costLines: Array,
});

const employeesData = ref([...employees])
const e_employeesData = ref([...e_employees])

const showDocModal = ref(false)
const docForm = useForm({})


//Each cell post transaction
function openDocModal(item) {
  showDocModal.value = true
  const cleanItem = JSON.parse(JSON.stringify(item));
  docForm.defaults(cleanItem);
  docForm.reset()
}

function closeDocModal() {
  docForm.defaults({})
  docForm.reset()
  isLoading.value = false
  errMsg.value = false
  showDocModal.value = false
}

const isLoading = ref(false)
async function submit() {
  isLoading.value = true
  let url = route('document.rrhh.status.store', { dr_id: docForm?.id })
  try {
    const formToSend = toFormData(docForm.data());
    const res = await axios.post(url, formToSend)
    if (docForm.employee_id) {
      let index = employeesData.value.findIndex(item => item.id == docForm.employee_id)
      let emp = JSON.parse(JSON.stringify(employeesData.value[index]))
      employeesData.value[index].document_registers = {
        ...emp.document_registers,
        ...res.data,
      }
    }
    if (docForm.e_employee_id) {
      let index = e_employeesData.value.findIndex(item => item.id == docForm.e_employee_id)
      let emp =  JSON.parse(JSON.stringify(e_employeesData.value[index])) 
      e_employeesData.value[index].document_registers = {
        ...emp.document_registers,
        ...res.data,
      }
    }

    closeDocModal()
    setTimeout(() => {
      notify('Registro Documentario Guardado')
    }, 100)
  } catch (e) {
    closeDocModal()
    setTimeout(() => {
      notifyError('Server Error')
    }, 100)
  }
}

const errMsg = ref('')
async function destroy() {
  let url = route('document.rrhh.status.destroy', { dr_id: docForm?.id })
  try {
    const res = await axios.delete(url)
    if (res?.data?.msg === 'Eliminado') {
      if (docForm.employee_id) {
        let index = employeesData.value.findIndex(item => item.id == docForm.employee_id)
        let emp = employeesData.value[index]
        delete emp.document_registers[docForm.subdivision_id]
      }
      if (docForm.e_employee_id) {
        let index = e_employeesData.value.findIndex(item => item.id == docForm.e_employee_id)
        let emp = e_employeesData.value[index]
        delete emp.document_registers[docForm.subdivision_id]
      }
      closeDocModal()
      setTimeout(() => {
        notify('Registro Documentario Eliminado')
      }, 100)
    } else {
      errMsg.value = res.data.msg
    }
  } catch (e) {
    closeDocModal()
    setTimeout(() => {
      notifyError('Server Error')
    }, 100)
  }
}


//Section visible
let sectionsArray = ['Datos Personales', ...sections.map(item => item.name)]
const selectedOptions = ref(sectionsArray)
function sectionIsVisible(name) {
  return selectedOptions.value.includes(name)
}


//insurance
const showInsuranceModal = ref(false)
const insuranceForm = useForm({})
function closeInsuranceModal() {
  insuranceForm.defaults({ exp_date: '' })
  insuranceForm.reset()
  showInsuranceModal.value = false
}

function openInsuranceModal(title) {
  insuranceForm.title = title
  showInsuranceModal.value = true
}

async function submitInsurance() {
  let url = route("document.rrhh.status.in_expdate")
  let title = insuranceForm.title
  try {
    await axios.post(url, insuranceForm)
    employeesData.value.forEach(item => {
      let actual = new Date();
      actual.setDate(actual.getDate() + 7)
      let newDate = new Date(insuranceForm.exp_date);
      if (item.contract?.discount_sctr && title === 'SCTR') {
        item.sctr_exp_date = insuranceForm.exp_date
        item.sctr_about_to_expire = actual >= newDate
      }
      if (item.l_policy && title === 'Póliza') {
        item.policy_exp_date = insuranceForm.exp_date
        item.policy_about_to_expire = actual >= newDate
      }
    });
    e_employeesData.value.forEach(item => {
      let actual = new Date();
      actual.setDate(actual.getDate() + 7)
      let newDate = new Date(insuranceForm.exp_date);
      if (item.sctr && title === 'SCTR') {
        item.sctr_exp_date = insuranceForm.exp_date
        item.sctr_about_to_expire = actual >= newDate
      }
      if (item.l_policy && title === 'Póliza') {
        item.policy_exp_date = insuranceForm.exp_date
        item.policy_about_to_expire = actual >= newDate
      }
    });
    closeInsuranceModal()
    setTimeout(() => {
      notify(`${title} Actualizado`)
    }, 100)
  } catch {
    closeInsuranceModal()
    setTimeout(() => {
      notify('Server Error')
    }, 100)
  }
}

async function filterExpenseLine(search) {
  let url = route('document.rrhh.status')
  try {
    let response = await axios.post(url, { searchquery: search })
    console.log(response.data)
    employeesData.value = response.data.employees
    e_employeesData.value = response.data.e_employees
  } catch (error) {
    notifyError(error)
  }
}


watch(()=>docForm.has_exp_date, ()=>{
  if(!docForm.has_exp_date) docForm.exp_date = ''
})

watch(()=>docForm.state, ()=>{
  if(docForm.state !== 'Completado') {
    docForm.document = null
    docForm.exp_date = ''
  }
})

</script>