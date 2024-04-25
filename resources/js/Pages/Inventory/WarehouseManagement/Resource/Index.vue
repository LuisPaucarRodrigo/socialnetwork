<template>

    <Head title="Productos" />
    <AuthenticatedLayout :redirectRoute="'warehouses.warehouses'">
        <template #header>
            Activos
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <div class="flex justify-between items-center gap-4">
                <Link :href="route('warehouses.resource.create')"
                    class="inline-flex items-center px-4 py-2 min-w-[115px] border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                + Agregar
                </Link>
                <PrimaryButton @click="reentry" type="button">
                    {{ boolean == false ? "Sin Revisar" : "Revisados" }}
                </PrimaryButton>
            </div>
            <br>
            <div class="min-w-full overflow-x-auto rounded-lg shadow">
                <table class="w-full table-auto">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Código
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Unidad
                            </th>
                            <th v-if="resources && !boolean"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Numero de Serie
                            </th>
                            <th v-if="resources && !boolean"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Precio de Entrada
                            </th>
                            <th v-if="resources && !boolean"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Condición
                            </th>
                            <th v-if="boolean"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in resources.data" :key="item.id"
                            class="text-gray-700 border-b">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.purchase_product.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.purchase_product.code }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.purchase_product.unit }}</p>
                            </td>
                            <td v-if="!boolean"
                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.serial_number }}</p>
                            </td>
                            <td v-if="!boolean"
                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">S/. {{ item.entry_price }}</p>
                            </td>
                            <td v-if="!boolean"
                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.condition }}</p>
                            </td>
                            <td v-if="boolean" class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex justify-center items-center space-x-3">
                                    <button @click="add_serial_number(item.id)" class="text-gray-600 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="resources.links" />
            </div>
        </div>
        <Modal :show="showModalAddSerialNumber">
            <form class="p-6" @submit.prevent="submit_add_serial_number">
                <h2 class="text-lg font-medium text-gray-900">
                    Agregar Numero de serie
                </h2>
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="mt-2">
                        <InputLabel for="serial_number" class="font-medium" value="Numero de Serie" />
                        <div class="mt-2">
                            <TextInput required id="serial_number" type="text" v-model="form.serial_number" />
                        </div>
                        <InputError :message="form.errors.serial_number" class="mt-2" />
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="close_add_serial_number"> Cerrar </SecondaryButton>
                    <PrimaryButton type="submit"> Aceptar </PrimaryButton>
                </div>
            </form>
        </Modal>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { ref } from 'vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    auth: Object,
    resources: {
        type: Object,
        required: false
    },
    boolean: {
        type: Boolean,
        required: false
    }
});

const resource_id = ref(null);
const showModalAddSerialNumber = ref(false);

const form = useForm({
    serial_number: '',
    resource_id: ''
})

const reentry = () => {
    if (props.boolean == true) {
        router.get(route('warehouses.index.resource'))
    } else {
        router.get(route('warehouses.index.resource', { boolean: false }))
    }
};

function add_serial_number(id) {
    resource_id.value = id
    showModalAddSerialNumber.value = true
}

function close_add_serial_number() {
    showModalAddSerialNumber.value = false
}

function submit_add_serial_number() {
    form.resource_id = resource_id.value
    form.post(route('warehouses.resource.add_serial_number'), {
        onSuccess: () => {
            form.reset()
            close_add_serial_number()
        }
    })
}
</script>