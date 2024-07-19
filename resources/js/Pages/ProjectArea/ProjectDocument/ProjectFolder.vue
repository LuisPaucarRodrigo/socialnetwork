<template>

    <Head title="G. Documentaria" />

    <AuthenticatedLayout
        :redirectRoute="backUrl">
        <template #header>
            {{ currentPath }}
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-between">
                <PrimaryButton 
                    @click="openAddFoldermodal" type="button">
                    + Agregar
                </PrimaryButton>

                <div class="flex items-center mt-4 sm:mt-0">
                    <form 
                        @submit.prevent="search"
                        class="flex items-center w-full sm:w-auto"
                    >
                        <TextInput type="text" placeholder="Buscar..." pattern="[a-zA-Z0-9áéíóúüÁÉÍÓÚÜ\s\-_]*" v-model="searchForm.searchTerm" />
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

            <div class="overflow-x-auto">
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
                                Tamaño
                            </th>
                           
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 pl-4 pr-10 py-3 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, i) in folders_archives" :key="i" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white  text-sm">
                                <!-- for button instead of Link -->
                                <!-- @click="() => router.visit(route('documment.management.folders', { folder_id: item.item_db.id }))" -->
                                <Link :href="route('project.document.index',{path: item.path})"
                                    class="inline-block w-full h-full text-left px-5 py-5 text-gray-900 whitespace-nowrap font-bold hover:cursor-pointer hover:text-indigo-600 tracking-widest text-base hover:opacity-70 hover:underline">
                                <div class="flex space-x-8 items-center w-full mr-8 xl:mr-0">
                                    <FolderIcon v-if="item.type === 'folder'" 
                                        class="h-10 w-10"/>
                                    <DocumentTextIcon v-if="item.type === 'archive'" 
                                        class="h-9 w-9"/>
                                    <p>
                                        {{ item.name }}
                                    </p>
                                </div>

                                </Link>

                                
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">

                                <p  class="text-gray-700">
                                    {{ item.size }}
                                </p>
                               
                            </td>
                            
                            <td class="border-b border-gray-200 bg-white pl-5 py-5 pr-10 text-sm">
                                <div class="flex space-x-3 justify-end">
                                    <!-- <Link class="text-blue-900 whitespace-no-wrap" :href="'#'">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    </Link> -->
                                    <a type="button" :href="'#'">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="green" :class="`w-6 h-6`">
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
            </div>

        </div>


        <Modal :show="showAddFolderModal" @close="closeAddFolderModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Nuevo
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-10 gap-y-5 sm:grid-cols-2">
                        <div>
                            <InputLabel for="resorceOrProduct">Tipo</InputLabel>
                            <div class="flex gap-4 items-center mt-4">
                                <label class="flex gap-2 items-center text-sm">
                                    Carpeta
                                    <input type="radio" v-model="createFolderForm.type" @input="handleFolderType"
                                        id="resorceOrProduct" :value="'Carpeta'"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <label class="flex gap-2 items-center text-sm">
                                    Archivos
                                    <input type="radio" v-model="createFolderForm.type" @input="handleFolderType"
                                        id="resorceOrProduct" :value="'Archivo'"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                            </div>
                        </div>
                        <div  v-if="createFolderForm.type === 'Carpeta'" class="">
                            <InputLabel>Nombre</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="createFolderForm.name" id="first-name"
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="createFolderForm.errors.name" />
                            </div>
                        </div>
                        <div v-if="createFolderForm.type === 'Archivo'" class="">
                            <InputLabel>Archivo</InputLabel>
                            <div class="mt-2">
                                <InputFile type="file" v-model="createFolderForm.file" 
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="createFolderForm.errors.file" />
                            </div>
                        </div>
                         

                    </div>
                    <br>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeAddFolderModal"> Cancelar </SecondaryButton>

                        <PrimaryButton class="ml-3" :class="{ 'opacity-25': createFolderForm.processing }"
                            :disabled="createFolderForm.processing" type="submit">
                            Guardar
                        </PrimaryButton>
                    </div>

                </form>
            </div>
        </Modal>


        <Modal :show="showDeleteFolderModal" @close="closeDeleteFolderModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-2">
                    Eliminación de Carpeta
                </h2>
                <InputLabel class="mb-5">
                    ¿Estás seguro de querer eliminar toda la carpeta?, esta acción eliminará también las subcarpetas y
                    archivos
                    de esta. La acción es irreversible.
                </InputLabel>
                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeDeleteFolderModal"> Cancelar </SecondaryButton>

                    <DangerButton type="button" class="ml-3" @click="deleteFolder">
                        Eliminar
                    </DangerButton>
                </div>
            </div>
        </Modal>

        <SuccessOperationModal :confirming="confirmFolderCreate" :title="'Nueva Carpeta Creada'"
            :message="'Carpeta creada con éxito'" />


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
import InputFile from '@/Components/InputFile.vue';
import { FolderIcon } from '@heroicons/vue/24/solid';
import { DocumentTextIcon } from '@heroicons/vue/24/outline';


const { folders_archives, folder, currentPath, previousPath, auth, areas } = defineProps({
    folders_archives: Object,
    folder: Object,
    currentPath: String,
    previousPath: String,
    areas: Object,
    auth: Object
})

let backUrl = previousPath ? { route: 'project.document.index' , params: {path: previousPath}} 
                : { route: 'projectmanagement.index'}

//---------- Check Permission -------//
function checkSeeDownloadPermission(item) {
    if (auth.user.role_id === 1) {
        return true
    }
    return item?.folder_areas?.find(i => i.area_id == auth.user.area_id)?.see_download
}
function checkCreatePermission() {
    return folder?.folder_areas.find(i => i.area_id == auth.user.area_id)?.create
}
//---------------------------------//



//------------ Add Folder ----------//
const showAddFolderModal = ref(false)
const confirmFolderCreate = ref(false)
const createFolderForm = useForm({
    name: '',
    type: 'Carpeta',
    file: undefined,
    path: currentPath,
})
function openAddFoldermodal() {
    showAddFolderModal.value = true
}
function closeAddFolderModal() {
    showAddFolderModal.value = false
    createFolderForm.reset()
}
function handleFolderType() {
    createFolderForm.file = undefined
    createFolderForm.name = ''
}


function submit() {
    createFolderForm.post(route('project.document.store'), {
        onSuccess: () => {
            closeAddFolderModal()
            confirmFolderCreate.value = true
            setTimeout(() => {
                confirmFolderCreate.value = false
            }, 1300)
        }
    })
}
//---------------------------------//



//--------- Delete Folder ---------//
const showDeleteFolderModal = ref(false)
const folderToDelete = ref(null)
function openDeleteFolderModal(item) {
    folderToDelete.value = item
    showDeleteFolderModal.value = true
}
function closeDeleteFolderModal() {
    folderToDelete.value = null
    showDeleteFolderModal.value = false
}
function deleteFolder() {
    router.delete(route('document.management.folder.destroy', { folder_id: folderToDelete.value.item_db.id }), {
        onSuccess: () => {
            closeDeleteFolderModal()
        }, onError: (e) => {
            console.error(e)
        }
    })
}
//-------------------------------//

const searchForm = useForm({
    searchTerm: '',
})
const search = () => {
    if (searchForm.searchTerm !== '') {
        router.visit(route('documment.management.folders', {folder_id:folder?.id})+`?search=${searchForm.searchTerm}`)
    }
}

</script>
