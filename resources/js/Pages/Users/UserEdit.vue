<template>
    <Head title="Editar Proveedor" />
    <AuthenticatedLayout>
        <template #header>
            Proveedor
        </template>
        <form @submit.prevent="submit">
            <div class="mt-6 border-t border-gray-100">
                <dl class="divide-y divide-gray-100">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Nombre</dt>
                        <TextInput type="text" v-model="form.name" id="name"
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-4 sm:mt-0 border-gray-300 bg-transparent focus:outline-none focus:border-indigo-500" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Plataforma</dt>
                        <select v-model="form.platform" id="platform"
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-4 sm:mt-0 border-gray-300 bg-transparent focus:outline-none focus:border-indigo-500">
                            <option disabled> {{ props.users.platform }}</option>
                            <option>Web</option>
                            <option>Movil</option>
                            <option>Ambos</option>
                        </select>
                        <InputError :message="form.errors.platform" />
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Email</dt>
                        <TextInput type="email" v-model="form.email" id="email"
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-4 sm:mt-0 border-gray-300 bg-transparent focus:outline-none focus:border-indigo-500" />
                        <InputError :message="form.errors.email" />
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Dni</dt>
                        <TextInput type="text" v-model="form.dni" id="dni" maxlength="8"
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-4 sm:mt-0 border-gray-300 bg-transparent focus:outline-none focus:border-indigo-500" />
                        <InputError :message="form.errors.dni" />
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Rol</dt>
                        <select v-model="form.rol" id="rol"
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-4 sm:mt-0 border-gray-300 bg-transparent focus:outline-none focus:border-indigo-500">
                            <option :value="props.users.role.id" disabled> {{ props.users.role.name }} | {{ props.users.role.description }}</option>
                            <option v-for="rol in rols" :key="rol.id" :value="rol.id">
                                {{ rol.name }} | {{ rol.description }}
                            </option>
                        </select>
                        <InputError :message="form.errors.rol" />
                    </div>
                </dl>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue'
import { Head, useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    users: {
        type: Object,
        required: true
    },
    rols: Object
});

const form = useForm({
    name: props.users.name,
    platform: props.users.platform,
    email: props.users.email,
    dni: props.users.dni,
    rol: props.users.role.id
});

const submit = () => {
    form.put(route('users.update', props.users.id), form)
}
</script>
