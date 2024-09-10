<template>

    <Head title="G. Documentaria" />

    <AuthenticatedLayout :redirectRoute="{ route: 'documment.management.folders', params: { folder_id: folder?.id } }">
        <template #header>
            {{ currentPath }}
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-end">
                <div class="flex items-center mt-4 sm:mt-0">
                    <form @submit.prevent="search" class="flex items-center w-full sm:w-auto">
                        <TextInput type="text" placeholder="Buscar..."  pattern="[a-zA-Z0-9áéíóúüÁÉÍÓÚÜ\s\-_]*" v-model="searchForm.searchTerm" />
                        <button type="submit"
                            class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <div v-if="folders.length > 0" class="overflow-x-auto">
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
                                Estado
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Propietario
                            </th>

                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Tipo
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 pl-4 pr-10 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Creación
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in folders" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white  text-sm">
                                <Link v-if="item.state && checkSeeDownloadPermission(item)"
                                    :href="item.type === 'Carpeta' ? route('documment.management.folders', { folder_id: item.id }) : (item.type === 'Archivos' ? route('archives.show', { folder: item.id }) : '#')"
                                    class="inline-block w-full h-full text-left px-5 py-5 text-gray-900 whitespace-nowrap font-bold hover:cursor-pointer hover:text-indigo-600 tracking-widest text-base hover:opacity-70 hover:underline">
                                <div class="flex space-x-3 items-center w-full mr-8 xl:mr-0">
                                    <img v-if="item.type === 'Archivos'" src="/image/FolderIcons/archive_5.png"
                                        class="h-12 w-12">
                                    <img v-if="item.type === 'Carpeta'" src="/image/FolderIcons/folder_5.png"
                                        class="h-12 w-12">
                                    <div class="flex flex-col space-y-4">
                                        <p v-html="highlightText(item.name)"></p>
                                        <p class="text-xs" v-html="highlightText(item.path)">
                                        </p>
                                    </div>
                                </div>

                                </Link>

                                <div v-else class="px-5 py-5 ">
                                    <div
                                        class="flex space-x-3 opacity-60 items-center mr-8 xl:mr-0 text-gray-700 whitespace-nowrap font-bold tracking-widest text-base">
                                        <img v-if="item.type === 'Archivos'" src="/image/FolderIcons/archive_5.png"
                                            class="h-12 w-12">
                                        <img v-if="item.type === 'Carpeta'" src="/image/FolderIcons/folder_5.png"
                                            class="h-12 w-12">
                                        <div class="flex flex-col space-y-4">
                                            <p v-html="highlightText(item.name)"></p>
                                            <p class="text-xs" v-html="highlightText(item.path)"></p>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">

                                <p v-if="item.state" class="text-gray-700">
                                    {{ checkSeeDownloadPermission(item)
        ? 'Autorizado'
        : 'No Autorizado' }}
                                </p>
                                <p v-else class="text-red-400">
                                    Por aprobar
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.user.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.type }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white pl-5 py-5 pr-10 text-sm">
                                <div class="flex space-x-3 justify-end">
                                    {{ formattedDate(item.created_at) }}
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-else>
                <br>
                <h2 class="text-lg text-center">
                    No se encontraron coincidencias
                </h2>
                <br>
            </div>
        </div>



    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import { Link, Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { formattedDate } from '@/utils/utils.js';

const { folders, folder, currentPath, areas, searchTerm, auth } = defineProps({
    folders: Object,
    folder: Object,
    currentPath: String,
    searchTerm: String,
    auth: Object,
})

const searchForm = useForm({
    searchTerm: searchTerm ? searchTerm : '',
})
const search = () => {
    searchForm.searchTerm !== ''
        ? router.visit(route('documment.management.folders', { folder_id: folder?.id }) + `?search=${searchForm.searchTerm}`)
        : router.visit(route('documment.management.folders', { folder_id: folder?.id }))
}


function checkSeeDownloadPermission(item) {
    if (auth.user.role_id === 1) {
        return true
    }
    return item?.folder_areas?.find(i => i.area_id == auth.user.area_id)?.see_download
}



const highlightText = (originalText) => {
    const escapedHighlight = searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    const searchTermRegex = escapedHighlight.replace(/[\s-_]/g, '[\\s-_]*');
    const searchTermWithTilde = searchTermRegex.replace(/[áÁ]/g, '[aAáÁ]')
                                                .replace(/[éÉ]/g, '[eEéÉ]')
                                                .replace(/[íÍ]/g, '[iIíÍ]')
                                                .replace(/[óÓ]/g, '[oOóÓ]')
                                                .replace(/[úÚ]/g, '[uUúÚ]')
                                                .replace(/[üÜ]/g, '[uUüÜ]');
    const regex = new RegExp(`(${searchTermWithTilde})`, 'gi');
    return originalText.replace(regex, '<span class="bg-yellow-200 text-bold">$1</span>');
};

</script>
