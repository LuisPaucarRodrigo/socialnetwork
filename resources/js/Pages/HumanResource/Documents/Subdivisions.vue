<template>
  <div>

    <Head title="Gestion de Secciones" />
    <AuthenticatedLayout :redirectRoute="'documents.sections'">
      <template #header>
        Gestión de Subdivisiones de la Sección: {{ section.name }}
      </template>
      <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
        <PrimaryButton @click="openCreateSubdivisionModal">
          Crear Nueva Subdivisión
        </PrimaryButton>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead>
              <tr>
                <th scope="col"
                  class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Nombre
                </th>
                <th scope="col"
                  class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Acciones
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="subdivision in subdivisions" :key="subdivision.id">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ subdivision.name }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-left">
                    <div class="flex space-x-2">
                        <a :href="route('documents.zipSubdivision', { section: subdivision.section_id, subdivisionId: subdivision.id })" class="text-blue-600 hover:underline">
                            <ArrowDownIcon class="h-5 w-5" />
                        </a>
                        <button @click="openUpdateSubdivisionModal(subdivision)" class="text-orange-400 hover:underline">
                            <PencilSquareIcon class="h-5 w-5" />
                        </button>
                        <button v-if="subdivision.id>154" @click="confirmDeleteSubdivision(subdivision.id)" class="text-red-600 hover:underline">
                            <TrashIcon class="h-5 w-5" />
                        </button>
                    </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

      </div>
      <Modal :show="isCreateSubdivisionModalOpen || isUpdateSubdivisionModalOpen">
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
        :deleteFunction="deleteSubdivision" @closeModal="closeModalSubdivision" />
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

const showModal = ref(false);
const showModalEdit = ref(false);

const props = defineProps({
  subdivisions: Object,
  section: Object
});

const form = useForm({
  id: '',
  name: '',
});

const isCreateSubdivisionModalOpen = ref(false);
const isUpdateSubdivisionModalOpen = ref(false);
const editingSubdivision = ref(null);
const create_subdivision = ref(false);
const subdivisionToDelete = ref(null);

const openCreateSubdivisionModal = () => {
  isCreateSubdivisionModalOpen.value = true;
};

const openUpdateSubdivisionModal = (item) => {
    editingSubdivision.value = JSON.parse(JSON.stringify(item));
    form.id = editingSubdivision.value.id;
    form.name = editingSubdivision.value.name;
    isUpdateSubdivisionModalOpen.value = true;
};


const closeCreateSubdivisionModal = () => {
  form.reset();
  isCreateSubdivisionModalOpen.value = false;
};

const closeUpdateSubdivisionModal = () => {
  form.reset();
  isUpdateSubdivisionModalOpen.value = false;
};


const submit = (update) => {
    if (update) {
        form.put(route('documents.updateSubdivision', { section: props.section.id, subdivision: form.id }), {
        onSuccess: () => {
        closeUpdateSubdivisionModal();
        form.reset();
        showModalEdit.value = true
        setTimeout(() => {
            showModalEdit.value = false;
            router.visit(route('documents.subdivisions', { section: props.section.id }))
        }, 2000);
        }
    });
    } else {
        form.post(route('documents.storeSubdivision', { section: props.section.id }), {
        onSuccess: () => {
        closeCreateSubdivisionModal();
        form.reset();
        showModal.value = true
        setTimeout(() => {
            showModal.value = false;
            router.visit(route('documents.subdivisions', { section: props.section.id }))
        }, 2000);
        }
    });
    }
};

const confirmDeleteSubdivision = (subdivisionId) => {
  subdivisionToDelete.value = subdivisionId;
  create_subdivision.value = true;
};

const closeModalSubdivision = () => {
  create_subdivision.value = false;
};

const deleteSubdivision = async () => {
  const subdivisionId = subdivisionToDelete.value;
  router.delete(route('documents.destroySubdivision', { section: props.section.id, subdivision: subdivisionId }), {
    onSuccess: () => {
      closeModalSubdivision();
    }
  })

};

</script>
