<template>

    <Head title="CICSA Orden de Compra" />

    <AuthenticatedLayout :redirectRoute="{ route: 'cicsa.index', params: { type } }">
        <Toaster richColors />
        <template #header>
            {{ type == 1 ? 'Pint' : 'Pext' }} - Orden de Compra
        </template>
        <div class="min-w-full">
            <div class="flex justify-between">
                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <a :href="route('purchase.order.export', { type }) + '?' + uniqueParam"
                        class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">Exportar</a>
                </div>
                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <TextInput type="text" @input="search($event.target.value)" placeholder="Buscar ..." />
                    <SelectCicsaComponent currentSelect="Orden de Compra" :type="type" />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Nombre,Codigo,CPE,OC,Observaciones
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <br>
            <TableStructure>
                <template #thead>
                    <TableTitle :colspan="2">Nombre de Proyecto</TableTitle>
                    <TableTitle :colspan="2">Codigo de Proyecto</TableTitle>
                    <TableTitle :colspan="2">Centro de Costos</TableTitle>
                    <TableTitle :colspan="2">CPE</TableTitle>
                    <TableTitle>Monto Proyectado sin IGV</TableTitle>
                    <TableTitle></TableTitle>
                </template>
                <template #tbody>
                    <template v-for="item in purchaseOrders.data ?? purchaseOrders" :key="item.id">
                        <tr>
                            <TableRow :colspan="2">{{ item.project_name }}</TableRow>
                            <TableRow :colspan="2">{{ item.project_code }}</TableRow>
                            <TableRow :colspan="2">{{ item.project?.cost_center?.name }}</TableRow>
                            <TableRow :colspan="2">{{ item.cpe }}</TableRow>
                            <TableRow>{{ item.cicsa_installation?.projected_amount ? 'S/' +
                                item.cicsa_installation.projected_amount.toFixed(2) : '' }}</TableRow>
                            <TableRow>
                                <div class="flex space-x-3 justify-center">
                                    <button @click="openCreateModal(item)">
                                        <PlusCircleIcon />
                                    </button>
                                    <button v-if="item.cicsa_purchase_order.length > 0" type="button"
                                        @click="toggleDetails(item?.cicsa_purchase_order)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <DownArrowIcon v-if="purcahse_order_row !== item.id" />
                                        <UpArrowIcon v-else />
                                    </button>
                                </div>
                            </TableRow>
                        </tr>
                        <template v-if="purcahse_order_row == item.id">
                            <tr
                                class="border-b bg-red-500 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <TableTitle :style="'bg-gray-200'">Fecha de OC</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Numero de OC</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Monto sin IGV</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Formato Maestro</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Item 3456</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Presupuesto</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Documento</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Observaciones</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Encargado</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Acciones</TableTitle>
                            </tr>
                            <tr v-for="materialDetail in item.cicsa_purchase_order" :key="materialDetail.id"
                                class="bg-gray-100">
                                <TableRow>{{ formattedDate(materialDetail?.oc_date) }}</TableRow>
                                <TableRow>{{ materialDetail?.oc_number }}</TableRow>
                                <TableRow>
                                    {{ materialDetail?.amount ? 'S/ ' + materialDetail?.amount.toFixed(2) : '' }}
                                </TableRow>
                                <TableRow>{{ materialDetail?.master_format }}</TableRow>
                                <TableRow>{{ materialDetail?.item3456 }}</TableRow>
                                <TableRow>{{ materialDetail?.budget }}</TableRow>
                                <TableRow>
                                    <button v-if="materialDetail?.document" type="button"
                                        @click="openPDF(materialDetail?.id)">
                                        <ShowIcon />
                                    </button>
                                </TableRow>
                                <TableRow>{{ materialDetail?.observation }}</TableRow>
                                <TableRow>{{ materialDetail?.user_name }}</TableRow>

                                <TableRow>
                                    <button class="text-blue-900"
                                        @click="openEditModal(materialDetail, item.project_name, item.cpe)">
                                        <EditIcon />
                                    </button>
                                </TableRow>
                            </tr>
                        </template>
                    </template>
                </template>
            </TableStructure>
            <div v-if="purchaseOrders.data"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="purchaseOrders.links" />
            </div>
        </div>

        <Modal :show="showAddEditModal" @close="closeAddPuchaseOrderModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ form.id ? 'Editar Orden de Compra' : 'Nueva Orden de Compra' }} {{ ': ' + dateModal.project_name
                        + ' - '
                        + dateModal.cpe }}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <InputLabel for="oc_date">Fecha de OC</InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="form.oc_date" autocomplete="off" id="oc_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.oc_date" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="oc_number">Numero de OC</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.oc_number" autocomplete="off" id="oc_number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.oc_number" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="amount">Monto sin IGV</InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="form.amount" autocomplete="off" id="oc_namountumber"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.amount" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="master_format">Formato Maestro</InputLabel>
                            <div class="mt-2">
                                <select id="master_format" v-model="form.master_format" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>En Proceso</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.master_format" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="item3456">Item3456</InputLabel>
                            <div class="mt-2">
                                <select id="item3456" v-model="form.item3456" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>En Proceso</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.item3456" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="budget">Presupuesto</InputLabel>
                            <div class="mt-2">
                                <select id="budget" v-model="form.budget" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option>Pendiente</option>
                                    <option>En Proceso</option>
                                    <option>Completado</option>
                                </select>
                                <InputError :message="form.errors.budget" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="document">Documento</InputLabel>
                            <div>
                                <InputFile type="file" v-model="form.document" id="document" accept=".pdf" />
                                <InputError :message="form.errors.document" />
                            </div>
                        </div>
                        <div class="sm:col-span-1">
                            <InputLabel for="observation">Observaciones</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.observation" id="observation"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.observation" />
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="mt-6 flex justify-end">
                        <SecondaryButton type="button" @click="closeAddPuchaseOrderModal"> Cancelar </SecondaryButton>
                        <PrimaryButton class="ml-3 tracking-widest uppercase text-xs"
                            :class="{ 'opacity-25': form.processing }" :disabled="form.processing" type="submit">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
        <!-- <SuccessOperationModal :confirming="confirmPuchaseOrder" :title="title" :message="message" /> -->
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
import { notify, notifyError } from '@/Components/Notification';
import { Toaster } from 'vue-sonner';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import { DownArrowIcon, UpArrowIcon, PlusCircleIcon, ShowIcon, EditIcon } from '@/Components/Icons/Index';

