<template>

    <Head title="Sot Programación" />

    <AuthenticatedLayout :redirectRoute="'socialnetwork.sot'">
        <template #header>
            Área de Programación
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-between">
                <PrimaryButton @click="openAddSotModal" type="button">
                    + Agregar
                </PrimaryButton>
                <SelectSNSotComponent currentSelect="Área de Programación" />
            </div>
            <br>
            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                SOT
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Desripción
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Asignación
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cliente
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Plano del Cliente
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Teléfono del Cliente
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Dirección del Cliente
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Distrito del Cliente
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Referencia del Cliente
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in sots.data" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.description }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.assigned_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.customer }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.customer_flat }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.customer_phone }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.customer_address }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.customer_district }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.customer_ref }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">


                                    <button class="text-blue-900" @click="openEditSotModal(item)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-amber-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="sots.links" />
            </div>
        </div>

        <Modal :show="showAddEditModal" @close="closeAddSotModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{formSot.id ? 'Editar SOT' : 'Nueva SOT'}}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="">
                            <InputLabel>SOT</InputLabel>
                            <div class="mt-2">
                                <input type="number" v-model="formSot.name" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="formSot.errors.name" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel>Fecha de Asignación</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="formSot.assigned_date" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="formSot.errors.assigned_date" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel>Cliente</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="formSot.customer" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                <InputError :message="formSot.errors.customer" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel>Plano de Cliente</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="formSot.customer_flat" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                <InputError :message="formSot.errors.customer_flat" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel>Teléfono del Cliente</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="formSot.customer_phone" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                <InputError :message="formSot.errors.customer_phone" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel>Dirección del Cliente</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="formSot.customer_address" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                <InputError :message="formSot.errors.customer_address" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel>Distrito del Cliente</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="formSot.customer_district" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                <InputError :message="formSot.errors.customer_district" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel>Referencia del Cliente</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="formSot.customer_ref" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"/>
                                <InputError :message="formSot.errors.customer_ref" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel>Descripción</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="formSot.description" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                <InputError :message="formSot.errors.description" />
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeAddSotModal"> Cancelar </SecondaryButton>

                        <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                            :class="{ 'opacity-25': formSot.processing }" :disabled="formSot.processing" type="submit">
                            Guardar
                        </PrimaryButton>
                    </div>

                </form>
            </div>
        </Modal>
        <SuccessOperationModal :confirming="confirmSot" :title="'Nueva SOT creada'"
            :message="'La SOT fue creada con éxito'" />
        <SuccessOperationModal :confirming="confirmUpdateSot" :title="'SOT Actualizada'"
            :message="'La SOT fue actualizada'" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectSNSotComponent from '@/Components/SelectSNSotComponent.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import {formattedDate} from '@/utils/utils.js';

const { sots, customers, auth } = defineProps({
    sots: Object,
    auth: Object
})


const initialState = {
    id: '',
    user_owner_id: auth.user.id,
    customer: '',
    customer_flat: '',
    customer_phone: '',
    customer_address: '',
    customer_district: '',
    customer_ref: '',
    name: '',
    description: '',
    assigned_date: ''
}
const formSot = useForm(
    { ...initialState }
);


//Add SOT
const showAddEditModal = ref(false);
const confirmSot = ref(false);
function openAddSotModal() {
    showAddEditModal.value = true
}
function closeAddSotModal() {
    showAddEditModal.value = false
    formSot.defaults({...initialState})
    formSot.reset()
}
function submitStore() {
    let url = route('socialnetwork.sot.programation.store')
    formSot.post(url, {
        onSuccess: () => {
            closeAddSotModal()
            confirmSot.value = true
            setTimeout(() => {
                confirmSot.value = false
            }, 1500)
        },
        onError: (e) => {
            console.error(e)
        }
    })
}

//Edit SOT
const confirmUpdateSot = ref(false);
function openEditSotModal (item) {
    formSot.defaults({...item})
    formSot.reset()
    showAddEditModal.value = true
}
function submitUpdate() {
    let url = route('socialnetwork.sot.programation.update', {sot_id: formSot.id})
    formSot.post(url, {
        onSuccess: () => {
            closeAddSotModal()
            confirmUpdateSot.value = true
            setTimeout(() => {
                confirmUpdateSot.value = false
            }, 1500)
        }
    })
}

//Submit
function submit() {
    formSot.id ? submitUpdate() : submitStore()
}

</script>
