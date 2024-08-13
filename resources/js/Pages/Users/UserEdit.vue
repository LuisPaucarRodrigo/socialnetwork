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
                        <TextInput class="sm:col-span-4" type="text" v-model="form.name" id="name" />
                        <InputError :message="form.errors.name" />
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Plataforma</dt>
                        <select @change="select_platform" v-model="form.platform" id="platform"
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
                        <TextInput class="sm:col-span-4"  type="email" v-model="form.email" id="email" />
                        <InputError :message="form.errors.email" />
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">DNI</dt>
                        <TextInput class="sm:col-span-4" type="text" v-model="form.dni" id="dni" maxlength="8"/>
                        <InputError :message="form.errors.dni" />
                    </div>

                    <div class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
                        <dt class="text-sm font-medium leading-6 text-gray-900 sm:col-span-2">Teléfono</dt>
                        <TextInput class="sm:col-span-4" type="text" v-model="form.phone" id="phone"/>
                        <InputError :message="form.errors.phone" />
                    </div>

                    <div v-if="props.users.role_id != 1 && form.platform !== 'Movil'" class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
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

                    <div v-if="props.users.role_id != 1 && form.platform !== 'Movil'" class="px-4 py-6 sm:grid sm:grid-cols-6 sm:gap-4 sm:px-0">
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
                </dl>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">Guardar</PrimaryButton>
            </div>
        </form>

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
import { ref } from 'vue';

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

const submit = () => {
    form.put(route('users.update', {id: props.users.id}), {
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

const select_platform = (e) => {
    if (e.target.value === 'Movil'){
        form.role_id = '';
        form.area_id = '';
    }
}

</script>
