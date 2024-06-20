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
                    <button @click="downloadZip(subdivision.id)" class="text-blue-600 hover:underline">
                      <ArrowDownIcon class="h-5 w-5" />
                    </button>
                    <button @click="openUpdateSubdivisionModal(subdivision)" class="text-orange-400 hover:underline ml-1">
                      <PencilSquareIcon class="h-5 w-5" />
                    </button>
                    <button @click="confirmDeleteSubdivision(subdivision.id)" class="text-red-600 hover:underline ml-1">
                    <TrashIcon class="h-5 w-5" />
                  </button>
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
                    <TextInput type="text" v-model="form.name" id="name" autocomplete="address-level1" />
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

const downloadZip = async (subdivisionId) => {
    try {
        // URL para descargar el ZIP
        const downloadUrl = route('documents.zipSubdivision', { section: props.section.id, subdivisionId: subdivisionId });

        // Realizar la petición GET para descargar el archivo ZIP
        const response = await axios.post(downloadUrl, { responseType: 'blob' });

        if (response.status === 200) {
            // Crear un objeto URL para el archivo descargado
            const link = document.createElement('a');
            link.href = window.location.origin + '/documents/documents/subdivision_' + subdivisionId + '_documents.zip';
            link.download = 'Subdivision ' + subdivisionId + '.zip'; // Nombre de archivo predeterminado
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);

            // Esperar un tiempo breve para asegurar la descarga completa
            setTimeout(async () => {
                try {
                    // URL para eliminar el ZIP
                    const deleteUrl = route('documents.deleteZipSubdivision', { section: props.section.id, subdivisionId: subdivisionId });

                    // Realizar la petición GET para eliminar el archivo ZIP
                    const deleteResponse = await axios.delete(deleteUrl);

                    if (deleteResponse.data.status === 'success') {
                        console.log('Archivo ZIP eliminado exitosamente.');
                    } else {
                    }
                } catch (deleteError) {
                    console.error('Error en la petición para eliminar el archivo:', deleteError);
                }
            }, 1000); // Espera de 1 segundo (puede ajustarse según sea necesario)
        } else {
            console.error('Error al descargar el archivo:', response.statusText);
        }
    } catch (error) {
        console.error('Error en la petición:', error);
    }
};


</script>
