<template>

    <Head title="Asignación de programas" />
    <AuthenticatedLayout :redirectRoute="'management.employees.formation_development'">
        <template #back>
            <a :href="route('management.employees.formation_development')"
                class="ml-4 text-gray-500 focus:outline-none">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
        </template>
        <template #header>
            Asignación de Programas de Formación
        </template>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-3">
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Asignar capacitaciones</h2>
                    <br>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <InputLabel for="first-name">Empleados</InputLabel>
                            <div class="mt-2">
                                <select required multiple v-model="form.employees"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled>
                                        Selecciona uno o varios
                                    </option>
                                    <option v-for="employee in employees" :key="employee.id" :value="employee.id">
                                        {{ `${employee.name} ${employee.lastname}` }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.employees" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="description">Programas
                            </InputLabel>
                            <div class="mt-2">
                                <select required multiple v-model="form.formation_programs"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled>
                                        Selecciona uno o varios
                                    </option>
                                    <option v-for="formation_program in formation_programs" :key="formation_program.id"
                                        :value="formation_program.id">
                                        {{ formation_program.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.formation_programs" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="start_date">Fecha de Inicio
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput required type="date" v-model="form.start_date" />

                                <InputError :message="form.errors.start_date" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="end_date">Fecha de fin
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput required type="date" v-model="form.end_date" />

                                <InputError :message="form.errors.end_date" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-between gap-x-6">
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">Guardar</PrimaryButton>
            </div>
        </form>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="asignacion de programa" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const showModal = ref(false);
const props = defineProps({
    employees: Object,
    formation_programs: Object
})

const form = useForm({
    employees: [],
    formation_programs: [],
    start_date: '',
    end_date: ''
});

const submit = () => {
    form.post(route('management.employees.formation_development.assignation.store'), {
        onSuccess: () => {
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('management.employees.formation_development'))
            }, 2000);
        }
    })
}
</script>
