<template>
    <div class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
        <div class="grid grid-cols-2">
            <p class="col-start-1 col-span-1 text-sm font-semibold mb-3">
                Nombre: {{ item.project_name }}
            </p>
            <div class="inline-flex justify-end items-start gap-x-2">
                <button type="button" @click="rejectOrReturnAdditionalProject(item.project.id)">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 text-red-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 12H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </button>
                <!-- <button type="button" @click="editProject(item)">
                    <EditIcon />
                </button> -->
            </div>
            <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                Descripción: {{ item.project.description }}
            </p>
            <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                Centro de Costo: {{ item.project.cost_center?.name }}
            </p>
        </div>
        <div>
            <div class="grid grid-cols-1 gap-y-1">
                <Link
                    :href="route('pext.additional.expense.index', { project_id: item.project.id, fixedOrAdditional: false, type })"
                    class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                Compras y Gastos
                </Link>
                <div class="flex gap-x-3">
                    <button @click="openQuickQuote(item.project)" class="text-blue-600 underline hover:text-purple-600">
                        Cotización Rápida
                    </button>
                    <a v-if="item.project.project_quote"
                        :href="route('projectmanagement.pext.export.pdf.quote', { project_id: item.project.id })"
                        target="_blank" rel="noopener noreferrer">
                        <svg class="h-5 w-5 text-green-200" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                            stroke="currentColor">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M9.29289 1.29289C9.48043 1.10536 9.73478 1 10 1H18C19.6569 1 21 2.34315 21 4V9C21 9.55228 20.5523 10 20 10C19.4477 10 19 9.55228 19 9V4C19 3.44772 18.5523 3 18 3H11V8C11 8.55228 10.5523 9 10 9H5V20C5 20.5523 5.44772 21 6 21H7C7.55228 21 8 21.4477 8 22C8 22.5523 7.55228 23 7 23H6C4.34315 23 3 21.6569 3 20V8C3 7.73478 3.10536 7.48043 3.29289 7.29289L9.29289 1.29289ZM6.41421 7H9V4.41421L6.41421 7ZM19 12C19.5523 12 20 12.4477 20 13V19H23C23.5523 19 24 19.4477 24 20C24 20.5523 23.5523 21 23 21H19C18.4477 21 18 20.5523 18 20V13C18 12.4477 18.4477 12 19 12ZM11.8137 12.4188C11.4927 11.9693 10.8682 11.8653 10.4188 12.1863C9.96935 12.5073 9.86526 13.1318 10.1863 13.5812L12.2711 16.5L10.1863 19.4188C9.86526 19.8682 9.96935 20.4927 10.4188 20.8137C10.8682 21.1347 11.4927 21.0307 11.8137 20.5812L13.5 18.2205L15.1863 20.5812C15.5073 21.0307 16.1318 21.1347 16.5812 20.8137C17.0307 20.4927 17.1347 19.8682 16.8137 19.4188L14.7289 16.5L16.8137 13.5812C17.1347 13.1318 17.0307 12.5073 16.5812 12.1863C16.1318 11.8653 15.5073 11.9693 15.1863 12.4188L13.5 14.7795L11.8137 12.4188Z" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>
<script setup>
import { EditIcon } from '@/Components/Icons';
import { Link } from '@inertiajs/vue3';

const { item, rejectOrReturnAdditionalProject, openQuickQuote, editProject, type } = defineProps({
    item: Object,
    rejectOrReturnAdditionalProject: Function,
    openQuickQuote: Function,
    editProject: Function,
    type: String
})
</script>