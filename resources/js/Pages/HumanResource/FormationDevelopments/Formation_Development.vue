
<template>
    <Head title="Formación y Desarrollo" />

    <AuthenticatedLayout>
        <template #header>
            Formación y Desarrollo
        </template>

    
        <div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Card 1 -->
                <Link :href="route('management.employees.formation_development.formation_programs')"> 
                    <!-- :href=""route('tu.ruta') -->
                    <button class="bg-white p-4 rounded-md shadow-md text-left h-full w-full">
                        <div>
                            <h3 class="text-lg font-semibold">Programas de Formación</h3>
                            <div class="mb-2">
                                <!-- Icono de Heroicons para Vue -->
                                <BookmarkSquareIcon class="h-6 w-6 text-blue-500" />
                            </div>
                        </div>
                    </button>
                </Link>
                <Link :href="route('management.employees.formation_development.assignation.create')"> 
                    <!-- :href=""route('tu.ruta') -->
                    <button class="bg-white p-4 rounded-md shadow-md text-left h-full w-full">
                        <div>
                            <h3 class="text-lg font-semibold">Asignación de programas</h3>
                            <div class="mb-2">
                                <!-- Icono de Heroicons para Vue -->
                                <PaperAirplaneIcon class="h-6 w-6 text-blue-500" />
                            </div>
                        </div>
                    </button>
                </Link>
                <Link :href="route('management.employees.formation_development.trainings')"> 
                    <!-- :href=""route('tu.ruta') -->
                    <button class="bg-white p-4 rounded-md shadow-md text-left h-full w-full">
                        <div>
                            <h3 class="text-lg font-semibold">Gestion de capacitaciones</h3>
                            <div class="mb-2">
                                <!-- Icono de Heroicons para Vue -->
                                <ClipboardDocumentIcon class="h-6 w-6 text-blue-500" />
                            </div>
                        </div>
                    </button>
                </Link>
            </div>
        </div>


    <!-- <div class="inline-block min-w-full">
            <div class="flex items-center">
                <button @click="add_information" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    Agregar Informacion
                </button>
                <button type="button"
                    class="ml-4 rounded-md bg-blue-200 px-4 py-2 text-center text-sm text-blue-900 hover:bg-blue-100">
                    Asignación de Programas
                </button>
            </div>
            <br>
            <table class="w-full whitespace-no-wrap  overflow-hidden rounded-lg shadow">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            N°
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Nombre
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Descripción
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Tipo de programa
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(formationProgram, i) in formationPrograms.data" :key="formationProgram.id"
                        class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ i + 1 }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ formationProgram.name }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ formationProgram.description }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ formationProgram.type }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">

                            <Link class="text-blue-900 whitespace-no-wrap" :href="route('management.formationPrograms.information.details',{ id: formationProgram.id })">Mas Detalles</Link>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <Link class="text-blue-900 whitespace-no-wrap flex items-center">Mas Detalles</Link>
                                <button class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                    📝
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="formationPrograms.links" />
            </div>
            </div> -->
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { Head, Link, router } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue'
import { BookmarkSquareIcon, ClipboardDocumentIcon, PaperAirplaneIcon } from '@heroicons/vue/24/solid'



// const props = defineProps({
//     formationPrograms: Object
// })


// const add_information = () => {
//     router.get(route('management.employees.formation_development.create'));

// };
</script>