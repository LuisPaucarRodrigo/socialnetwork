<template>

  <Head title="Gestion de Costos Adicionales" />
  <AuthenticatedLayout
    :redirectRoute="{ route: 'projectmanagement.purchases_request.index', params: { id: project_id.id } }">
    <template #header>
      Gastos del Proyecto {{ props.project_id.name }}
    </template>
    <br>
    <div class="inline-block min-w-full mb-4 overflow-hidden">
      <div class="flex gap-4 justify-between">
        <PrimaryButton v-if="project_id.status === null && hasPermission('ProjectManager')"
          @click="openCreateAdditionalModal" type="button" class="">
          + Agregar
        </PrimaryButton>
        <input type="text" @input="handleInput" placeholder="Buscar...">
      </div>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full whitespace-no-wrap">
        <thead>
          <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Zona</th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Tipo de Gasto</th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              RUC</th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Proveedor</th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Tipo de Documento</th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Numero de Doc</th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Fecha de Documento</th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-8 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Monto</th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Archivo</th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Descripción</th>
            <th v-if="auth.user.role_id === 1 && project_id.status === null"
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in additional_costs.data" :key="item.id" class="text-gray-700">
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.zone }}</td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.expense_type }}</td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.ruc }}</td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item?.provider?.company_name }}</td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.type_doc }}</td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.doc_number }}</td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ formattedDate(item.doc_date) }}
            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
              S/. {{ (item.amount).toFixed(2) }}
            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
              <a v-if="item.photo" :href="route('additionalcost.archive', { additional_cost_id: item.id })" target="_blank">

                <EyeIcon class="w-5 h-5 text-teal-600" />
              </a>
              <span v-else>-</span>
            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.description }}</td>
            <td v-if="auth.user.role_id === 1 && project_id.status === null"
              class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
              <div class="flex items-center">
                <button @click="openEditAdditionalModal(item)" class="text-amber-600 hover:underline mr-2">
                  <PencilSquareIcon class="h-5 w-5 ml-1" />
                </button>
                <button @click="confirmDeleteAdditional(item.id)" class="text-red-600 hover:underline">
                  <TrashIcon class="h-5 w-5" />
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <pagination :links="additional_costs.links" />
      </div>
    </div>
    <Modal :show="create_additional">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
          Agregar Costo adicional
        </h2>
        <form @submit.prevent="submit">
          <div class="space-y-12 mt-4">
            <div class="border-b grid sm:grid-cols-2 gap-6 border-gray-900/10 pb-12">
              <div>
                <InputLabel for="expense_type" class="font-medium leading-6 text-gray-900">Tipo de Gasto</InputLabel>
                <div class="mt-2">
                  <select v-model="form.expense_type" id="expense_type"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    <option disabled value="">Seleccionar Gasto</option>
                    <option>Habitaciones</option>
                    <option>Camionetas</option>
                    <option>Combustible</option>
                    <option>Hospedaje</option>
                    <option>Movilidad</option>
                    <option>Peaje</option>
                    <option>Seguros y Pólizas</option>
                    <option>Herramientas</option>
                    <option>Fletes</option>
                    <option>EPPs</option>
                    <option>Gastos de Representación</option>
                    <option>Combustible GEP</option>
                    <option>Otros</option>
                    <option>Consumibles</option>
                    <option>Equipos</option>
                    <option>Otros</option>
                  </select>
                  <InputError :message="form.errors.expense_type" />
                </div>
              </div>
              <div>
                <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Zona</InputLabel>
                <div class="mt-2">
                  <select v-model="form.zone" id="zone"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    <option disabled value="">Seleccionar</option>
                    <option>Arequipa</option>
                    <option>Chala</option>
                    <option>Moquegua</option>
                    <option>Tacna</option>
                    <option>MDD</option>
                  </select>
                  <InputError :message="form.errors.zone" />
                </div>
              </div>
              <div>
                <InputLabel for="ruc" class="font-medium leading-6 text-gray-900">RUC / DNI </InputLabel>
                <div class="mt-2">
                  <input type="text" v-model="form.ruc" id="ruc" maxlength="11" @input="handleRucDniAutocomplete"
                    autocomplete="off" list="options"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <datalist id="options">
                    <option v-for="item in providers" :value="item.ruc">
                      {{ item.company_name }}
                    </option>
                  </datalist>
                  <InputError :message="form.errors.ruc" />
                </div>
              </div>
              <div>
                <InputLabel for="type_doc" class="font-medium leading-6 text-gray-900">Tipo de Documento</InputLabel>
                <div class="mt-2">
                  <select v-model="form.type_doc" id="type_doc"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    <option disabled value="">Seleccionar Documento</option>
                    <option>Efectivo</option>
                    <option>Deposito</option>
                    <option>Factura</option>
                    <option>Boleta</option>
                    <option>Voucher de Pago</option>
                  </select>
                  <InputError :message="form.errors.type_doc" />
                </div>
              </div>
              <div>
                <InputLabel for="doc_number" class="font-medium leading-6 text-gray-900">Numero de Documento
                </InputLabel>
                <div class="mt-2">
                  <input type="text" v-model="form.doc_number" id="doc_number"
                    pattern="^([a-zA-Z0-9]+([-|\/][a-zA-Z0-9]+)*)|([0-9]+)$"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.doc_number" />
                </div>
              </div>

              <div>
                <InputLabel for="doc_date" class="font-medium leading-6 text-gray-900">Fecha de Documento
                </InputLabel>
                <div class="mt-2">
                  <input type="date" v-model="form.doc_date" id="doc_date"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.doc_date" />
                </div>
              </div>

              <div>
                <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto</InputLabel>
                <div class="mt-2">
                  <input type="number" step="0.0001" v-model="form.amount" id="amount"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.amount" />
                </div>
              </div>

              <div>
                <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción</InputLabel>
                <div class="mt-2">
                  <textarea type="text" v-model="form.description" id="description"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.description" />
                </div>
              </div>
              <div class="sm:col-span-2">
                <InputLabel class="font-medium leading-6 text-gray-900">
                  Archivo
                </InputLabel>
                <div class="mt-2">
                  <InputFile type="file" v-model="form.photo" accept=".jpeg, .jpg, .png, .pdf"
                    class="block w-full h-24 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.photo" />

                </div>
              </div>

            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
              <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>
              <button type="submit" :class="{ 'opacity-25': form.processing }"
                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
          </div>
        </form>
      </div>
    </Modal>

    <Modal :show="editAdditionalModal">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
          Editar Costo Adicional
        </h2>
        <form @submit.prevent="submitEdit">
          <div class="space-y-12">
            <div class="border-b grid sm:grid-cols-2 gap-6 border-gray-900/10 pb-12">
              <div>
                <InputLabel for="expense_type" class="font-medium leading-6 text-gray-900">Tipo de Gasto</InputLabel>
                <div class="mt-2">
                  <select v-model="form.expense_type" id="expense_type"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    <option disabled value="">Seleccionar Gasto</option>
                    <option>Habitaciones</option>
                    <option>Camionetas</option>
                    <option>Combustible</option>
                    <option>Hospedaje</option>
                    <option>Movilidad</option>
                    <option>Peaje</option>
                    <option>Seguros y Pólizas</option>
                    <option>Herramientas</option>
                    <option>Fletes</option>
                    <option>EPPs</option>
                    <option>Gastos de Representación</option>
                    <option>Combustible GEP</option>
                    <option>Otros</option>
                    <option>Consumibles</option>
                    <option>Equipos</option>
                    <option>Otros</option>
                  </select>
                  <InputError :message="form.errors.expense_type" />
                </div>
              </div>
              <div>
                <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Zona</InputLabel>
                <div class="mt-2">
                  <select v-model="form.zone" id="zone"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    <option disabled value="">Seleccionar</option>
                    <option>Arequipa</option>
                    <option>Chala</option>
                    <option>Moquegua</option>
                    <option>Tacna</option>
                    <option>MDD</option>
                  </select>
                  <InputError :message="form.errors.zone" />
                </div>
              </div>

              <div>
                <InputLabel for="ruc" class="font-medium leading-6 text-gray-900">RUC / DNI </InputLabel>
                <div class="mt-2">
                  <input type="text" v-model="form.ruc" id="ruc" maxlength="11" @input="handleRucDniAutocomplete"
                    autocomplete="off" list="options"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <datalist id="options">
                    <option v-for="item in providers" :value="item.ruc">
                      {{ item.company_name }}
                    </option>
                  </datalist>
                  <InputError :message="form.errors.ruc" />
                </div>
              </div>

              <div>
                <InputLabel for="type_doc" class="font-medium leading-6 text-gray-900">Tipo de Documento</InputLabel>
                <div class="mt-2">
                  <select v-model="form.type_doc" id="type_doc"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    <option disabled value="">Seleccionar Documento</option>
                    <option>Efectivo</option>
                    <option>Deposito</option>
                    <option>Factura</option>
                    <option>Boleta</option>
                    <option>Voucher de Pago</option>
                  </select>
                  <InputError :message="form.errors.type_doc" />
                </div>
              </div>
              <div>
                <InputLabel for="doc_number" class="font-medium leading-6 text-gray-900">Numero de Documento
                </InputLabel>
                <div class="mt-2">
                  <input type="text" v-model="form.doc_number" id="doc_number"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.doc_number" />
                </div>
              </div>

              <div>
                <InputLabel for="doc_date" class="font-medium leading-6 text-gray-900">Fecha de Documento
                </InputLabel>
                <div class="mt-2">
                  <input type="date" v-model="form.doc_date" id="doc_date"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.doc_date" />
                </div>
              </div>

              <div>
                <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto</InputLabel>
                <div class="mt-2">
                  <input type="number" step="0.0001" v-model="form.amount" id="amount"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.amount" />
                </div>
              </div>

              <div>
                <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción</InputLabel>
                <div class="mt-2">
                  <textarea v-model="form.description" id="description"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.description" />
                </div>
              </div>

              <div class="sm:col-span-2">
                <InputLabel class="font-medium leading-6 text-gray-900">
                  Archivo
                </InputLabel>
                <div class="mt-2">
                  <InputFile type="file" v-model="form.photo" accept=".jpeg, .jpg, .png, .pdf"
                    class="block w-full h-24 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.photo" />
                  <InputLabel class="font-medium leading-6 text-indigo-700">
                    Archivo
                  </InputLabel>
                </div>
              </div>


            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
              <SecondaryButton @click="closeEditModal"> Cancelar </SecondaryButton>
              <button type="submit" :class="{ 'opacity-25': form.processing }"
                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Actualizar</button>
            </div>
          </div>
        </form>
      </div>
    </Modal>

    <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Costo Adicional"
      :deleteFunction="deleteAdditional" @closeModal="closeModalDoc" />
    <ConfirmCreateModal :confirmingcreation="showModal" itemType="Costo Adicional" />
    <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Costo Adicional" />
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { TrashIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import { formattedDate } from '@/utils/utils';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputFile from '@/Components/InputFile.vue';
import Pagination from '@/Components/Pagination.vue'
import { EyeIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  additional_costs: Object,
  project_id: Object,
  providers: Object,
  auth: Object,
  userPermissions: Array
});

