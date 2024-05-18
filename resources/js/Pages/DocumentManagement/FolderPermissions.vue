<template>

    <Head title="Permisos" />

    <AuthenticatedLayout>
        <template #header>
            Permisos de carpeta {{ folder.name }}
        </template>
        <div class="min-w-full rounded-lg shadow">
            <br>
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
                                    <input type="checkbox" :checked="item.see_download"
                                        @input="openPermissionhandleModal($event.target.checked, item.id, modalOptions.see_download)"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-5 w-5 ring-inset placeholder:text-gray-400 sm:text-sm sm:leading-6" />
                                </div>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex justify-center">
                                    <input type="checkbox" :checked="item.create"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-5 w-5 ring-inset placeholder:text-gray-400 sm:text-sm sm:leading-6" />
                                </div>
                            </td>


                        </tr>
                    </tbody>
                </table>
            </div>

            <Modal :show="showPermissionHandler" @close="closePermissionHandlerModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Una vez que se elimine la cuenta, todos sus recursos y datos se eliminarán permanentemente. Por favor
                    ingrese su contraseña para confirmar que desea eliminar permanentemente la cuenta.
                </p>

                <div class="mt-6">
                    <InputLabel for="password" value="Password" class="sr-only" />
                    <TextInput id="password" ref="passwordInput" v-model="form.password" type="password"
                        class="mt-1 block w-3/4" placeholder="Password" @keyup.enter="deleteUser" />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>

                    <DangerButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        @click="deleteUser">
                        Delete Account
                    </DangerButton>
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
})

function see_download_handler (state, id ) {
    router.post(route('documment.management.folders.permission.see_download', {folder_area_id: id}), {state}, {
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
    title:'',
    message: '',
    option: '',
    postItem: {
        folder_area_id:'',
        state:'',
        down_recursive:false
    }
}

const modalItem = ref(JSON.parse(JSON.stringify(initialModalItem))) 


function openPermissionhandleModal (state, id, option) {
    if(state && option === modalOptions.see_download){
        modalItem.value = {
            title: 'Activar permiso de ver y descargar',
            message: 'Al realizar esta acción activará el permiso de ver y descargar de la actual carpeta y de las carpetas superiores a estas',
            option: option,
            postItem:{
                folder_area_id:id,
                state: state,
                down_recursive:false
            }
        }
        showPermissionHandler.value = true
    }
}



</script>
