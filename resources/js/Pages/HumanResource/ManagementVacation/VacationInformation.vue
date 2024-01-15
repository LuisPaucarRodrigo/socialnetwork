<template>
    <Head title="Information Additional" />
    <AuthenticatedLayout>

        <template #header>
            Perfil
        </template>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informacion sobre las vacaciones del
                        empleado</h2>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Use a permanent address where you can receive mail.</p>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <InputLabel for="employee" class="font-medium leading-6 text-gray-900">Empleado</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.employee_id"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione un empleado</option>
                                    <option v-for="employee in employees" :key="employee.id" :value="employee.id">{{
                                        employee.name + " " + employee.lastname }}</option>
                                </select>
                                <InputError :message="form.errors.employee_id" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="start_date" class="font-medium leading-6 text-gray-900">Fecha de Inicio
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.start_date" id="start_date"
                                    autocomplete="address-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.start_date" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="end_date" class="font-medium leading-6 text-gray-900">Fecha de Culminación
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" :value="form.end_date" v-model="form.end_date" id="end_date"
                                    autocomplete="address-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.end_date" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="reason" class="font-medium leading-6 text-gray-900">Razón</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.reason" id="reason" autocomplete="postal-code"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.reason" />
                            </div>
                        </div>

                    </div>
                    <hr class="mt-5">
                    <h2 class="text-base font-semibold leading-7 text-gray-900 mt-5">Aceptar o rechazar solicitud</h2>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 mt-5">
                        <div class="sm:col-span-2">
                            <InputLabel for="start_date" class="font-medium leading-6 text-gray-900">Fecha de Inicio
                                Aceptada</InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.start_date_accepted" id="start_date"
                                    autocomplete="address-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="end_date" class="font-medium leading-6 text-gray-900">Fecha de Culminación
                                Aceptada</InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.end_date_accepted" id="end_date"
                                    autocomplete="address-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-3" v-if="route().params.vacation !== undefined">
                            <InputLabel for="status" class="font-medium leading-6 text-gray-900">Estado</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.status"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione un estado</option>
                                    <option value="0">Pendiente</option>
                                    <option value="1">Activo</option>
                                </select>
                                <InputError :message="form.errors.status" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                <button type="button" v-if="route().params.vacation !== undefined" @click="destroy"
                    :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-red-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Rechazar</button>
            </div>
        </form>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import Swal from 'sweetalert2';

const props = defineProps({
    vacations: Object,
    employees: Object,
    vacation: Object
});

const form = useForm({
    id: null,
    employee_id: null,
    start_date: '',
    end_date: '',
    start_date_accepted: '',
    end_date_accepted: '',
    reason: '',
    status: '0',
});

if (route().params.vacation) {
    form.id = props.vacation.id;
    form.employee_id = props.vacation.employee_id;
    form.start_date = props.vacation.start_date;
    form.end_date = props.vacation.end_date;
    form.start_date_accepted = props.vacation.start_date_accepted;
    form.end_date_accepted = props.vacation.end_date_accepted;
    form.reason = props.vacation.reason;
    form.status = props.vacation.status;
}

const submit = () => {
    if (route().params.vacation) {
        form.put(route('management.vacation.information.update', form));
    } else {
        form.post(route('management.vacation.information.create'));
    }
};

const destroy = () => {
    // Muestra una alerta de SweetAlert
    Swal.fire({
        title: '¿Estás seguro?',
        text: '¡No podrás revertir esto!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sí, eliminarlo',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Realiza la eliminación
            router.delete(`/management_vacation/information_additional/${form.id}/delete`)
                .then(() => {
                    // Si la eliminación es exitosa, muestra una alerta de éxito
                    Swal.fire('¡Eliminado!', 'El registro ha sido eliminado.', 'success');
                })
                .catch((error) => {
                    // Si hay un error en la eliminación, muestra una alerta de error
                    console.error('Error deleting record:', error);
                    Swal.fire('Error', 'Hubo un problema al intentar eliminar el registro.', 'error');
                });
        }
    });
};

</script>

