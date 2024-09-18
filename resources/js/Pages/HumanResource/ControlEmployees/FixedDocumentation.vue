<template>

    <Head title="Documentacion Fija" />

    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header>
            Documentacion Fija
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
                    <SelectControlEmployees currentSelect="Documentacion Fija" />
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
                                Empleado
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Dni
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Licencia de Conducir
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Ficha de Registro
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Verificacion Domiciliaria
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Autorizacion de Datos de Vericom
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                DJ del Sistema Pensionario
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Recibo de Luz o Agua
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Curriculum
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Foto Digital
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Firma
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Carnet de Vacunacion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in fixedDocumentations.data ?? fixedDocumentations" :key="item.id" class="text-gray-700">
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
                                    {{ item.driver_license }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.registration_form }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.home_verification }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.vericom_data_authorization }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.dj_pension_system }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.electricity_water_receipt }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.curriculum }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.digital_photo }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.signature }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                <p class="text-gray-900 text-center">
                                    {{ item.vaccination_card }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
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
            <div v-if="fixedDocumentations.data"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="fixedDocumentations.links" />
            </div>
        </div>

        <Modal :show="showAddEditModal" @close="closeAddAssignationModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ form.id ? 'Editar Asignación' : 'Nueva Asignación' }}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="">
                            <InputLabel for="employees">Empleado</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.employees" autocomplete="off" id="employees"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.employees" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="driver_license">Licencia de Conducir</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.driver_license" autocomplete="off"
                                    id="driver_license"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.driver_license" />
                            </div>
                        </div>

                        <div class="">
                            <InputLabel for="registration_form">Ficha de Registro</InputLabel>
                            <div class="mt-2">
                                <select id="registration_form" v-model="form.registration_form" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>Proceso</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.registration_form" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="home_verification">Verificacion Domiciliaria</InputLabel>
                            <div class="mt-2">
                                <select id="home_verification" v-model="form.home_verification" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>Proceso</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.home_verification" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="vericom_data_authorization">Autorizacion de Datos de Vericom</InputLabel>
                            <div class="mt-2">
                                <select id="vericom_data_authorization" v-model="form.vericom_data_authorization" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>Proceso</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.vericom_data_authorization" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="dj_pension_system">DJ del Sistema Pensionario</InputLabel>
                            <div class="mt-2">
                                <select id="dj_pension_system" v-model="form.dj_pension_system" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>Proceso</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.dj_pension_system" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="electricity_water_receipt">Recibo de Luz o Agua</InputLabel>
                            <div class="mt-2">
                                <select id="electricity_water_receipt" v-model="form.electricity_water_receipt" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.electricity_water_receipt" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="curriculum">Curriculum</InputLabel>
                            <div class="mt-2">
                                <select id="curriculum" v-model="form.curriculum" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.curriculum" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="digital_photo">Foto Digital</InputLabel>
                            <div class="mt-2">
                                <select id="digital_photo" v-model="form.digital_photo" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.digital_photo" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="signature">Firma</InputLabel>
                            <div class="mt-2">
                                <select id="signature" v-model="form.signature" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.signature" />
                            </div>
                        </div>
                        <div class="">
                            <InputLabel for="vaccination_card">Carnet de Vacunacion</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.vaccination_card" autocomplete="off" id="vaccination_card"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.vaccination_card" />
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeAddAssignationModal"> Cancelar </SecondaryButton>
                        <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
        <SuccessOperationModal :confirming="confirmAssignation" :title="'Nuevo Control de Empleado creada'"
            :message="'La Asignacion fue creada con éxito'" />
        <SuccessOperationModal :confirming="confirmUpdateAssignation" :title="'Documentacion Fija Actualizada'"
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
import TextInput from '@/Components/TextInput.vue';

const { fixedDocumentation, auth } = defineProps({
    fixedDocumentation: Object,
    auth: Object
})

const fixedDocumentations = ref(fixedDocumentation);

const initialState = {
    id: null,
    employees: '',
    dni: '',
    driver_license: '',
    registration_form: '',
    home_verification: '',
    vericom_data_authorization: '',
    dj_pension_system: '',
    electricity_water_receipt: '',
    curriculum: '',
    digital_photo: '',
    signature: '',
    vaccination_card: '',
}

const form = useForm(
    { ...initialState }
);

const showAddEditModal = ref(false);
const confirmAssignation = ref(false);
function openModal() {
    showAddEditModal.value = true
}
function closeAddAssignationModal() {
    showAddEditModal.value = false
    form.defaults({ ...initialState })
    form.reset()
}
function submitStore() {
    let url = route('controlEmployees.fixed.documentation.store');
    form.put(url, {
        onSuccess: () => {
            closeAddAssignationModal()
            confirmAssignation.value = true
            setTimeout(() => {
                confirmAssignation.value = false
                router.get(route('controlEmployees.fixed.documentation.index'))
            }, 1500)
        },
        onError: (e) => {
            console.error(e)
        }
    })
}

const confirmUpdateAssignation = ref(false);

function openEditSotModal(item) {
    form.defaults({ ...item })
    form.reset()
    showAddEditModal.value = true
}

function submitUpdate() {
    let url = route('controlEmployees.fixed.documentation.update', { cicsa_assignation_id: form.id })
    form.put(url, {
        onSuccess: () => {
            closeAddAssignationModal()
            confirmUpdateAssignation.value = true
            setTimeout(() => {
                confirmUpdateAssignation.value = false
                router.get(route('controlEmployees.fixed.documentation.index'))
            }, 1500)
        },
        onError: (e) => {
            console.log(e)
        }
    })
}

function submit() {
    form.id ? submitUpdate() : submitStore()
}

const search = async ($search) => {
    try {
        const response = await axios.post(route('controlEmployees.fixed.documentation.index'), { searchQuery: $search });
        fixedDocumentations.value = response.data.fixedDocumentation;

    } catch (error) {
        console.error('Error searching:', error);
    }
};
</script>
