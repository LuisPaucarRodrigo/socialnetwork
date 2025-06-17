<template>
    <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#" @click="toogleHUawei">
        <svg v-if="pending_orders.length == 0" fill="white" width="23px" height="23px" viewBox="0 0 32 32"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M4.896 8.188c0 0-2.469 2.359-2.604 4.854v0.464c0.109 2.016 1.63 3.203 1.63 3.203 2.438 2.385 8.344 5.385 9.729 6.063 0 0 0.083 0.042 0.135-0.010l0.026-0.052v-0.057c-3.786-8.25-8.917-14.464-8.917-14.464zM12.865 24.802c-0.026-0.109-0.13-0.109-0.13-0.109l-9.839 0.349c1.063 1.906 2.865 3.37 4.745 2.932 1.281-0.333 4.214-2.375 5.172-3.068 0.083-0.068 0.052-0.12 0.052-0.12zM12.974 23.76c-4.323-2.922-12.693-7.385-12.693-7.385-0.203 0.609-0.266 1.198-0.281 1.729v0.094c0 1.427 0.531 2.427 0.531 2.427 1.068 2.255 3.12 2.938 3.12 2.938 0.938 0.396 1.87 0.411 1.87 0.411 0.161 0.026 5.865 0 7.385 0 0.068 0 0.109-0.068 0.109-0.068v-0.078c0-0.042-0.042-0.068-0.042-0.068zM12.078 4.255c-1.938 0.495-3.328 2.198-3.427 4.198v0.547c0.042 0.802 0.214 1.401 0.214 1.401 0.88 3.865 5.151 10.198 6.068 11.531 0.068 0.068 0.135 0.042 0.135 0.042 0.052-0.021 0.083-0.078 0.078-0.135 1.417-14.13-1.479-17.891-1.479-17.891-0.427 0.026-1.589 0.307-1.589 0.307zM23.146 7.281c0 0-0.651-2.401-3.25-3.042 0 0-0.76-0.188-1.563-0.292 0 0-2.906 3.745-1.495 17.906 0.016 0.094 0.083 0.104 0.083 0.104 0.094 0.042 0.13-0.036 0.13-0.036 0.964-1.375 5.203-7.682 6.068-11.521 0 0 0.479-1.87 0.026-3.12zM19.255 24.708c0 0-0.094 0-0.12 0.063 0 0-0.016 0.094 0.036 0.135 0.932 0.682 3.802 2.667 5.177 3.068 0 0 0.214 0.068 0.573 0.078h0.182c0.922-0.026 2.536-0.49 4-3.010l-9.865-0.333zM29.693 13.495c0.188-2.75-2.589-5.297-2.589-5.307 0 0-5.13 6.214-8.891 14.401 0 0-0.042 0.104 0.026 0.172l0.052 0.010h0.083c1.411-0.703 7.276-3.693 9.703-6.052 0 0 1.536-1.24 1.615-3.224zM31.719 16.349c0 0-8.37 4.49-12.693 7.396 0 0-0.068 0.057-0.042 0.151 0 0 0.042 0.078 0.094 0.078 1.547 0 7.417 0 7.563-0.026 0 0 0.76-0.026 1.693-0.385 0 0 2.078-0.667 3.161-3.031 0 0 0.974-1.932 0.224-4.182z" />
        </svg>
        <svg v-else fill="red" width="23px" height="23px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M4.896 8.188c0 0-2.469 2.359-2.604 4.854v0.464c0.109 2.016 1.63 3.203 1.63 3.203 2.438 2.385 8.344 5.385 9.729 6.063 0 0 0.083 0.042 0.135-0.010l0.026-0.052v-0.057c-3.786-8.25-8.917-14.464-8.917-14.464zM12.865 24.802c-0.026-0.109-0.13-0.109-0.13-0.109l-9.839 0.349c1.063 1.906 2.865 3.37 4.745 2.932 1.281-0.333 4.214-2.375 5.172-3.068 0.083-0.068 0.052-0.12 0.052-0.12zM12.974 23.76c-4.323-2.922-12.693-7.385-12.693-7.385-0.203 0.609-0.266 1.198-0.281 1.729v0.094c0 1.427 0.531 2.427 0.531 2.427 1.068 2.255 3.12 2.938 3.12 2.938 0.938 0.396 1.87 0.411 1.87 0.411 0.161 0.026 5.865 0 7.385 0 0.068 0 0.109-0.068 0.109-0.068v-0.078c0-0.042-0.042-0.068-0.042-0.068zM12.078 4.255c-1.938 0.495-3.328 2.198-3.427 4.198v0.547c0.042 0.802 0.214 1.401 0.214 1.401 0.88 3.865 5.151 10.198 6.068 11.531 0.068 0.068 0.135 0.042 0.135 0.042 0.052-0.021 0.083-0.078 0.078-0.135 1.417-14.13-1.479-17.891-1.479-17.891-0.427 0.026-1.589 0.307-1.589 0.307zM23.146 7.281c0 0-0.651-2.401-3.25-3.042 0 0-0.76-0.188-1.563-0.292 0 0-2.906 3.745-1.495 17.906 0.016 0.094 0.083 0.104 0.083 0.104 0.094 0.042 0.13-0.036 0.13-0.036 0.964-1.375 5.203-7.682 6.068-11.521 0 0 0.479-1.87 0.026-3.12zM19.255 24.708c0 0-0.094 0-0.12 0.063 0 0-0.016 0.094 0.036 0.135 0.932 0.682 3.802 2.667 5.177 3.068 0 0 0.214 0.068 0.573 0.078h0.182c0.922-0.026 2.536-0.49 4-3.010l-9.865-0.333zM29.693 13.495c0.188-2.75-2.589-5.297-2.589-5.307 0 0-5.13 6.214-8.891 14.401 0 0-0.042 0.104 0.026 0.172l0.052 0.010h0.083c1.411-0.703 7.276-3.693 9.703-6.052 0 0 1.536-1.24 1.615-3.224zM31.719 16.349c0 0-8.37 4.49-12.693 7.396 0 0-0.068 0.057-0.042 0.151 0 0 0.042 0.078 0.094 0.078 1.547 0 7.417 0 7.563-0.026 0 0 0.76-0.026 1.693-0.385 0 0 2.078-0.667 3.161-3.031 0 0 0.974-1.932 0.224-4.182z" />
        </svg>
        <span class="mx-3">Huawei</span>
    </a>
    <MyTransition v-if="subModulePermission(submodules.huawei_sites_submodule, userSubModules)"
        :transitiondemonstration="showHuawei">
        <Link class="w-full" :href="route('huawei.sites')">Sites Huawei</Link>
    </MyTransition>
    <MyTransition v-if="subModulePermission(submodules.huawei_projects_submodule, userSubModules)"
        :transitiondemonstration="showHuawei">
        <Link class="w-full" :href="route('huawei.projects', { status: 1, prefix: 'Claro' })">Proyectos
        Huawei
        </Link>
    </MyTransition>
    <MyTransition v-if="subModulePermission(submodules.huawei_inventory_submodule, userSubModules)"
        :transitiondemonstration="showHuawei">
        <div class="relative">
            <Link class="w-full" :href="route('huawei.inventory.show', { warehouse: 1 })">Inventario
            Huawei
            </Link>
            <button @click="showPendingOrders = !showPendingOrders">
                <span v-if="pending_orders.length > 0"
                    class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                    {{ pending_orders.length }}
                </span>
            </button>
        </div>
    </MyTransition>

    <template v-if="pending_orders.length !== 0">
        <MyTransition v-for="item in pending_orders" :key="item.id" class="ml-4"
            :transitiondemonstration="showPendingOrders">
            <Link class="w-full flex items-center" :href="route('huawei.inventory.pendingorders')">
            <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                fill="currentColor" viewBox="0 0 20 20">
                <path
                    d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
            </svg>
            <span>{{ item.order_number }}</span>
            </Link>
        </MyTransition>
    </template>

    <MyTransition v-if="subModulePermission(submodules.huawei_internal_inventory_submodule, userSubModules)"
        :transitiondemonstration="showHuawei">
        <Link class="w-full" :href="route('huawei.quickmaterials')">Inventario Interno</Link>
    </MyTransition>
    <MyTransition v-if="subModulePermission(submodules.huawei_interal_guides_submodule, userSubModules)"
        :transitiondemonstration="showHuawei">
        <Link class="w-full" :href="route('huawei.internalguides')">Guías Internas</Link>
    </MyTransition>
    <MyTransition v-if="subModulePermission(submodules.huawei_special_returns_submodule, userSubModules)"
        :transitiondemonstration="showHuawei">
        <Link class="w-full" :href="route('huawei.specialrefunds')">Devoluciones Especiales</Link>
    </MyTransition>
</template>
<script setup>
import MyTransition from '@/Components/MyTransition.vue';
import { Link, usePage } from '@inertiajs/vue3';
import { subModulePermission } from '@/utils/roles/roles';
import { onMounted, ref } from 'vue';

const showHuawei = ref(false)
const showPendingOrders = ref(false)
const pending_orders = ref([])

const { submodules } = usePage().props
const { userSubModules } = usePage().props.auth

function toogleHUawei() {
    showHuawei.value = !showHuawei.value;
    if (!showHuawei.value) {
        showPendingOrders.value = false;
    }
}

async function fetchPendingOrders() {
    try {
        const response = await axios.get(route('huawei.inventory.pendingorders.fetch'));
        pending_orders.value = Object.values(response.data.orders);
    } catch (error) {
        console.error(error);
    }
}

// onMounted(() => {
//     if (hasPermission('HuaweiManager')) {
//         fetchPendingOrders();
//         setInterval(fetchPendingOrders, 60000)
//     }
// })
</script>