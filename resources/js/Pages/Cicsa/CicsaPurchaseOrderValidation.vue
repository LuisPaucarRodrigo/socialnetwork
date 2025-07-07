<template>

    <Head title="CICSA Validación" />

    <AuthenticatedLayout :redirectRoute="{ route: 'cicsa.index', params: { type } }">
        <template #header>
            {{ type == 1 ? 'Pint' : 'Pext' }} - Validación de OC
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <div class="flex justify-between">
                <a :href="route('cicsa.purchase_orders.validation.export', { type }) + '?' + uniqueParam"
                    class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">Exportar</a>
                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <TextInput data-tooltip-target="search_fields" type="text" @input="search($event.target.value)"
                        placeholder="Buscar ..." />
                    <SelectCicsaComponent currentSelect="Validación de OC" :type="type" />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Nombre,Codigo,CPE,OC,Observaciones
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <br>
            <TableStructure :info="purchase_validations">
                <template #thead>
                    <tr>
                        <TableTitle :colspan="3">Nombre de Proyecto</TableTitle>
                        <TableTitle :colspan="3">Codigo de Proyecto</TableTitle>
                        <TableTitle :colspan="3">Centro de Costos</TableTitle>
                        <TableTitle :colspan="2">CPE</TableTitle>
                        <TableTitle></TableTitle>
                    </tr>
                </template>
                <template #tbody>
                    <template v-for="item in purchase_validations.data ?? purchase_validations" :key="item.id">
                        <tr>
                            <TableRow :colspan="3">{{ item.project_name }}</TableRow>
                            <TableRow :colspan="3">{{ item.project_code }}</TableRow>
                            <TableRow :colspan="3">{{ item.project?.cost_center?.name }}</TableRow>
                            <TableRow :colspan="2">{{ item.cpe }}</TableRow>
                            <TableRow>
                                <div class="flex space-x-3 justify-center">
                                    <button v-if="item.cicsa_purchase_order_validation.length > 0" type="button"
                                        @click="toggleDetails(item?.cicsa_purchase_order_validation)">
                                        <DownArrowIcon v-if="validation_purchase_order_row !== item.id" />
                                        <UpArrowIcon v-else />
                                    </button>
                                </div>
                            </TableRow>
                        </tr>
                        <template v-if="validation_purchase_order_row == item.id">
                            <tr
                                class="border-b bg-red-500 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <TableTitle :style="'bg-gray-200'">Numero de OC</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Fecha de Validación</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Validacion de expediente</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Control de Materiales</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Supervisor</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Almacén</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Jefe</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Liquidador</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Superintendente</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Observaciones</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Encargado</TableTitle>
                                <TableTitle :style="'bg-gray-200'">Acciones</TableTitle>
                            </tr>
                            <tr v-for="materialDetail in item.cicsa_purchase_order_validation" :key="materialDetail.id"
                                class="bg-gray-100">
                                <TableRow>{{ materialDetail?.cicsa_purchase_order?.oc_number }}</TableRow>
                                <TableRow>{{ formattedDate(materialDetail?.validation_date) }}</TableRow>
                                <TableRow>{{ materialDetail?.file_validation }}</TableRow>
                                <TableRow>{{ materialDetail?.materials_control }}</TableRow>
                                <TableRow>{{ materialDetail?.supervisor }}</TableRow>
                                <TableRow>{{ materialDetail?.warehouse }}</TableRow>
                                <TableRow>{{ materialDetail?.boss }}</TableRow>
                                <TableRow>{{ materialDetail?.liquidator }}</TableRow>
                                <TableRow>{{ materialDetail?.superintendent }}</TableRow>
                                <TableRow>{{ materialDetail?.observations }}</TableRow>
                                <TableRow>{{ materialDetail?.user_name }}</TableRow>
                                <TableRow>
                                    <button class="text-blue-900" @click="openUpdateModal(materialDetail)">
                                        <EditIcon />
                                    </button>
                                </TableRow>
                            </tr>
                        </template>
                    </template>
                </template>
            </TableStructure>
            <div v-if="purchase_validations.data"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="purchase_validations.links" />
            </div>
        </div>

        <Modal :show="showAddEditModal" @close="closeAddAssignationModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    {{ form.id ? 'Editar Validación' : 'Nueva Validación' }} {{ oc_number ? ": " + oc_number : "" }}
                </h2>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <InputLabel for="validation_date">Fecha de Validación</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.validation_date" autocomplete="off"
                                    id="validation_date" />
                                <InputError :message="form.errors.validation_date" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="file_validation">Validacion de expediente</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.file_validation" id="file_validation"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En Proceso">En Proceso</option>
                                    <option value="Completado">Completado</option>
                                </select>
                                <InputError :message="form.errors.file_validation" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="materials_control">Control de Materiales</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.materials_control" id="materials_control"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En Proceso">En Proceso</option>
                                    <option value="Completado">Completado</option>
                                </select>
                                <InputError :message="form.errors.materials_control" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="supervisor">Supervisor</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.supervisor" id="supervisor"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En Proceso">En Proceso</option>
                                    <option value="Completado">Completado</option>
                                </select>
                                <InputError :message="form.errors.supervisor" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="warehouse">Almacén</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.warehouse" id="warehouse"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En Proceso">En Proceso</option>
                                    <option value="Completado">Completado</option>
                                </select>
                                <InputError :message="form.errors.warehouse" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="boss">Jefe</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.boss" id="boss"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En Proceso">En Proceso</option>
                                    <option value="Completado">Completado</option>
                                </select>
                                <InputError :message="form.errors.boss" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="liquidator">Liquidador</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.liquidator" id="liquidator"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En Proceso">En Proceso</option>
                                    <option value="Completado">Completado</option>
                                </select>
                                <InputError :message="form.errors.liquidator" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="superintendent">Superintendente</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.superintendent" id="superintendent"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Pendiente">Pendiente</option>
                                    <option value="En Proceso">En Proceso</option>
                                    <option value="Completado">Completado</option>
                                </select>
                                <InputError :message="form.errors.superintendent" />
                            </div>
                        </div>

                        <div class="sm:col-span-1">
                            <InputLabel for="observations">Observaciones</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.observations" id="observations"
                                    class="block w-full mt-1 border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm rounded-md" />
                                <InputError :message="form.errors.observations" />
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
import { formattedDate, setAxiosErrors } from '@/utils/utils.js';
import TextInput from '@/Components/TextInput.vue';
import { Toaster } from 'vue-sonner';
import { notify, notifyError } from '@/Components/Notification';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import { DownArrowIcon, UpArrowIcon, EditIcon } from '@/Components/Icons/Index';

