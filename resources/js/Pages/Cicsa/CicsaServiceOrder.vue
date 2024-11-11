<template>

    <Head title="CICSA Orden de Servicio" />

    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header>
            Orden de Servicio
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-between">
                <a :href="route('cicsa.service_orders.export') + '?' + uniqueParam"
                    class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">Exportar</a>
                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <TextInput data-tooltip-target="search_fields" type="text" @input="search($event.target.value)" placeholder="Buscar ..." />
                    <SelectCicsaComponent currentSelect="Orden de Servicio" />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Nombre,Cod,CPE,OC,Observaciones
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <br>
            <div class="overflow-x-auto h-[70vh]">
                <table class="w-full ">
                    <thead>
                        <tr
                            class="sticky top-0 z-20 border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th colspan="3"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre de Proyecto
                            </th>
                            <th colspan="2"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Codigo de Proyecto
                            </th>
                            <th colspan="2"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Centro de Costos
                            </th>
                            <th colspan="2"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                CPE
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="item in service_orders.data ?? service_orders" :key="item.id">
                            <tr class="text-gray-700">
                                <td colspan="3" class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                    <p class="text-gray-900 text-center">
                                        {{ item.project_name }}
                                    </p>
                                </td>
                                <td colspan="2" class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                    <p class="text-gray-900 text-center">
                                        {{ item.project_code }}
                                    </p>
                                </td>
                                <td colspan="2" class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                    <p class="text-gray-900 text-center">
                                        {{ item.cost_center }}
                                    </p>
                                </td>
                                <td colspan="2" class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                    <p class="text-gray-900 text-center">
                                        {{ item.cpe }}
                                    </p>
                                </td>
                                <td colspan="2" class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                    <div class="flex space-x-3 justify-center">
                                        <button v-if="item.cicsa_service_order.length > 0" type="button"
                                            @click="toggleDetails(item?.cicsa_service_order)"
                                            class="text-blue-900 whitespace-no-wrap">
                                            <svg v-if="service_order_row !== item.id" xmlns="http://www.w3.org/2000/svg"
                                                fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                            </svg>
                                            <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <template v-if="service_order_row == item.id">
                                <tr
                                    class="border-b bg-red-500 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Numero de OC
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Fecha de Orden de Servicio
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Orden de Servicio
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Doc OS
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Hoja de Estimación
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Orden de Compra
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Factura en PDF
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Factura en ZIP
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Doc Fac
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Encargado
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-2 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Acciones
                                    </th>
                                </tr>
                                <tr v-for="materialDetail in item.cicsa_service_order" :key="materialDetail.id"
                                    class="bg-gray-100">
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail.cicsa_purchase_order?.oc_number }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ formattedDate(materialDetail?.service_order_date) }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.service_order }}
                                        </p>
                                    </td>
                                    <td class="border-b text-center border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <button v-if="materialDetail?.document" type="button"
                                            @click="openPDF(materialDetail?.id)">
                                            <EyeIcon class="w-5 h-5 text-green-600" />
                                        </button>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.estimate_sheet }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.purchase_order }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.pdf_invoice }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.zip_invoice }}
                                        </p>
                                    </td>
                                    <td class="border-b text-center border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <!-- <button v-if="materialDetail?.document" type="button"
                                            @click="openPDF(materialDetail?.id)">
                                            <EyeIcon class="w-5 h-5 text-green-600" />
                                        </button> -->
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.user_name }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px] text-center">
                                        <button class="text-blue-900" @click="openEditModal(materialDetail)">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-amber-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </template>
                        </template>
                    </tbody>
                </table>
            </div>

            <div v-if="service_orders.data"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="service_orders.links" />
            </div>
        </div>

        <Modal :show="showAddEditModal" @close="closeAddAssignationModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ form.id ? 'Editar Orden de Servicio' : 'Nueva Orden de Servicio' }} {{ oc_number ? ": " +
                        oc_number : ""
                    }}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <InputLabel for="service_order_date">Fecha de Orden de Servicio</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.service_order_date" autocomplete="off"
                                    id="service_order_date" />
                                <InputError :message="form.errors.service_order_date" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="service_order">Orden de Servicio</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.service_order" id="service_order"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En Proceso">En Proceso</option>
                                    <option value="Completado">Completado</option>
                                </select>
                                <InputError :message="form.errors.service_order" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="document">Documento OS</InputLabel>
                            <div>
                                <InputFile type="file" v-model="form.document" id="document" accept=".pdf" />
                                <InputError :message="form.errors.document" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="estimate_sheet">Hoja de Estimación</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.estimate_sheet" id="estimate_sheet"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En Proceso">En Proceso</option>
                                    <option value="Completado">Completado</option>
                                </select>
                                <InputError :message="form.errors.estimate_sheet" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="purchase_order">Orden de Compra</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.purchase_order" id="purchase_order"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En Proceso">En Proceso</option>
                                    <option value="Completado">Completado</option>
                                </select>
                                <InputError :message="form.errors.purchase_order" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="pdf_invoice">Factura en PDF</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.pdf_invoice" id="pdf_invoice"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En Proceso">En Proceso</option>
                                    <option value="Completado">Completado</option>
                                </select>
                                <InputError :message="form.errors.pdf_invoice" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="zip_invoice">Factura en ZIP</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.zip_invoice" id="zip_invoice"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En Proceso">En Proceso</option>
                                    <option value="Completado">Completado</option>
                                </select>
                                <InputError :message="form.errors.zip_invoice" />
                            </div>
                        </div>
                        <!-- <div class="sm:col-span-1">
                            <InputLabel for="document">Documento Fac</InputLabel>
                            <div>
                                <InputFile type="file" v-model="form.documentFac" id="document" accept=".pdf" />
                                <InputError :message="form.errors.documentFac" />
                            </div>
                        </div> -->
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

        <SuccessOperationModal :confirming="confirmAssignation" :title="'Nueva Orden de Servicio creada'"
            :message="'La Orden de Servicio fue creada con éxito'" />
        <SuccessOperationModal :confirming="confirmUpdateAssignation" :title="'Orden de Servicio Actualizada'"
            :message="'La Orden de Servicio fue actualizada'" />
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
import SelectCicsaComponent from '@/Components/SelectCicsaComponent.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import { formattedDate, setAxiosErrors, toFormData } from '@/utils/utils.js';
import TextInput from '@/Components/TextInput.vue';
import InputFile from '@/Components/InputFile.vue';
import { EyeIcon } from '@heroicons/vue/24/outline';

