<template>
  <Head title="Gestion de Miembros" />
  <AuthenticatedLayout :redirectRoute="'sections.cicsaSubSections'">
    <template #header>
      Miembros de los apartados Cicsa
    </template>
    <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
      <div class="flex gap-4">
        <button @click="openCreateSubSectionModal" type="button"
          class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
          + Agregar miembro
        </button>
        <button @click="management_section" type="button"
          class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
          Gestionar Apartados
        </button>
        <div class="flex items-center ml-auto">
          <label for="selectElement" class="mr-2 text-sm text-indigo-600">Seleccione un apartado:</label>
          <select v-model="selectedSection" id="selectElement"
            class="rounded-md py-2 text-sm text-black border-indigo-600">
            <option value="">Todos</option>
            <option v-for="section in props.sections" :key="section.id" :value="section.id">{{ section.name }}</option>
          </select>
        </div>
      </div>
    </div>

    <!-- ... (código existente) ... -->

    <!-- Tabla para mostrar las subsecciones -->
    <div class="mt-5">
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
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ subSection.start_date }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ subSection.end_date }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ subSection.cicsa_section.name }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <div class="flex items-center">
                  <Link :href="route('sections.cicsaSubSection', { subSection: subSection.id })"
                    class="text-green-600 hover:underline mr-2">
                  <EyeIcon class="h-4 w-4 ml-1" />
                  </Link>
                  <button @click="openEditSubSectionModal(subSection)" class="text-orange-200 hover:underline mr-2">
                    <PencilIcon class="h-4 w-4 ml-1" />
                  </button>
                  <button @click="confirmDeleteSubSection(subSection.id)" class="text-red-600 hover:underline">
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
                <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                <div class="mt-2">
                  <input type="text" v-model="form.name" id="name"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.name" />
                </div>
              </div>

              <div>
                <InputLabel for="description" class="font-medium leading-6 text-gray-900 mt-3">Descripción</InputLabel>
                <div class="mt-2">
                  <textarea v-model="form.description" id="description"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>
              </div>

              <div>
                <InputLabel for="start_date" class="font-medium leading-6 text-gray-900 mt-3">Fecha de inicio</InputLabel>
                <div class="mt-2">
                  <input type="date" v-model="form.start_date" id="start_date"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.start_date" />
                </div>
              </div>

              <div>
                <InputLabel for="end_date" class="font-medium leading-6 text-gray-900 mt-3">Fecha de Fin</InputLabel>
                <div class="mt-2">
                  <input type="date" v-model="form.end_date" id="end_date"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.end_date" />
                </div>
              </div>

              <div>
                <InputLabel for="requirements" class="font-medium leading-6 text-gray-900 mt-3">Requerimientos
                </InputLabel>
                <div class="mt-2">
                  <textarea v-model="form.requirements" id="requirements"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>
              </div>

              <div>
                <InputLabel for="Section" class="text-gray-700 mt-3">Sección:</InputLabel>
                <select v-model="form.cicsa_section_id" id="Section" class="border rounded-md px-3 py-2 mb-3 w-full">
                  <option value="">Seleccionar Apartado</option>
                  <option v-for="section in props.sections" :key="section.id" :value="section.id">{{ section.name }}
                  </option>
                </select>
                <InputError :message="form.errors.cicsa_section_id" />
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

    <Modal :show="editSubSectionModal">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
          Editar miembro
        </h2>
        <form @submit.prevent="submitEdit">
          <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">

              <div>
                <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                <div class="mt-2">
                  <input type="text" v-model="form.name" id="name"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.name" />
                </div>
              </div>

              <div>
                <InputLabel for="description" class="font-medium leading-6 text-gray-900 mt-3">Descripción</InputLabel>
                <div class="mt-2">
                  <textarea v-model="form.description" id="description"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>
              </div>

              <div>
                <InputLabel for="start_date" class="font-medium leading-6 text-gray-900 mt-3">Fecha de inicio</InputLabel>
                <div class="mt-2">
                  <input type="date" v-model="form.start_date" id="start_date"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.start_date" />
                </div>
              </div>

              <div>
                <InputLabel for="end_date" class="font-medium leading-6 text-gray-900 mt-3">Fecha de Fin</InputLabel>
                <div class="mt-2">
                  <input type="date" v-model="form.end_date" id="end_date"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                  <InputError :message="form.errors.end_date" />
                </div>
              </div>

              <div>
                <InputLabel for="requirements" class="font-medium leading-6 text-gray-900 mt-3">Requerimientos
                </InputLabel>
                <div class="mt-2">
                  <textarea v-model="form.requirements" id="requirements"
                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                </div>
              </div>

              <div>
                <InputLabel for="Section" class="text-gray-700 mt-3">Sección:</InputLabel>
                <select v-model="form.cicsa_section_id" id="Section" class="border rounded-md px-3 py-2 mb-3 w-full">
                  <option value="">Seleccionar Apartado</option>
                  <option v-for="section in props.sections" :key="section.id" :value="section.id">{{ section.name }}
                  </option>
                </select>
                <InputError :message="form.errors.cicsa_section_id" />
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

    <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Apartado Cicsa"
      :deleteFunction="deleteSubSection" @closeModal="closeModalDoc" />
    <ConfirmCreateModal :confirmingcreation="showModal" itemType="Apartado Cicsa" />
    <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Apartado Cicsa" />
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
import { ref, computed, watch } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { TrashIcon, PencilIcon, EyeIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
  sections: Object,
  subSections: Object,
});

const form = useForm({
  id: '',
  name: '',
  description: '',
  start_date: '',
  end_date: '',
  requirements: '',
  cicsa_section_id: '',
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
  router.get(route('sections.cicsaSections'));
};

const openCreateSubSectionModal = () => {
  create_subSection.value = true;
};

const openEditSubSectionModal = (subSection) => {
  // Copia de los datos de la subsección existente al formulario
  editingSubSection.value = JSON.parse(JSON.stringify(subSection));
  form.id = editingSubSection.value.id;
  form.name = editingSubSection.value.name;
  form.description = editingSubSection.value.description;
  form.start_date = editingSubSection.value.start_date;
  form.end_date = editingSubSection.value.end_date;
  form.requirements = editingSubSection.value.requirements;
  form.cicsa_section_id = editingSubSection.value.cicsa_section_id;

  editSubSectionModal.value = true;
};

const closeModal = () => {
  create_subSection.value = false;
};

const closeEditModal = () => {
  // Restablecer los valores del formulario
  form.reset();

  // Cerrar el modal de edición
  editSubSectionModal.value = false;
};


const submit = () => {
  form.post(route('sections.cicsaStoreSubSection'), {
    onSuccess: () => {
      closeModal();
      form.reset();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false;
        router.visit(route('sections.cicsaSubSections'))
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
  form.put(route('sections.cicsaUpdateSubSection', { subSection: form.id }, form), {
    onSuccess: () => {
      closeModal();
      form.reset();
      showModalEdit.value = true
      setTimeout(() => {
        showModalEdit.value = false;
        router.visit(route('sections.cicsaSubSections'))
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
    router.delete(route('sections.cicsaDestroySubSection', { subSection: docId }), {
      onSuccess: () => closeModalDoc()
    });
  }
};

const filteredSubSections = computed(() => {
  let filtered = props.subSections.data;

  // Filtrar por sección
  if (selectedSection.value) {
    filtered = filtered.filter(subSection => subSection.cicsa_section_id === selectedSection.value);
  }
  return filtered.map(subSection => ({ ...subSection }));
});

</script>
    
      