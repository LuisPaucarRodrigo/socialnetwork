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
                <label for="project" class="block text-sm font-medium text-gray-700">Descripcion</label>
                <input type="text" id="description" v-model="form.description"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="form.errors.description" />
            </div>
            <div class="sm:col-span-2">
                <label for="project" class="block text-sm font-medium text-gray-700">Cantidad</label>
                <input type="number" id="model" v-model="form.quantity"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="form.errors.quantity" />
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

            <div class="sm:col-span-2">
                <label for="project" class="block text-sm font-medium text-gray-700">Precio Unitario</label>
                <input type="number" id="price" v-model="form.price"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="form.errors.price" />
            </div>

        </div>
        <div class="mt-6 flex items-center justify-end gap-x-6">
            <button @click="cancel()"
                class="bg-red-500 text-white py-2 px-4 rounded-md hover:bg-red-600 focus:outline-none focus:ring focus:border-red-300">
                Cancelar
            </button>
            <button v-if="component_or_material" @click="updatedForm"
                class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                Guardar
            </button>
            <button v-else @click="submitForm"
                class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                Crear
            </button>
        </div>
        <ConfirmCreateModal :confirmingcreation="showCreateModal" itemType="Componente o Material" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import { ref } from 'vue';

const showCreateModal = ref(false);
const showUpdateModal = ref(false);

const props = defineProps({
    title: String,
    component_or_material: Object,
})
const form = useForm({
    name: '',
    description: '',
    quantity: '',
    adquisition_date: '',
    state: '',
    price: '',
});
if (props.component_or_material) {
    form.name = props.component_or_material.name;
    form.description = props.component_or_material.description;
    form.quantity = props.component_or_material.quantity;
    form.state = props.component_or_material.state;
    form.adquisition_date = props.component_or_material.adquisition_date;
    form.price = props.component_or_material.price;
}

const submitForm = () => {
    form.post(route('inventory.ComponentsAndMaterials.create'), {
        onSuccess: () => {
            showCreateModal.value = true
            setTimeout(() => {
                showCreateModal.value = false
                router.visit(route('inventory.ComponentsAndMaterials.index'));
            },2000)
        },
    })
}
const updatedForm = (CmId) => {
    form.put(route('inventory.ComponentsAndMaterials.update', { CmId: props.component_or_material.id }))
}
const cancel = () => {
    router.get(route('inventory.ComponentsAndMaterials.index'))
}

// const openModal = (modal) => {
//     if (modal === 1) {
//         showCreateModal.value = true;
//     } else {
//         showUpdateModal.value = true;
//     }
// }
// const closeModal = (modal) => {
//     if (modal === 1) {
//         showCreateModal.value = false;
//     } else {
//         showUpdateModal.value = false;
//     }
// }

</script>