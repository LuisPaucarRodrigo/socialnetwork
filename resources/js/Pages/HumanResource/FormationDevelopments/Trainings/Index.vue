
<template>
    <Head title="Capacitaciones" />

    <AuthenticatedLayout>
        <template #header>
            Capacitaciones
        </template>
        <div class="inline-block min-w-full">
            <div class="flex items-center">
                <button @click="add_information" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    Agregar Informacion
                </button>
            </div>
            <br>
            <table class="w-full whitespace-no-wrap  overflow-hidden rounded-lg shadow">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            N°
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Nombre
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Descripción
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(training, i) in trainings.data" :key="training.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ i + 1 }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ training.name }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ training.description }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm ">
                            <div class="flex space-x-3 justify-center">
                                <Link
                                    :href="route('management.employees.formation_development.trainings.create', { id: training.id })">
                                <PencilSquareIcon class="h-6 w-6 text-blue-500" />
                                </Link>
                                <button @click="openModalDelete(training)">
                                    <TrashIcon class="h-6 w-6 text-red-500" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="trainings.links" />
            </div>
        </div>
        <div class="mt-6 flex items-center justify-between gap-x-6">
            <a :href="route('management.employees.formation_development')"
                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Atras
            </a>
        </div>
        <ConfirmDeleteModal :confirmingDeletion="showModalDelete" itemType="capacitacion" :nameText="name"
            :deleteFunction="delete_training" @closeModal="closeModal" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { Head, Link, router } from '@inertiajs/vue3';
import { TrashIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { ref, defineProps } from 'vue';

const props = defineProps({
    trainings: Object
})

const add_information = () => {
    router.get(route('management.employees.formation_development.trainings.create'));
}

const showModalDelete = ref(false);
const selectedTraining = ref(null);
const name = ref(null);

const openModalDelete = (training) => {
    selectedTraining.value = training;
    name.value = training.name;
    showModalDelete.value = true;
}

const closeModal = () => {
    showModalDelete.value = false;
}
const delete_training = () => {
    const trainingId = selectedTraining.value.id;
    if (trainingId) {
        router.delete(route('management.employees.formation_development.trainings.destroy', { id: trainingId }), {
            onSuccess: () => closeModal()
        });
    }
}

</script>