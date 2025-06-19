<template>

    <Head title="Ver Programa de Formación" />

    <AuthenticatedLayout :redirectRoute="'management.employees.formation_development.formation_programs'">
        <template #header>
            Ver Programa de Formación
        </template>
        <div class="sm:px-0">
            <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Informacion del Programa de Formacion</p>
        </div>
        <div class="border-t border-gray-100">
            <dl class="divide-y divide-gray-100">
                <div class="py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Nombre del Programa</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ formation_program.name }}
                    </dd>
                </div>
                <div class="py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Descripcion</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                        {{ formation_program.description }}
                    </dd>
                </div>
                <div>
                    <div class="text-sm font-medium leading-6 text-gray-900 mb-3">Capacitaciones</div>
                    <div v-for="training in formation_program.trainings" :key="training.id" class="mb-1">
                        <p class="mt-1 text-sm leading-6 text-gray-700">- {{ training.name }}</p>
                    </div>
                </div>

                <br>
                <p class="font-medium text-sm">Empleados:</p>
                <div class="px-4 py-6 sm:grid sm:grid-cols-2 sm:gap-x-24 sm:gap-y-8 sm:px-0">
                    <!-- Capacitaciones -->

                    <!-- Empleados -->
                    <div>
                        <div class="text-sm font-normal leading-6 text-gray-900 mb-3">Actualmente en el programa</div>
                        <div v-for="employee in formation_program.employees.filter(item => item.pivot.state === null)"
                            :key="employee.id"
                            class="flex items-center justify-between m-1 text-sm leading-6 text-gray-700 sm:mt-0 border-b-2 border-gray-200">
                            <p class="flex-shrink-0">{{ `${employee.name} ${employee.lastname}` }}</p>
                            <button @click="openModalDelete(employee)">
                                <DeleteIcon />
                            </button>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm font-normal leading-6 text-green-600 mb-3">Completaron el programa</div>
                        <div v-for="employee in formation_program.employees.filter(item => item.pivot.state == true)"
                            :key="employee.id"
                            class="flex items-center justify-between m-1 text-sm leading-6 text-gray-700 sm:mt-0">
                            <p class="flex-shrink-0">{{ `${employee.name} ${employee.lastname}` }}</p>
                        </div>
                    </div>
                    <div>
                        <div class="text-sm font-normal leading-6 text-red-600 mb-3">No completaron el programa</div>
                        <div v-for="employee in formation_program.employees.filter(item => item.pivot.state == false)"
                            :key="employee.id"
                            class="flex items-center justify-between m-1 text-sm leading-6 text-gray-700 sm:mt-0">
                            <p class="flex-shrink-0">{{ `${employee.name} ${employee.lastname}` }}</p>
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
                    <SecondaryButton type="button" @click="closeModal()">Cancelar</SecondaryButton>
                    <DangerButton type="button" @click="delete_employee(selectedEmployee.pivot.id)">Eliminar
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import { DeleteIcon } from "@/Components/Icons";

const { formation_program, userPermissions } = defineProps({
    formation_program: Object,
    userPermissions: Array
});

const hasPermission = (permission) => {
    return userPermissions.includes(permission);
}

const delete_employee = (id) => {
    router.delete(route('management.employees.formation_development.employee.delete',
        { efp_id: id }), {
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