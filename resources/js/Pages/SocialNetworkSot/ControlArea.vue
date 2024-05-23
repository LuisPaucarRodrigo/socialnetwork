<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'management.employees'">
        <template #header>
            Empleados
        </template>

        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 sm:flex sm:gap-4 sm:justify-end">
                <div class="mt-2">
                    <select id="state_civil" v-model="form.state_civil"
                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                        <option disabled value="">Seleccionar Estado Civil</option>
                        <option>Casado(a)</option>
                        <option>Soltero(a)</option>
                        <option>Viudo(a)</option>
                        <option>Divorciado(a)</option>
                        <option>Conviviente</option>
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cod SOT
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                SOT a Facturar
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Factura
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cobranza
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="control in controls" :key="control.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ control.soat.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ control.sot_bill }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ formattedDate(control.sot_bill_date) }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ control.bill }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ formattedDate(control.bill_date) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ control.charge }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ formattedDate(control.charge_date) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button @click="actionShowStore(control)" type="button"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="props.search === undefined"
                class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="employees.links" />
            </div>
        </div>
        <Modal :show="showStoreControl">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Area de Control
                </h2>
                <form @submit.prevent="submit">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="mt-2">
                            <InputLabel for="sot_bill">SOT a Facturar
                            </InputLabel>
                            <div class="mt-2">
                                <select id="sot_bill" v-model="form.sot_bill"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Estado Civil</option>
                                    <option>Casado(a)</option>
                                    <option>Soltero(a)</option>
                                    <option>Viudo(a)</option>
                                    <option>Divorciado(a)</option>
                                    <option>Conviviente</option>
                                </select>
                                <InputError :message="form.errors.sot_bill" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="sot_bill_date">Fecha SOT a Facturar
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.sot_bill_date" id="sot_bill_date" />
                                <InputError :message="form.errors.sot_bill_date" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="bill">Factura
                            </InputLabel>
                            <div class="mt-2">
                                <select id="bill" v-model="form.bill"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Estado Civil</option>
                                    <option>Casado(a)</option>
                                    <option>Soltero(a)</option>
                                    <option>Viudo(a)</option>
                                    <option>Divorciado(a)</option>
                                    <option>Conviviente</option>
                                </select>
                                <InputError :message="form.errors.bill" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="bill_date">Fecha Facturar
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.bill_date" id="bill_date" />
                                <InputError :message="form.errors.bill_date" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="charge">Cobranza
                            </InputLabel>
                            <div class="mt-2">
                                <select id="charge" v-model="form.charge"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Estado Civil</option>
                                    <option>Casado(a)</option>
                                    <option>Soltero(a)</option>
                                    <option>Viudo(a)</option>
                                    <option>Divorciado(a)</option>
                                    <option>Conviviente</option>
                                </select>
                                <InputError :message="form.errors.charge" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="charge_date">Fecha Cobranza
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.charge_date" id="charge_date" />
                                <InputError :message="form.errors.charge_date" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="closeShowStoreControl"> Cancel </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
        <ConfirmUpdateModal :confirmingupdate="sotControlUpdate" itemType="SOT" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import { formattedDate } from '@/utils/utils';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const showStoreControl = ref(false);
const sotControlUpdate = ref(false);

const props = defineProps({
    controls: Object,
})

const form = useForm({
    soat_id: '',
    sot_bill: '',
    sot_bill_date: '',
    bill: '',
    bill_date: '',
    charge: '',
    charge_date: '',
})

function submit() {
    form.put(route('sn.controlArea.update', { sot_id: form.soat_id }), form, {
        onSuccess: () => {
            sotControlUpdate.value = true
            setTimeout(() => {
                sotControlUpdate.value = false
            }, 2000)
            router.visit(route('management.employees'));
        }
    })
}

function initializeForm(control) {
    form.soat_id = control.soat_id;
    form.sot_bill = control.sot_bill;
    form.sot_bill_date = control.sot_bill_date;
    form.bill = control.bill;
    form.bill_date = control.bill_date;
    form.charge = control.charge;
    form.charge_date = control.charge_date;
}

function actionShowStore(control) {
    initializeForm(control);
    showStoreControl.value = !showStoreControl.value;
}

function closeShowStoreControl() {
    showStoreControl.value = !showStoreControl.value
}


</script>