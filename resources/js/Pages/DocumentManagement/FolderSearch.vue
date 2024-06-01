<template>

    <Head title="G. Documentaria" />

    <AuthenticatedLayout
        :redirectRoute="{ route: 'documment.management.folders', params: { folder_id: folder?.id } }">
        <template #header>
            {{ currentPath }}
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-end">
                <div class="flex items-center mt-4 sm:mt-0">
                    <form 
                        @submit.prevent="search"
                        class="flex items-center w-full sm:w-auto"
                    >
                        <TextInput type="text" placeholder="Buscar..." v-model="searchForm.searchTerm" />
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
            halo
            <!-- <div class="overflow-x-auto">
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
                            <th v-if="auth.user.role_id === 1"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Permisos
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Tipo
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 pl-4 pr-10 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in folders" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white  text-sm">
                                <Link v-if="item.item_db.state && checkSeeDownloadPermission(item.item_db)"
                                    :href="item.item_db.type === 'Carpeta' ? route('documment.management.folders', { folder_id: item.item_db.id }) : (item.item_db.type === 'Archivos' ? route('archives.show', { folder: item.item_db.id }) : '#')"
                                    class="inline-block w-full h-full text-left px-5 py-5 text-gray-900 whitespace-nowrap font-bold hover:cursor-pointer hover:text-indigo-600 tracking-widest text-base hover:opacity-70 hover:underline">
                                <div class="flex space-x-3 items-center w-full mr-8 xl:mr-0">
                                    <img v-if="item.item_db.type === 'Archivos'" src="/image/FolderIcons/archive_5.png"
                                        class="h-12 w-12">
                                    <img v-if="item.item_db.type === 'Carpeta'" src="/image/FolderIcons/folder_5.png"
                                        class="h-12 w-12">
                                    <p>
                                        {{ item.name }}
                                    </p>
                                </div>

                                </Link>

                                <div v-else class="px-5 py-5 ">
                                    <div class="flex space-x-3 opacity-60 items-center  mr-8 xl:mr-0">
                                        <img v-if="item.item_db.type === 'Archivos'"
                                            src="/image/FolderIcons/archive_5.png" class="h-12 w-12">
                                        <img v-if="item.item_db.type === 'Carpeta'"
                                            src="/image/FolderIcons/folder_5.png" class="h-12 w-12">
                                        <p class="text-gray-700 whitespace-nowrap font-bold tracking-widest text-base">
                                            {{ item.name }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">

                                <p v-if="item.item_db.state" class="text-gray-700">
                                    {{ checkSeeDownloadPermission(item.item_db)
            ? 'Autorizado'
            : 'No Autorizado' }}
                                </p>
                                <p v-else class="text-red-400">
                                    Por aprobar
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.item_db.user.name }}</p>
                            </td>
                            <td v-if="auth.user.role_id === 1"
                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <Link
                                    :href="route('documment.management.folders.permissions', { folder_id: item.item_db.id })"
                                    class="text-indigo-500 hover:underline hover:text-indigo-400 hover:cursor-pointer">
                                Administrar</Link>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.item_db.type }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white pl-5 py-5 pr-10 text-sm">
                                <div class="flex space-x-3 justify-end">
                                    
                                    <a type="button" :href="checkSeeDownloadPermission(item.item_db)
            ? route('folder.test.download', {
                folder_id: item.item_db.id
            })
            : '#'" :class="checkSeeDownloadPermission(item.item_db)
            ? ''
            : 'cursor-default'">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="green" :class="`w-6 h-6 ${checkSeeDownloadPermission(item.item_db)
            ? ''
            : 'opacity-50'}`">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                    </a>
                                    <button v-if="auth.user.role_id === 1" type="button"
                                        @click="openDeleteFolderModal(item)" class="text-blue-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div> -->

        </div>



    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link, Head, router, useForm } from '@inertiajs/vue3';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { ref } from 'vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import DangerButton from '@/Components/DangerButton.vue';


const { folders, folder, currentPath, auth, areas, searchTerm } = defineProps({
    folders: Object,
    folder: Object,
    currentPath: String,
    searchTerm: String,
    areas: Object,
    auth: Object
})

const searchForm = useForm({
    searchTerm: searchTerm ? searchTerm : '',
})
const search = () => {
    searchForm.searchTerm !== '' 
        ? router.visit(route('documment.management.folders', {folder_id:folder?.id})+`?search=${searchForm.searchTerm}`)
        : router.visit(route('documment.management.folders', {folder_id:folder?.id}))
}


</script>
