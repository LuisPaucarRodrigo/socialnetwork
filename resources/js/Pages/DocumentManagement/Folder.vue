<template>

    <Head title="G. Documentaria" />

    <AuthenticatedLayout
        :redirectRoute="{ route: 'documment.management.folders', params: { folder_id: folder?.upper_folder_id } }">
        <template #header>
            {{ currentPath }}
        </template>
        <div class="min-w-full rounded-lg shadow">
            <PrimaryButton v-if="(currentPath !== 'CCIP' && checkCreatePermission())
            || (auth.user.role_id === 1)" @click="openAddFoldermodal" type="button">
                + Agregar
            </PrimaryButton>
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
                                <!-- for button instead of Link -->
                                <!-- @click="() => router.visit(route('documment.management.folders', { folder_id: item.item_db.id }))" -->
                                <Link v-if="item.item_db.state && checkSeeDownloadPermission(item.item_db)"
                                    :href="item.item_db.type === 'Carpeta' ? route('documment.management.folders', { folder_id: item.item_db.id }) : (item.item_db.type === 'Archivos' ? route('archives.show', { folder: item.item_db.id}) : '#')"
                                    class="inline-block w-full h-full text-left px-5 py-5 text-gray-900 whitespace-nowrap font-bold hover:cursor-pointer hover:text-indigo-600 tracking-widest text-base hover:opacity-70 hover:underline">
                                <div class="flex space-x-3 items-center w-full pr-8 md:pr-0">
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
                                    <div class="flex space-x-3 opacity-60 items-center pr-10 md:pr-0">
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
                                    <!-- <Link class="text-blue-900 whitespace-no-wrap" :href="'#'">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    </Link> -->
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
            </div>

        </div>


        <Modal :show="showAddFolderModal" @close="closeAddFolderModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Nueva Carpeta
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-10 gap-y-5 sm:grid-cols-2">
                        <div class="">
                            <InputLabel>Nombre</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="createFolderForm.name" id="first-name"
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="createFolderForm.errors.name" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="resorceOrProduct">Tipo de Carpeta</InputLabel>
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
                                        id="resorceOrProduct" :value="'Archivos'"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                            </div>
                        </div>

                        <div v-if="createFolderForm.type === 'Archivos'">
                            <InputLabel>
                                Tipo de Archivos
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="createFolderForm.archive_type" id="payment_type"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccionar Tipo</option>
                                    <option>PDF</option>
                                    <option>Word</option>
                                    <option>Excel</option>
                                    <option>Power Point</option>
                                    <option>Imágenes</option>
                                </select>
                                <InputError :message="createFolderForm.errors.archive_type" />
                            </div>
                        </div>


                        <div>
                            <InputLabel>
                                Areas
                            </InputLabel>
                            <div class="mt-2">
                                <select multiple size="4" v-model="createFolderForm.areas" id="payment_type"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Uno o Varios ctrl+click</option>
                                    <option v-for="item in areas" :key="item.id" :value="item.id">
                                        {{ item.name }}
                                    </option>

                                </select>
                                <InputError :message="createFolderForm.errors.areas" />
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


const { folders, folder, currentPath, auth, areas } = defineProps({
    folders: Object,
    folder: Object,
    currentPath: String,
    areas: Object,
    auth: Object
})

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
    archive_type: '',
    areas: [],
    currentPath,
    user_id: auth.user.id,
    upper_folder_id: folder?.id
})
function openAddFoldermodal() {
    showAddFolderModal.value = true
}
function closeAddFolderModal() {
    showAddFolderModal.value = false
    createFolderForm.reset()
}
function handleFolderType() {
    createFolderForm.archive_type = ''
}
function submit() {
    createFolderForm.post(route('documment.management.folders.store'), {
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



</script>
