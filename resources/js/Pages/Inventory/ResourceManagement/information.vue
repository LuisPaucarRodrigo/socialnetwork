<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout>
        <template #header>
            {{ title }}
        </template>
        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label for="project" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" id="name" v-model="newResource.name"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="newResource.errors.name" />
            </div>

            <div class="sm:col-span-3">
                <label for="task" class="block text-sm font-medium text-gray-700">Categoria</label>
                <div class="flex">
                    <select v-model="newResource.type"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <option disabled value="">Seleccione una categoria</option>
                        <option value="Hardware">Hardware</option>
                        <option value="Software">Software</option>
                        <option value="Vehiculo">Vehiculo</option>
                        <option value="Equipo de Oficina">Equipo de Oficina</option>
                        <option value="Equipo de Campo">Equipo de Campo</option>
                        <option value="Otros">Otros</option>
                    </select>
                </div>
                <InputError :message="newResource.errors.type" />
            </div>
            <div class="sm:col-span-2">
                <label for="unique_identification" class="block text-sm font-medium text-gray-700">Codigo</label>
                <input :disabled="!!resource" type="text" id="unique_identification"
                    v-model="newResource.unique_identification"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="newResource.errors.unique_identification" />
            </div>
            <div class="sm:col-span-1">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Cantidad</label>
                <input type="number" id="task" v-model="newResource.quantity"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="newResource.errors.quantity" />
            </div>
            <!--Local -->
            <div class="sm:col-span-3">
                <label for="task" class="block text-sm font-medium text-gray-700">Ubicacion</label>
                <div class="flex">
                    <select v-model="newResource.current_location"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <option disabled value="">Seleccione un Almacen</option>
                        <option value="Conproco">Conproco</option>
                        <option value="Social Network">Social Network</option>
                    </select>
                </div>
                <InputError :message="newResource.errors.current_location" />
            </div>

            <!-- Fecha de Inicio (date input) -->
            <div class="sm:col-span-2">
                <label for="startDate" class="block text-sm font-medium text-gray-700">Fecha de Adquisicion</label>
                <input type="date" id="adquisition_date" v-model="newResource.adquisition_date"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="newResource.errors.adquisition_date" />
            </div>
            <!-- Descricion (text-Area) -->
            <div class="sm:col-span-5">
                <label for="startDate" class="block text-sm font-medium text-gray-700">Descripcion</label>
                <textarea type="text" id="start_date" v-model="newResource.description"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300" />
                <InputError :message="newResource.errors.description" />
            </div>
        </div>

        <!-- Botón de enviar -->
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button  @click="cancel()"
                class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-red-300">
                Cancelar
            </button>
            <button v-if="resource" @click="update_resource(resource.id)"
                class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                Guardar
            </button>
            <button v-else @click="add_resource()"
                class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                Crear
            </button>
        </div>
        <!-- <Modal :show="showmodal" :maxWidth="'md'">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    {{ titleModal }}
                </h2>
                <p class="mt-2 text-sm text-gray-500">
                    {{ newResource.name }} {{ content }}
                </p>
                <div class="mt-6 flex justify-end">
                    <button
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-red-500 text-white hover:bg-red-400 mr-2"
                        type="button" @click="closeModal"> Cancelar
                    </button>
                    <button v-if="textButton === 'Crear'"
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-indigo-500 text-white hover:bg-indigo-400"
                        type="button" @click="add_resource()"> Crear
                    </button>
                    <button v-else @click="update_resource(resource.id)"
                        class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                        Guardar
                    </button>
                </div>
            </div>
        </Modal> -->
        <ConfirmCreateModal :confirmingcreation="showmodal" itemType="recurso" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import InputError from '@/Components/InputError.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';

const showmodal = ref(false);

const props = defineProps({
    title: String,
    resource: Object
})

const newResource = useForm({
    name: '',
    type: '',
    quantity: '',
    description: '',
    adquisition_date: '',
    current_location: '',
    unique_identification: '',
});

if (props.resource) {
    newResource.name = props.resource.name;
    newResource.type = props.resource.type;
    newResource.unique_identification = props.resource.unique_identification;
    newResource.quantity = props.resource.quantity;
    newResource.current_location = props.resource.current_location;
    newResource.adquisition_date = props.resource.adquisition_date;
    newResource.description = props.resource.description;
}

const add_resource = () => {
    newResource.post(route('resource.create'), {
        onSuccess: () => {
            showmodal.value = true
            setTimeout(()=> {
                showmodal.value = false
                router.visit(route('resources.index'))
            },2000)
        },
        onError: () => {
            closeModal()
        }
    })
};

const update_resource = (resourceId) => {
    newResource.put(route('resource.update', { resourceId: resourceId }), {
        onError: () => {
            closeModal()
        }
    })
};

const cancel =()=>{
    router.get(route('resources.index'))
};

// const titleModal = ref(null);
// const content = ref(null);
// const textButton = ref(null);

// const openModalAdded = () => {
//     titleModal.value = 'Agregar Nuevo Recurso';
//     content.value = 'se agregara a la lista de recursos';
//     textButton.value = 'Crear'
//     showmodal.value = true;
// }
// const openModalSaved = () => {
//     titleModal.value = 'Editar Recurso';
//     content.value = '¿Esta seguro de guardar los cambios?';
//     textButton.value = 'Guardar'
//     showmodal.value = true;
// }
const closeModal = () => {
    showmodal.value = false;
}

</script>