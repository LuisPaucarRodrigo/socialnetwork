<template>

    <Head title="Sot Liquidación" />

    <AuthenticatedLayout :redirectRoute="'socialnetwork.sot'">
        <template #header>
            Área de Liquidación
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-between">
                <PrimaryButton @click="openAddSotLiquidationModal" type="button">
                    + Agregar
                </PrimaryButton>
                <SelectSNSotComponent currentSelect="Área de Liquidación" />
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
                                Subir Actas
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Liquidación
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Descargo de almacén
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Liquidación
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Estado de SOT
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Observaciones
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Monto a Facturar
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in sotsLiquidation.data" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.sot.name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.up_minutes }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.liquidation }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.down_warehouse }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.liquidation_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.sot_status }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 text-center">
                                    {{ item.observations }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap">
                                <p class="text-gray-900 text-center">
                                    S/. {{ item.bill_amount.toFixed(2) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button class="text-blue-900" @click="openEditSotLiquidationModal(item)">
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
                <pagination :links="sotsLiquidation.links" />
            </div>
        </div>

        <Modal :show="showAddEditModal" @close="closeAddSotLiquidationModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{formSot.id ? 'Editar Liquidación' : 'Nueva Liquidación'}}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div v-if="!formSot.id">
                            <InputLabel>
                                SOTS
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="formSot.sot_id"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccionar</option>
                                    <option v-for="item in sots" :key="item.id" :value="item.id">
                                        {{ item.name }}
                                    </option>

                                </select>
                                <InputError :message="formSot.errors.areas" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel>Subir Actas</InputLabel>
                            <div class="mt-2">
                                <select v-model="formSot.up_minutes"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Ok">Ok</option>
                                    <option value="Falta">Falta</option>
                                </select>
                                <InputError :message="formSot.errors.up_minutes" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel>Liquidación</InputLabel>
                            <div class="mt-2">
                                <select v-model="formSot.liquidation"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Ok">Ok</option>
                                    <option value="Falta">Falta</option>
                                </select>
                                <InputError :message="formSot.errors.liquidation" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel>Descargo de Almacén</InputLabel>
                            <div class="mt-2">
                                <select v-model="formSot.down_warehouse"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Ok">Ok</option>
                                    <option value="Falta">Falta</option>
                                </select>
                                <InputError :message="formSot.errors.down_warehouse" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel>Fecha de Liquidación</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="formSot.liquidation_date" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="formSot.errors.liquidation_date" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel>Estado de SOT</InputLabel>
                            <div class="mt-2">
                                <select v-model="formSot.sot_status"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="Aceptado">Aceptado</option>
                                    <option value="Rechazado">Rechazado</option>
                                </select>
                                <InputError :message="formSot.errors.sot_status" />
                            </div>
                        </div>

                        <div v-if="formSot.sot_status=='Rechazado'" class="">
                            <InputLabel>Observaciones</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="formSot.observations" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="formSot.errors.observations" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel>Monto a Facturar</InputLabel>
                            <div class="mt-2">
                                <input type="number" step="0.01" v-model="formSot.bill_amount" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="formSot.errors.bill_amount" />
                            </div>
                        </div>

                    </div>
                    <br>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeAddSotLiquidationModal"> Cancelar </SecondaryButton>

                        <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                            :class="{ 'opacity-25': formSot.processing }" :disabled="formSot.processing" type="submit">
                            {{formSot.id ? 'Actualizar' : 'Guardar'}}
                        </PrimaryButton>
                    </div>

                </form>
            </div>
        </Modal>
        <SuccessOperationModal :confirming="confirmSot" :title="'Nueva liquidación de SOT creada'"
            :message="'La liquidación de la SOT fue creada con éxito'" />
        <SuccessOperationModal :confirming="confirmUpdateSot" :title="'Liquidación de SOT Actualizada'"
            :message="'La liquidación de SOT fue actualizada'" />
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

const { sotsLiquidation, sots, auth } = defineProps({
    sotsLiquidation: Object,
    sots: Object,
    auth: Object
})

const initialState = {
    id: '',
    sot_id: '',
    up_minutes: '',
    liquidation: '',
    down_warehouse: '',
    liquidation_date: '',
    observations: '',
    bill_amount: '',
    sot_status: '',
}

const formSot = useForm(
    { ...initialState }
);

//Add SOT
const showAddEditModal = ref(false);
const confirmSot = ref(false);
function openAddSotLiquidationModal() {
    showAddEditModal.value = true
}
function closeAddSotLiquidationModal() {
    showAddEditModal.value = false
    formSot.defaults({...initialState})
    formSot.reset()
}
function submitStore() {
    let url = route('socialnetwork.sot.liquidation.store')
    formSot.post(url, {
        onSuccess: () => {
            closeAddSotLiquidationModal()
            confirmSot.value = true
            setTimeout(() => {
                confirmSot.value = false
            }, 1500)
        }
    })
}

//Edit SOT
const confirmUpdateSot = ref(false);
function openEditSotLiquidationModal (item) {
    formSot.defaults({...item})
    formSot.reset()
    showAddEditModal.value = true
}
function submitUpdate() {
    let url = route('socialnetwork.sot.liquidation.update', {sot_liquidation_id: formSot.id})
    formSot.put(url, {
        onSuccess: () => {
            closeAddSotLiquidationModal()
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
