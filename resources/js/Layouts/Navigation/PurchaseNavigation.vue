<template>
    <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
        @click="showingShoppingArea = !showingShoppingArea">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" :stroke="purchaseOrdersAlarms.length + shoppingPurchases.length + shoppingPurchases7.length + pendingPayments.length
            > 0 ? 'red' : 'currentColor'" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
        </svg>
        <span class="mx-3">Area de Compras</span>
    </a>

    <MyTransition v-if="subModulePermission(submodules.pprovider_submodule, userSubModules)"
        :transitiondemonstration="showingShoppingArea">
        <Link class="w-full" :href="route('providersmanagement.index')">Proveedores</Link>
    </MyTransition>

    <MyTransition v-if="subModulePermission(submodules.ppayment_approval_submodule, userSubModules)"
        :transitiondemonstration="showingShoppingArea">
        <div class="relative">
            <button @click="showPendingPaymentsAlarms = !showPendingPaymentsAlarms">
                <span v-if="pendingPayments.length > 0"
                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                    {{ pendingPayments.length }}
                </span>
            </button>
            <Link class="w-full" :href="route('payment.approval.index')">Programación de Pagos</Link>
        </div>
    </MyTransition>

    <template v-if="pendingPayments.length !== 0">
        <MyTransition v-for="item in pendingPayments" :key="item.id" class="ml-4"
            :transitiondemonstration="showPendingPaymentsAlarms">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-2 text-red-400 dark:text-red" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                </svg>
                <span>{{ item.zone }} - {{ item.ruc }}</span>
            </div>
        </MyTransition>
    </template>

    <MyTransition v-if="subModulePermission(submodules.pprequest_submodule, userSubModules)"
        :transitiondemonstration="showingShoppingArea">
        <div class="relative">
            <button @click="tooglePurchaseRequest">
                <span v-if="shoppingPurchases.length + shoppingPurchases7.length > 0"
                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                    {{ shoppingPurchases.length + shoppingPurchases7.length }}</span>
            </button>
            <Link class="w-full" :href="route('purchasesrequest.index')">Solicitudes</Link>
        </div>
    </MyTransition>
    <template v-if="showShoppingPurchaseRequestAlarms">
        <MyTransition v-for="item in shoppingPurchases" :key="item.id" class="ml-4"
            :transitiondemonstration="showShoppingPurchaseRequestAlarms">
            <Link class="w-full flex items-center" :href="route('purchasingrequest.details', { id: item.id })">
            <div class="flex items-center"> <!-- Contenedor del ícono y el título -->
                <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                </svg>
                <span class="overflow-hidden whitespace-nowrap">{{ item.title }}</span>
                <!-- Estilo específico para el título -->
            </div>
            </Link>
        </MyTransition>
        <MyTransition v-for="item in shoppingPurchases7" :key="item.id" class="ml-4"
            :transitiondemonstration="showShoppingPurchaseRequestAlarms">
            <Link class="w-full flex items-center" :href="route('purchasingrequest.details', { id: item.id })">
            <div class="flex items-center"> <!-- Contenedor del ícono y el título -->
                <svg class="w-4 h-4 mr-2 text-yellow-600 dark:text-yellow" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                </svg>
                <span class="overflow-hidden whitespace-nowrap">{{ item.title }}</span>
                <!-- Estilo específico para el título -->
            </div>
            </Link>
        </MyTransition>
    </template>

    <MyTransition v-if="subModulePermission(submodules.pporder_submodule, userSubModules)"
        :transitiondemonstration="showingShoppingArea">
        <div class="relative">
            <button @click="showPurchaseOrdersAlarms = !showPurchaseOrdersAlarms">
                <span v-if="purchaseOrdersAlarms.length > 0"
                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                    {{ purchaseOrdersAlarms.length }}
                </span>
            </button>
            <Link class="w-full" :href="route('purchaseorders.index')">Ordenes</Link>
        </div>
    </MyTransition>
    <!-- purchase order alarms -->
    <template v-if="purchaseOrdersAlarms.length !== 0">
        <MyTransition v-for="item in purchaseOrdersAlarms" :key="item.id" class="ml-4"
            :transitiondemonstration="showPurchaseOrdersAlarms">
            <Link class="w-full flex items-center"
                :href="route('purchaseorders.details', { purchase_order_id: item.id })">
            <div class="flex items-center">
                <svg :class="`w-4 h-4 mr-2 ${item.critical ? 'text-red-600' : 'text-yellow-400'} dark:text-red`"
                    aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                </svg>
                <span>{{ item.purchase_quote.purchasing_requests.title }}</span>
            </div>

            </Link>
        </MyTransition>
    </template>

    <MyTransition v-if="subModulePermission(submodules.ppcpurchase_submodule, userSubModules)"
        :transitiondemonstration="showingShoppingArea">
        <Link class="w-full" :href="route('purchaseorders.history')">Compras Completadas</Link>
    </MyTransition>
</template>
<script setup>
import MyTransition from '@/Components/MyTransition.vue';
import { subModulePermission } from '@/utils/roles/roles';
import { Link, usePage } from '@inertiajs/vue3';
import { onMounted, ref, onUnmounted } from 'vue';

const { submodules } = usePage().props
const { userSubModules } = usePage().props.auth
const showingShoppingArea = ref(false)
const showShoppingPurchaseRequestAlarms = ref(false)
const showPendingPaymentsAlarms = ref(false)
const purchaseOrdersAlarms = ref([])
const shoppingPurchases = ref([])
const shoppingPurchases7 = ref([])

const pendingPayments = ref([])


function tooglePurchaseRequest() {
    showShoppingPurchaseRequestAlarms.value = !showShoppingPurchaseRequestAlarms.value
}

async function fetchPurchasesRequest(params) {
    try {
        const response = await axios.get(route('purchasesrequest.alarm'));
        shoppingPurchases.value = Object.values(response.data.purchasesLessThanThreeDays);
        shoppingPurchases7.value = Object.values(response.data.purchasesBetweenFourAndSevenDays);
    } catch (error) {
        console.error('Error al obtener el contador de subsecciones:', error);
    }
}

async function fetchPurchaseOrderAlarms(params) {
    try {
        const response = await axios.get(route('purchaseorders.alarms'));
        purchaseOrdersAlarms.value = [...response.data.purchaseOrders3d.map(i => ({ ...i, 'critical': true })),
        ...response.data.purchaseOrders7d
        ]
    } catch (error) {
        console.error('Error al obtener alarmas de finanzas:', error);
    }
}

async function fetchPendingPaymentAlarms() {
    try {
        const response = await axios.get(route('payment.approval.alarm.pending.payments'));
        pendingPayments.value = response.data
    } catch (error) {
        console.error('Error al obtener alarmas de programación de pagos:', error);
    }
}


let intervalId;
const fetchAllAlarms = () => {
    return Promise.all([
        fetchPurchasesRequest(),
        fetchPurchaseOrderAlarms(),
        fetchPendingPaymentAlarms()
    ]);
};
onMounted(() => {
    fetchAllAlarms();
    intervalId = setInterval(fetchAllAlarms, 60000);
});
onUnmounted(() => {
    clearInterval(intervalId);
});
</script>
