<template>
    <Head title="Registro" />

    <AuthenticatedLayout>
        <template #header>
            Registro de Programas de Formación
        </template>
        <form @submit.prevent="submit">
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
                    </div>
                </div>

            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ModalImage from '@/Layouts/ModalImage.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue'
import { Head, useForm } from '@inertiajs/vue3';

// const props = defineProps({
//     users: Object
// })

const form = useForm({
    name: '',
    type: '',
    description: '',

})


const submit = () => {
    form.post(route('management.employees.formation_development.store'))
}
</script>
