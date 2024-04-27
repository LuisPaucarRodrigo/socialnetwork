<template>

  <Head title="Gestion de Miembros" />
  <AuthenticatedLayout :redirectRoute="'sections.subSections'">
    <template #header>
      Miembros de los apartados
    </template>
    <div class="min-w-full rounded-lg shadow">
      <div class="mt-6 flex items-center justify-between gap-x-6">
        <div class="hidden sm:flex sm:items-center sm:space-x-4">
          <PrimaryButton @click="openCreateSubSectionModal" type="button">
            + Agregar miembro
          </PrimaryButton>
          <PrimaryButton @click="management_section" type="button">
            Gestionar Apartados
          </PrimaryButton>
          <Link :href="route('sections.calendar')"
            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
          </svg>
          </Link>
        </div>

        <!-- Dropdown para pantallas pequeñas -->
        <div class="sm:hidden">
          <dropdown align='left'>
            <template #trigger>
              <button @click="dropdownOpen = !dropdownOpen"
                class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M4 6H20M4 12H20M4 18H20" stroke="#000000" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
                </svg>
              </button>
            </template>

            <template #content class="origin-left">
              <div> <!-- Alineación a la derecha -->
                <div class="dropdown">
                  <div class="dropdown-menu">
                    <PrimaryButton @click="openCreateSubSectionModal" type="button">
                      + Agregar miembro
                    </PrimaryButton>
                  </div>
                </div>
                <div class="dropdown">
                  <div class="dropdown-menu">
                    <PrimaryButton @click="management_section" type="button">
                      Gestionar Apartados
                    </PrimaryButton>
                  </div>
                </div>
                <dropdown-link :href="route('sections.calendar')">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5m-9-6h.008v.008H12v-.008ZM12 15h.008v.008H12V15Zm0 2.25h.008v.008H12v-.008ZM9.75 15h.008v.008H9.75V15Zm0 2.25h.008v.008H9.75v-.008ZM7.5 15h.008v.008H7.5V15Zm0 2.25h.008v.008H7.5v-.008Zm6.75-4.5h.008v.008h-.008v-.008Zm0 2.25h.008v.008h-.008V15Zm0 2.25h.008v.008h-.008v-.008Zm2.25-4.5h.008v.008H16.5v-.008Zm0 2.25h.008v.008H16.5V15Z" />
                  </svg>
                </dropdown-link>
              </div>
            </template>
          </dropdown>
        </div>
        <div class="flex items-center ml-auto">
          <label for="selectElement" class="mr-2 text-sm text-indigo-600">Seleccione un apartado:</label>
          <select v-model="selectedSection" id="selectElement"
            class="rounded-md py-2 text-sm text-black border-indigo-600">
            <option value="">Todos</option>
            <option v-for="section in props.sections" :key="section.id" :value="section.id">{{ section.name }}
            </option>
          </select>
        </div>
      </div>
      <div class="overflow-x-auto mt-3">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Nombre</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Descripción</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Fecha de Inicio</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Fecha de Fin</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Seccion</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Acciones</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="subSection in filteredSubSections" :key="subSection.id" class="text-gray-700" :class="[
    'text-gray-700',
    {
      'border-l-8': true,
      'border-green-500': Date.parse(subSection.end_date) > Date.now() + (7 * 24 * 60 * 60 * 1000), // Si la fecha de finalización es 'Disponible', pinta el borde de verde
      'border-red-500': Date.parse(subSection.end_date) <= Date.now() + (3 * 24 * 60 * 60 * 1000), // Si la fecha vence en 3 días o menos, pinta el borde de rojo
      'border-yellow-500': Date.parse(subSection.end_date) > Date.now() + (3 * 24 * 60 * 60 * 1000) && Date.parse(subSection.end_date) <= Date.now() + (7 * 24 * 60 * 60 * 1000) // Si la fecha está entre 3 y 7 días desde hoy, pinta el borde de amarillo
    }
  ]">
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ subSection.name }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ subSection.description }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ formattedDate(subSection.start_date) }}
              </td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ formattedDate(subSection.end_date) }}
              </td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ subSection.section.name }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <div class="flex items-center">
                  <Link :href="route('sections.subSection', { subSection: subSection.id })"
                    class="text-green-600 hover:underline mr-2">
                  <EyeIcon class="h-4 w-4 ml-1" />
                  </Link>
                  <button v-if="hasPermission('UserManager')" @click="openEditSubSectionModal(subSection)"
                    class="text-orange-200 hover:underline mr-2">
                    <PencilIcon class="h-4 w-4 ml-1" />
                  </button>
                  <button v-if="hasPermission('UserManager')" @click="confirmDeleteSubSection(subSection.id)"
                    class="text-red-600 hover:underline">
                    <TrashIcon class="h-4 w-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <Modal :show="create_subSection">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
          Agregar miembro
        </h2>
        <form @submit.prevent="submit">
          <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">

              <div>
                <InputLabel for="name">Nombre</InputLabel>
                <div class="mt-2">
                  <TextInput type="text" v-model="form.name" id="name" />
                  <InputError :message="form.errors.name" />
                </div>
              </div>

              <div>
                <InputLabel for="description">Descripción</InputLabel>
                <div class="mt-2">
                  <textarea v-model="form.description" id="description"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>
              </div>

              <div>
                <InputLabel for="start_date">Fecha de inicio
                </InputLabel>
                <div class="mt-2">
                  <TextInput type="date" v-model="form.start_date" id="start_date" />
                  <InputError :message="form.errors.start_date" />
                </div>
              </div>

              <div>
                <InputLabel for="end_date">Fecha de Fin</InputLabel>
                <div class="mt-2">
                  <TextInput type="date" v-model="form.end_date" id="end_date" />
                  <InputError :message="form.errors.end_date" />
                </div>
              </div>

              <div>
                <InputLabel for="requirements">Requerimientos
                </InputLabel>
                <div class="mt-2">
                  <textarea v-model="form.requirements" id="requirements"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>
              </div>

              <div>
                <InputLabel for="Section" class="text-gray-700 mt-3">Sección:</InputLabel>
                <select v-model="form.section_id" id="Section" class="border rounded-md px-3 py-2 mb-3 w-full">
                  <option value="">Seleccionar Apartado</option>
                  <option v-for="section in props.sections" :key="section.id" :value="section.id">{{ section.name }}
                  </option>
                </select>
                <InputError :message="form.errors.section_id" />
              </div>
              <div class="mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                  Guardar
                </PrimaryButton>
              </div>
            </div>
          </div>
        </form>
      </div>
    </Modal>

    <Modal :show="editSubSectionModal">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
          Editar miembro
        </h2>
        <form @submit.prevent="submitEdit">
          <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">

              <div>
                <InputLabel for="name">Nombre</InputLabel>
                <div class="mt-2">
                  <TextInput type="text" v-model="form.name" id="name" />
                  <InputError :message="form.errors.name" />
                </div>
              </div>

              <div>
                <InputLabel for="description">Descripción</InputLabel>
                <div class="mt-2">
                  <textarea v-model="form.description" id="description" />
                </div>
              </div>

              <div>
                <InputLabel for="start_date">Fecha de inicio
                </InputLabel>
                <div class="mt-2">
                  <TextInput type="date" v-model="form.start_date" id="start_date" />
                  <InputError :message="form.errors.start_date" />
                </div>
              </div>

              <div>
                <InputLabel for="end_date">Fecha de Fin</InputLabel>
                <div class="mt-2">
                  <TextInput type="date" v-model="form.end_date" id="end_date" />
                  <InputError :message="form.errors.end_date" />
                </div>
              </div>

              <div>
                <InputLabel for="requirements">Requerimientos
                </InputLabel>
                <div class="mt-2">
                  <textarea v-model="form.requirements" id="requirements"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>
              </div>

              <div>
                <InputLabel for="Section" class="text-gray-700 mt-3">Sección:</InputLabel>
                <select v-model="form.section_id" id="Section" class="border rounded-md px-3 py-2 mb-3 w-full">
                  <option value="">Seleccionar Apartado</option>
                  <option v-for="section in props.sections" :key="section.id" :value="section.id">{{ section.name }}
                  </option>
                </select>
                <InputError :message="form.errors.section_id" />
              </div>
              <div class="mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeEditModal"> Cancelar </SecondaryButton>
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                  Actualizar
                </PrimaryButton>
              </div>
            </div>
          </div>
        </form>
      </div>
    </Modal>

    <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Apartado"
      :deleteFunction="deleteSubSection" @closeModal="closeModalDoc" />
    <ConfirmCreateModal :confirmingcreation="showModal" itemType="Apartado" />
    <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Apartado" />
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Dropdown from '@/Components/Dropdown.vue';
import Modal from '@/Components/Modal.vue';
import { ref, computed } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { TrashIcon, PencilIcon, EyeIcon } from '@heroicons/vue/24/outline';
import { formattedDate } from '@/utils/utils'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
  sections: Object,
  subSections: Object,
  userPermissions: Array

});

