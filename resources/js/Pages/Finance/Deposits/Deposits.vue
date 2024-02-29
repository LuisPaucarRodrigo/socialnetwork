<template>
    <Head title="Depositos" />
    <AuthenticatedLayout>
        <template #header>
            Depósitos
        </template>

        <div class="min-w-full rounded-lg">
            <button @click="openAddEditModal" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 mb-3">
                + Agregar
            </button>
            <button @click="openGenerateSummary" type="button"
                class="ml-3 rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 mb-3">
                Generar Resumen
            </button>
            <br>
            <div class="inline-flex items-center p-1 text-sm text-gray-800 rounded-lg bg-gray-50 dark:bg-gray-800 dark:text-gray-300"
                role="alert">
                <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                </svg>
                <span class="sr-only">Info</span>
                <div>
                    <span class="font-medium">Importante:</span> Los registros son del MES ACTUAL
                </div>
            </div>


            <div class="overflow-x-auto mt-5 ">
                <table class="w-full ">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-2 border-gray-200 bg-gray-100 px-3 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center w-3">
                                #
                            </th>
                            <th
                                class="sticky left-0 z-10 border-2 border-gray-200 bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 w-3">
                                Fecha de la operación
                            </th>
                            <th
                                class="border-2 border-gray-200 bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 w-3 whitespace-normal">
                                Cod. operación
                            </th>
                            <th
                                class="border-2 border-gray-200 bg-gray-100 px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 w-3">
                                Descripción de la operación
                            </th>
                            <th
                                class="border-2 border-gray-200 bg-gray-100 px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600 w-3">
                                Código
                            </th>
                            <th
                                class="border-2 border-gray-200 bg-gray-100 px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Denominación
                            </th>
                            <th
                                class="border-2 border-gray-200 bg-gray-100 px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Observación
                            </th>
                            <th
                                class="w-5 border-2 border-gray-200 bg-gray-100 px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Comisión
                            </th>
                            <th
                                class="w-5 border-2 border-gray-200 bg-gray-100 px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Debe
                            </th>
                            <th
                                class="w-5 border-2 border-gray-200 bg-gray-100 px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Haber
                            </th>
                            <th v-if="auth.user.role_id === 1"
                                class="w-4 border-2 border-gray-200 bg-gray-100 px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(item, i) in deposits" :key="item.id" class="text-gray-700">
                            <td class="text-center border border-gray-200 bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900">{{ i + 1 }}</p>
                            </td>
                            <td class="text-center sticky left-0 z-10 border border-gray-200  bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900 ">{{ formattedDate(item.operation_date) }}</p>
                            </td>
                            <td class="text-center border border-gray-200 bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900 ">{{ item.operation_code }}</p>
                            </td>
                            <td class="text-center border border-gray-200 bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900">{{ item.operation_description }}</p>
                            </td>
                            <td class="text-center border border-gray-200 bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900">{{ item.accounting_account?.code }}</p>
                            </td>
                            <td class="border border-gray-200 bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900">{{ item.denomination }}</p>
                            </td>
                            <td class="border border-gray-200 bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900">{{ item.observation }}</p>
                            </td>
                            <td class="text-right border border-gray-200 bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900">{{ item.comission && (item.comission).toFixed(2) }}</p>
                            </td>
                            <td class="text-right border border-gray-200 bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900">{{ item.total_type == 'Debe' ? (item.total).toFixed(2) : '' }}</p>
                            </td>
                            <td class="text-right border border-gray-200 bg-white px-3 py-2 text-sm">
                                <p class="text-gray-900">{{ item.total_type == 'Haber' ? (item.total).toFixed(2) : '' }}</p>
                            </td>

                            <td v-if="auth.user.role_id === 1" class="border border-gray-200 bg-white px-3 py-2 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button type="button" @click="openAddEditModal(item)">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
                                        </svg>
                                    </button>
                                    <button>
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Modal :show="generateSummary">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                Generar Resumen
                </h2>
                <form @submit.prevent="submitSummary">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">

                        <div>
                            <InputLabel for="codes" class="text-gray-700">Seleccionar Código:</InputLabel>
                            <select v-model="summaryForm.code" id="codes" class="border rounded-md px-3 py-2 mb-3 w-full">
                                <option value="">Todos</option>
                                <option v-for="code in accounts" :key="code.id" :value="code.id">{{ code.code }}</option>
                            </select>
                            <InputError :message="summaryForm.errors.section_id" />
                        </div>

                        <div class="grid sm:grid-cols-2 sm:gap-x-6">
                            <div>
                                <InputLabel for="startDate" class="text-gray-700">Fecha de inicio:</InputLabel>
                                <input type="date" v-model="summaryForm.start_date" id="startDate"
                                    class="border rounded-md px-3 py-2 mt-1 w-full">
                                <InputError :message="summaryForm.errors.start_date" />
                            </div>
                            <div>
                                <InputLabel for="endDate" class="text-gray-700">Fecha de fin:</InputLabel>
                                <input type="date" v-model="summaryForm.end_date" id="endDate"
                                    class="border rounded-md px-3 py-2 mt-1 w-full">
                                <InputError :message="summaryForm.errors.end_date" />
                            </div>
                        </div>

                        <div class="mt-3">
                            <InputLabel for="headers" class="font-medium leading-6 text-gray-900">Columnas a mostrar:
                            </InputLabel>
                            <select multiple v-model="summaryForm.columns" id="headers" size="8"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 mt-3">
                            <option disabled>
                                Selecciona una o varias
                            </option>
                            <option v-for="(column, index) in columns" :key="index" :value="column">
                                {{ column }}
                            </option>
                            </select>
                            <InputError :message="summaryForm.errors.columns" />
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeGenerateSummary"> Cancel </SecondaryButton>
                            <button type="submit" :class="{ 'opacity-25': form.processing }"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                        </div>
                    </div>
                </div>
                </form>
            </div>
        </Modal>

        <Modal :show="showAddModal">
            <form @submit.prevent="submitDeposit" class="p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-5">
                    {{ itemToModify.id ? 'Editando depósito' : 'Nuevo depósito'}}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5 mb-2">
                    <div>
                        <InputLabel for="operation_date" class="font-medium leading-6 text-gray-900">
                            Fecha de operación
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput id="operation_date" v-model="form.operation_date" type="date"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 " />
                            <InputError class="mt-2" :message="form.errors.operation_date" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="operation_code" class="font-medium leading-6 text-gray-900">
                            Código de operación
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput id="operation_code" min:="0" type="number" v-model="form.operation_code"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 " />
                            <InputError class="mt-2" :message="form.errors.operation_code" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="operation_description" class="font-medium leading-6 text-gray-900">
                            Descripción de la operación
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput id="operation_description" v-model="form.operation_description"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 " />
                            <InputError class="mt-2" :message="form.errors.operation_description" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="accounting_account_id" class="font-medium leading-6 text-gray-900">
                            Código
                        </InputLabel>
                        <div class="mt-2">
                            <select id="accounting_account_id" v-model="form.accounting_account_id"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 ">
                                <option disabled value="">Seleccione uno</option>
                                <option v-for="item in accounts" :key="item.id" :value="item.id">{{ item.code }}</option>
                            </select>
                            <InputError class="mt-2" :message="form.errors.accounting_account_id" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="denomination" class="font-medium leading-6 text-gray-900">
                            Denominación
                        </InputLabel>
                        <div class="mt-2">
                            <textarea id="denomination" v-model="form.denomination"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 " />
                            <InputError class="mt-2" :message="form.errors.denomination" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="observation" class="font-medium leading-6 text-gray-900">
                            Observacion
                        </InputLabel>
                        <div class="mt-2">
                            <textarea id="observation" v-model="form.observation"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 " />
                            <InputError class="mt-2" :message="form.errors.observation" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="comission" class="font-medium leading-6 text-gray-900">
                            Comisión
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput id="comission" v-model="form.comission" min="0" step="0.01" type="number"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 " />
                            <InputError class="mt-2" :message="form.errors.comission" />
                        </div>
                    </div>


                    <div>

                        <select id="total_type" v-model="form.total_type"
                            class="block rounded-md border-0 py-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 ">
                            <option>Debe</option>
                            <option>Haber</option>
                        </select>

                        <div class="mt-2">
                            <TextInput id="total" v-model="form.total" min="0" step="0.01" type="number"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 " />
                            <InputError class="mt-2" :message="form.errors.total" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <button
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-red-500 text-white hover:bg-red-400"
                        type="button" @click="closeAddModal"> Cerrar </button>
                    <button
                        class="inline-flex items-center p-2 rounded-md font-semibold bg-indigo-500 text-white hover:bg-indigo-400"
                        type="submit"> Agregar </button>
                </div>
            </form>
        </Modal>
        <SuccessOperationModal :confirming="isOprationSuccess" :title="'Nuevo Depósito añadido'"
            :message="'Despósito creado con éxito'" />


    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import { formattedDate } from '@/utils/utils'

