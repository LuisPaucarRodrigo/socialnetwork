<template>
    <Head title="Inventario" />
    <AuthenticatedLayout>
        <template #header>
            {{ title }}
        </template>

        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-3">
                <label for="project" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" id="name" v-model="form.name"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="form.errors.name" />
            </div>
            <div class="sm:col-span-3">
                <label for="project" class="block text-sm font-medium text-gray-700">Modelo</label>
                <input type="text" id="model" v-model="form.model"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="form.errors.model" />
            </div>
            <div class="sm:col-span-2">
                <label for="project" class="block text-sm font-medium text-gray-700">Numero de Serie</label>
                <input :disabled="!!network_equipment" type="text" id="model" v-model="form.serie_number"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="form.errors.serie_number" />
            </div>

            <div class="sm:col-span-2">
                <label for="startDate" class="block text-sm font-medium text-gray-700">Fecha de Adquisicion</label>
                <input type="date" id="adquisition_date" v-model="form.adquisition_date"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="form.errors.adquisition_date" />
            </div>
            <div class="sm:col-span-2">
                <label for="state" class="block text-sm font-medium text-gray-700">Estado</label>
                <div class="flex">
                    <select v-model="form.state"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <option disabled value="">Estado del Equipo</option>
                        <option value="Disponible">Disponible</option>
                        <option value="Mantenimiento">En Mantenimiento</option>
                        <option value="Ocupado">Ocupado</option>
                    </select>
                </div>
                <InputError :message="form.errors.state" />
            </div>
            <div class="sm:col-span-3">
                <label for="state" class="block text-sm font-medium text-gray-700">Proveedor</label>
                <div class="flex">
                    <select v-model="form.supplier"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <option disabled value="">Seleccione un Proveedor</option>
                        <option v-for="item in providers" :value="item.company_name">
                            {{ item.company_name }}
                        </option>
                    </select>
                </div>
                <InputError :message="form.errors.supplier" />
            </div>
            <div class="sm:col-span-3">
                <label for="project" class="block text-sm font-medium text-gray-700">Precio</label>
                <input type="number" id="price" v-model="form.price"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="form.errors.price" />
            </div>

        </div>
        <!-- Botón de enviar -->
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button @click="cancel()"
                class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-red-300">
                Cancelar
            </button>
            <button v-if="network_equipment" @click="openModal()"
                class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                Guardar
            </button>

            <button v-else @click="openModal(1)"
                class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                Crear
            </button>
        </div>
        <ConfirmCreateModal :confirmingcreation="showCreateModal" itemType="Equipo de Red" :nameText="'del Equipo de Red'"
            :createFunction="submitForm" @closeModal="closeModal(1)" />
        <ConfirmCreateModal :confirmingcreation="showUpdateModal" itemType="Equipo de Red" :nameText="'del Equipo de Red'"
            :createFunction="updatedForm" @closeModal="closeModal" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import { ref } from 'vue';

const props = defineProps({
    providers: Object,
    title: String,
    network_equipment: Object,
})

const form = useForm({
    name: '',
    model: '',
    serie_number: '',
    adquisition_date: '',
    state: '',
    supplier: '',
    price: '',
});
if (props.network_equipment) {
    form.name = props.network_equipment.name;
    form.model = props.network_equipment.model;
    form.serie_number = props.network_equipment.serie_number;
    form.state = props.network_equipment.state;
    form.supplier = props.network_equipment.supplier;
    form.adquisition_date = props.network_equipment.adquisition_date;
    form.price = props.network_equipment.price;
}

const submitForm = () => {
    form.post(route('inventory.NetworkEquipment.create'), {
        onError: () => {
            closeModal(1)
        }
    })
}
const updatedForm = () => {
    form.put(route('inventory.NetworkEquipment.update', { NeId: props.network_equipment.id }),{
        onError: () => {
            closeModal()
        }
    })
}
const cancel = () => {
    router.get(route('inventory.NetworkEquipment.index'))
}
const showCreateModal = ref(false);
const showUpdateModal = ref(false);

const openModal = (modal) => {
    if (modal === 1){
        showCreateModal.value = true;
    }else{
        showUpdateModal.value = true;
    }
}
const closeModal = (modal) => {
    if (modal === 1){
        showCreateModal.value = false;
    }else{
        showUpdateModal.value = false;
    }
}
</script>