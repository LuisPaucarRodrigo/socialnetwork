<template>

    <Head title="Usuarios" />

    <AuthenticatedLayout :redirectRoute="'users.index'">
        <template #header>
            Usuarios
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <div class="flex justify-between">
                <PrimaryButton @click="add_users" type="button">
                    + Agregar
                </PrimaryButton>
                <Search v-model:search="formSearch.searchQuery" fields="Nombre, Dni" />
            </div>
            <UsersTable :users="users" :formSearch="formSearch" :platforms="platforms"
            :linkEmployee="linkEmployee" :confirmUserDeletion="confirmUserDeletion"/>

            <div v-if="users.data"
                class="flex flex-col items-center border-t bg-white px-5 py-3 xs:flex-row xs:justify-between">
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
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { notify, notifyError } from '@/Components/Notification';
import { Toaster } from 'vue-sonner';
import Search from '@/Components/Search.vue';
import UsersTable from '@/Layouts/Users/UsersTable.vue';

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
        updateFrontEnd("updateRelations", response.data)
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

function updateFrontEnd(action, data) {
    const validations = users.value.data || users.value
    if (action === "updateRelations") {
        const index = validations.findIndex(item => item.dni === data.dni)
        validations[index].employee = data
        notify(`El vínculo se realizó correctamente con ${data.name}`);

    }
}
</script>
