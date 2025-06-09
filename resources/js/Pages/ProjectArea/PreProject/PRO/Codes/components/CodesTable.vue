<template>
    <div class="overflow-x-auto rounded-lg shadow mt-2">
        <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Código
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Descripción
                    </th>
                    <th
                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="code in codes.data" :key="code.id" class="text-gray-700">
                    <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                        <p class="text-gray-900 whitespace-no-wrap">{{ code.code }}</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                        <p class="text-gray-900 whitespace-no-wrap">{{ code.description }}</p>
                    </td>
                    <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                        <div class="flex justify-center space-x-3">
                            <button @click="openImagesForm(code.id)">
                                <PlusCircleIcon />
                            </button>
                            <button @click="openImagesModal(code.id)">
                                <ShowIcon />
                            </button>
                            <button type="button" @click="openModal(code)">
                                <EditIcon />
                            </button>
                            <button type="button" @click="confirmDeleteCode(code.id)">
                                <DeleteIcon />
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <pagination :links="codes.links" />
    </div>
</template>
<script setup>
import Pagination from '@/Components/Pagination.vue';
import { EditIcon, DeleteIcon, PlusCircleIcon, ShowIcon } from '@/Components/Icons/Index';

const { codes, openImagesForm, openModal, openImagesModal, confirmDeleteCode } = defineProps({
    codes: Object,
    openImagesForm: Function,
    openModal: Function,
    openImagesModal: Function,
    confirmDeleteCode: Function
})
</script>