<template>
    <Head title="Detalles de Rol" />
    <AuthenticatedLayout :redirectRoute="'rols.index'">
        <template #header>
            Rol
        </template>
        <div class="mt-6 border-t border-gray-100">
            <dl class="divide-y divide-gray-100">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Nombre</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ rols.name }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Descripcion</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ rols.description }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Permisos</dt>
                    <div class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        <dd v-for="(permission, index) in rols.permissions" :key="index">{{ permission.name }}</dd>
                    </div>
                </div>
            </dl>
        </div>
    </AuthenticatedLayout>  
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

const props = defineProps({
    rols: Object
})
</script>