<template>

    <Head title="CICSA Validación" />

    <AuthenticatedLayout :redirectRoute="'cicsa.index'">
        <template #header>
            Validación de OC
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="flex justify-between">
                <a :href="route('cicsa.purchase_orders.validation.export')"
                    class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">Exportar</a>
                <div class="flex items-center mt-4 space-x-3 sm:mt-0">
                    <TextInput type="text" @input="search($event.target.value)" placeholder="Nombre,Codigo,CPE,OC" />
                    <SelectCicsaComponent currentSelect="Validación de OC" />
                </div>
            </div>
            <br>
            <div class="overflow-x-auto h-[70vh]">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="sticky top-0 z-20 border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th colspan="3"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre de Proyecto
                            </th>
                            <th colspan="3"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Codigo de Proyecto
                            </th>
                            <th colspan="3"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                CPE
                            </th>
                            <th colspan="2"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-for="item in purchase_validations.data ?? purchase_validations" :key="item.id">

                            <tr class="text-gray-700">
                                <td colspan="3" class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                    <p class="text-gray-900 text-center">
                                        {{ item.project_name }}
                                    </p>
                                </td>
                                <td colspan="3" class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                    <p class="text-gray-900 text-center">
                                        {{ item.project_code }}
                                    </p>
                                </td>
                                <td colspan="3" class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                    <p class="text-gray-900 text-center">
                                        {{ item.cpe }}
                                    </p>
                                </td>
                                <td colspan="2" class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                    <div class="flex space-x-3 justify-center">
                                        <button v-if="item.cicsa_purchase_order_validation.length > 0" type="button"
                                            @click="toggleDetails(item?.cicsa_purchase_order_validation)"
                                            class="text-blue-900 whitespace-no-wrap">
                                            <svg v-if="validation_purchase_order_row !== item.id"
                                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
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
                            <template v-if="validation_purchase_order_row == item.id">
                                <tr
                                    class="border-b bg-red-500 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Numero de OC
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Fecha de Validación
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Validacion de expediente
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Control de Materiales
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Supervisor
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Almacén
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Jefe
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Liquidador
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        Superintendente
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
                                <tr v-for="materialDetail in item.cicsa_purchase_order_validation"
                                    :key="materialDetail.id" class="bg-gray-100">
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.cicsa_purchase_order.oc_number }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ formattedDate(materialDetail?.validation_date) }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.file_validation }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.materials_control }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.supervisor }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.warehouse }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.boss }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.liquidator }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.superintendent }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px]">
                                        <p class="text-gray-900 text-center">
                                            {{ materialDetail?.user_name }}
                                        </p>
                                    </td>
                                    <td class="border-b border-gray-200 bg-white px-5 py-3 text-[13px] text-center">
                                        <button class="text-blue-900" @click="openUpdateModal(materialDetail)">
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

        <SuccessOperationModal :confirming="confirmUpdateAssignation" :title="'Validación Actualizada'"
            :message="'La Validación fue actualizada'" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectCicsaComponent from '@/Components/SelectCicsaComponent.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import { formattedDate } from '@/utils/utils.js';
import TextInput from '@/Components/TextInput.vue';

const { purchase_validation, auth } = defineProps({
    purchase_validation: Object,
    auth: Object
})

const purchase_validations = ref(purchase_validation)
const oc_number = ref(null)
const validation_purchase_order_row = ref(0);
const confirmUpdateAssignation = ref(false);

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
    oc_number.value = item.cicsa_purchase_order.oc_number
    form.defaults({ ...item, user_name: auth.user.name, user_id: auth.user.id })
    form.reset()
    showAddEditModal.value = true
}

function submit() {
    form.put(route('cicsa.purchase_orders.validation.storeOrUpdate', { cicsa_validation_order_id: form.id }), {
        onSuccess: () => {
            closeAddAssignationModal()
            confirmUpdateAssignation.value = true
            setTimeout(() => {
                confirmUpdateAssignation.value = false
                router.get(route('cicsa.purchase_orders.validation'))
            }, 1500)
        },
        onError: (e) => {
            console.error(e)
        }
    })
}

const search = async ($search) => {
    try {
        const response = await axios.post(route('cicsa.purchase_orders.validation'), { searchQuery: $search });
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
</script>