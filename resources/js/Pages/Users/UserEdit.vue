<template>
    <Head title="Editar Proveedor" />
    <AuthenticatedLayout :redirectRoute="'users.index'">
        <template #header>
            Usuario
        </template>
        <form @submit.prevent="submit">
            <div class="mt-6 border-t border-gray-100">
                <dl class="divide-y divide-gray-100">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Nombre</dt>
                        <TextInput type="text" v-model="form.name" id="name" />
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
                        <TextInput type="email" v-model="form.email" id="email" />
                        <InputError :message="form.errors.email" />
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Dni</dt>
                        <TextInput type="text" v-model="form.dni" id="dni" maxlength="8"/>
                        <InputError :message="form.errors.dni" />
                    </div>
                    <div v-if="props.users.role_id != 1" class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
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
                </dl>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">Guardar</PrimaryButton>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue'
import { Head, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    users: {
        type: Object,
        required: true
    },
    rols: Object
});

const form = useForm({
    user_id: props.users.id,
    name: props.users.name,
    platform: props.users.platform,
    email: props.users.email,
    dni: props.users.dni,
    role_id: props.users.role.id
});

const submit = () => {
    form.put(route('users.update', {id: props.users.id}))
}
</script>
