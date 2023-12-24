<template>
    <Head title="Registro de programas" />

    <AuthenticatedLayout>
        <template #header>
            Registro de Programas de Formación
        </template>
        <form @submit.prevent="openModal()">
            <div class="space-y-12">
                <!-- Linea de borde abajo -->
                <div class="border-b border-gray-900/10 pb-3">
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Registrar nuevo programa</h2>
                    <br>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <InputLabel for="first-name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                            <div class="mt-2">
                                <TextInput required type="text" v-model="form.name" id="first-name"
                                    autocomplete="given-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.name" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="type" class="font-medium leading-6 text-gray-900">Tipo</InputLabel>
                            <div class="mt-2">
                                <select required id="type" v-model="form.type"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione uno</option>
                                    <option value="taller">taller</option>
                                    <option value="curso">curso</option>
                                    <option value="seminario">seminario</option>
                                    <option value="otros">otros</option>
                                </select>
                                <InputError :message="form.errors.type" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción
                            </InputLabel>
                            <div class="mt-2">
                                <textarea required v-model="form.description" id="description"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                <InputError :message="form.errors.description" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="month_year" class="font-medium leading-6 text-gray-900">Fecha
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.month_year" id="month_year"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.month_year" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="trainings" class="font-medium leading-6 text-gray-900">Capacitaciones
                            </InputLabel>
                            <div class="mt-2">
                                <select required multiple v-model="form.trainings" id="trainings"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>
                                        Select multiple ods
                                        (ctrl+click)
                                    </option>
                                    <option v-for="item in trainings" :key="item.id" :value="item.id">
                                        {{ item.name }}
                                    </option>

                                </select>
                                <InputError :message="form.errors.trainings" />
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="programa de formacion"
            :nameText="'del nuevo programa de formacion'" :createFunction="submit" @closeModal="closeModal" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref, defineProps } from 'vue';

const { trainings } = defineProps({
    trainings: Object
})

const initialState = {
    name: '',
    type: '',
    description: '',
    month_year: '',
    trainings: []
}

const form = useForm({ ...initialState })

const submit = () => {
    form.post(route('management.employees.formation_development.formation_programs.store'), {
        onSuccess: () => {
            form.data = { ...initialState }
        },
    })
}
const showModal = ref(false);

const openModal = () => {
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
}
</script>
