<template>
    <Head title="Editar Proveedor" />
    <AuthenticatedLayout>
        <template #header>
            Rol
        </template>
        <form @submit.prevent="submit">
            <div class="mt-6 border-t border-gray-100">
                <dl class="divide-y divide-gray-100">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Nombre</dt>
                        <TextInput type="text" v-model="form.name" id="company_name"
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-4 sm:mt-0 border-gray-300 bg-transparent focus:outline-none focus:border-indigo-500" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Descripcion</dt>
                        <TextInput type="text" v-model="form.description" id="contact_name"
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-4 sm:mt-0 border-gray-300 bg-transparent focus:outline-none focus:border-indigo-500" />
                        <InputError :message="form.errors.description" />
                    </div>

                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Permisos</dt>
                        <select required multiple v-model="form.permissions" id="permissions"
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-4 sm:mt-0 border-gray-300 bg-transparent focus:outline-none focus:border-indigo-500">
                            <option :class="{ 'bg-blue-200': form.permissions.includes(permission.id) }"
                                v-for="permission in permissions" :key="permission.id" :value="permission.id">
                                {{ permission.name }} | {{ permission.description }}
                            </option>
                        </select>
                        <InputError :message="form.errors.permissions" />
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
import InputError from '@/Components/InputError.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { defineProps } from 'vue';

const props = defineProps({
    rols: {
        type: Object,
        required: true
    },
    permissions: Object
});

const form = useForm({
    name: props.rols.name,
    description: props.rols.description,
    permissions: [1,2,3],
});
console.log(props.rols.permissions);
// const permission = (permissionId) => {

// }

const submit = () => {
    form.put(route('rols.update', props.rols.id), form)
}
</script>
