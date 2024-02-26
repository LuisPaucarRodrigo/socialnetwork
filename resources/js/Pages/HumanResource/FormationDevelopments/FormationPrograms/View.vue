<template>
    <Head title="Ver Programa de Formación" />

    <AuthenticatedLayout :redirectRoute="'management.employees.formation_development.formation_programs'">
        <template #header>
            Ver Programa de Formación
        </template>
        <div class="px-4 sm:px-0">
            <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Informacion del Programa de Formacion</p>
        </div>
        <div class="mt-6 border-t border-gray-100">
            <dl class="divide-y divide-gray-100">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Nombre del Programa</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ formation_program.name }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Descripcion</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ formation_program.description
                    }}</dd>
                </div>
                <!-- <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Fecha</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ formatMonthYear(formation_program.month_year)
                    }}</dd>
                </div> -->
                <div class="px-4 py-6 sm:grid sm:grid-cols-2 sm:gap-4 sm:px-0">
                    <!-- Capacitaciones -->
                    <div>
                        <div class="text-sm font-medium leading-6 text-gray-900 mb-3">Capacitaciones</div>
                        <div v-for="training in formation_program.trainings" :key="training.id" class="mb-1">
                            <p class="mt-1 text-sm leading-6 text-gray-700">- {{ training.name }}</p>
                        </div>
                    </div>

                    <!-- Empleados -->
                    <div>
                        <div class="text-sm font-medium leading-6 text-gray-900 mb-3">Empleados</div>
                        <div v-for="employee in formation_program.employees" :key="employee.id"
                            class="flex items-center justify-between mt-1 text-sm leading-6 text-gray-700 sm:mt-0">
                            <p class="flex-shrink-0">{{ `${employee.name} ${employee.lastname}` }}</p>
                            <button @click="openModalDelete(employee)" class="ml-2 flex-shrink-0">
                                <TrashIcon class="h-5 w-5 text-red-500" />
                            </button>
                        </div>
                    </div>
                </div>
            </dl>
        </div>

        <div class="mt-6 flex items-center justify-end gap-x-6">
            <!-- Puedes agregar aquí botones o acciones específicas para el modo de visualización si es necesario -->
        </div>
        <Modal :show="showModalDelete" :maxWidth="'md'">
            <!-- Contenido del modal cuando no hay empleados -->
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    ¿Esta seguro?
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    Se eliminara a <b>{{ selectedEmployee.name }} {{ selectedEmployee.lastname }}</b> del Programa de
                    Formacion. Esta accion no se podra revertir mas adelante.
                </p>
                <div class="mt-6 flex justify-end">
                    <button
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-red-500 text-white hover:bg-red-400 mr-2"
                        type="button" @click="closeModal()"> Cancelar
                    </button>
                    <button
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-indigo-500 text-white hover:bg-indigo-400"
                        type="button" @click="delete_employee(selectedEmployee.id)"> Eliminar
                    </button>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>
  
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/24/outline';
import Swal from 'sweetalert2';
import Modal from '@/Components/Modal.vue';
import { ref, defineProps } from 'vue';

const { formation_program } = defineProps(['formation_program']);

const delete_employee = (id) => {
    router.post(`/management_employees/formation_development/delete-employee/`,
        {
            formation_program_id: formation_program.id,
            employee_id: id
        }
        , {
            onSuccess: () => {
                closeModal()
            },
        })
}

const showModalDelete = ref(false);
const selectedEmployee = ref(null);

const openModalDelete = (employee) => {
    selectedEmployee.value = employee;
    showModalDelete.value = true;
}
const closeModal = () => {
    showModalDelete.value = false;
}

</script>
  