const hasPermission = (permission) => {
  return props.userPermissions.includes(permission);
}

const form = useForm({
  id: '',
  expense_type: '',
  ruc: '',
  zone: '',
  provider_id: '',
  project_id: props.project_id.id,
  type_doc: '',
  doc_number: '',
  doc_date: '',
  description: '',
  photo: '',
  amount: ''
});

const create_additional = ref(false);
const showModal = ref(false);
const showModalEdit = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const editAdditionalModal = ref(false);
const editingAdditional = ref(null);

const openCreateAdditionalModal = () => {
  create_additional.value = true;
};

const openEditAdditionalModal = (additional) => {
  // Copia de los datos de la subsección existente al formulario
  editingAdditional.value = JSON.parse(JSON.stringify(additional));
  form.id = editingAdditional.value.id;
  form.expense_type = editingAdditional.value.expense_type;
  form.ruc = editingAdditional.value.ruc;
  form.amount = editingAdditional.value.amount;
  form.type_doc = editingAdditional.value.type_doc;
  form.doc_number = editingAdditional.value.doc_number;
  form.doc_date = editingAdditional.value.doc_date;
  form.amount = editingAdditional.value.amount;
  form.description = editingAdditional.value.description;
  form.zone = editingAdditional.value.zone;
  form.provider_id = editAdditionalModal.value.provider_id

  editAdditionalModal.value = true;
};

