<template>

    <Head title="Cotizaciones" />
    <AuthenticatedLayout :redirectRoute="'purchasesrequest.index'">
        <template #header>
            Nueva cotización
        </template>
        <div class="mt-6 border-t border-gray-100">
            <dl class="divide-y divide-gray-100 p-4 rounded-lg ring-1 ring-gray-100 shadow-md">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-24">
                    <div class="col-span-2 sm:col-span-1">
                        <h5 class="font-semibold py-3 mb-2 border-b-2 border-gray-300 col-span-2">Solicitud</h5>
                        <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Código</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ purchases.code }}
                            </dd>
                        </div>
                        <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Nombre</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ purchases.title }}
                            </dd>
                        </div>
                        <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Fecha límite de Compra</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ formattedDate(purchases.due_date) }}</dd>
                        </div>
                        <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Estado</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ purchases.state }}
                            </dd>
                        </div>
                    </div>
                    <!-- ---------------------------------------- -->
                    <div v-if="purchases.project" class="col-span-2 sm:col-span-1">
                        <h5 class="font-semibold py-3 mb-2 border-b-2 border-gray-300 col-span-2">Proyecto</h5>
                        <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Código</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ purchases.project.code }}
                            </dd>
                        </div>
                        <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Nombre</dt>
                            <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
                                {{ purchases.project.name }}
                            </dd>
                        </div>
                        <div class="py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                            <dt class="text-sm font-medium leading-6 text-gray-900">Presupuesto restante</dt>
                            <dd class="mt-1 text-sm leading-6 text-green-700 sm:col-span-2 sm:mt-0">
                                S./ {{ (purchases.project.remaining_budget).toFixed(2) }}</dd>
                        </div>
                    </div>
                </div>
                <br>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 sm:grid-cols-6 gap-x-24 gap-y-6">
                        <h5 class="font-semibold py-3 border-b-2 border-gray-300 sm:col-span-6">Registro</h5>

                        <div class="sm:col-span-3">
                            <InputLabel for="provider_id" class="text-sm font-medium leading-6 text-gray-900">Proveedor
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="form.provider_id" id="provider_id"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Compañia | Contacto | Telefono1 - Telefono2</option>
                                    <option v-for="provider in providers" :key="provider"
                                        :value="provider.id">
                                        {{ provider.company_name }} | {{ provider.contact_name }} | {{ provider.phone1 }} -
                                        {{ provider.phone2 }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.provider_id" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="quote_deadline" class="text-sm font-medium leading-6 text-gray-900">
                                Fecha limite de Aprobacion de Finanzas
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.quote_deadline" id="quote_deadline"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.quote_deadline" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="deliverable_time" class="text-sm font-medium leading-6 text-gray-900">
                                Tiempo de entrega (días)
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" min="0" v-model="form.deliverable_time" id="deliverable_time"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.deliverable_time" />
                            </div>
                        </div>


                        <div class="sm:col-span-3">
                            <InputLabel for="account_number" class="text-sm font-medium leading-6 text-gray-900">
                                Número de cuenta
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput v-model="form.account_number" id="account_number" pattern="[0-9]+(-[0-9]+)*"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.account_number" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="payment_type" class="text-sm font-medium leading-6 text-gray-900">
                                Forma de pago
                            </InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.payment_type" id="payment_type"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.payment_type" />
                            </div>
                        </div>


                        <div class="sm:col-span-3">
                            <InputLabel for="purchase_image" class="text-sm font-medium leading-6 text-gray-900">
                                Documento de Cotizacion
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile type="file" v-model="form.purchase_doc" id="purchase_image"
                                    class="mt-1 text-sm leading-6 border-0 ring-1 ring-gray-300 text-gray-700 sm:col-span-2 sm:mt-0 focus:ring-2 focus:ring-inset focus:ring-indigo-600" />
                                <InputError :message="form.errors.purchase_doc" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel class="text-sm font-medium leading-6 text-gray-900">
                                ¿IGV incluido?
                            </InputLabel>
                            <div class="mt-2 class flex gap-4">
                                <label class="flex gap-2 items-center">
                                    Sí
                                    <input type="radio" min="0" v-model="form.igv" id="igv" @input="handleWithIgv" :value="true"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <label class="flex gap-2 items-center">
                                    No
                                    <input type="radio" min="0" v-model="form.igv" id="igv" @input="handleWithIgv" :value="false"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <InputError :message="form.errors.igv" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel class=" text-sm font-medium leading-6 text-gray-900">
                                <div class="flex gap-3">
                                    <span>
                                        Moneda
                                    </span>
                                    <select v-model="currency" @change="handleCurrencyType"
                                        class="w-32 block rounded-md border-0 py-0 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 ">
                                        <option value="S/.">Soles</option>
                                        <option value="$">Dólares</option>
                                    </select>

                                </div>
                            </InputLabel>
                            <div v-if="currency !== 'S/.'" class="mt-2 flex gap-3 items-center">
                                <p class="text-sm font-medium leading-6 text-indigo-900">
                                    El valor de tipo de cambio será definido al momento del registro de pago.
                                </p>
                                <!-- <input v-model="currencyChange" type="number" step="0.001"
                                    @input="handleCurrencyChange" autocomplete="off" placeholder="$ -> S/."
                                    class="tracking-wide block w-32 bg-white rounded-md border-0 py-1 text-gray-700 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" /> -->
                            </div>
                        </div>
                        <div class="col-span-1 sm:col-span-6 ">
                            <br>
                            <div class="overflow-x-auto ring-1 rounded-md ring-gray-300">
                                <table class="w-full">
                                    <thead>
                                        <tr
                                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                #
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                Código
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                Nombre del producto
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                Cantidad
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                Unidad
                                            </th>
                                            <th
                                                class="text-center border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                P.U.
                                            </th>
                                            <!-- v-if="auth.user.role_id === 1 || preproject.quote === null" -->
                                            <th v-if="form.igv"
                                                class="w-32 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                Sin IGV
                                            </th>
                                            <!-- <th v-if="currency !== 'S/.'"
                                                class="w-32 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                                Soles
                                            </th> -->
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-xs font-semibold uppercase tracking-wider text-gray-600 text-right">
                                                Monto Total
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- v v-for="(item, index) in (form.items)" :key="index" -->
                                        <tr v-for="(item, index) in (purchases.products)" :key="index"
                                            class="text-gray-700 hover:bg-gray-200 bg-white">
                                            <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                                <p>
                                                    {{ index + 1 }}
                                                </p>
                                            </td>
                                            <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                                <p>
                                                    {{ item.code }}
                                                </p>
                                            </td>
                                            <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                                <p class="text-gray-900">
                                                    {{ item.name }}
                                                </p>
                                            </td>
                                            <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                                <p class="text-gray-900">
                                                    {{ item.pivot?.quantity }}
                                                </p>
                                            </td>
                                            <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                                <p class="text-gray-900">
                                                    {{ item.unit }}
                                                </p>
                                            </td>
                                            <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                                <p class="text-gray-500 whitespace-nowrap text-center">
                                                    {{ currency }} {{ ((form.products[item.id].unitary_amount ?
                                                    form.products[item.id].amount : 0 ) / (form.igv ? 1.18:1) / form.products[item.id].quantity).toFixed(2) }}
                                                </p>
                                            </td>
                                            <td v-if="form.igv"
                                                class=" w-32 border-b border-gray-200 px-5 py-5 text-sm text-center">
                                                <p class="text-gray-500 whitespace-nowrap">
                                                    {{ currency }} {{ (form.products[item.id].amount * 1/1.18).toFixed(2) }}
                                                </p>
                                            </td>
                                            <!-- <td v-if="currency !== 'S/.'"
                                                class=" w-32 border-b border-gray-200 px-5 py-5 text-sm text-center">
                                                <p class="text-gray-500 whitespace-nowrap">
                                                    S/. {{ (form.products[item.id].amount ? : ).toFixed(2) }}
                                                </p>
                                            </td> -->
                                            <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                                <div class="flex items-center justify-end gap-2">
                                                    {{ currency }}
                                                    <input required type="number" min="0" step="0.01"
                                                        v-model="form.products[item.id].amount"
                                                        @input="() => handleItemIgv(item.id)"
                                                        class="tracking-wide w-28 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 text-right" />

                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex justify-end py-4 px-10 text-indigo-900 text-sm">
                                <div class="grid grid-cols-2 gap-3">
                                    <p class="w-28">Subtotal</p>
                                    <p class="w-28 text-right flex justify-between">
                                        <span>
                                            {{ currency }}
                                        </span>
                                        <span>
                                            {{ getTotals(form.products, form.igv).subTotal }}
                                        </span>
                                    </p>
                                    <p class="w-28">IGV</p>
                                    <p class="w-28 text-right flex justify-between">
                                        <span>
                                            {{ currency }}
                                        </span>
                                        <span>
                                            {{ getTotals(form.products, form.igv).igv }}
                                        </span>
                                    </p>
                                    <p class="w-28">Total</p>
                                    <p class="w-28 text-right flex justify-between">
                                        <span>
                                            {{ currency }}
                                        </span>
                                        <span>
                                            {{ getTotals(form.products, form.igv).total }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <InputError :message="form.errors.products" />
                    <div v-if="purchases.state == 'Pendiente' || purchases.state == 'En progreso'"
                        class="mt-6 sm:col-span-6 flex items-center justify-end gap-x-6">
                        <button v-if="purchases.state == 'Pendiente'" @click.prevent="purchaseRequestReject()"
                            class="rounded-md bg-red-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600"
                            :disabled="process">Rechazar</button>
                        <button type="submit" :class="{ 'opacity-25': form.processing }"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Guardar
                        </button>
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
        <SuccessOperationModal :confirming="successRegistration" title="Cotización guardada"
            message="La cotización ha sido guardad de forma exitosa" />
        <SuccessOperationModal :confirming="successRejection" title="Rechazo exitoso"
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
import { formattedDate } from '@/utils/utils';

const props = defineProps({
    purchases: Object,
    providers: Object
})

function arrayToObject(products) {
    let rpta = {}
    products.forEach(item => {
        rpta[item.id] = { id: item.id, amount: '', quantity: item.pivot.quantity }
    });
    return rpta
}

const form = useForm({
    due_date: props.purchases.due_date,
    quote_deadline: '',
    purchase_doc: null,
    igv: true,
    deliverable_time: '',
    payment_type: '',
    account_number: '',
    provider_id: '',
    purchasing_request_id: props.purchases.id,
    currency:'sol',
    products: {
        ...arrayToObject(props.purchases.products)
    }
})

const showError = ref(false)
const successRegistration = ref(false)
const submit = () => {
    if (props.purchases.project && form.amount > props.purchases.project.remaining_budget) {
        showError.value = true
        setTimeout(() => {
            showError.value = false;
        }, 2500);
    } else {
        form.post(route('purchasesrequest.storequotes'),{
            onSuccess:()=>{
                successRegistration.value = true
                setTimeout(()=> {
                    successRegistration.value = false
                    router.visit(route('purchasesrequest.index'))
                },1500)
            }
        })
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
        }
    })
}

