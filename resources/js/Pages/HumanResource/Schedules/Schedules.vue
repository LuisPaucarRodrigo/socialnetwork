<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'management.employees'">
        <template #header>
            Historial de Horarios
        </template>

        <div class="min-w-full rounded-lg shadow">
            <div class="overflow-x-auto">
                <button @click="openScheduleModal" type="button"
                    class="mx-3 rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    + Horario
                </button>
                <Modal :show="showModalSchedule">
                    <div class="p-4 sm:p-8 md:p-12 lg:p-16">
                        <h2 class="text-lg md:text-xl lg:text-2xl font-medium leading-7 text-gray-900 mb-4">
                            {{ props.file ? 'Actualizar Horario' : 'Agregar Horario' }}
                        </h2>
                        <div v-if="props.file" class="mb-4 sm:mb-8">
                            <div class="flex items-center p-2 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
                                role="alert">
                                <svg class="flex-shrink-0 w-4 h-4 me-3" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                                </svg>
                                <span class="sr-only">Info</span>
                                <div>
                                    <span class="font-small">Al actualizar el horario, cambiará el que está en
                                        curso.</span>
                                </div>
                            </div>
                        </div>
                        <form @submit.prevent="submitSchedule">
                            <div class="space-y-4 sm:space-y-6 lg:space-y-8">
                                <div class="border-b border-gray-900/10 pb-4 sm:pb-6 lg:pb-8">
                                    <div>
                                        <div class="mb-2">
                                            <InputFile type="file" v-model="formSchedule.document" id="documentFile"
                                                accept=".pdf"
                                                class="block w-full rounded-md border-0 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                            <InputError :message="formSchedule.errors.document" />
                                        </div>
                                    </div>
                                </div>
                                <div v-if="props.file" class="mb-4 sm:mb-8">
                                    <div class="aspect-w-16 aspect-h-9">
                                        <iframe :src="getDocumentUrl()" width="100%" height="100%"></iframe>
                                    </div>
                                </div>
                                <div class="flex items-center justify-end gap-x-4 sm:gap-x-6 lg:gap-x-8">
                                    <SecondaryButton @click="closeScheduleModal">Cancelar</SecondaryButton>
                                    <button type="submit" :class="{ 'opacity-25': formSchedule.processing }"
                                        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                        Guardar
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </Modal>
                <ConfirmCreateModal :confirmingcreation="createSchedule" itemType="Horario" />
                <ConfirmUpdateModal :confirmingupdate="updateSchedule" itemType="Horario" />
                <table class="min-w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Título
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Mes
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Año
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="schedule in schedules.data" :key="schedule.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ schedule.schedule_title }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ formattedDate(schedule.date) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ schedule.month }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ schedule.year }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <button @click="openPreviewDocumentModal(schedule.id)">
                                    <EyeIcon class="h-4 w-4 ml-1 text-green-500" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="schedules.links" />
            </div>
        </div>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';
import { EyeIcon } from '@heroicons/vue/24/outline';
import { formattedDate } from '@/utils/utils';


import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/InputFile.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const createSchedule = ref(false);
const updateSchedule = ref(false);
const showModalSchedule = ref(false);

const props = defineProps({
    schedules: Object,
    file: Object,
})

const formSchedule = useForm({
    document: null,
    schedule: null
})

function openPreviewDocumentModal(documentId) {
    const routeToShow = route('management.employees.schedule.preview', { schedule: documentId });
    window.open(routeToShow, '_blank');
}

const openScheduleModal = () => {
    showModalSchedule.value = true;
};

const closeScheduleModal = () => {
    showModalSchedule.value = false;
};

function getDocumentUrl() {
    return route('management.employees.schedule.preview', { schedule: props.file.id });
}

const submitSchedule = () => {
    if(props.file){
        formSchedule.schedule = props.file.id;
    }
    formSchedule.post(route('management.employees.schedule.post'), {
        onSuccess: () => {
            closeScheduleModal();
            formSchedule.reset();
            props.file ? updateSchedule.value = true : createSchedule.value = true
            setTimeout(() => {
                props.file ? updateSchedule.value = false : createSchedule.value = false
            }, 2000);
        },
        onError: () => {
            formSchedule.reset();
        },
        onFinish: () => {
            formSchedule.reset();
        }
    });
};
</script>