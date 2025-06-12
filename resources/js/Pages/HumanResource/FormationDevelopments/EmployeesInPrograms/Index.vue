<template>

    <Head title="Empleados en programas" />

    <AuthenticatedLayout :redirectRoute="'management.employees.formation_development'">
        <template #header>
            Personal en programas de formación
        </template>
        <div class="min-w-full">
            <div class="overflow-x-auto">
                <table class="w-full rounded-lg shadow">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                N°
                            </th>
                            <th
                                class="sticky left-0 z-10 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre y apellidos
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Teléfono 1
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Telefono 2
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Email
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                <div class="w-[600px] grid grid-cols-3 gap-3">
                                    <p class="text-left">Programa</p>
                                    <p class="text-center">Inicio-Fin</p>
                                    <p class="text-center">Detalles</p>
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, i) in employees.data" :key="item.id"
                            class="text-gray-700 hover:bg-gray-200 bg-white">
                            <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ i + 1 }}</p>
                            </td>
                            <td class="sticky left-0 z-10 border-b border-gray-200 px-5 py-5 text-sm bg-white">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.name }} {{ item.lastname }}</p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.phone1 }}</p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.phone2 }}</p>
                            </td>
                            <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.email }}</p>
                            </td>
                            <td class="border-b border-gray-200 text-sm">
                                <div v-for="ap in item.assignated_programs"
                                    class=" grid grid-cols-3 gap-3 items-center hover:bg-gray-300 py-2 px-5" :class="[
                                        {
                                            'border-l-8': true,
                                            'border-yellow-500': ap.urgent_state === 'medium',
                                            'border-red-500': ap.urgent_state === 'high',
                                        }
                                    ]">
                                    <p class="text-left">{{ ap.formation_program.name }}</p>
                                    <p class="text-center whitespace-nowrap">{{ formattedDate(ap.start_date) }} -
                                        {{ formattedDate(ap.end_date) }}
                                    </p>
                                    <div v-if="ap.state === null"
                                        class="text-center flex gap-3 justify-center items-center ">
                                        <button @click="UpdateToCompleted(ap.id)"
                                            class="flex items-center text-blue-500 rounded-xl hover:bg-green-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                        <button @click="openNotCompletedModal(ap.id)" type="button"
                                            class="rounded-xl whitespace-no-wrap text-center text-sm text-red-900 hover:bg-red-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                    </div>
                                    <div v-else class="text-center flex gap-2 justify-center items-center">
                                        <p :class="`${ap.state ? 'text-green-500' : 'text-red-500'}`">
                                            {{ ap.state ? 'Completo' : 'Incompleto' }}
                                        </p>
                                        <button type="button" @click="openReasonModal(ap.reason)">
                                            <ShowIcon v-if="ap.state == false" />
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="employees.links" />
            </div>

            <Modal :show="showNotCompletedModal">
                <!-- Contenido del modal cuando no hay empleados -->
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Incumplimiento
                    </h2>
                    <InputLabel for="reason" class="font-medium leading-6 mt-3 text-gray-900">
                        Ingrese la razón o motivo por el cual el trabajador no cumplió el programa
                    </InputLabel>
                    <div class="mt-3">
                        <textarea type="text" v-model="form.reason" id="reason"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                </textarea>
                        <InputError :message="form.errors.reason" />
                    </div>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeModal()"> Cancelar
                        </SecondaryButton>
                        <PrimaryButton type="button" @click="UpdateToNotCompleted()"> Guardar
                        </PrimaryButton>
                    </div>
                </div>
            </Modal>

            <Modal :show="showReason" :maxWidth="'md'">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Razón o Motivo
                    </h2>
                    <p for="reason" class="font-medium text-md leading-6 mt-3 text-gray-900">
                        {{ currentReason }}
                    </p>

                    <div class="mt-6 flex justify-end">
                        <PrimaryButton type="button" @click="closeModal2()"> Cerrar
                        </PrimaryButton>
                    </div>
                </div>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { formattedDate } from '@/utils/utils.js';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ShowIcon } from '@/Components/Icons/Index';

const { employees, userPermissions } = defineProps({
    employees: Object,
    userPermissions: Array
})

const showNotCompletedModal = ref(false)
const apNotCompleted = ref(null)
const form = useForm({
    state: null,
    reason: ''
})

const openNotCompletedModal = (id) => {
    apNotCompleted.value = id
    showNotCompletedModal.value = true
}
const closeModal = () => {
    apNotCompleted.value = null
    showNotCompletedModal.value = false
    form.reset()
}
function updateHelper(form, id) {
    form.post(route('management.employees.formation_development.employees_in_programs.update', {
        efp_id: id
    }), {
        onSuccess: () => {
            closeModal()
        }
    })
}
const UpdateToNotCompleted = () => {
    form.state = false
    updateHelper(form, apNotCompleted.value)
}
const UpdateToCompleted = (id) => {
    form.state = true
    updateHelper(form, id)
}

const showReason = ref(false)
const currentReason = ref('');
const openReasonModal = (reason) => {
    showReason.value = true
    currentReason.value = reason
}

const closeModal2 = () => {
    showReason.value = false
    currentReason.value = ''
}


</script>