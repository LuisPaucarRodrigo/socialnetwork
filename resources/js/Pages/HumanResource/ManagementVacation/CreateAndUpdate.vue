<template>
    <Head title="Gestion de Vacaciones y Permisos" />
    <AuthenticatedLayout :redirectRoute="'management.vacation'">
        <template #header>
            Permisos y Vacaciones
        </template>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informacion sobre las vacaciones y permisos
                        del empleado</h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <InputLabel for="employee" class="font-medium leading-6 text-gray-900">Empleado</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.employee_id" id="employee" :disabled="vacation"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione un Empleado</option>
                                    <option v-for="employee in employees" :key="employee.id" :value="employee.id">{{
                                        employee.name + " " + employee.lastname }}</option>
                                </select>
                                <InputError :message="form.errors.employee_id" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="type" class="font-medium leading-6 text-gray-900">Tipo</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.type" id="type" @change="type_vacations_permissions($event)"
                                    :disabled="vacation"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione un Tipo</option>
                                    <option>Vacaciones</option>
                                    <option>Permisos</option>
                                </select>
                                <InputError :message="form.errors.type" />
                            </div>
                        </div>
                        <div v-if="vacations" class="sm:col-span-2">
                            <InputLabel for="start_date" class="font-medium leading-6 text-gray-900">Fecha de Inicio
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.start_date" id="start_date"
                                    autocomplete="address-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.start_date" />
                            </div>
                        </div>
                        <div v-if="vacations" class="sm:col-span-2">
                            <InputLabel for="end_date" class="font-medium leading-6 text-gray-900">Fecha de Culminación
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.end_date" id="end_date" autocomplete="address-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.end_date" />
                            </div>
                        </div>
                        <div v-if="permissions" class="sm:col-span-2">
                            <InputLabel for="start_permissions" class="font-medium leading-6 text-gray-900">Horas de Inicio
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="datetime-local" v-model="form.start_permissions" id="start_permissions"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.start_permissions" />
                            </div>
                        </div>
                        <div v-if="permissions" class="sm:col-span-2">
                            <InputLabel for="end_permissions" class="font-medium leading-6 text-gray-900">Horas de
                                Culminacion
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="datetime-local" v-model="form.end_permissions" id="end_permissions"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.end_permissions" />
                            </div>
                        </div>
                        <div v-if="permissions && !vacation" class="sm:col-span-3 sm:col-start-1">
                            <InputLabel for="doc_permission" class="font-medium leading-6 text-gray-900">Documentacion
                            </InputLabel>
                            <InputFile type="file" v-model="form.doc_permission" id="doc_permission"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            <InputError :message="form.errors.doc_permission" />
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="reason" class="font-medium leading-6 text-gray-900">Razón</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.reason" id="reason" autocomplete="postal-code"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.reason" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{
                        vacation ? 'Actualizar' : 'Crear' }}</button>
            </div>
        </form>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="vacaciones y permisos" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/InputFile.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';

const props = defineProps({
    employees: {
        type: Object,
        required: true
    },
    vacation: {
        type: Object,
        required: false
    },
    userPermissions: Array
});

const vacations = ref(false)
const permissions = ref(false)
const showModal = ref(false);

const type_vacations_permissions = (event) => {
    event.target.value == 'Vacaciones' ? (vacations.value = true, permissions.value = false) : (permissions.value = true, vacations.value = false);
}

const form = useForm({
    employee_id: '',
    type: '',
    start_date: '',
    end_date: '',
    start_permissions: '',
    end_permissions: '',
    doc_permission: '',
    start_date_accepted: '',
    end_date_accepted: '',
    reason: '',
    status: ''
});

if (props.vacation) {
    props.vacation.type == 'Permisos' ? permissions.value = true : vacations.value = true
    form.employee_id = props.vacation.employee.id;
    form.type = props.vacation.type;
    form.start_date = props.vacation.start_date;
    form.end_date = props.vacation.end_date;
    form.start_permissions = props.vacation.start_permissions;
    form.end_permissions = props.vacation.end_permissions;
    form.doc_permission = props.vacation.doc_permission;
    form.start_date_accepted = props.vacation.start_date_accepted;
    form.end_date_accepted = props.vacation.end_date_accepted;
    form.reason = props.vacation.reason;
}

const submit = () => {
    if (props.vacation) {
        form.put(route('management.vacation.information.update', props.vacation.id), form);
    } else {
        form.post(route('management.vacation.information.store'), {
            onSuccess: () => {
                showModal.value = true
                setTimeout(() => {
                    showModal.value = false;
                    router.visit(route('management.vacation'))
                }, 2000);
            },
            onError: () => {
                alert('Ha ocurrido un error. Por favor, inténtelo de nuevo.');
            }
        })
    }
};

</script>

