<template>
    <Head title="Usuarios" />

    <AuthenticatedLayout :redirectRoute="'users.index'">
        <template #header>
            Aprobación de Carpetas
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-end">
                <PrimaryButton @click="add_users" type="button">
                    Desaprobados
                </PrimaryButton>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Carpeta
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Ruta
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Propietario
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Areas
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in folders.data" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.path }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.user.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.areas?.map((i)=>i.name).join(', ') }}</p>
                            </td>
                            <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <div
                                        class="flex space-x-3 justify-center">
                                        <button 
                                        @click="validateFolder(item.id)"
                                        class="rounded-xl text-center text-sm text-green-900 hover:bg-green-200">
                                            <svg 
                                                xmlns="http://www.w3.org/2000/svg" 
                                                fill="none" 
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5" 
                                                stroke="currentColor" 
                                                class="w-6 h-6 text-green-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                        <button 
                                            @click="openFolderDeletionModal(item.id)"
                                            type="button"
                                            class="rounded-xl text-center text-sm text-red-900 hover:bg-red-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="folders.links" />
            </div>
        </div>

        <Modal :show="showFolderDeletion" @close="closeFolderDeletionModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Eliminación de Carpeta
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Al realizar esta acción la carpeta será eliminada.
                </p>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeFolderDeletionModal"> Cancelar </SecondaryButton>

                    <DangerButton class="ml-3" 
                        @click="deleteFolder">
                        Eliminar Carpeta
                    </DangerButton>
                </div>
            </div>
        </Modal>
       
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
import { nextTick, ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const confirmingUserDeletion = ref(false);
const usersToDelete = ref(null);
const passwordInput = ref(null);

const props = defineProps({
    folders: Object
})

const add_users = () => {
    router.get(route('register'));
}


function validateFolder (id) {
    router.post(route('documment.management.folders.check', {folder_id: id}), null, {
        onSuccess:() => {

        }
    })
}


const showFolderDeletion = ref(false);
const idToDelete = ref(null)

function openFolderDeletionModal (id) {
    showFolderDeletion.value = true
    idToDelete.value = id
}
function closeFolderDeletionModal () {showFolderDeletion.value = false}

function deleteFolder() {
    router.delete(route(''))
}


</script>
