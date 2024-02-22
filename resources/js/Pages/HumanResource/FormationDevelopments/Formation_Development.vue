
<template>
    <Head title="Formación y Desarrollo" />

    <AuthenticatedLayout>
        <template #header>
            Formación y Desarrollo
        </template>

        <div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
                <!-- Card 1 -->
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
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link } from '@inertiajs/vue3';
import { BookmarkSquareIcon, ClipboardDocumentIcon, PaperAirplaneIcon } from '@heroicons/vue/24/solid'

</script>