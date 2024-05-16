<template>

    <Head title="G. Documentaria" />

    <AuthenticatedLayout :redirectRoute="'users.index'">
        <template #header>
            {{ currentPath }}
        </template>
        <div class="min-w-full rounded-lg shadow">
            <PrimaryButton @click="openAddFoldermodal" type="button">
                + Agregar
            </PrimaryButton>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold sm:w-2/3 uppercase tracking-wider text-gray-600">
                                Nombre
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Permisos
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Tipo
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in folders" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap">{{ item.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">permisos</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">tipo</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <Link class="text-blue-900 whitespace-no-wrap" :href="'#'">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-teal-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    </Link>
                                    <Link class="text-blue-900 whitespace-no-wrap" :href="'#'">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    </Link>
                                    <button v-if="auth.user.role_id === 1" type="button"
                                        class="text-blue-900 whitespace-no-wrap">
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
                                </select>
                                <InputError :message="createFolderForm.errors.archive_type" />
                            </div>
                        </div>


                        <div >
                            <InputLabel>
                                Tipo de Archivos
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




    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Link, Head, router, useForm } from '@inertiajs/vue3';
import { nextTick, ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';


const { folders, currentPath, auth, areas } = defineProps({
    folders: Object,
    currentPath: String,
    areas: Object,
    auth: Object
})


const showAddFolderModal = ref(false)

const createFolderForm = useForm({
    name: '',
    type: 'Carpeta',
    archive_type: '',
    areas: [],
    currentPath,
    user_id: auth.user.id
})

function openAddFoldermodal() {
    showAddFolderModal.value = true
}

function closeAddFolderModal() {
    showAddFolderModal.value = false
    createFolderForm.reset()
}




function handleFolderType () {
    createFolderForm.archive_type = ''
}




function submit() {
    createFolderForm.post(route('documment.management.folders.store'))
    console.log(createFolderForm.data())
}








</script>
