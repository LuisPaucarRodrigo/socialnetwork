<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout>
        <template #header>
            {{ title }}
        </template>
        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label for="description" class="block text-sm font-medium text-gray-700">Descripcion</label>
                <input type="text" id="description" v-model="newResource.description"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="newResource.errors.description" />
            </div>

            <div class="sm:col-span-3">
                <label for="type" class="block text-sm font-medium text-gray-700">Categoria</label>
                <div class="flex">
                    <select v-model="newResource.type" id="type"
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
            <div class="sm:col-span-2">
                <label for="serial_number" class="block text-sm font-medium text-gray-700">Numero de Serie</label>
                <input type="text" id="serial_number" v-model="newResource.serial_number"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="newResource.errors.serial_number" />
            </div>
            <!--Local -->
            <div class="sm:col-span-2">
                <label for="current_location" class="block text-sm font-medium text-gray-700">Ubicacion</label>
                <div class="flex">
                    <select v-model="newResource.current_location" id="current_location"
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
                <label for="adquisition_date" class="block text-sm font-medium text-gray-700">Fecha de Recepcion</label>
                <input type="date" id="adquisition_date" v-model="newResource.adquisition_date"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="newResource.errors.adquisition_date" />
            </div>
            <div class="sm:col-span-2">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Cantidad</label>
                <input type="number" id="quantity" v-model="newResource.quantity"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="newResource.errors.quantity" />
            </div>
            <div class="sm:col-span-2 sm:col-start-1">
                <label for="unit_price" class="block text-sm font-medium text-gray-700">Precio Unitario Nuevo</label>
                <input type="number" maxlength="4" id="unit_price" v-model="newResource.unit_price" :disabled="resource"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="newResource.errors.unit_price" />
            </div>
            <div v-if="resource" class="sm:col-span-2">
                <label for="depreciation" class="block text-sm font-medium text-gray-700">Depreciacion Anual(%)</label>
                <input type="number" id="depreciation" v-model="newResource.depreciation" @input="depreciation($event)"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="newResource.errors.depreciation" />
            </div>
            <div v-if="resource" class="sm:col-span-2">
                <label for="unit_price_depreciation" class="block text-sm font-medium text-gray-700">Precio
                    Depreciado</label>
                <input type="number" disabled id="unit_price_depreciation" v-model="newResource.unit_price_depreciation"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="newResource.errors.unit_price_depreciation" />
            </div>
            <!-- Descricion (text-Area) -->
            <div v-if="!resource" class="sm:col-span-2">
                <label for="rent" class="block text-sm font-medium text-gray-700">Si tiene un precio especial para
                    alquiler</label>
                <div class="flex">
                    <select id="rent" @change="rent_active($event)"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <option disabled value="">Seleccione</option>
                        <option value="Normal">Normal</option>
                        <option value="Renta">Renta</option>
                    </select>
                </div>
            </div>
            <div v-if="rent" class="sm:col-span-2">
                <label for="price_rent" class="block text-sm font-medium text-gray-700">Precio
                    Depreciado</label>
                <input type="number" id="price_rent" v-model="newResource.price_rent"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="newResource.errors.price_rent" />
            </div>
            <div class="sm:col-span-5">
                <label for="observations" class="block text-sm font-medium text-gray-700">Observaciones</label>
                <textarea type="text" id="observations" v-model="newResource.observations"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300" />
                <InputError :message="newResource.errors.observations" />
            </div>
        </div>

        <!-- Botón de enviar -->
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button @click="cancel()"
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
        <ConfirmCreateModal :confirmingcreation="showmodal" itemType="activo" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import InputError from '@/Components/InputError.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';

const showmodal = ref(false);
const rent = ref(false);

const props = defineProps({
    title: String,
    resource: Object
})

const newResource = useForm({
    description: '',
    type: '',
    serial_number: '',
    quantity: '',
    unit_price: '',
    depreciation: '',
    unit_price_depreciation: '',
    price_rent: '',
    observations: '',
    adquisition_date: '',
    current_location: '',
    unique_identification: '',
});

if (props.resource) {
    newResource.description = props.resource.description;
    newResource.type = props.resource.type;
    newResource.serial_number = props.resource.serial_number;
    newResource.unique_identification = props.resource.unique_identification;
    newResource.quantity = props.resource.quantity;
    newResource.unit_price = props.resource.unit_price;
    newResource.depreciation = props.resource.depreciation;
    newResource.current_location = props.resource.current_location;
    newResource.adquisition_date = props.resource.adquisition_date;
    newResource.observations = props.resource.observations;
}

const depreciation = (event) => {
    newResource.unit_price_depreciation = newResource.unit_price - ((event.target.value * 0.01) * newResource.unit_price);
}

const add_resource = () => {
    newResource.post(route('resource.create'), {
        onSuccess: () => {
            showmodal.value = true
            setTimeout(() => {
                showmodal.value = false
                router.visit(route('resources.index'))
            }, 2000)
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

const cancel = () => {
    router.get(route('resources.index'))
};

const rent_active = (event) => {
    event.target.value == 'Renta' ? rent.value = true : rent.value = false;
}

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