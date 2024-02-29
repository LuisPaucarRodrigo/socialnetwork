<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="'resources.index'">
        <template #header>
            {{ title }}
        </template>
        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <div class="flex items-center gap-2">
                    <label for="resource_description_id" class="block text-sm font-medium text-gray-700">Descripcion</label>
                    <button v-if="auth.user.role_id === 1"  @click="()=>{
                        showModal = true
                        section = 1
                    }">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-indigo-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                    </button>
                </div>

                <select v-model="newResource.resource_description_id" id="type"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                    <option disabled value="">Seleccione</option>
                    <option v-for="item in descriptions" :key="item.id" :value="item.id">{{ item.name }}</option>
                </select>
                <InputError :message="newResource.errors.resource_description_id" />
            </div>

            <div class="sm:col-span-3">
                <div class="flex items-center gap-2">
                    <label for="resource_category_id" class="block text-sm font-medium text-gray-700">Categoria</label>
                    <button v-if="auth.user.role_id === 1"  @click="()=>{
                        showModal = true
                        section = 2
                    }">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-indigo-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>

                    </button>
                </div>
                <div class="flex">
                    <select v-model="newResource.resource_category_id" id="resource_category_id"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <option disabled value="">Seleccione</option>
                        <option v-for="item in types" :key="item.id" :value="item.id">{{ item.name }}</option>
                    </select>
                </div>
                <InputError :message="newResource.errors.resource_category_id" />
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
            <div v-if="!resource" class="sm:col-span-2 sm:col-start-1">
                <label for="conditional_rent" class="block text-sm font-medium text-gray-700">Si tiene un precio especial
                    para
                    alquiler</label>
                <input type="checkbox" id="conditional_rent" v-model="newResource.conditional_rent"
                    class="mt-1 block py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
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
            <button @click="add_resource()"
                class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                {{ resource ? "Guardar" : "Crear" }}
            </button>
        </div>
        <ConfirmCreateModal :confirmingcreation="showmodal" itemType="activo" />

        <!-- ADD DESCRIPTION -->
        <Modal :show="showModal">
            <form class="p-6" @submit.prevent="submit">
                <h2 class="text-lg font-medium text-gray-900">
                    Nueva {{ section === 1 ? 'descripción' : section===2&&'categoría' }}
                </h2>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">

                    <div class="sm:col-span-6">
                        <InputLabel for="name" class="font-medium leading-6 text-gray-900">Descripción</InputLabel>
                        <div class="mt-2">
                            <TextInput id="name" :to-uppercase="true"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                v-model="form.name" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="closeAddModal"> Cerrar </SecondaryButton>
                    <PrimaryButton type="submit"> Agregar </PrimaryButton>
                </div>
            </form>
        </Modal>
        <SuccessOperationModal :confirming="addDescriptionSuccess" title="" message=""/>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref, defineProps } from 'vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';

const showmodal = ref(false);

const props = defineProps({
    title: String,
    resource: Object,
    descriptions: Object,
    types: Object,
    auth:Object
})

const newResource = useForm({
    resource_description_id: '',
    resource_category_id: '',
    serial_number: '',
    quantity: '',
    unit_price: '',
    depreciation: '',
    unit_price_depreciation: '',
    conditional_rent: false,
    observations: '',
    adquisition_date: '',
    current_location: '',
    unique_identification: '',
});

if (props.resource) {
    newResource.resource_description_id = props.resource.resource_description_id;
    newResource.resource_category_id = props.resource.resource_category_id;
    newResource.serial_number = props.resource.serial_number;
    newResource.unique_identification = props.resource.unique_identification;
    newResource.quantity = props.resource.quantity;
    newResource.unit_price = props.resource.unit_price;
    newResource.depreciation = props.resource.depreciation;
    newResource.current_location = props.resource.current_location;
    newResource.adquisition_date = props.resource.adquisition_date;
    newResource.conditional_rent = props.resource.conditional_rent;
    newResource.observations = props.resource.observations;
}

const depreciation = (event) => {
    newResource.unit_price_depreciation = newResource.unit_price - ((event.target.value * 0.01) * newResource.unit_price);
}

const add_resource = () => {
    if (props.resource) {
        newResource.put(route('resource.update', { resourceId: props.resource.id }), {
            onError: () => {
                closeModal()
            }
        })
    } else {
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
    }
};

const cancel = () => {
    router.get(route('resources.index'))
};

const closeModal = () => {
    showmodal.value = false;
}



//Resource  modals
const section = ref(null)
const showModal = ref(false)
const addDescriptionSuccess = ref(false)

const closeAddModal = () => {
    showModal.value = false
}

const form = useForm({
    name:''
})

const submit = () => {
    let url
    if (section.value === 1) url = route('resource_description.store')
    if (section.value === 2) url = route('resource_category.store')
    form.post(url,{
        onSuccess:() => {
            closeAddModal()
            addDescriptionSuccess.value = true
            setTimeout(()=>{
                addDescriptionSuccess.value = false
            }, 600)
            form.reset()
        }
    })
}


</script>