const { service_order, auth } = defineProps({
    service_order: Object,
    auth: Object
})

const uniqueParam = ref(`timestamp=${new Date().getTime()}`);
const service_orders = ref(service_order)
const oc_number = ref(null)
const service_order_row = ref(0)

const initialState = {
    id: null,
    user_id: '',
    service_order_date: '',
    service_order: 'Pendiente',
    estimate_sheet: 'Pendiente',
    purchase_order: 'Pendiente',
    pdf_invoice: 'Pendiente',
    zip_invoice: 'Pendiente',
    document: '',
    documentFac: '',
    user_name: '',
    cicsa_assignation_id: '',
    cicsa_purchase_order_id: '',
}

const form = useForm(
    { ...initialState }
);


const showAddEditModal = ref(false);
const confirmAssignation = ref(false);

function closeAddAssignationModal() {
    showAddEditModal.value = false
    form.clearErrors()
    form.defaults({ ...initialState })
    form.reset()
}

const confirmUpdateAssignation = ref(false);

function openEditModal(item) {
    oc_number.value = item.cicsa_purchase_order.oc_number
    form.defaults({ ...item, user_name: auth.user.name, user_id: auth.user.id })
    form.reset()
    showAddEditModal.value = true
}

async function submit() {
    let url = route('cicsa.service_orders.update', { cicsa_service_order_id: form.id });
    try {
        let formData = toFormData(form)
        const response = await axios.post(url, formData)
        updateServiceOrder(response.data)
        closeAddAssignationModal()
        confirmUpdateAssignation.value = true
        setTimeout(() => {
            confirmUpdateAssignation.value = false
        }, 1500)
    } catch (error) {
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form)
            } else {
                console.error("Server error:", error.response.data)
            }
        } else {
            console.error("Network or other error:", error)
        }
    }
}

const search = async ($search) => {
    try {
        const response = await axios.post(route('cicsa.service_orders'), { searchQuery: $search });
        service_orders.value = response.data.service_order;
    } catch (error) {
        console.error('Error searching:', error);
    }
};

const toggleDetails = (cicsa_service_order) => {
    if (service_order_row.value === cicsa_service_order[0].cicsa_assignation_id) {
        service_order_row.value = 0;
    } else {
        service_order_row.value = cicsa_service_order[0].cicsa_assignation_id;
    }
}

async function openPDF(serviceOrderId) {
    if (serviceOrderId) {
        const url = route('cicsa.service_orders.showDocument', { serviceOrder: serviceOrderId });
        await axios.get(url)
            .then(response => {
                const imageUrl = response.data.url;
                window.open(imageUrl, '_blank');
            })
            .catch(error => {
                console.error('Error fetching image URL:', error);
            });
    } else {
        console.error('No se proporcionó un ID de imagen válido');
    }
}

function updateServiceOrder(serviceOrder) {
    const validations = service_orders.value.data || service_orders.value;
    const index = validations.findIndex(item => item.id === serviceOrder.cicsa_assignation_id)
    const indexServiceOrder = validations[index].cicsa_service_order.findIndex(item => item.id === serviceOrder.id)
    validations[index].cicsa_service_order[indexServiceOrder] = serviceOrder
}
</script>