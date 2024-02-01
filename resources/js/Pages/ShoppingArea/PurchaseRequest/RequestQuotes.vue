<template>
    <Head title="Cotizaciones" />
    <AuthenticatedLayout>
        <template #header>
            Cotizaciones
        </template>
        <div class="px-4 sm:px-0">
            <h3 class="text-base font-semibold leading-7 text-gray-900">Applicant Information</h3>
            <p class="mt-1 max-w-2xl text-sm leading-6 text-gray-500">Personal details and application.</p>
        </div>
        <div class="mt-6 border-t border-gray-100">
            <dl class="divide-y divide-gray-100">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Proyecto</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ purchases.project.name }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Presupuesto restante</dt>
                    <dd class="mt-1 text-sm leading-6 text-green-700 sm:col-span-2 sm:mt-0">S./ {{
                        purchases.project.remaining_budget }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Solicitud</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ purchases.title }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Fecha limite</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ purchases.due_date }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Descripcion</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ purchases.product_description
                    }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Estado</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ purchases.state }}</dd>
                </div>
                <form @submit.prevent="submit">
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <InputLabel for="provider" class="text-sm font-medium leading-6 text-gray-900">Proveedores
                        </InputLabel>
                        <select v-model="form.provider" id="provider"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="" disabled>Compañia | Contacto | Telefono1 - Telefono2</option>
                            <option v-for="provider in providers" :key="provider" :value="provider.company_name">{{
                                provider.company_name }} | {{ provider.contact_name }} | {{ provider.phone1 }} -
                                {{ provider.phone2 }}
                            </option>
                        </select>
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <InputLabel for="quote_deadline" class="text-sm font-medium leading-6 text-gray-900">Fecha Limite de
                            Aprobacion
                        </InputLabel>
                        <TextInput type="date" v-model="form.quote_deadline" id="quote_deadline"
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" />
                        <InputError :message="form.errors.quote_deadline" />
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <InputLabel for="total_mount" class="text-sm font-medium leading-6 text-gray-900">Monto Total
                        </InputLabel>
                        <TextInput type="text" v-model="form.amount" id="total_mount"
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" />
                        <InputError :message="form.errors.amount" />
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <InputLabel for="response" class="text-sm font-medium leading-6 text-gray-900">Respuestas
                        </InputLabel>
                        <textarea v-model="form.response" id="response"
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" />
                        <InputError :message="form.errors.response" />
                    </div>
                    <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                        <InputLabel for="purchase_image" class="text-sm font-medium leading-6 text-gray-900">Documento de de
                            Cotizacion</InputLabel>
                        <InputFile type="file" v-model="form.purchase_image" id="purchase_image"
                            class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0" />
                        <InputError :message="form.errors.purchase_image" />
                    </div>
                    <div v-if="purchases.state == 'pendiente' || purchases.state == 'En Progreso'"
                        class="mt-6 flex items-center justify-end gap-x-6">
                        <button v-if="purchases.state == 'pendiente'" @click.prevent="purchaseRequestReject()"
                            class="rounded-md bg-red-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600"
                            :disabled="process">Rechazar</button>
                        <button type="submit" :class="{ 'opacity-25': form.processing }"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Cotizado</button>
                    </div>
                </form>
            </dl>
        </div>
        <Modal :show="confirmPurchaseRequestReject">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    ¿Estás seguro de rechazar la solicitud de compra?
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    Se va a rechazar la solicitud y no volverá a activarse
                </p>
                <div class="mt-6 flex justify-end">
                    <div v-if="process">
                        <p>
                            Procesando...
                        </p>
                    </div>
                    <div v-else>
                        <SecondaryButton @click="closeModal"> Cancel </SecondaryButton>
                        <DangerButton class="ml-3" @click="reject_quote(purchases.id)">
                            Rechazar
                        </DangerButton>
                    </div>
                </div>
            </div>
        </Modal>
        <SuccessOperationModal :confirmingFunction="successRejection" title="Rechazo exitoso"
            message="La solicitud de compra ha sido rechazada" />
        <ErrorOperationModal :showError="showError" title="Presupuesto excedido"
            message="La cantidad sobrepasa el presupuesto restante" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputFile from '@/Components/InputFile.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';

const props = defineProps({
    purchases: Object,
    providers: Object
})

const form = useForm({
    provider: '',
    purchasing_request_id: props.purchases.id,
    quote_deadline: '',
    amount: '',
    response: '',
    purchase_image: ''
})

const showError = ref(false)
const submit = () => {
    if (form.amount > props.purchases.project.remaining_budget) {
        showError.value = true
        setTimeout(() => {
            showError.value = false;
        }, 2500);
    } else {
        form.post(route('purchasesrequest.storequotes'))
    }
}


//Reject
const confirmPurchaseRequestReject = ref(false);
const successRejection = ref(false)
const process = ref(false)

const purchaseRequestReject = () => {
    confirmPurchaseRequestReject.value = true;
};
const closeModal = () => {
    confirmPurchaseRequestReject.value = false;
};
const reject_quote = (id) => {
    process.value = true
    console.log
    router.post(route('purchasesrequest.reject_request', { id }), null, {
        onSuccess: () => {
            process.value = false
            closeModal()
            setTimeout(() => {
                successRejection.value = true
            }, 500)
            setTimeout(() => {
                successRejection.value = false
                router.visit(route('purchasesrequest.index'))
            }, 3000)
        },
        onError: (errors) => {
            alert('Algo salió mal')
            console.log(errors)
        }
    })
}


</script>