//IGV AND DOLLARS
const currency = ref('S/.')
// const currencyChange = ref(1)

const handleWithIgv = (e) => { (addItemsNewAmount(JSON.parse(e.target.value))) };
const handleItemIgv = (id) => { getTrueAmount(id, form.igv) }

function getTotals(products, hasIGV) {
    let subTotal = 0;
    let igv = 0;
    let total = 0;
    for (const key in products) {
        if (products[key].amount !== '' && !isNaN(products[key].amount)) {
            subTotal += parseFloat(products[key].amount);
        }
    }
    if (hasIGV) {
        total = subTotal.toFixed(2)
        subTotal = (total/1.18).toFixed(2)
        igv = (total - subTotal).toFixed(2)
    } else {
        subTotal = subTotal.toFixed(2)
        igv = (subTotal * .18).toFixed(2)
        total = (+subTotal + +igv).toFixed(2)
    }
    return { subTotal, igv, total }
}

function handleCurrencyType(e) {
    if (e.target.value === 'S/.') {
        // currencyChange.value = 1
        form.currency = 'sol'
    } else if (e.target.value === '$') {
        form.currency = 'dolar'
    }
}

function addItemsNewAmount(has_igv) {
    Object.keys(form.products).forEach(key => {
        getTrueAmount(key, has_igv)
    });
}
function getTrueAmount(id, has_igv) {
    let amount = form.products[id].amount
    form.products[id].unitary_amount = +(typeof amount !== 'number' || isNaN(amount) ?
        (0).toFixed(2) : ((amount * (has_igv ? 1 : 1.18))/(+form.products[id].quantity)).toFixed(6));
}

</script>