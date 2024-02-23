<template>
    <Head title="Registrar Capacitaciones" />

    <AuthenticatedLayout :redirectRoute="'management.employees.formation_development.trainings'">
        <template #header>
            {{ titleModal }} de capacitaciones
        </template>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <!-- Linea de borde abajo -->
                <div class="border-b border-gray-900/10 pb-3">
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Registrar nueva capacitacion</h2>
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
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="capacitacion" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';

const props = defineProps({
    training: Object
})

const initialState = {
    name: '',
    description: '',
}
const form = useForm({
    ...initialState,
    ...props.training,
});

const showModal = ref(false);
const titleModal = props.training ? "Actualizacion" : "Registro";

const update = (training_id) => {
    form.post(route('management.employees.formation_development.trainings.store', { id: training_id }), {
        onSuccess: () => {
            router.visit(route('management.employees.formation_development.trainings'))
        }
    })
}

const create = () => {
    form.post(route('management.employees.formation_development.trainings.store'), {
        onSuccess: () => {
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('management.employees.formation_development.trainings'))
            }, 2000);
        }
    })
}

const submit = () => {
    if (props.training) {
        update(props.training.id)
    } else {
        create()
    }
}

</script>
