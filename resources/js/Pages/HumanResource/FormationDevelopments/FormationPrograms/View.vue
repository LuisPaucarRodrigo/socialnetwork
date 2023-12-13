<template>
    <Head title="Ver Programa de Formación" />

    <AuthenticatedLayout>
        <template #header>
            Ver Programa de Formación
        </template>

        <div>
            <p><strong>Nombre:</strong> {{ formation_program.name }}</p>
            <p><strong>Tipo:</strong> {{ formation_program.type }}</p>
            <p><strong>Descripción:</strong> {{ formation_program.description }}</p>
            <p><strong>Fecha:</strong> {{ formatMonthYear(formation_program.month_year) }}</p>
            <p><strong>Empleados:</strong></p>
            <ul>

                <div class="flex space-x-2" v-for="employee in formation_program.employees" :key="employee.id">
                    <p> {{ `${employee.name} ${employee.lastname}` }}</p>
                    <button @click="delete_employee(employee.id)">
                        <TrashIcon class="h-6 w-6 text-red-500" />
                    </button>
                </div>
            </ul>
            <p><strong>Capacitaciones:</strong></p>
            <ul>
                <li v-for="training in formation_program.trainings_list" :key="training.id">
                    {{ training.name }}
                </li>
            </ul>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <!-- Puedes agregar aquí botones o acciones específicas para el modo de visualización si es necesario -->
        </div>
    </AuthenticatedLayout>
</template>
  
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/24/outline';
import Swal from 'sweetalert2';

const formatMonthYear = (fullDate) => {
    const date = new Date(fullDate);
    const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];
    const month = monthNames[date.getMonth()];

    const year = date.getFullYear();
    return `${month} ${year}`;
};
const { formation_program } = defineProps(['formation_program']);

const delete_employee = (id) => {
    Swal.fire({
        title: "¿Estás seguro?",
        text: "Esto no se podrá deshacer",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then((result) => {
        if (result.isConfirmed) {
            router.post(`/management_employees/formation_development/delete-employee/`,
                {
                    formation_program_id: formation_program.id,
                    employee_id: id
                }
                , {
                    onSuccess: () => {
                        return Swal.fire({
                            title: "Éxito",
                            text: "Capacitación eliminada",
                            icon: "success",
                        })
                    },
                })

        }
    });
}



</script>
  