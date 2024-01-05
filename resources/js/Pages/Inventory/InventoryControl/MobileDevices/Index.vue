<template>
    <Head title="Inventario" />
    <AuthenticatedLayout>
        <template #header>
            Dispositivos Moviles
        </template>

        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <div>
                <button @click="add_mobile_device" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 ">
                    + Agregar
                </button>
            </div>
            <div class="talwing mt-4">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Marca
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Modelo
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Numero de Serie
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Adquisicion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in mobile_devices" :key="item.id" :class="[
                            'text-gray-700',
                            { 'border-l-4': true, 'border-green-500': item.state === 'Disponible', 'border-red-500': item.state !== 'Disponible' }
                        ]">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.phone_brand }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.model }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.serie_number }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.adquisition_date }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button type="button" @click="edit_network_equipment(item.id)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <PencilIcon class="text-yellow-500 h-4 w-4"></PencilIcon>
                                    </button>
                                    <button type="button" @click="delete_network_equipment(item.id)"
                                        class="text-red-900 whitespace-no-wrap">
                                        <TrashIcon class="text-red-500 h-4 w-4" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    mobile_devices: Object
})
const add_mobile_device = () => {
    router.get(route('inventory.MobileDevices.new'));
}
const edit_network_equipment = (MdId) => {
    router.get(route('inventory.MobileDevices.edit', { MdId: MdId }));
}
const delete_network_equipment = (MdId) => {
    router.delete(route('inventory.MobileDevices.delete', { MdId: MdId }));
}

</script>