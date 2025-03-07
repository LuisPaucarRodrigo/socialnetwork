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
            <UserNavigation :userPermissions="$page.props.userPermissions" />
            <EmployeesNavigation :userPermissions="$page.props.userPermissions" />
            <InventoryNavigation :userPermissions="$page.props.userPermissions" />
            <PurchaseNavigation :userPermissions="$page.props.userPermissions" />
            <ProjectsNavigation :userPermissions="$page.props.userPermissions" />
            <FinanceNavigation :userPermissions="$page.props.userPermissions" />

            <!-- <DocumentGestionNavigation :userPermissions="$page.props.userPermissions" :auth="$page.props.auth"/> -->
            <BillingNavigation :userPermissions="$page.props.userPermissions" />
            <FleetNavigation :userPermissions="$page.props.userPermissions" />
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
// import DocumentGestionNavigation from './Navigation/DocumentGestionNavigation.vue';

export default {
    props: {
        userPermissions: {
            type: Array,
        },
        auth: {
            type: Object
        }
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
        // DocumentGestionNavigation
    },

    methods: {
        hasPermission(permission) {
            return this.$page.props.userPermissions.includes(permission);
        },
    },
}
</script>
