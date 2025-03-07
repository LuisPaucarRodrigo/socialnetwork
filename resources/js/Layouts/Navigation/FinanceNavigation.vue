<template v-if="hasPermission('FinanceManager') || hasPermission('Finance')">
    <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#" @click="showingFinance = !showingFinance">
        <svg v-if="financePurchases.length + financePurchases7.length + paymentAlarms3.length + paymentAlarms7.length > 0"
            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red"
            class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
            stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round"
                d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
        </svg>

        <span class="mx-3">Finanzas</span>
    </a>
    <MyTransition :transitiondemonstration="showingFinance">
        <Link class="w-full" :href="route('selectproject.index')">Presupuestos</Link>
    </MyTransition>
    <MyTransition :transitiondemonstration="showingFinance">
        <div class="relative">
            <button @click="tooglePurchaseQuote"><span v-if="financePurchases.length + financePurchases7.length > 0"
                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                    {{ financePurchases.length + financePurchases7.length }}</span>
            </button>
            <Link class="w-full" :href="route('managementexpense.index')">Aprobación de Compras</Link>
        </div>
    </MyTransition>
    <template v-if="showFinancePurchaseQuoteAlarms">
        <div class="mb-4">
            <MyTransition v-for="item in financePurchases" :key="item.id" class="ml-4"
                :transitiondemonstration="showFinancePurchaseQuoteAlarms">
                <Link class="w-full flex items-center"
                    :href="route('managementexpense.details', { purchase_quote: item.id })">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                    </svg>
                    <span>{{ item.purchasing_requests.title }}</span>
                </div>
                </Link>
            </MyTransition>
            <MyTransition v-for="item in financePurchases7" :key="item.id" class="ml-4"
                :transitiondemonstration="showFinancePurchaseQuoteAlarms">
                <Link class="w-full flex items-center"
                    :href="route('managementexpense.details', { purchase_quote: item.id })">
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-yellow-600 dark:text-yellow" aria-hidden="true"
                        xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                        <path
                            d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                    </svg>
                    <span>{{ item.purchasing_requests.title }}</span>
                </div>
                </Link>
            </MyTransition>
        </div>
    </template>
    <MyTransition :transitiondemonstration="showingFinance">
        <Link class="w-full" :href="route('deposits.index')">Depósitos</Link>
    </MyTransition>

    <MyTransition :transitiondemonstration="showingFinance">
        <div class="relative">
            <button @click="tooglePayment"><span v-if="paymentAlarms3.length + paymentAlarms7.length > 0"
                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                    {{ paymentAlarms3.length + paymentAlarms7.length }}</span>
            </button>
            <Link class="w-full" :href="route('payment.index')">Pagos OC</Link>
        </div>
    </MyTransition>
    <template v-if="paymentAlarms3.length !== 0">
        <MyTransition v-for="item in paymentAlarms3" :key="item.id" class="ml-4"
            :transitiondemonstration="paymentPorVencer">
            <Link class="w-full flex items-center" :href="route('payment.index')">
            <div class="flex items-center"> <!-- Contenedor del ícono y el título -->
                <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                </svg>
                <span class="overflow-hidden whitespace-nowrap">{{ item.cod_payment }}</span>
            </div>
            </Link>
        </MyTransition>
        <MyTransition v-for="item in paymentAlarms7" :key="item.id" class="ml-4"
            :transitiondemonstration="paymentPorVencer">
            <Link class="w-full flex items-center" :href="route('payment.index')">
            <div class="flex items-center"> <!-- Contenedor del ícono y el título -->
                <svg class="w-4 h-4 mr-2 text-yellow-600 dark:text-red" aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                    <path
                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                </svg>
                <span class="overflow-hidden whitespace-nowrap">{{ item.cod_payment }}</span>
            </div>
            </Link>
        </MyTransition>
    </template>
    <MyTransition :transitiondemonstration="showingFinance">
        <Link class="w-full" :href="route('finance.account_statement')">Estado de Cuenta</Link>
    </MyTransition>
</template>
<script setup>
import MyTransition from '@/Components/MyTransition.vue';
import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const { userPermissions } = defineProps({
    userPermissions: Array
})

const showingFinance = ref(false)
const paymentPorVencer = ref(false)
const showFinancePurchaseQuoteAlarms = ref(false)
const financePurchases = ref([])
const financePurchases7 = ref([])
const paymentAlarms3 = ref([])
const paymentAlarms7 = ref([])

function hasPermission(permission) {
    return userPermissions.includes(permission)
}

async function fetchFinancePurchases() {
    try {
        const response = await axios.get(route('approve_quote.alarm'));
        financePurchases.value = response.data.purchasesLessThanThreeDays;
        financePurchases7.value = response.data.purchasesBetweenFourAndSevenDays;
    } catch (error) {
        console.error('Error al obtener el contador de subsecciones:', error);
    }
}

async function fetchPaymentsAlarm() {
    try {
        const response = await axios.get(route('payment.alarm'));
        paymentAlarms3.value = response.data.payment3Days;
        paymentAlarms7.value = response.data.payment7Days;

    } catch (error) {
        console.error('Error al obtener el contador de payments:', error);
    }
}

onMounted(() => {
    if (hasPermission('FinanceManager') || hasPermission('Finance')) {
        fetchFinancePurchases();
        fetchPaymentsAlarm();
    }
    setInterval(() => {
        fetchFinancePurchases();
        fetchPaymentsAlarm();
    }, 60000)
})
</script>
