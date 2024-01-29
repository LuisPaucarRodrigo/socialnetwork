<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout>
        <template #header>
            Asignación de Recursos
        </template>
        <div class=" lg:w-1/2 p-3 rounded-lg shadow">

            <div class="sm:col-span-3">
                <div class="flex gap-2">
                    <h3 class="text-lg leading-6 text-gray-900">Añadir Recursos
                    </h3>
                    <button @click="showToAddEmployee" type="button">
                        <PlusCircleIcon class="text-lg text-indigo-800 h-7 w-7 hover:text-purple-400" />
                    </button>
                </div>

                <div class="mt-7">
                    <div v-for="(resource, index) in project.resources" :key="index"
                        class="grid grid-cols-8 items-center my-3">
                        <p class="text-md col-span-7 line-clamp-2">{{ resource.unique_identification }} - {{ resource.description
                        }} / Cantidad: {{ resource.pivot.quantity }}</p>
                        <button type="button" @click="delete_resource(resource.pivot.id)"
                            class="col-span-1 flex justify-end">
                            <TrashIcon class="text-red-500 h-5 w-5" />
                        </button>
                        <div class="border-b col-span-8 border-gray-900/10"></div>
                    </div>
                </div>
            </div>

            <Modal :show="showModal">
                <form class="p-6" @submit.prevent="submit">
                    <h2 class="text-lg font-medium text-gray-900">
                        Solo recursos disponibles
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                        <div class="sm:col-span-3">
                            <InputLabel for="resource_id" class="font-medium leading-6 text-gray-900">Recuso
                            </InputLabel>
                            <div class="mt-2">
                                <select required id="resource_id" v-model="form.resource_id"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione uno</option>
                                    <option v-for="item in resources" :key="item.id" :value="item.id">{{
                                        item.unique_identification }} - {{ item.description }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="quantity" class="font-medium leading-6 text-gray-900">Cantidad</InputLabel>
                            <div class="mt-2">
                                <TextInput id="quantity" type="number" min="1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.quantity" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="observation" class="font-medium leading-6 text-gray-900">Observaciones
                            </InputLabel>
                            <div class="mt-2">
                                <textarea id="observation" type="text"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.observation" />
                            </div>
                        </div>

                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <button
                            class="inline-flex items-center p-2 rounded-md font-semibold bg-red-500 text-white hover:bg-red-400"
                            type="button" @click="closeModal"> Cerrar </button>
                        <button
                            class="inline-flex items-center p-2 rounded-md font-semibold bg-indigo-500 text-white hover:bg-indigo-400"
                            type="submit"> Agregar </button>
                    </div>
                </form>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { PlusCircleIcon, TrashIcon } from '@heroicons/vue/24/outline';

const showModal = ref(false);

const { project, resources } = defineProps({
    project: Object,
    resources: Object
})

const initialState = {
    project_id: project.id,
    resource_id: '',
    quantity: '',
    observation: '',
}

const form = useForm(
    { ...initialState }
)

const submit = () => {
    form.post(route('projectmanagement.resources.store'), {
        onSuccess: () => {
            form.reset();
            alert('Recurso agregado')
        }
    })
}

const showToAddEmployee = () => {
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
};

const delete_resource = (resource_id) => {
    router.delete(route('projectmanagement.resources.delete', { resource_id }), {
        onSuccess: () => {
            alert('Recurso removido del proyecto')
        },
        onError: () => {
            alert('SERVER ERROR')
        },
    })
}

console.log(resources)
</script>