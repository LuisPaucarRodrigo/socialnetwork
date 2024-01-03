<template>
    <Head title="Roles" />
    <AuthenticatedLayout>
        <template #header>
            Roles
        </template>
        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <button @click="add_rol" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                + Agregar
            </button>
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Nombre
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Descripcion
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="rol in rols.data" :key="rol.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ rol.name }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ rol.description }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <div class="flex space-x-3 justify-center">
                                <Link class="text-blue-900 whitespace-no-wrap"
                                    :href="route('rols.details', { id: rol.id })">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-6 h-6 text-teal-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                </Link>
                                <button type="button" @click="confirmRolsDeletion(rol.id)"
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

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="rols.links" />
            </div>
        </div>
        <ConfirmDeleteModal :confirmingDeletion="confirmingRolDeletion" itemType="rol" :deleteText="deleteButtonText"
            :deleteFunction="deleteRol" @closeModal="closeModalRol" />
        <Modal :show="create_rol">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Crear Rol
                </h2>
                <form @submit.prevent="openModal">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <div>
                                <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.name" id="name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.name" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripcion
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.description" id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.description" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="permission" class="font-medium leading-6 text-gray-900">Permisos
                                </InputLabel>
                                <select required multiple v-model="form.permission" id="permission"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled>
                                        Selecciona uno o varios
                                    </option>
                                    <option v-for="permission in permissions" :key="permission.id" :value="permission.id">
                                        {{ permission.name }}| {{ permission.description }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.permission" />
                            </div>
                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                                <button type="submit" :class="{ 'opacity-25': form.processing }"
                                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="rol"
            :nameText="'del nuevo rol'" :createFunction="submit" @closeModal="close" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';


const create_rol = ref(false);
const confirmingRolDeletion = ref(false);
const rolToDelete = ref(null);

const form = useForm({
    name: '',
    description: '',
    permission: []
})

const props = defineProps({
    rols: Object,
    permissions: Object
});

const add_rol = () => {
    create_rol.value = true;
};

const closeModal = () => {
    create_rol.value = false;
};

const submit = () => {
    form.post(route('rols.store'), {
        onSuccess: () => {
            closeModal();
            close();
        },
        onError: () => {
            close();
        }
    })
};

const confirmRolsDeletion = (rolId) => {
    rolToDelete.value = rolId;
    confirmingRolDeletion.value = true;
};

const deleteRol = () => {
    const rolId = rolToDelete.value;
    if (rolId) {
        router.delete(route('rols.delete', { id: rolId }), {
            onSuccess: () => closeModalDelete()
        });
    }
};

const closeModalRol = () => {
    confirmingRolDeletion.value = false;
};
const showModal = ref(false);

const openModal = () => {
    showModal.value = true;
}
const close = () => {
    showModal.value = false;
}

</script>