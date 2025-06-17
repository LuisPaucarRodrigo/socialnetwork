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
                            Icono de Capacitaciones
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
                            Icono de Programas de Formación
                        </div>
                    </div>
                </button>
                </Link>
                <Link :href="route('management.employees.formation_development.assignation.create')">
                <button class="bg-white p-4 rounded-md shadow-md text-left h-full w-full">
                    <div>
                        <h3 class="text-lg font-semibold">Asignación de Programas</h3>
                        <div class="mb-2">
                            Icono de Asignación de Programas
                        </div>
                    </div>
                </button>
                </Link>
                <Link :href="route('management.employees.formation_development.employees_in_programs')">
                <button class="bg-white p-4 rounded-md shadow-md text-left h-full w-full">
                    <div>
                        <h3 class="text-lg font-semibold">Personal Asignado</h3>
                        <div class="mb-2">
                            Icono de Personal Asignado
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
</script>