const { purchaseOrder, auth, searchCondition, type } = defineProps({
    purchaseOrder: Object,
    auth: Object,
    searchCondition: {
        type: String,
        Required: false
    },
    type: Number
})

const uniqueParam = ref(`timestamp=${new Date().getTime()}`);
const purchaseOrders = ref(purchaseOrder)
const dateModal = ref({})

const initialState = {
    id: null,
    cicsa_assignation_id: null,
    user_id: auth.user.id,
    oc_date: '',
    oc_number: '',
    amount: '',
    master_format: 'Pendiente',
    item3456: 'Pendiente',
    budget: 'Pendiente',
    document: '',
    observation: '',
    user_name: auth.user.name,
}

const form = useForm(
    { ...initialState }
);

const showAddEditModal = ref(false);
// const confirmPuchaseOrder = ref(false);
const cicsa_purchase_order_id = ref(null)
const purcahse_order_row = ref(0);
// const title = ref('Nueva Orden de Compra creada')
// const message = ref('La Orden de Compra fue creada con éxito')

function closeAddPuchaseOrderModal() {
    showAddEditModal.value = false
    form.clearErrors()
    form.defaults({ ...initialState })
    form.reset()
}

function openCreateModal(cicsa_assignation) {
    dateModal.value = { 'project_name': cicsa_assignation.project_name, 'cpe': cicsa_assignation.cpe }
    form.defaults({ ...initialState, cicsa_assignation_id: cicsa_assignation.id })
    form.reset()
    showAddEditModal.value = true
}

function openEditModal(item, project_name, cpe) {
    dateModal.value = { 'project_name': project_name, 'cpe': cpe }
    cicsa_purchase_order_id.value = item.id;
    form.defaults({ ...item })
    form.reset()
    showAddEditModal.value = true
}

async function submit() {
    let url = cicsa_purchase_order_id.value ? route('purchaseOrder.storeOrUpdate', { cicsa_purchase_order_id: cicsa_purchase_order_id.value }) : route('purchaseOrder.storeOrUpdate')
    try {
        let formData = toFormData(form)
        const response = await axios.post(url, formData)
        closeAddPuchaseOrderModal()
        if (cicsa_purchase_order_id.value) {
            updatePurchaseOrder(false, response.data)
            // title.value = 'Orden de Compra Actualizada'
            // message.value = 'La Orden de Compra fue actualizada'
            cicsa_purchase_order_id.value = null
        } else {
            updatePurchaseOrder(true, response.data)
        }
        // confirmPuchaseOrder.value = true
        // setTimeout(() => {
        //     confirmPuchaseOrder.value = false
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
        const response = await axios.post(route('purchase.order.index', { type }), { searchQuery: $search });
        purchaseOrders.value = response.data.purchaseOrder;
    } catch (error) {
        notifyError('Error searching:', error);
    }
};

const toggleDetails = (purchase_order) => {
    if (purcahse_order_row.value === purchase_order[0].cicsa_assignation_id) {
        purcahse_order_row.value = 0;
    } else {
        purcahse_order_row.value = purchase_order[0].cicsa_assignation_id;
    }
}

async function openPDF(purchaseOrderId) {
    if (purchaseOrderId) {
        const url = route('purchase.order.showDocument', { purchaseOrder: purchaseOrderId });
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

function updatePurchaseOrder(item, purchaseOrder) {
    const validations = purchaseOrders.value.data || purchaseOrders.value;
    const index = validations.findIndex(item => item.id === Number(purchaseOrder.cicsa_assignation_id));
    if (item) {
        validations[index].cicsa_purchase_order.push(purchaseOrder)
        notify('Se creo Correctamente')
    } else {
        const indexMaterial = validations[index].cicsa_purchase_order.findIndex(item => item.id === purchaseOrder.id);
        validations[index].cicsa_purchase_order[indexMaterial] = purchaseOrder
        notify('Se Actualizo Correctamente')
    }
}

if (searchCondition) {
    search(searchCondition)
}
</script>
