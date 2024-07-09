<template>

    <Head title="Registro" />

    <AuthenticatedLayout :redirectRoute="'users.index'">
        <template #header>
            Registro
        </template>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informacion</h2>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <InputLabel for="name" value="Nombre" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <TextInput id="name" type="text"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.name" required autofocus autocomplete="off" />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="email" value="Email" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <TextInput id="email" type="email"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.email" required autocomplete="off" />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="dni" value="DNI" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <TextInput id="dni" type="text" maxlength="8"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.dni" required autocomplete="off" />
                                <InputError class="mt-2" :message="form.errors.dni" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="phone" value="Teléfono" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <TextInput id="phone" type="text" maxlength="9"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.phone" required autocomplete="off" />
                                <InputError class="mt-2" :message="form.errors.phone" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="platform" value="Plataforma" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <select id="platform"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.platform" required autocomplete="off">
                                    <option disabled>Seleccionar Plataforma</option>
                                    <option>Web</option>
                                    <option>Movil</option>
                                    <option>Web/Movil</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.platform" />
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <InputLabel>Empresa</InputLabel>
                            <div class="flex gap-4 items-center mt-4">
                                <label class="flex gap-2 items-center text-sm">
                                    CCIP
                                    <input type="radio" :value="'CCIP'" v-model="form.company" @input="handleCompany"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <label class="flex gap-2 items-center text-sm">
                                    Social Network
                                    <input type="radio" :value="'Social Network'" v-model="form.company"
                                        @input="handleCompany"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel class="font-medium leading-6 text-gray-900" value="Área" />
                            <div class="mt-2">
                                <select :disabled="form.company === 'CCIP' ? false : true"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.area_id" autocomplete="off">
                                    <option value="" disabled>Seleccionar Area</option>
                                    <option v-for="item in areas" :key="item.id" :value="item.id">{{ item.name }}
                                    </option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.area_id" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="rols" class="font-medium leading-6 text-gray-900">Roles</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.rol" id="rols"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccionar Rol</option>
                                    <option v-for="rol in rols" :key="rol.id" :value="rol.id">
                                        {{ `${rol.name} | ${rol.description}` }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.rol" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="password" value="Contraseña" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2 relative">
                                <TextInput id="password" :type="passwordVisible ? 'text' : 'password'"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.password" required autocomplete="off" />
                                <button type="button" @click="togglePasswordVisibility"
                                    class="absolute inset-y-0 right-0 flex items-center p-3">
                                    <span v-if="passwordVisible" class="">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                        </svg>
                                    </span>
                                    <span v-else>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </span>
                                </button>
                            </div>
                            <InputError class="mt-2" :message="form.errors.password" />
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="password_confirmation" value="Confirmar Contraseña"
                                class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2 relative">
                                <TextInput id="password_confirmation"
                                    :type="passwordConfirmationVisible ? 'text' : 'password'"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.password_confirmation" required autocomplete="off" />
                                <button type="button" @click="togglePasswordConfirmationVisibility"
                                    class="absolute inset-y-0 right-0 flex items-center p-3">
                                    <span v-if="passwordConfirmationVisible">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                                        </svg>
                                    </span>
                                    <span v-else>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                        </svg>
                                    </span>
                                </button>
                            </div>
                            <InputError class="mt-2" :message="form.errors.password_confirmation" />
                        </div>

                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end">
                <button
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Registrar
                </button>
            </div>
        </form>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="usuario" />
        <ErrorOperationModal :showError="errorModal" :title="'Error'" :message="'Ha ocurrido un error'" />

    </AuthenticatedLayout>
</template>

<script setup>
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';

const { rols, areas } = defineProps({
    rols: Object,
    areas: Object
})

const showModal = ref(false);
const errorModal = ref(false);

const form = useForm({
    name: '',
    email: '',
    dni: '',
    platform: 'Seleccionar Plataforma',
    rol: '',
    area_id: '',
    password: '',
    phone: '',
    password_confirmation: '',
    terms: false,
    company: 'CCIP'
});

const submit = () => {
    form.post(route('register'), {
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
    });
};

function handleCompany(e) {
    form.area_id = '';
    form.rol = '';
}


//visible password
const passwordVisible = ref(false);
const passwordConfirmationVisible = ref(false);
const togglePasswordVisibility = () => {
    passwordVisible.value = !passwordVisible.value;
};
const togglePasswordConfirmationVisibility = () => {
    passwordConfirmationVisible.value = !passwordConfirmationVisible.value;
};

</script>
