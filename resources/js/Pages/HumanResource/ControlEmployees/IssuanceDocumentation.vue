<template>

    <Head title="Emision de Documentacion" />

    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header>
            Emision de Documentacion
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-between">
                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <PrimaryButton @click="openModal" type="button">
                        + Agregar
                    </PrimaryButton>
                </div>

                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <TextInput type="text" @input="search($event.target.value)" placeholder="Nombre,DNI" />
                    <SelectControlEmployees currentSelect="Control de Emision de Documentacion" />
                </div>

            </div>
            <br>
            <div class="overflow-x-auto h-[70vh]">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="sticky top-0 z-20 border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Personal
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Dni
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Sctr
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Poliza
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Beneficiario Poliza
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha EMO-Anexo 16
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha EMO-Anexo 16 A
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Primeros Auxilios
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Riesgo Electrico
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Comite de Seguridad
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Trabajos de Altura
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Claro
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Ccip
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Vericom Anual
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Vericom
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha de Entrega de Epps
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in issuanceDocumentations.data ?? issuanceDocumentations" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.employees }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.dni }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.sctr) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.policy_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.policy_beneficiary }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.emo_anexo_16) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.emo_anexo_16_a) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.first_aid) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.electrical_risk) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.safety_committee) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.height_work) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.claro }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.ccip }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.vericom_annual) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ formattedDate(item.vericom) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.epps_delivery }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <div class="flex space-x-3 justify-center">
                                    <button class="text-blue-900" @click="openEditModal(item)">
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
            <div v-if="issuanceDocumentations.data"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="issuanceDocumentations.links" />
            </div>
        </div>

        <Modal :show="showModal" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ form.id ? 'Editar Emision de Documentacion' : 'Nueva Emision de Documentacion' }}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="">
                            <InputLabel for="sctr">Sctr</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.sctr" autocomplete="off" id="sctr"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.sctr" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="policy_date">Fecha de Poliza</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.policy_date" autocomplete="off"
                                    id="policy_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.policy_date" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel for="policy_beneficiary">Beneficiario de Poliza</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.policy_beneficiary" autocomplete="off" id="policy_beneficiary"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.policy_beneficiary" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="emo_anexo_16">Fecha EMO-Anexo 16</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.emo_anexo_16" autocomplete="off" id="emo_anexo_16"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.emo_anexo_16" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="emo_anexo_16_a">Fecha EMO-Anexo 16 A</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.emo_anexo_16_a" autocomplete="off" id="emo_anexo_16_a"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.emo_anexo_16_a" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="first_aid">Fecha de Primeros Auxilios</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.first_aid" autocomplete="off" id="first_aid"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.first_aid" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="electrical_risk">Fecha de Riesgo Electrico</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.electrical_risk" autocomplete="off"
                                    id="electrical_risk"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.electrical_risk" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="safety_committee">Fecha de Comite de Seguridad</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.safety_committee" autocomplete="off"
                                    id="safety_committee"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.safety_committee" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="height_work">Fecha de Trabajos de Altura</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.height_work" autocomplete="off"
                                    id="height_work"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.height_work" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="claro">Claro</InputLabel>
                            <div class="mt-2">
                                <select id="claro" v-model="form.claro" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>No Corresponde</option>
                                    <option>Proceso</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.claro" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="ccip">Ccip</InputLabel>
                            <div class="mt-2">
                                <select id="ccip" v-model="form.ccip" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>No Corresponde</option>
                                    <option>Proceso</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.ccip" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="vericom_annual">Fecha de Vericom Anual</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.vericom_annual" autocomplete="off"
                                    id="vericom_annual"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.vericom_annual" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="vericom">Fecha de Vericom</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.vericom" autocomplete="off"
                                    id="vericom"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.vericom" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="epps_delivery">Fecha de Entrega de Epps</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.epps_delivery" autocomplete="off"
                                    id="epps_delivery"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.epps_delivery" />
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeModal"> Cancelar </SecondaryButton>
                        <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
        <SuccessOperationModal :confirming="confirmUpdateAssignation" :title="'Asignacion Actualizada'"
            :message="'La Asignacion fue actualizada'" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectControlEmployees from '@/Components/SelectControlEmployees.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import { formattedDate } from '@/utils/utils.js';
import TextInput from '@/Components/TextInput.vue';

const { issuanceDocumentation, auth } = defineProps({
    issuanceDocumentation: Object,
    auth: Object
})

const issuanceDocumentations = ref(issuanceDocumentation);

const initialState = {
    id: null,
    sctr: '',
    policy_date: '',
    policy_beneficiary: '',
    emo_anexo_16: '',
    emo_anexo_16_a: '',
    first_aid: '',
    electrical_risk: '',
    safety_committee: '',
    height_work: '',
    claro: '',
    ccip: '',
    vericom_annual: '',
    vericom: '',
    epps_delivery: '',
}

const form = useForm(
    { ...initialState }
);

const showModal = ref(false);

function closeModal() {
    showModal.value = false
    form.defaults({ ...initialState })
    form.reset()
}

const confirmUpdateAssignation = ref(false);

function openModal(item) {
    form.defaults({ ...item })
    form.reset()
    showModal.value = true
}

function submit() {
    let url = route('controlEmployees.issuance.documentation.storeOrUpdate', { cicsa_assignation_id: form.id })
    form.put(url, {
        onSuccess: () => {
            closeModal()
            confirmUpdateAssignation.value = true
            setTimeout(() => {
                confirmUpdateAssignation.value = false
                router.get(route('controlEmployees.issuance.documentation.index'))
            }, 1500)
        },
        onError: (e) => {
            console.log(e)
        }
    })
}

const search = async ($search) => {
    try {
        const response = await axios.post(route('controlEmployees.issuance.documentation.index'), { searchQuery: $search });
        issuanceDocumentations.value = response.data.issuanceDocumentation;

    } catch (error) {
        console.error('Error searching:', error);
    }
};
</script>
