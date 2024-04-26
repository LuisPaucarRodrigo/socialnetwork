<template>
    <Head title="Registro de programas" />
    <AuthenticatedLayout :redirectRoute="'management.employees.formation_development.formation_programs'">
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
                            <InputLabel for="first-name">Nombre</InputLabel>
                            <div class="mt-2">
                                <TextInput required type="text" v-model="form.name" id="first-name" autocomplete="given-name" />
                                <InputError :message="form.errors.name" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="description">Descripción
                            </InputLabel>
                            <div class="mt-2">
                                <textarea required v-model="form.description" id="description"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                <InputError :message="form.errors.description" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="trainings">Capacitaciones
                            </InputLabel>
                            <div class="mt-2">
                                <select required multiple v-model="form.trainings" id="trainings" row="7"
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
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">Guardar</PrimaryButton>
            </div>
        </form>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="programa de formacion" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, useForm, router } from '@inertiajs/vue3'
import { ref } from 'vue';

const showModal = ref(false);

const { trainings } = defineProps({
    trainings: Object
})

const initialState = {
    name: '',
    description: '',
    trainings: []
}

const form = useForm({ ...initialState });

const submit = () => {
    form.post(route('management.employees.formation_development.formation_programs.store'), {
        onSuccess: () => {
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('management.employees.formation_development.formation_programs'))
            }, 2000);
        },
    })
}

</script>
