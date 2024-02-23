
<template>
    <Head title="Programas de Formación" />

    <AuthenticatedLayout :redirectRoute="'management.employees.formation_development'">
        <template #header>
            Programas de Formación
        </template>
        <div class="min-w-full">
            <div class="flex items-center">
                <button @click="add_information" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    Agregar Informacion
                </button>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap rounded-lg shadow">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
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
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Tipo de programa
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(formationProgram, i) in formationPrograms.data" :key="formationProgram.id"
                            class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ i + 1 }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ formationProgram.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ formationProgram.description }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ formationProgram.type }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <Link
                                        :href="route('management.employees.formation_development.view', { id: formationProgram.id })">
                                    <EyeIcon class="h-6 w-6 text-teal-500" />
                                    </Link>
                                    <button @click="openModalDelete(formationProgram)">
                                        <TrashIcon class="h-6 w-6 text-red-500" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="formationPrograms.links" />
            </div>
        </div>
        <ConfirmDeleteModal :confirmingDeletion="showModalDelete" itemType="programa de formacion" :nameText="name"
            :deleteFunction="delete_program" @closeModal="closeModal" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { EyeIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { ref, defineProps } from 'vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';

const props = defineProps({
    formationPrograms: Object
})

const add_information = () => {
    router.get(route('management.employees.formation_development.formation_programs.create'));
}

const showModalDelete = ref(false);
const selectedProgram = ref(null);
const name = ref(null);

const openModalDelete = (program) => {
    selectedProgram.value = program;
    name.value = program.name;
    showModalDelete.value = true;
}
const closeModal = () => {
    showModalDelete.value = false;
}

const delete_program = () => {
    const programId = selectedProgram.value.id;
    if (programId) {
        router.delete(route('management.employees.formation_development.delete', { id: programId }), {
            onSuccess: () => closeModal()
        });
    }
}

</script>