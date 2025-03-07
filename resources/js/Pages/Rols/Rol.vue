<template>

    <Head title="Roles" />
    <AuthenticatedLayout :redirectRoute="'rols.index'">
        <template #header>
            Roles
        </template>
        <div class="min-w-full overflow-hidden rounded-lg shadow">
            <PrimaryButton @click="add_rol" type="button">
                + Agregar
            </PrimaryButton>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
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
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-teal-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    </Link>
                                    <button type="button" @click="editModalRol(rol)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>
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
            </div>
            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="rols.links" />
            </div>
        </div>
        <Modal :show="create_rol">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ stateCreateUpdate == true ? 'Crear' : 'Actualizar' }} Rol
                </h2>
                <form @submit.prevent="submit">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <div class="shadow border-gray-300/10">
                                <InputLabel for="name">Aviso</InputLabel>
                                <div class="mt-4 p-4 border border-red-300 rounded-md bg-red-50">
                                    <p class="text-red-600">
                                        Aviso Importante:<br>
                                        Si no se siguen las indicaciones mencionadas, podrían surgir fallos en el
                                        aplicativo.
                                    </p>
                                </div>
                                <div class="mt-2 p-4 border border-gray-300 rounded-md bg-gray-50">
                                    <p>
                                        Los roles administradores tienen únicamente permisos de gerente.<br>
                                        Un rol gerente posee solo permisos de gerente.<br>
                                        Un rol que solo puede visualizar tendrá permisos normales, pero no de gerente.
                                    </p>
                                </div>

                            </div>

                            <div>
                                <InputLabel for="name">Nombre</InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.name" id="name" />
                                    <InputError :message="form.errors.name" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="description">Descripcion</InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.description" id="description" />
                                    <InputError :message="form.errors.description" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="permission">Permisos</InputLabel>
                                <select required multiple v-model="form.permission" id="permission" size="15"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled>
                                        Selecciona uno o varios
                                    </option>
                                    <option v-for="permission in permissions" :key="permission.id"
                                        :value="permission.id" :selected="form.permission.includes(permission.id)">
                                        {{ permission.name }}| {{ permission.description }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.permission" />
                            </div>
                            <div class="mt-6 flex items-center justify-end gap-x-3">
                                <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                                    {{ stateCreateUpdate === true ? 'Crear' : 'Actualizar' }}
                                </PrimaryButton>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
        <ConfirmDeleteModal :confirmingDeletion="confirmingRolDeletion" itemType="rol" :deleteFunction="deleteRol"
            @closeModal="closeModalRol" />
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="rol" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Pagination from '@/Components/Pagination.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';

const create_rol = ref(false);
const confirmingRolDeletion = ref(false);
const rolToDelete = ref(null);
const showModal = ref(false);
const stateCreateUpdate = ref(true);
const rol_id_update = ref(null);

const form = useForm({
    name: '',
    description: '',
    permission: []
});

const props = defineProps({
    rols: Object,
    permissions: Object
});

const add_rol = () => {
    create_rol.value = true;
};

const closeModal = () => {
    create_rol.value = false;
    stateCreateUpdate.value = true
};

const submit = () => {
    let url = stateCreateUpdate.value ? route('rols.store') : route('rols.update', { id: rol_id_update.value })
    form.post(url, {
        onSuccess: () => {
            closeModal();
            showModal.value = true
            setTimeout(() => {
                showModal.value = false
                router.visit(route('rols.index'))
            }, 2000);
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
            onSuccess: () => closeModalRol()
        });
    }
};

const closeModalRol = () => {
    confirmingRolDeletion.value = false;
};

function editModalRol(rol) {
    rol_id_update.value = rol.id
    stateCreateUpdate.value = false
    form.name = rol.name
    form.description = rol.description
    form.permission = rol.permissions.map(permission => permission.id);
    create_rol.value = true
};
</script>