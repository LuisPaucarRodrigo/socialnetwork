<template>
    <Head title="Register" />

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
                            <InputLabel for="name" value="Name" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <TextInput id="name" type="text"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.name" required autofocus autocomplete="name" />
                                <InputError class="mt-2" :message="form.errors.name" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="email" value="Email" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <TextInput id="email" type="email"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.email" required autocomplete="username" />
                                <InputError class="mt-2" :message="form.errors.email" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="dni" value="DNI" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <TextInput id="dni" type="text" maxlength="8"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.dni" required autocomplete="dni" />
                                <InputError class="mt-2" :message="form.errors.dni" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="platform" value="Plataforma" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <select id="platform"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.platform" required autocomplete="platform">
                                    <option disabled>Seleccionar Plataforma</option>
                                    <option>Web</option>
                                </select>
                                <InputError class="mt-2" :message="form.errors.platform" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="rols" class="font-medium leading-6 text-gray-900">Roles</InputLabel>
                            <div class="mt-2">
                                <select required v-model="form.rol" id="rols"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled>Seleccionar Rol</option>
                                    <option v-for="rol in rols" :key="rol.id" :value="rol.id">
                                        {{ `${rol.name} | ${rol.description}` }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.rol" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="password" value="Password" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <TextInput id="password" type="password"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.password" required autocomplete="new-password" />
                                <InputError class="mt-2" :message="form.errors.password" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="password_confirmation" value="Confirm Password"
                                class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <TextInput id="password_confirmation" type="password"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.password_confirmation" required autocomplete="new-password" />
                                <InputError class="mt-2" :message="form.errors.password_confirmation" />
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end">
                <button
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </button>
            </div>
        </form>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="usuario" />
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

const props = defineProps({
    rols: Object
})

const showModal = ref(false);

const form = useForm({
    name: '',
    email: '',
    dni: '',
    platform: 'Seleccionar Plataforma',
    rol: 'Seleccionar Rol',
    password: '',
    password_confirmation: '',
    terms: false,
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
            alert('Ha ocurrido un error. Por favor, inténtelo de nuevo.');
        },
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>
