<template>
    <Head title="Tareas" />
    <AuthenticatedLayout>
        <template #header>
            Seguimiento de Tareas
        </template>
        <div class="flex flex-col sm:flex-row items-center justify-between space-y-4 sm:space-y-0 space-x-0 sm:space-x-4">
            <div class="relative inline-block">
                <select
                    class="block w-full sm:w-48 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring focus:border-blue-300">
                    <option selected value="opcion1">Proyecto 1</option>
                    <option value="opcion2">Proyecto 2</option>
                    <option value="opcion3">Proyecto 3</option>
                </select>
            </div>

            <button @click="addTask" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                + Agregar
            </button>

            <input v-model="filterText" type="text" placeholder="Filtrar datos"
                class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring focus:border-blue-300" />
        </div>


        <div class="tailwing mt-10 m-5">
            <ul role="list" class="divide-y divide-gray-100">
                <a @click="edittask" v-for="person in people" :key="person.email" class="flex justify-between gap-x-6 py-5">
                    <div class="flex min-w-0 gap-x-4">
                        <div class="min-w-0 flex-auto">
                            <p class="text-sm font-semibold leading-6 text-gray-900">{{ person.name }}</p>
                            <p class="mt-1 truncate text-xs leading-5 text-gray-500">{{ person.email }}</p>
                        </div>
                    </div>
                    <div class="hidden shrink-0 sm:flex sm:flex-col sm:items-end">
                        <p class="text-sm leading-6 text-gray-900">{{ person.role }}</p>
                        <p v-if="person.lastSeen" class="mt-1 text-xs leading-5 text-gray-500">
                            Last seen <time :datetime="person.lastSeenDateTime">{{ person.lastSeen }}</time>
                        </p>
                        <div v-else class="mt-1 flex items-center gap-x-1.5">
                            <div class="flex-none rounded-full bg-emerald-500/20 p-1">
                                <div class="h-1.5 w-1.5 rounded-full bg-emerald-500" />
                            </div>
                            <p class="text-xs leading-5 text-gray-500">Online</p>
                        </div>
                    </div>
                </a>
            </ul>
        </div>


    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';

const people = [
    {
        name: 'Tarea 1',
        email: 'Desarrollo de tarea 1',
        role: '',
        lastSeen: '3h ago',
        lastSeenDateTime: '2023-01-23T13:23Z',
    },]

const addTask = () => {
    router.get(route('tasks.new'));
};
const edittask = () => {
    router.get(route('tasks.edit'));
};
</script>
