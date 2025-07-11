<template>
    <div class="overflow-x-auto mt-2">
        <table class="min-w-full divide-y divide-gray-200">
            <thead>
                <tr>
                    <th
                        class="px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider text-center"
                    >
                        Código
                    </th>
                    <th
                        class="px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider text-center"
                    >
                        Nombre
                    </th>
                    <th
                        class="px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider text-center"
                    >
                        Operador
                    </th>
                    <th
                        class="px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider text-center"
                    >
                        Dirección
                    </th>
                    <th
                        class="px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider text-center"
                    >
                        Latitud
                    </th>
                    <th
                        class="px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider text-center"
                    >
                        Longitud
                    </th>
                    <th
                        class="px-6 py-3 bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wider text-center"
                    >
                        Acciones
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="site in dataToRender" :key="site.id">
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm font-medium text-gray-900">
                            {{ site.code }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm font-medium text-gray-900">
                            {{ site.name }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm font-medium text-gray-900">
                            {{ site.prefix }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm font-medium text-gray-900">
                            {{ site.address }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm font-medium text-gray-900">
                            {{ site.latitude }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="text-sm font-medium text-gray-900">
                            {{ site.longitude }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="flex justify-center gap-2 items-center">
                            <button
                                v-permission="'huawei_sites_edit'"
                                @click="$emit('edit', site)"
                                class="text-orange-400 hover:underline"
                            >
                                <PencilSquareIcon class="h-5 w-5" />
                            </button>
                            <button
                                v-permission="'huawei_sites_delete'"
                                @click="$emit('delete', site.id)"
                                class="text-red-600 hover:underline"
                            >
                                <TrashIcon class="h-5 w-5" />
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div
        v-if="!search"
        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between"
    >
        <pagination :links="links" />
    </div>
</template>

<script setup>
// import { PencilSquareIcon, TrashIcon } from "@heroicons/vue/24/outline";
import Pagination from "@/Components/Pagination.vue";

const { dataToRender, links, search } = defineProps({
    dataToRender: Array,
    links: Object,
    search: String,
});

defineEmits(["edit", "delete"]);
</script>
