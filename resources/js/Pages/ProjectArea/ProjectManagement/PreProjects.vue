<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout>
        <template #header>
            Gestión de Anteproyectos
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <div class="flex gap-4">
                <button @click="openCreatePreprojectModal" type="button"
                    class="inline-flex items-center px-4 py-2 border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                    + Agregar
                </button>
            </div>
            <br>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                <div v-for="item in preprojects.data" :key="item.id"
                    class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                    <div class="grid grid-cols-2">
                        <h2 class="text-sm font-semibold mb-3">
                            N° {{ item.code }}
                        </h2>
                        <div class="inline-flex justify-end gap-x-2">
                            <button @click="openEditPreprojectModal(item)" class="text-orange-200 hover:underline mb-4">
                                <PencilIcon class="h-4 w-4" />
                            </button>
                            <button class="flex items-start" @click="confirmProjectDeletion(item.id)">
                                <TrashIcon class="h-4 w-4 text-red-500" />
                            </button>
                        </div>
                    </div>
                    <h3 class="text-sm font-semibold text-gray-700 line-clamp-1 mb-2">
                        {{ item.name }}
                    </h3>
                    <Link href="#" class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">Cotizaciones</Link>
                </div>
            </div>
            <br>
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="preprojects.links" />
            </div>
        </div>

        <Modal :show="create_preproject || edit_preproject">
            <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{edit_preproject ? 'Actualizar AnteProyecto' : 'Crear Anteproyecto'}}
            </h2>
            <form @submit.prevent="edit_preproject ? submitEdit() : submit()">
                <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div>
                        <InputLabel for="name" class="mt-2 font-medium leading-6 text-gray-900">Nombre</InputLabel>
                        <div class="mt-2">
                            <input type="text" v-model="form.name" id="name"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            <InputError :message="form.errors.name" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="description" class="mt-2 font-medium leading-6 text-gray-900">Descripción</InputLabel>
                        <div class="mt-2">
                            <input type="text" v-model="form.description" id="description"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            <InputError :message="form.errors.description" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="unit_type" class="mt-2 font-medium leading-6 text-gray-900">Unidad de Medida</InputLabel>
                        <div class="mt-2">
                            <input type="text" v-model="form.unit_type" id="unit_type"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            <InputError :message="form.errors.unit_type" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="cost" class="mt-2 font-medium leading-6 text-gray-900">Costo</InputLabel>
                        <div class="mt-2">
                            <input type="number" step="0.01" v-model="form.cost" id="cost"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            <InputError :message="form.errors.cost" />
                        </div>
                    </div>

                    <div>
                        <InputLabel for="visit" class="mt-2 font-medium leading-6 text-gray-900">Visita</InputLabel>
                        <div class="mt-2">
                            <select v-model="form.customervisit_id" id="visit"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="" disabled selected>Seleccionar visita</option>
                                <option v-for="visit in filteredVisits" :key="visit.id" :value="visit.id">
                                    {{ visit.customer }}
                                </option>
                            </select>
                            <InputError :message="form.errors.facade" />
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="edit_preproject ? closeEditPreprojectModal() : closeCreatePreprojectModal()"> Cancelar </SecondaryButton>
                        <button type="submit" :class="{ 'opacity-25': form.processing }"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                    </div>
                </div>
                </div>
            </form>
            </div>
        </Modal>



        <ConfirmDeleteModal :confirmingDeletion="confirmingProjectDeletion" itemType="Anteproyecto"
            :deleteFunction="delete_project" @closeModal="closeModal" />
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Anteproyecto" />
        <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Anteproyecto" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import Pagination from '@/Components/Pagination.vue'
import InputError from '@/Components/InputError.vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { ref, computed } from 'vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';

const { preprojects, visits } = defineProps({
    preprojects: Object,
    visits: Object,
})

const filteredVisits = computed(() => {
    if (edit_preproject.value) {
        const currentPreprojectVisit = preprojects.data.find(item => item.id === form.id)?.customervisit_id;
        return visits.filter(visit => visit.id === currentPreprojectVisit || !visit.preproject);
    } else {
        return visits.filter(visit => !visit.preproject);
    }
});

const form = useForm({
    id: '',
    name: '',
    description: '',
    unit_type: '',
    cost: null,
    customervisit_id: '',
  });
  
  const submit = () => {
    form.post(route('preprojects.store'), {
      onSuccess: () => {
        closeCreatePreprojectModal();
        form.reset();
        showModal.value = true
        setTimeout(() => {
          showModal.value = false;
          router.visit(route('preprojects.index'))
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
    form.put(route('preprojects.update', {preproject: form.id}), {
      onSuccess: () => {
        closeEditPreprojectModal();
        form.reset();
        showModalEdit.value = true
        setTimeout(() => {
            showModalEdit.value = false;
          router.visit(route('preprojects.index'))
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

const confirmingProjectDeletion = ref(false);
const projectToDelete = ref('');
const create_preproject = ref(false);
const edit_preproject = ref(false);
const showModal = ref(false);
const showModalEdit = ref(false);

const openCreatePreprojectModal = () => {
   create_preproject.value = true;
}

const closeCreatePreprojectModal = () => {
    create_preproject.value = false;
}

const openEditPreprojectModal = (preproject) => {
    const editingpreproject = ref('');
    editingpreproject.value = JSON.parse(JSON.stringify(preproject));
    form.id = editingpreproject.value.id;
    form.name = editingpreproject.value.name;
    form.description = editingpreproject.value.description;
    form.unit_type = editingpreproject.value.unit_type;
    form.cost = editingpreproject.value.cost;
    form.customervisit_id = editingpreproject.value.customervisit_id;

    edit_preproject.value = true;
}

const closeEditPreprojectModal = () => {
    form.reset();
    edit_preproject.value = false;
}

const delete_project = () => {
    const projectId = projectToDelete.value;
    router.delete(route('preprojects.destroy', { preproject: projectId }), {
        onSuccess: () => closeModal()
    });
}

const confirmProjectDeletion = (employeeId) => {
    projectToDelete.value = employeeId;
    confirmingProjectDeletion.value = true;
};

const closeModal = () => {
    confirmingProjectDeletion.value = false;
};


</script>