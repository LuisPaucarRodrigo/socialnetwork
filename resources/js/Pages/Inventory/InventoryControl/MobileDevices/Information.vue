<template>
    <Head title="Inventario" />
    <AuthenticatedLayout>
        <template #header>
            {{ title }}
        </template>

        <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
            <div class="sm:col-span-2">
                <label for="project" class="block text-sm font-medium text-gray-700">Marca</label>
                <input type="text" id="phone_brand" v-model="form.phone_brand"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="form.errors.phone_brand" />
            </div>
            <div class="sm:col-span-2">
                <label for="project" class="block text-sm font-medium text-gray-700">Modelo</label>
                <input type="text" id="model" v-model="form.model"
                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                <InputError :message="form.errors.model" />
            </div>
            <div class="sm:col-span-2">
                <label for="project" class="block text-sm font-medium text-gray-700">Numero de Serie</label>
                <input :disabled="!!mobile_device" type="text" id="model" v-model="form.serie_number"
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
                        <option disabled value="">Estado del Dispositivo</option>
                        <option value="Disponible">Disponible</option>
                        <option value="Mantenimiento">En Mantenimiento</option>
                        <option value="Ocupado">Ocupado</option>
                    </select>
                </div>
                <InputError :message="form.errors.state" />
            </div>

            <div class="sm:col-span-2">
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
            <button v-if="mobile_device" @click="updatedForm"
                class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                Guardar
            </button>
            <button v-else @click="submitForm"
                class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-600 focus:outline-none focus:ring focus:border-blue-300">
                Crear
            </button>
        </div>
        <ConfirmCreateModal :confirmingcreation="showCreateModal" itemType="movil" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import InputError from '@/Components/InputError.vue';
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const showCreateModal = ref(false);

const props = defineProps({
    title: String,
    mobile_device: Object,
})

const form = useForm({
    phone_brand: '',
    model: '',
    serie_number: '',
    adquisition_date: '',
    state: '',
    price: '',
});
if (props.mobile_device) {
    form.phone_brand = props.mobile_device.phone_brand;
    form.model = props.mobile_device.model;
    form.serie_number = props.mobile_device.serie_number;
    form.state = props.mobile_device.state;
    form.adquisition_date = props.mobile_device.adquisition_date;
    form.price = props.mobile_device.price;
}

const submitForm = () => {
    form.post(route('inventory.MobileDevices.create'),{
        onSuccess: () => {
            showCreateModal.value = true
            setTimeout(() => {
                showCreateModal.value = false
                router.visit(route('inventory.MobileDevices.index'))
            },2000)
        }
    })
}
const updatedForm = () => {
    form.put(route('inventory.MobileDevices.update', { MdId: props.mobile_device.id }))
}
const cancel = () => {
    router.get(route('inventory.MobileDevices.index'))
}

</script>