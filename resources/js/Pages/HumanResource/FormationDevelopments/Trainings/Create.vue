<template>
    <Head title="Registrar Capacitaciones" />

    <AuthenticatedLayout>
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
        <!-- <Modal :show="showModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ titleModal }}
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    {{ content }}
                </p>
                <div class="mt-6 flex justify-end">
                    <button
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-red-500 text-white hover:bg-red-400 mr-2"
                        type="button" @click="closeModal"> Cancelar
                    </button>
                    <button v-if="!training" @click="create()"
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-indigo-500 text-white hover:bg-indigo-400"
                        type="button"> {{ textButton }}
                    </button>
                    <button v-else @click="update(training.id)"
                        class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                        {{ textButton }}
                    </button>
                </div>
            </div>
        </Modal> -->
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
const titleModal = props.training ? "Actualizacion":"Registro";
// const content = ref(null);
// const textButton = ref(null);

// const openModalCreate = () => {
//     titleModal.value = 'Creacion de una nueva Capacitacion';
//     content.value = '¿Desea continuar con la creacion de una nueva Capacitacion?';
//     textButton.value = 'Continuar';
//     showModal.value = true;
// }

// const openModalSave = () => {
//     titleModal.value = 'Modificar la Capacitacion';
//     content.value = '¿Desea guardar los cambios realizados a esta Capacitacion?';
//     textButton.value = 'Guardar';
//     showModal.value = true;
// }

// const closeModal = () => {
//     showModal.value = false;
// }

const update = (training_id) => {
    form.post(route('management.employees.formation_development.trainings.store', { id: training_id }))
}

const create = () => {
    form.post(route('management.employees.formation_development.trainings.store'),{
        onSuccess: () => {
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('management.employees.formation_development.trainings'))
            },2000);
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
