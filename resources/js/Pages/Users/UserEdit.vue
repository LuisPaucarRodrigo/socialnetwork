<template>

    <Head title="Editar Usuario" />
    <AuthenticatedLayout :redirectRoute="'users.index'">
        <template #header>
            Usuario
        </template>
        <form @submit.prevent="submit">
            <div class="mt-6 border-t border-gray-100">
                <dl class="divide-y divide-gray-100">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Nombre</dt>
                        <TextInput class="sm:col-span-4" type="text" v-model="form.name" id="name" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Plataforma</dt>
                        <select v-model="form.platform" id="platform"
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-4 sm:mt-0 border-gray-300 bg-transparent focus:outline-none focus:border-indigo-500">
                            <option disabled> {{ props.users.platform }}</option>
                            <option>Web</option>
                            <option>Movil</option>
                            <option>Web/Movil</option>
                        </select>
                        <InputError :message="form.errors.platform" />
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Email</dt>
                        <TextInput class="sm:col-span-4" type="email" v-model="form.email" id="email" />
                        <InputError :message="form.errors.email" />
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">DNI</dt>
                        <TextInput class="sm:col-span-4" type="text" v-model="form.dni" id="dni" maxlength="8" />
                        <InputError :message="form.errors.dni" />
                    </div>

                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Teléfono</dt>
                        <TextInput class="sm:col-span-4" type="text" v-model="form.phone" id="phone" />
                        <InputError :message="form.errors.phone" />
                    </div>

                    <div v-if="form.platform !== 'Movil'" class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Rol</dt>
                        <select v-model="form.role_id" id="rol"
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-4 sm:mt-0 border-gray-300 bg-transparent focus:outline-none focus:border-indigo-500">
                            <option value="" disabled>Seleccione un rol</option>
                            <option v-for="rol in rols" :key="rol.id" :value="rol.id">
                                {{ rol.name }} | {{ rol.description }}
                            </option>
                        </select>
                        <InputError :message="form.errors.role_id" />
                    </div>

                    <div v-if="form.platform !== 'Movil'" class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Área</dt>
                        <select v-model="form.area_id" id="area_id"
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-4 sm:mt-0 border-gray-300 bg-transparent focus:outline-none focus:border-indigo-500">
                            <option value="" disabled>Seleccione un área</option>
                            <option v-for="area in props.areas" :key="area.id" :value="area.id">
                                {{ area.name }}
                            </option>
                        </select>
                        <InputError :message="form.errors.area_id" />
                    </div>
                    <button @click.prevent="open_change_password" type="button"
                        class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 whitespace-nowrap">
                        Cambiar Contraseña
                    </button>
                </dl>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">Guardar</PrimaryButton>
            </div>
        </form>



        <Modal :show="change_password">
            <div class="relative shadow sm:rounded-lg px-5 py-5">
                <!-- Botón de cierre -->
                <button @click="close_change_password"
                    class="absolute mt-2 mr-2 top-2 right-2 text-gray-500 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
                <!-- Contenido del modal -->
                <UpdatePasswordForm class="max-w-xl" :user_id="props.users.id" />
            </div>
        </Modal>


        <ConfirmUpdateModal :confirmingupdate="showModal" itemType="Usuario" />
        <ErrorOperationModal :showError="errorModal" :title="'Error'" :message="'Ha ocurrido un error'" />

    </AuthenticatedLayout>

</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue'
import { Head, useForm, router } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import { ref, watch } from 'vue';
import UpdatePasswordForm from '../Profile/Partials/UpdatePasswordForm.vue';
import Modal from '@/Components/Modal.vue';

const props = defineProps({
    users: {
        type: Object,
        required: true
    },
    rols: Object,
    areas: Object
});

const errorModal = ref(false);
const showModal = ref(false);
const change_password = ref(false);

const form = useForm({
    user_id: props.users.id,
    name: props.users.name,
    platform: props.users.platform,
    email: props.users.email,
    dni: props.users.dni,
    phone: props.users.phone,
    role_id: props.users.role ? props.users.role.id : '',
    area_id: props.users.area ? props.users.area.id : ''
});

watch(() => form.platform, (newVal) => {
    if (newVal === "Movil") {
        form.role_id = null;
        form.area_id = null;
    }
});

const open_change_password = () => {
    change_password.value = true;
}

const close_change_password = () => {
    change_password.value = false;
}

const submit = () => {
    form.put(route('users.update', { id: props.users.id }), {
        onSuccess: () => {
            showModal.value = true;
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('users.index'))
            }, 2000);
        },
        onError: () => {
            errorModal.value = true;
            setTimeout(() => {
                errorModal.value = false
            }, 1500);
        },
        onFinish: () => form.reset('password', 'password_confirmation'),
    })
}

</script>
