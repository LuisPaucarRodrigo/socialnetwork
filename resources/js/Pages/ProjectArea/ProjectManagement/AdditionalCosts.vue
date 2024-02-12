<template>
  <Head title="Gestion de Costos Adicionales" />
  <AuthenticatedLayout>
    <template #header>
      Costos adicionales del Proyecto {{ props.project_id.name }}
    </template>
    <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
      <div class="flex gap-4">
        <button @click="openCreateAdditionalModal" type="button"
          class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
          + Agregar
        </button>
      </div>
    </div>
    <div class="mt-5">
      <div class="overflow-x-auto mt-3">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Descripción</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Monto</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in additional_costs" :key="item.id" class="text-gray-700">
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.description }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.amount }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <div class="flex items-center">
                  <button @click="openEditAdditionalModal(item)" class="text-orange-200 hover:underline mr-2">
                    <PencilIcon class="h-4 w-4 ml-1" />
                  </button>
                  <button @click="confirmDeleteAdditional(item.id)" class="text-red-600 hover:underline">
                    <TrashIcon class="h-4 w-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <Modal :show="create_additional">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
          Agregar Costo adicional
        </h2>
        <form @submit.prevent="submit">
          <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">

              <div>
                <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción</InputLabel>
                <div class="mt-2">
                  <input type="text" v-model="form.description" id="description"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.description" />
                </div>
              </div>

              <div>
                <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto</InputLabel>
                <div class="mt-2">
                  <input type="number" step="0.01" v-model="form.amount" id="amount"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.amount" />
                </div>
              </div>

              <div class="mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                  class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
              </div>
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
            <div class="border-b border-gray-900/10 pb-12">

              <div>
                <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción</InputLabel>
                <div class="mt-2">
                  <input type="text" v-model="form.description" id="description"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.description" />
                </div>
              </div>

              <div>
                <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto</InputLabel>
                <div class="mt-2">
                  <input type="text" v-model="form.amount" id="amount"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.amount" />
                </div>
              </div>


              <div class="mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeEditModal"> Cancelar </SecondaryButton>
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                  class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Actualizar</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </Modal>

    <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Costo Adicional"
      :deleteFunction="deleteAdditional" @closeModal="closeModalDoc" />
    <ConfirmCreateModal :confirmingcreation="showModal" itemType="Costo Adicional" />
    <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Costo Adicional" />
    <div class="mt-6 flex items-center justify-between gap-x-6">
      <a :href="route('projectmanagement.purchases_request.index', { id: project_id.id })"
        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
        Atras
      </a>
    </div>
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
import { TrashIcon, PencilIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  additional_costs: Object,
  project_id: Object,
});

const form = useForm({
  id: '',
  description: '',
  amount: null
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
  form.description = editingAdditional.value.description;
  form.amount = editingAdditional.value.amount;

  editAdditionalModal.value = true;
};

const closeModal = () => {
  create_additional.value = false;
};

const closeEditModal = () => {
  // Restablecer los valores del formulario
  form.reset();

  // Cerrar el modal de edición
  editAdditionalModal.value = false;
};


const submit = () => {
  form.post(route('projectmanagement.storeAdditionalCost', { project_id: props.project_id.id }), {
    onSuccess: () => {
      closeModal();
      form.reset();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false;
        router.visit(route('projectmanagement.additionalCosts', { project_id: props.project_id.id }))
      }, 2000);
    },
    onError: () => {
      form.reset();
    },
    onFinish: () => {
      form.reset();
    }
  });
};

const submitEdit = () => {
  form.put(route('projectmanagement.updateAdditionalCost', { project_id: props.project_id.id, additional_cost: form.id }), {
    onSuccess: () => {
      closeModal();
      form.reset();
      showModalEdit.value = true;
      setTimeout(() => {
        showModalEdit.value = false;  // <-- Cambio aquí
        router.visit(route('projectmanagement.additionalCosts', { project_id: props.project_id.id }));
      }, 2000);
    },
    onError: () => {
      form.reset();
    },
    onFinish: () => {
      form.reset();
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

</script>
  
    