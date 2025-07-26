<template>
    <div :class="$page.props.showingMobileMenu ? 'block' : 'hidden'" @click="$page.props.showingMobileMenu = false"
        class="fixed inset-0 z-20 bg-black opacity-50 transition-opacity"></div>

    <div :class="$page.props.showingMobileMenu ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
        class="overflow-y-auto fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 transition duration-300 transform">
        <div class="flex justify-center items-center mt-8">
            <div class="flex items-center">
                <span class="mx-2 text-2xl font-semibold text-white">CCIP</span>
            </div>
        </div>

        <nav class="mt-10" x-data="{ isMultiLevelMenuOpen: false }">
            <template v-for="module in navModules" :key="module.name">
                <SuspenseWrapper :when="modulePermission(module.name, userModules)">
                    <template #component>
                        <component :is="module.component" />
                    </template>
                </SuspenseWrapper>
            </template>
        </nav>
    </div>
</template>

<script>
import { modulePermission } from '@/utils/roles/roles';
import { defineAsyncComponent } from 'vue';
import SuspenseWrapper from '@/Components/SuspenseWrapper.vue';

// import DocumentGestionNavigation from './Navigation/DocumentGestionNavigation.vue';

export default {
    props: {
        userPermissions: {
            type: Array,
        },
    },

    components: {
        SuspenseWrapper,
        UserNavigation: defineAsyncComponent(() => import('./Navigation/UserNavigation.vue')),
        FleetNavigation: defineAsyncComponent(() => import('./Navigation/FleetNavigation.vue')),
        EmployeesNavigation: defineAsyncComponent(() => import('./Navigation/EmployeesNavigation.vue')),
        ProjectsNavigation: defineAsyncComponent(() => import('./Navigation/ProjectsNavigation.vue')),
        InventoryNavigation: defineAsyncComponent(() => import('./Navigation/InventoryNavigation.vue')),
        FinanceNavigation: defineAsyncComponent(() => import('./Navigation/FinanceNavigation.vue')),
        PurchaseNavigation: defineAsyncComponent(() => import('./Navigation/PurchaseNavigation.vue')),
        BillingNavigation: defineAsyncComponent(() => import('./Navigation/BillingNavigation.vue')),
        HuaweiNavigation: defineAsyncComponent(() => import('@/Layouts/Navigation/HuaweiNavigation.vue')),
        SharePointNavigation: defineAsyncComponent(() => import('./Navigation/SharePointNavigation.vue')),
        // DocumentGestionNavigation
    },

    data() {
        return {
            userModules: this.$page.props.auth.userModules,
            navModules: [
                { name: 'USERS_MODULE', component: 'UserNavigation' },
                { name: 'HR_MODULE', component: 'EmployeesNavigation' },
                { name: 'INVENTORY_MODULE', component: 'InventoryNavigation' },
                { name: 'PURCHASING_MODULE', component: 'PurchaseNavigation' },
                { name: 'PROJECT_MODULE', component: 'ProjectsNavigation' },
                { name: 'FINANCE_MODULE', component: 'FinanceNavigation' },
                { name: 'BILLING_MODULE', component: 'BillingNavigation' },
                { name: 'HUAWEI_MODULE', component: 'HuaweiNavigation' },
                { name: 'CAR_MODULE', component: 'FleetNavigation' },
                { name: 'SHAREPOINT_MODULE', component: 'SharePointNavigation' }
            ],
        };
    },

    methods: {
        modulePermission: modulePermission
    },
    // mounted() {
    //     // Código que quieres que se ejecute cuando se monte el componente
    //     console.log('El componente fue montado');
    // },
}
</script>
