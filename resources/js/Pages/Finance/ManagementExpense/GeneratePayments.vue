<template>
    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'managementexpense.index'">
        <template #header>
            Registro de Pagos
        </template>
        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 flex items-center justify-between gap-x-6">
                <button @click="add_payment" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    Agregar Pago
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Monto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Descripcion
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="payment in paymentsArray" :key="payment.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">

                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.amount }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ payment.description }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <button @click="submit()" type="button"
                                            class="rounded-xl whitespace-no-wrap text-center text-sm text-red-900 hover:bg-red-200">
Guardar
                                        </button>
        <Modal :show="showModalPayment">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                        Agregar un Registro de Pago
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                        <div class="sm:col-span-3">
                            <InputLabel for="amount" class="font-medium leading-6 text-gray-900">Monto
                            </InputLabel>
                            <div class="mt-2">
                                <input required type="text" v-model="amount" id="amount"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>
                        <div class="sm:col-span-3 sm:col-start-1">
                            <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripcion
                            </InputLabel>
                            <div class="mt-2">
                                <textarea v-model="description" id="description"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <SecondaryButton type="button" @click="closeModal"> Cerrar </SecondaryButton>
                        <PrimaryButton type="button" @click="addItem" > Agregar </PrimaryButton>
                    </div>
            </div>

            
            </Modal>
            <!-- <SuccessOperationModal :confirming="showModal" :title="modalVariables.title" :message="modalVariables.message" /> -->
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/InputFile.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { Head, Link, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    quote_id:String
})

const showModalPayment = ref(false);
const amount = ref('');
const description = ref('');
const paymentsArray = ref([]);
const form = useForm({
    state: true,
    payments: '',
})

function add_payment(){
    showModalPayment.value = true
}

function submit(){
    form.payments = paymentsArray.value.map(payment => ({
            amount: payment.amount,
            description: payment.description
        }))
    form.put(route('managementexpense.reviewed',{id:props.quote_id}),form,{
        onSuccess: () => {
                showModal.value = true
                setTimeout(() => {
                    showModal.value = false;
                    router.visit(route('managementexpense.index'))
                }, 2000);
        },
    })
}

function addItem() {
    if(amount.value != '' && description.value != ''){
        paymentsArray.value.push({
    amount: amount.value,
    description: description.value
  });
  amount.value = '',
  description.value = ''
}
    }


function closeModal(){
    showModalPayment.value = false
}
</script>