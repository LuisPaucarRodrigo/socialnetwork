<template>

    <Head title="Permisos" />

    <AuthenticatedLayout>
        <template #header>
            Permisos de la carpeta {{ folder.name }}
        </template>
        <div class="min-w-full rounded-lg shadow">
            <PrimaryButton @click="openAddFAModal" type="button">
                + Agregar
            </PrimaryButton>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Área
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Ver/Descargar
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Crear
                            </th>
                            <th v-if="permissions.length !== 1"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">

                            </th>

                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in permissions" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900">
                                    {{ item.area.name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex justify-center">
                                    <input type="checkbox" :checked="item.see_download" :id="'sd_checkbox' + item.id"
                                        @input="openPermissionhandleModal($event.target.checked, item.id, modalOptions.see_download, item.area.name)"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-5 w-5 ring-inset placeholder:text-gray-400 sm:text-sm sm:leading-6" />
                                </div>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex justify-center">
                                    <input type="checkbox" :checked="item.create" :id="'create_checkbox' + item.id"
                                        @input="openPermissionhandleModal($event.target.checked, item.id, modalOptions.create, item.area.name)"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-5 w-5 ring-inset placeholder:text-gray-400 sm:text-sm sm:leading-6" />
                                </div>
                            </td>
                            <td v-if="permissions.length !== 1"
                                class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <button>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </td>


                        </tr>
                    </tbody>
                </table>
            </div>

            <Modal :show="showPermissionHandler" @close="closePermissionHandlerModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-2">
                        {{ modalItem.title }}
                    </h2>

                    <InputLabel>
                        {{ modalItem.message }}
                    </InputLabel>
                    <br>
                    <div v-if="modalItem.postItem.state">
                        <InputLabel>¿Aplicar el permiso a todas las subcarpetas de esta carpeta?</InputLabel>
                        <div class="flex gap-4 items-center mt-4">
                            <label class="flex gap-2 items-center text-sm">
                                Si
                                <input type="radio" :value="true" v-model="modalItem.postItem.down_recursive"
                                    class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                            </label>
                            <label class="flex gap-2 items-center text-sm">
                                No
                                <input type="radio" :value="false" v-model="modalItem.postItem.down_recursive"
                                    class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                            </label>
                        </div>
                    </div>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closePermissionHandlerModal"> Cancelar </SecondaryButton>

                        <PrimaryButton class="ml-3 bg-black" customClass="tracking-widest" @click="updatePermission">
                            Aceptar
                        </PrimaryButton>
                    </div>
                </div>
            </Modal>

            <Modal :show="showAddFA" @close="closeAddFAModal">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-2">
                        Agregar Nueva Área con Permisos
                    </h2>
                    <InputLabel>
                        Se Agregará el siguiente Área con los permisos de ver, descargar y ver. 
                    </InputLabel>
                    <InputLabel class="mb-5">
                        Las áreas disponibles para selección corresponden a las de la carpeta superior inmediata.
                    </InputLabel>
                    <form @submit.prevent="" class="mb-5">
                        <div class="mt-2">
                            <select required v-model="addFolderAreaform.area_id"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option value="" disabled>
                                    Selecciona un area
                                </option>
                                <option v-for="item in makeAreasArray(permissions, upper_folder_areas)" :key="item.id" :value="item.id">
                                    {{ `${item.name}` }}
                                </option>
                            </select>
                            <InputError :message="addFolderAreaform.errors.employees" />
                        </div>
                    </form>

                    <div class="mt-6 flex justify-end">
                        <SecondaryButton @click="closeAddFAModal"> Cancelar </SecondaryButton>

                        <PrimaryButton class="ml-3 bg-black" customClass="tracking-widest">
                            Aceptar
                        </PrimaryButton>
                    </div>
                </div>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link, Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';



const props = defineProps({
    permissions: Object,
    folder: Object,
    upper_folder_areas: Object
})

const makeAreasArray = (permissions, upper_folder_areas) => {
   let permissionsAreasIds = permissions.map(item=>item.area_id);
   return upper_folder_areas.filter(item=>!permissionsAreasIds.includes(item.id)) 
}

function see_download_handler(state, id) {
    router.post(route('documment.management.folders.permission.see_download', { folder_area_id: id }), { state }, {
        onSuccess: () => {
            console.log('siuuuuuuuuuu')
        }
    })
}


const showPermissionHandler = ref(false)

const modalOptions = {
    see_download: 'see_download',
    create: 'create'
}

const initialModalItem = {
    title: '',
    message: '',
    option: '',
    currentItem: null,
    postItem: {
        folder_area_id: '',
        state: '',
        down_recursive: false
    }
}

const modalItem = ref(JSON.parse(JSON.stringify(initialModalItem)))


function openPermissionhandleModal(state, id, option, area) {
    if (state && option === modalOptions.see_download) {
        modalItem.value = {
            title: `Activar permiso de ver y descargar del área ${area}`,
            message: 'Al realizar esta acción activará el permiso de ver y descargar de la actual carpeta y de las carpetas superiores a esta.',
            option: option,
            postItem: {
                folder_area_id: id,
                state: state,
                down_recursive: false
            }
        }
        showPermissionHandler.value = true
    }
    if (!state && option === modalOptions.see_download) {
        modalItem.value = {
            title: `Desactivar permiso de ver y descargar del área ${area}`,
            message: 'Al realizar esta acción desactivará el permiso de ver, descargar y crear de la carpeta actual y subcarpetas que contenga.',
            option: option,
            postItem: {
                folder_area_id: id,
                state: state,
                down_recursive: false
            }
        }
        showPermissionHandler.value = true
    }
    if (state && option === modalOptions.create) {
        modalItem.value = {
            title: `Activar permiso de crear del área ${area}`,
            message: 'Al realizar esta acción activará el permiso de ver, descargar y crear de la actual carpeta y de las carpetas superiores a esta solo los permisos de ver y descargar.',
            option: option,
            postItem: {
                folder_area_id: id,
                state: state,
                down_recursive: false
            }
        }
        showPermissionHandler.value = true
    }
    if (!state && option === modalOptions.create) {
        modalItem.value = {
            title: `Desactivar permiso de crear del área ${area}`,
            message: 'Al realizar esta acción desactivará el permiso crear de la carpeta actual y subcarpetas que contenga.',
            option: option,
            postItem: {
                folder_area_id: id,
                state: state,
                down_recursive: false
            }
        }
        showPermissionHandler.value = true
    }
}

function closePermissionHandlerModal() {
    showPermissionHandler.value = false
    let id = modalItem.value.postItem.folder_area_id
    if (modalItem.value.option === modalOptions.see_download) {
        const checkbox = document.getElementById('sd_checkbox' + id)
        checkbox.checked = !checkbox.checked
    }
    if (modalItem.value.option === modalOptions.create) {
        const checkbox = document.getElementById('create_checkbox' + id)
        checkbox.checked = !checkbox.checked
    }
    modalItem.value = JSON.parse(JSON.stringify(initialModalItem))
}

function updatePermission() {
    let url
    if (modalItem.value.option === modalOptions.see_download) {
        url = 'documment.management.folders.permission.see_download'
    } else if (modalItem.value.option === modalOptions.create) {
        url = 'documment.management.folders.permission.create'
    }
    router.post(route(url, { folder_area_id: modalItem.value.postItem.folder_area_id }), { down_recursive: modalItem.value.postItem.down_recursive, state: modalItem.value.postItem.state }, {
        onSuccess: () => {
            console.log('siudddddd si se pudo')
            showPermissionHandler.value = false
            modalItem.value = JSON.parse(JSON.stringify(initialModalItem))
        }
    })
}


//Add Folder Area

const showAddFA = ref(false)
const addFolderAreaform = useForm({
    area_id: ''
})

function openAddFAModal() {
    showAddFA.value = true
}

function closeAddFAModal() {
    showAddFA.value = false
}


</script>
