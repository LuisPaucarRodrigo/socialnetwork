<template>
    <Head title="Inventario" />
    <AuthenticatedLayout>
        <template #header>
            Componentes y Materiales
        </template>

        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <div>
                <button @click="add_component_or_material" type="button"
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
                                Nombre
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Precio
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
                        <tr v-for="item in components_or_materials" :key="item.id" :class="[
                            'text-gray-700',
                            { 'border-l-4': true, 'border-green-500': item.state === 'Disponible', 'border-red-500': item.state !== 'Disponible' }
                        ]">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.quantity }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">S/. {{ item.price }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.adquisition_date }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button type="button" @click="edit_component_or_material(item.id)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <PencilIcon class="text-yellow-500 h-4 w-4"></PencilIcon>
                                    </button>
                                    <button type="button" @click="delete_component_or_material(item.id)"
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
    components_or_materials: Object
})
const add_component_or_material = () => {
    router.get(route('inventory.ComponentsAndMaterials.new'));
}
const edit_component_or_material = (CmId) => {
    router.get(route('inventory.ComponentsAndMaterials.edit', { CmId: CmId }));
}
const delete_component_or_material = (CmId) => {
    router.delete(route('inventory.ComponentsAndMaterials.delete', { CmId: CmId }));
}
</script>