const { purchase_validation, auth, searchCondition, type } = defineProps({
    purchase_validation: Object,
    auth: Object,
    searchCondition: {
        type: String,
        Required: false
    },
    type: Number,
})

const uniqueParam = ref(`timestamp=${new Date().getTime()}`);
const purchase_validations = ref(purchase_validation)
const oc_number = ref(null)
const validation_purchase_order_row = ref(0);
// const confirmUpdateAssignation = ref(false);

const initialState = {
    id: null,
    user_id: '',
    validation_date: '',
    materials_control: 'Pendiente',
    file_validation: 'Pendiente',
    supervisor: 'Pendiente',
    warehouse: 'Pendiente',
    boss: 'Pendiente',
    liquidator: 'Pendiente',
    superintendent: 'Pendiente',
    observations: '',
    user_name: '',
    cicsa_assignation_id: '',
    cicsa_purchase_order_id: '',
}

const form = useForm(
    { ...initialState }
);

const showAddEditModal = ref(false);

function closeAddAssignationModal() {
    showAddEditModal.value = false
    form.defaults({ ...initialState })
    form.reset()
}

function openUpdateModal(item) {
    oc_number.value = item.cicsa_purchase_order?.oc_number
    form.defaults({ ...item, user_name: auth.user.name, user_id: auth.user.id })
    form.reset()
    showAddEditModal.value = true
}

async function submit() {
    let url = route('cicsa.purchase_orders.validation.update', { cicsa_validation_order_id: form.id });
    try {
        const response = await axios.put(url, form)
        updatePurchaseOrderValidation(response.data)
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
        const response = await axios.post(route('cicsa.purchase_orders.validation', { type }), { searchQuery: $search });
        purchase_validations.value = response.data.purchase_validation;
    } catch (error) {
        console.error('Error searching:', error);
    }
};

const toggleDetails = (cicsa_purchase_order_validation) => {
    if (validation_purchase_order_row.value === cicsa_purchase_order_validation[0].cicsa_assignation_id) {
        validation_purchase_order_row.value = 0;
    } else {
        validation_purchase_order_row.value = cicsa_purchase_order_validation[0].cicsa_assignation_id;
    }
}

function updatePurchaseOrderValidation(OCValidation) {
    const validations = purchase_validations.value.data || purchase_validations.value;
    const index = validations.findIndex(item => item.id === OCValidation.cicsa_assignation_id)
    const indexOCValidation = validations[index].cicsa_purchase_order_validation.findIndex(item => item.id === OCValidation.id)
    validations[index].cicsa_purchase_order_validation[indexOCValidation] = OCValidation
    notify('Actualización Exitosa')
}

if (searchCondition) {
    search(searchCondition)
}
</script>