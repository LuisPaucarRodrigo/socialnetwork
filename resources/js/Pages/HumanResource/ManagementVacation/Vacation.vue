<template>

    <Head title="Gestion de Vacaciones y Permisos" />

    <AuthenticatedLayout :redirectRoute="'management.vacation'">
        <template #header>
            Vacaciones y Permisos
        </template>

        <div class="min-w-full overflow-hidden rounded-lg shadow">
            <div class="flex items-center justify-between gap-4 mb-4">
                <PrimaryButton type="button" @click="add_information">
                    + Agregar
                </PrimaryButton>
                <div class="flex items-center gap-x-3">
                    <PrimaryButton @click="review" type="button">
                        {{ boolean == true ? "Sin Revisar" : "Revisados" }}
                    </PrimaryButton>
                    <form @submit.prevent="search" class="flex items-center w-full sm:w-auto">
                        <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" />
                        <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                            class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Empleado
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Tipo
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha u horas de Inicio
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha u horas de culminación
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Estado
                            </th>
                            <th v-if="boolean == true"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Revision
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in (props.search === '' ? vacations.data : vacations)" :key="item.id"
                            class="text-gray-700" :class="['text-gray-700', {
                                'border-l-8': boolean == true,
                                'border-green-500': item.status === 'Finalizado',
                                'border-red-900': item.status === 'Ausente',
                                'border-red-500': (Date.parse(item.end_date) <= Date.now() + (3 * 24 * 60 * 60 * 1000) && item.review_date != null && item.status === 'Aceptado') ||
                                    (Date.parse(item.end_permissions) <= Date.now() + (1 * 60 * 60 * 1000) && item.review_date != null && item.status == 'Aceptado'),
                                'border-yellow-500': (Date.parse(item.end_date) >= Date.now() + (3 * 24 * 60 * 60 * 1000) && item.review_date != null && item.status === 'Aceptado') &&
                                    (Date.parse(item.end_date) <= Date.now() + (7 * 24 * 60 * 60 * 1000) && item.review_date != null && item.status == 'Aceptado') ||
                                    (Date.parse(item.end_permissions) <= Date.now() + (3 * 60 * 60 * 1000) && item.review_date != null && item.status == 'Aceptado'),
                            }]">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ item.employee.name + " " + item.employee.lastname }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.type }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ formattedDate(item.start_date ?
                                    item.start_date :
                                    item.start_permissions) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ formattedDate(item.end_date ?
                                    item.end_date :
                                    item.end_permissions) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.status }}</p>
                            </td>
                            <td v-if="boolean == true" class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ item.review_date && formattedDate(item.review_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <Link class="text-blue-900 whitespace-no-wrap"
                                        :href="route('management.vacation.information.details', { id: item.id })">
                                    <ShowIcon />
                                    </Link>
                                    <template v-if="boolean == false">
                                        <Link v-if="item.status === 'Pendiente'"
                                            class="text-blue-900 whitespace-no-wrap"
                                            :href="route('management.vacation.information.edit', { id: item.id })">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                        </Link>
                                        <span v-if="item.status !== 'Pendiente'" class="text-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </span>
                                        <Link v-if="item.status === 'Pendiente'"
                                            :href="route('management.vacation.information.review', { id: item.id })"
                                            class="text-blue-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                        </Link>
                                        <span v-if="item.status !== 'Pendiente'" class="text-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                            </svg>
                                        </span>
                                    </template>
                                    <template v-else>
                                        <Link class="text-blue-900 whitespace-no-wrap"
                                            :href="route('management.vacation.information.edit', { id: item.id })">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                        </Link>
                                        <button v-if="item.status === 'Aceptado'"
                                            @click="sendStatus(item.id, 'Finalizado')" type="button"
                                            class="flex items-center text-blue-500 hover:underline">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                        <span v-if="item.status !== 'Aceptado'" class="text-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </span>
                                        <button v-if="item.status === 'Aceptado'" @click="sendModal(item.id, 'Ausente')"
                                            type="button"
                                            class="rounded-xl whitespace-no-wrap text-center text-sm text-red-900 hover:bg-red-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                        <span v-if="item.status !== 'Aceptado'" class="text-gray-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </span>
                                    </template>
                                    <button type="button" @click="confirmDeletion(item.id)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="props.search === undefined || props.search === ''"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="vacations.links" />
            </div>
        </div>
        <Modal :show="showModalAbsent">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Incumplimiento
                </h2>
                <form @submit.prevent="submit">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <div>
                                <InputLabel for="coment">Ingrese la razón o
                                    motivo
                                    por el cual el trabajador no cumplio con lo acordado
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.coment" id="coment" />
                                    <InputError :message="form.errors.coment" />
                                </div>
                            </div>
                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <SecondaryButton @click="closeModalAbsent"> Cancelar
                                </SecondaryButton>
                                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                                    Guardar
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
        <ConfirmDeleteModal :confirmingDeletion="confirmingDeletion" itemType="vacaciones" :deleteFunction="deleteitem"
            @closeModal="closeModal" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import Pagination from '@/Components/Pagination.vue'
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { formattedDate } from '@/utils/utils';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ShowIcon } from '@/Components/Icons/Index';

const props = defineProps({
    vacations: Object,
    search: String,
    boolean: Boolean
})

const form = useForm({
    id: '',
    status: '',
    coment: ''
})

const confirmingDeletion = ref(false);
const itemDelete = ref(null);
const showModalAbsent = ref(false);

const add_information = () => {
    router.get(route('management.vacation.information.create'));
};

const confirmDeletion = (itemId) => {
    itemDelete.value = itemId;
    confirmingDeletion.value = true;
};

const deleteitem = () => {
    const Delete = itemDelete.value;
    if (Delete) {
        router.delete(route('management.vacation.information.destroy', { vacation: Delete }), {
            onSuccess: () => closeModal()
        });
    }
};

const closeModal = () => {
    confirmingDeletion.value = false;
};

const reviewstate = ref(props.boolean);

function review() {
    if (props.boolean == true) {
        router.get(route('management.vacation'))
    } else {
        reviewstate.value = true
        router.get(route('management.vacation', { review: reviewstate.value }))
    }
}



const searchForm = useForm({
    searchTerm: props.search,
})

const search = () => {
    let data = { searchTerm: searchForm.searchTerm }
    if (props.boolean == true) {
        router.get(route('management.vacation', { review: reviewstate.value }), data)
    } else {
        router.get(route('management.vacation'), data)
    }
}

function sendStatus(id, status) {
    router.post(route('management.vacation.information.reviewed_decline'), { id: id, status: status }, {
        onSuccess: () => {
            router.visit(route('management.vacation', { review: true }))
        },
    })
}

function sendModal(id, status) {
    form.id = id
    form.status = status
    showModalAbsent.value = true;
}

function submit() {
    form.post(route('management.vacation.information.reviewed_decline'), {
        onSuccess: () => {
            router.visit(route('management.vacation', { review: true }))
        },
    })
}

function closeModalAbsent() {
    showModalAbsent.value = false;
}
</script>