const { deposits, accounts } = defineProps({
    deposits: Object,
    accounts: Object,
    auth: Object
})

const initialState = {
    operation_date: '',
    operation_code: '',
    operation_description: '',
    denomination: '',
    observation: '',
    comission: '',
    total_type: 'Haber',
    total: '',
    accounting_account_id: '',
}
const form = useForm({ ...initialState })

const columns = {
    1: 'Fecha de Operación',
    2: 'Cod. Operación',
    3: 'Descripción de la operación',
    4: 'Código',
    5: 'Denominación',
    6: 'Observación',
    7: 'Comisión',
}

const summaryForm = useForm({
    code: '',
    start_date: '',
    end_date: '',
    columns: []
});

const showAddModal = ref(false)
const generateSummary = ref(false)
const isOprationSuccess = ref(false)
const itemToModify = ref(null)

const openAddEditModal = (item=null) => { 
    if(item){ 
        itemToModify.value = item 
        form.defaults({...item})
        form.reset()
    }
    showAddModal.value = true 
}

const closeAddModal = () => {
    itemToModify.value = null
    form.defaults({...initialState})
    form.reset()
    showAddModal.value = false 
}

const openGenerateSummary = () => {
    generateSummary.value = true;
};

const closeGenerateSummary = () => {
    generateSummary.value = false;
};

const submitSummary = () => {
    summaryForm.post(route('deposits.generateSummary'), {
        onSuccess: (response) => {
            // Manejar la respuesta exitosa
        },
        onError: (error) => {
            // Manejar errores
            console.error('Ha ocurrido un error:', error);
        },
        onFinish: () => {
            // Restablecer el formulario
            summaryForm.reset();
        }
    });
}

function submitDeposit() {
    form.post(route('deposits.store', {deposit_id: itemToModify?.id}), {
        onSuccess: () => {
            isOprationSuccess.value = true
            setTimeout(() => {
                isOprationSuccess.value = false
            }, 1500)
            showAddModal.value = false
        }
    })
}

//Update Modal






</script>