<template>

    <Head title="Formación y Desarrollo" />

    <AuthenticatedLayout :redirectRoute="'management.employees.formation_development'">
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
                        <h3 class="text-lg font-semibold">Gestión de Capacitaciones</h3>
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
                <Link v-if="hasPermission('HumanResourceManager')" :href="route('management.employees.formation_development.assignation.create')">
                <button class="bg-white p-4 rounded-md shadow-md text-left h-full w-full">
                    <div>
                        <h3 class="text-lg font-semibold">Asignación de Programas</h3>
                        <div class="mb-2">
                            <PaperAirplaneIcon class="h-6 w-6 text-blue-500" />
                        </div>
                    </div>
                </button>
                </Link>
                <Link :href="route('management.employees.formation_development.employees_in_programs')">
                <button class="bg-white p-4 rounded-md shadow-md text-left h-full w-full">
                    <div>
                        <h3 class="text-lg font-semibold">Personal Asignado</h3>
                        <div class="mb-2">
                            <ClipboardDocumentCheckIcon class="h-6 w-6 text-blue-500" />
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
import { BookmarkSquareIcon, ClipboardDocumentIcon, PaperAirplaneIcon, ClipboardDocumentCheckIcon } from '@heroicons/vue/24/solid'

const props = defineProps({
    userPermissions:Array
})

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

</script>