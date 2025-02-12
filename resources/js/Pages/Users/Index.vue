<template>

    <Head title="Usuarios" />

    <AuthenticatedLayout :redirectRoute="'users.index'">
        <template #header>
            Usuarios
        </template>
        <Toaster richColors />
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-between">
                <PrimaryButton @click="add_users" type="button">
                    + Agregar
                </PrimaryButton>
                <div>
                    <TextInput v-model="formSearch.searchQuery" placeholder="Nombre,Dni" />
                </div>
            </div>
            <div class="overflow-x-auto h-[72vh]">
                <table class="w-full">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Name
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                <TableHeaderFilter labelClass="text-[11px]" label="Platform" :options="platforms"
                                    v-model="formSearch.platform" width="w-44" />
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Email
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                DNI
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Teléfono
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="user in users.data || users" :key="user.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ user.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ user.platform }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ user.email }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ user.dni }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ user.phone }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button v-if="(user.platform === 'Web/Movil' || user.platform === 'Movil')
                                     && !user.employee"
                                        @click="linkEmployee(user.id)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w=6 h-6 text-green-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M13.19 8.688a4.5 4.5 0 0 1 1.242 7.244l-4.5 4.5a4.5 4.5 0 0 1-6.364-6.364l1.757-1.757m13.35-.622 1.757-1.757a4.5 4.5 0 0 0-6.364-6.364l-4.5 4.5a4.5 4.5 0 0 0 1.242 7.244" />
                                        </svg>
                                    </button>
                                    <Link class="text-blue-900 whitespace-no-wrap"
                                        :href="route('users.details', { id: user.id })">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-teal-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    </Link>
                                    <Link class="text-blue-900 whitespace-no-wrap"
                                        :href="route('users.edit', { id: user.id })">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    </Link>
                                    <button v-if="user.id != 1" type="button" @click="confirmUserDeletion(user.id)"
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

            <div v-if="users.data" class="flex flex-col items-center border-t bg-white px-5 py-3 xs:flex-row xs:justify-between">
                <pagination :links="users.links" />
            </div>
        </div>
        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    ¿Estás seguro de que quieres eliminar la cuenta?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Una vez que se elimine la cuenta, todos sus recursos y datos se eliminarán permanentemente. Por
                    favor
                    ingrese su contraseña para confirmar que desea eliminar permanentemente la cuenta.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />
                    <TextInput id="password" ref="passwordInput" v-model="form.password" type="password"
                        class="mt-1 block w-3/4" placeholder="Password" @keyup.enter="deleteUser" />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                    <DangerButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        @click="deleteUser">
                        Delete Account
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link, Head, router, useForm } from '@inertiajs/vue3';
import { nextTick, ref, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { notify, notifyError } from '@/Components/Notification';
import TableHeaderFilter from '@/Components/TableHeaderFilter.vue';
import { Toaster } from 'vue-sonner';

const confirmingUserDeletion = ref(false);
const usersToDelete = ref(null);
const passwordInput = ref(null);

const { user } = defineProps({
    user: Object
})

const users = ref(user)
const platforms = [
    'Web',
    'Web/Movil',
    'Movil'
]
const add_users = () => {
    router.get(route('register'));
}

const form = useForm({
    password: '',
});

const initialSearch = {
    searchQuery: '',
    platform: [...platforms]
}

const formSearch = ref({ ...initialSearch })

watch(formSearch, searchAdvance, { deep: true })

async function searchAdvance() {
    let url = route('users.search')
    try {
        let res = await axios.post(url, formSearch.value);
        users.value = res.data;
    } catch (error) {
        console.error(error)
    }
}

const confirmUserDeletion = (userId) => {
    confirmingUserDeletion.value = true;
    usersToDelete.value = userId;

};

const deleteUser = () => {
    const userId = usersToDelete.value;
    form.delete(route('users.destroy', { id: userId }), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
};

async function linkEmployee(id) {
    let url = route('users.linkEmployee', { user: id })
    try {
        let response = await axios.get(url)
        updateFrontEnd("updateRelations",response.data)
    } catch (error) {
        console.error("Error al vincular empleado:", error);
        if (error.response) {
            if (error.response.status === 404) {
                notifyError("No se encontró un empleado para vincular.");
            } else if (error.response.status === 500) {
                notifyError("Error en el servidor. Inténtalo más tarde.");
            } else {
                notifyError(`Error ${error.response.status}: ${error.response.data}`);
            }
        } else {
            notifyError("Error inesperado. Verifica tu conexión.");
        }
    }
}

function updateFrontEnd(action,data){
    const validations = users.value.data || users.value
    if(action === "updateRelations"){
        const index = validations.findIndex(item => item.dni === data.dni)
        validations[index].employee = data
        notify(`El vínculo se realizó correctamente con ${data.name}`);

    }
}
</script>