const hasPermission = (permission) => {
  return props.userPermissions.includes(permission);
}

const form = useForm({
  id: '',
  name: '',
  description: '',
  start_date: '',
  end_date: '',
  requirements: '',
  section_id: '',
});

const create_subSection = ref(false);
const showModal = ref(false);
const showModalEdit = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const selectedSection = ref('');
const editSubSectionModal = ref(false);
const editingSubSection = ref(null);

const management_section = () => {
  router.get(route('sections.sections'));
};

const openCreateSubSectionModal = () => {
  create_subSection.value = true;
};

const openEditSubSectionModal = (subSection) => {
  editingSubSection.value = JSON.parse(JSON.stringify(subSection));
  form.id = editingSubSection.value.id;
  form.name = editingSubSection.value.name;
  form.description = editingSubSection.value.description;
  form.start_date = editingSubSection.value.start_date;
  form.end_date = editingSubSection.value.end_date;
  form.requirements = editingSubSection.value.requirements;
  form.section_id = editingSubSection.value.section_id;

  editSubSectionModal.value = true;
};

const closeModal = () => {
  create_subSection.value = false;
};

const closeEditModal = () => {
  form.reset();
  editSubSectionModal.value = false;
};


const submit = () => {
  form.post(route('sections.storeSubSection'), {
    onSuccess: () => {
      closeModal();
      form.reset();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false;
        router.visit(route('sections.subSections'))
      }, 2000);
    },
  });
};

const submitEdit = () => {
  form.put(route('sections.updateSubSection', { subSection: form.id }, form), {
    onSuccess: () => {
      closeModal();
      form.reset();
      showModalEdit.value = true
      setTimeout(() => {
        showModalEdit.value = false;
        router.visit(route('sections.subSections'))
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

const confirmDeleteSubSection = (subSectionId) => {
  docToDelete.value = subSectionId;
  confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
  confirmingDocDeletion.value = false;
};

const deleteSubSection = () => {
  const docId = docToDelete.value;
  if (docId) {
    router.delete(route('sections.destroySubSection', { subSection: docId }), {
      onSuccess: () => closeModalDoc()
    });
  }
};

const filteredSubSections = computed(() => {
  let filtered = props.subSections.data;

  // Filtrar por sección
  if (selectedSection.value) {
    filtered = filtered.filter(subSection => subSection.section_id === selectedSection.value);
  }
  return filtered.map(subSection => ({ ...subSection }));
});

</script>