<template>

    <Head title="CICSA Orden de Servicio" />

    <AuthenticatedLayout :redirectRoute="{ route: 'cicsa.index', params: { type } }">
        <template #header>
            {{ type == 1 ? 'Pint' : 'Pext' }} - Orden de Servicio
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <div class="flex justify-between">
                <a :href="route('cicsa.service_orders.export', { type }) + '?' + uniqueParam"
                    class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">Exportar</a>
                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <TextInput data-tooltip-target="search_fields" type="text" @input="search($event.target.value)"
                        placeholder="Buscar ..." />
                    <SelectCicsaComponent currentSelect="Orden de Servicio" :type="type" />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Nombre,Cod,CPE,OC,Observaciones
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <br>
            <TableStructure>
                <template #thead>
                    <tr>
                        <TableTitle :colspan="3">Nombre de Proyecto</TableTitle>
                        <TableTitle :colspan="2">Codigo de Proyecto</TableTitle>
                        <TableTitle :colspan="2">Centro de Costos</TableTitle>
                        <TableTitle :colspan="2">CPE</TableTitle>
                        <TableTitle :colspan="2"></TableTitle>
                    </tr>
                </template>
                <template #tbody>
                    <template v-for="item in service_orders.data ?? service_orders" :key="item.id">
                        <tr>
                            <TableRow :colspan="3">{{ item.project_name }}</TableRow>
                            <TableRow :colspan="2">{{ item.project_code }}</TableRow>
                            <TableRow :colspan="2">{{ item.project?.cost_center?.name }}</TableRow>
                            <TableRow :colspan="2">{{ item.cpe }}</TableRow>
                            <TableRow :colspan="2">
                                <div class="flex space-x-3 justify-center">
                                    <button v-if="item.cicsa_service_order.length > 0" type="button"
                                        @click="toggleDetails(item?.cicsa_service_order)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <DownArrowIcon v-if="service_order_row !== item.id"/>
                                        <UpArrowIcon v-else/>
                                    </button>
                                </div>
                            </TableRow>
                        </tr>
                        <template v-if="service_order_row == item.id">
                            <tr
                                class="border-b bg-red-500 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <TableTitle :style="'bg-gray-200'">Numero de OC</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Fecha de Orden de Servicio</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Orden de Servicio</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Doc OS</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Hoja de Estimación</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Orden de Compra</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Factura en PDF</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Factura en ZIP</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Doc Fac</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Encargado</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Acciones</TableTitle>
                            </tr>
                            <tr v-for="materialDetail in item.cicsa_service_order" :key="materialDetail.id"
                                class="bg-gray-100">
                                <TableRow>{{ materialDetail.cicsa_purchase_order?.oc_number }}</TableRow>
                                <TableRow>{{ formattedDate(materialDetail?.service_order_date) }}</TableRow>
                                <TableRow>{{ materialDetail?.service_order }}</TableRow>
                                <TableRow>
                                    <button v-if="materialDetail?.document" type="button"
                                        @click="openPDF(materialDetail?.id, 'OS')">
                                        <ShowIcon />
                                    </button>
                                </TableRow>
                                <TableRow>{{ materialDetail?.estimate_sheet }}</TableRow>
                                <TableRow>{{ materialDetail?.purchase_order }}</TableRow>
                                <TableRow>{{ materialDetail?.pdf_invoice }}</TableRow>
                                <TableRow>{{ materialDetail?.zip_invoice }}</TableRow>
                                <TableRow>
                                    <button v-if="materialDetail?.document_invoice" type="button"
                                        @click="openPDF(materialDetail?.id, 'invoice')">
                                        <ShowIcon />
                                    </button>
                                </TableRow>
                                <TableRow>{{ materialDetail?.user_name }}</TableRow>
                                <TableRow>
                                    <button class="text-blue-900" @click="openEditModal(materialDetail)">
                                        <EditIcon/>
                                    </button>
                                </TableRow>
                            </tr>
                        </template>
                    </template>
                </template>
            </TableStructure>
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
                        <div class="sm:col-span-1">
                            <InputLabel for="document">Documento Fac</InputLabel>
                            <div>
                                <InputFile type="file" v-model="form.document_invoice" id="document" accept=".pdf" />
                                <InputError :message="form.errors.document_invoice" />
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

        <!-- <SuccessOperationModal :confirming="confirmAssignation" :title="'Nueva Orden de Servicio creada'"
            :message="'La Orden de Servicio fue creada con éxito'" /> -->
        <!-- <SuccessOperationModal :confirming="confirmUpdateAssignation" :title="'Orden de Servicio Actualizada'"
            :message="'La Orden de Servicio fue actualizada'" /> -->
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
import SelectCicsaComponent from '@/Components/SelectCicsaComponent.vue';
import { formattedDate, setAxiosErrors, toFormData } from '@/utils/utils.js';
import TextInput from '@/Components/TextInput.vue';
import InputFile from '@/Components/InputFile.vue';
import { Toaster } from 'vue-sonner';
import { notify, notifyError } from '@/Components/Notification';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import ShowIcon from '@/Components/Icons/ShowIcon.vue';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import DownArrowIcon from '@/Components/Icons/DownArrowIcon.vue';
import UpArrowIcon from '@/Components/Icons/UpArrowIcon.vue';

const { service_order, auth, searchCondition, type } = defineProps({
    service_order: Object,
    auth: Object,
    searchCondition: {
        type: String,
        Required: false
    },
    type: Number
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
    document_invoice: '',
    user_name: '',
    cicsa_assignation_id: '',
    cicsa_purchase_order_id: '',
}

const form = useForm(
    { ...initialState }
);


const showAddEditModal = ref(false);
// const confirmAssignation = ref(false);

function closeAddAssignationModal() {
    showAddEditModal.value = false
    form.clearErrors()
    form.defaults({ ...initialState })
    form.reset()
}

// const confirmUpdateAssignation = ref(false);

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
        // confirmUpdateAssignation.value = true
        // setTimeout(() => {
        //     confirmUpdateAssignation.value = false
        // }, 1500)
    } catch (error) {
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form)
            } else {
                notifyError("Server error:", error.response.data)
            }
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

const search = async ($search) => {
    try {
        const response = await axios.post(route('cicsa.service_orders', { type }), { searchQuery: $search });
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

async function openPDF(serviceOrderId, doc) {
    if (serviceOrderId) {
        const url = route('cicsa.service_orders.showDocument', { serviceOrder: serviceOrderId, doc: doc });
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
    notify('Actualización Exitosa')
}

if (searchCondition) {
    search(searchCondition)
}
</script>