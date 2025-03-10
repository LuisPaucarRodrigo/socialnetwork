<template>

    <Head title="Roles" />
    <AuthenticatedLayout :redirectRoute="'rols.index'">
        <template #header>
            Roles
        </template>
        <div class="min-w-full overflow-hidden">
            <PrimaryButton @click="add_rol" type="button">
                + Agregar
            </PrimaryButton>

            <TableStructure>
                <template #thead>
                    <tr>
                        <TableTitle>Nombre</TableTitle>
                        <TableTitle>Descripcion</TableTitle>
                        <TableTitle></TableTitle>
                    </tr>
                </template>
                <template #tbody>
                    <tr v-for="rol in rols.data" :key="rol.id">
                        <TableRow>{{ rol.name }}</TableRow>
                        <TableRow>{{ rol.description }}</TableRow>
                        <TableRow>
                            <div class="flex space-x-3 justify-center">
                                <Link class="text-blue-900 whitespace-no-wrap"
                                    :href="route('rols.details', { id: rol.id })">
                                <EyeIcon class="w-6 h-6 text-teal-500" />
                                </Link>
                                <button type="button" @click="editModalRol(rol)"
                                    class="text-blue-900 whitespace-no-wrap">
                                    <PencilSquareIcon class="w-5 h-5 text-yellow-400" />
                                </button>
                                <button type="button" @click="confirmRolsDeletion(rol.id)"
                                    class="text-blue-900 whitespace-no-wrap">
                                    <TrashIcon class="w-5 h-5 text-red-500" />
                                </button>
                            </div>
                        </TableRow>
                    </tr>
                </template>
            </TableStructure>
            <div v-if="rols.total === rols.per_page"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
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
                                <select required size="8" multiple v-model="form.permission" id="permission"
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
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import { EyeIcon, PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';

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