const closeModal = () => {
  form.reset();
  create_additional.value = false;
};

const closeEditModal = () => {
  form.reset();
  editAdditionalModal.value = false;
};


const submit = () => {
  form.post(route('projectmanagement.storeAdditionalCost', { project_id: props.project_id.id }), {
    onSuccess: () => {
      closeModal();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false;
      }, 2000);
    }
  });
};



const submitEdit = () => {
  form.post(route('projectmanagement.updateAdditionalCost', { additional_cost: form.id }), {
    onSuccess: () => {
      closeEditModal();
      showModalEdit.value = true;
      setTimeout(() => {
        showModalEdit.value = false;
      }, 2000);
    }, onError: (e) => {
      console.log(e)
    }
  });
};


const confirmDeleteAdditional = (additionalId) => {
  docToDelete.value = additionalId;
  confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
  confirmingDocDeletion.value = false;
};

const deleteAdditional = () => {
  const docId = docToDelete.value;
  if (docId) {
    router.delete(route('projectmanagement.deleteAdditionalCost', { project_id: props.project_id.id, additional_cost: docId }), {
      onSuccess: () => closeModalDoc()
    });
  }
};


const handleRucDniAutocomplete = (e) => {
  let ruc = e.target.value
  let findProv = props.providers.find(item => item.ruc == ruc)
  if (findProv) {
    form.provider_id = findProv.id
  } else {
    form.provider_id = ''
  }
}


const timeout = ref(null);
function debounce(func, delay) {
  return (...args) => {
    if (timeout.value) {
      clearTimeout(timeout.value);
    }
    timeout.value = setTimeout(() => {
      func(...args);
    }, delay);
  };
}
function search(query) {
  console.log('Buscando:', query);
}
const debouncedSearch = debounce(search, 700);
function handleInput(event) {
  debouncedSearch(event.target.value);
}










</script>