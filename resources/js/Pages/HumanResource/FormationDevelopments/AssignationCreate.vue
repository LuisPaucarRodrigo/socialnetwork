<template>
    <Head title="Asignación de programas" />
    <AuthenticatedLayout>
        <template #header>
            Asignación de Capacitaciones
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
                            <InputLabel for="first-name" class="font-medium leading-6 text-gray-900">Empleados</InputLabel>
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
                            <InputLabel for="description" class="font-medium leading-6 text-gray-900">Programas
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
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-between gap-x-6">
                <a :href="route('management.employees.formation_development')"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Atras
                </a>
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="asignacion de programa" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue'
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
