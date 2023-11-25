
<template>
    <Head title="Capacitaciones" />

    <AuthenticatedLayout>
        <template #header>
            Capacitaciones
        </template>
        <div class="inline-block min-w-full">
            <div class="flex items-center">
                <button @click="add_information" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    Agregar Informacion
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
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(training, i) in trainings.data" :key="training.id"
                        class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ i + 1 }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ training.name }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ training.description }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">

                            <!-- <Link class="text-blue-900 whitespace-no-wrap"
                                :href="route('management.formationPrograms.information.details', { id: training.id })">
                            Mas Detalles</Link> -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <Link class="text-blue-900 whitespace-no-wrap flex items-center">Mas Detalles</Link>
                                
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="trainings.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { Head, Link, router } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue'
import { BookmarkSquareIcon, ClipboardDocumentIcon, PaperAirplaneIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
    trainings: Object
})


const add_information = () => {
    router.get(route('management.employees.formation_development.trainings.create'));
}
</script>