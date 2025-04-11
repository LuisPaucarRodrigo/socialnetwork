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
                <!-- <component 
                    v-if="modulePermission(module.name, userModules)"
                    :is="module.component"
                /> -->
                <component
                    :is="module.component"
                />
            </template>
        </nav>
    </div>
</template>

<script>
import NavLink from '@/Components/NavLink.vue';
import MyTransition from '@/Components/MyTransition.vue';
import { Link, } from '@inertiajs/vue3';
import FleetNavigation from './Navigation/FleetNavigation.vue';
import EmployeesNavigation from './Navigation/EmployeesNavigation.vue';
import ProjectsNavigation from './Navigation/ProjectsNavigation.vue';
import InventoryNavigation from './Navigation/InventoryNavigation.vue';
import FinanceNavigation from './Navigation/FinanceNavigation.vue';
import PurchaseNavigation from './Navigation/PurchaseNavigation.vue';
import BillingNavigation from './Navigation/BillingNavigation.vue';
import UserNavigation from './Navigation/UserNavigation.vue';
import HuaweiNavigation from '@/Layouts/Navigation/HuaweiNavigation.vue';
import SharePointNavigation from './Navigation/SharePointNavigation.vue';
import { modulePermission } from '@/utils/roles/roles';

// import DocumentGestionNavigation from './Navigation/DocumentGestionNavigation.vue';

export default {
    props: {
        userPermissions: {
            type: Array,
        },
    },

    components: {
        NavLink,
        Link,
        MyTransition,
        UserNavigation,
        FleetNavigation,
        EmployeesNavigation,
        ProjectsNavigation,
        InventoryNavigation,
        FinanceNavigation,
        PurchaseNavigation,
        BillingNavigation,
        HuaweiNavigation,
        SharePointNavigation
        // DocumentGestionNavigation
    },

    data() {
        return {
            userModules : this.$page.props.auth.userModules,
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
                { name: 'SHARE_POINT_MODULE', component: 'SharePointNavigation' }
            ],
        };
    },

    methods: {
        modulePermission: modulePermission
    },
}
</script>
