<template>
    <div :class="$page.props.showingMobileMenu ? 'block' : 'hidden'" @click="$page.props.showingMobileMenu = false"
        class="fixed inset-0 z-20 bg-black opacity-50 transition-opacity lg:hidden"></div>

    <div :class="$page.props.showingMobileMenu ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
        class="overflow-y-auto fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 transition duration-300 transform lg:translate-x-0 lg:static lg:inset-0">
        <div class="flex justify-center items-center mt-8">
            <div class="flex items-center">
                <svg class="w-12 h-12" viewBox="0 0 512 512" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M364.61 390.213C304.625 450.196 207.37 450.196 147.386 390.213C117.394 360.22 102.398 320.911 102.398 281.6C102.398 242.291 117.394 202.981 147.386 172.989C147.386 230.4 153.6 281.6 230.4 307.2C230.4 256 256 102.4 294.4 76.7999C320 128 334.618 142.997 364.608 172.989C394.601 202.981 409.597 242.291 409.597 281.6C409.597 320.911 394.601 360.22 364.61 390.213Z"
                        fill="#4C51BF" stroke="#4C51BF" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M201.694 387.105C231.686 417.098 280.312 417.098 310.305 387.105C325.301 372.109 332.8 352.456 332.8 332.8C332.8 313.144 325.301 293.491 310.305 278.495C295.309 263.498 288 256 275.2 230.4C256 243.2 243.201 320 243.201 345.6C201.694 345.6 179.2 332.8 179.2 332.8C179.2 352.456 186.698 372.109 201.694 387.105Z"
                        fill="white" />
                </svg>

                <span class="mx-2 text-2xl font-semibold text-white">Dashboard</span>
            </div>
        </div>

        <nav class="mt-10" x-data="{ isMultiLevelMenuOpen: false }">
            <nav-link :href="route('users.index')" :active="route().current('users.index')">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </template>
                Usuarios
            </nav-link>

            <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                @click="showingRecursosHumanos = !showingRecursosHumanos">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                </svg>
                <span class="mx-3">Recursos Humanos</span>
            </a>

            <MyTransition :transitiondemonstration="showingRecursosHumanos">
                <Link class="w-full" :href="route('management.employees')">Gestion de Empleados</Link>
            </MyTransition>
            <MyTransition :transitiondemonstration="showingRecursosHumanos">
                <Link class="w-full" :href="route('spreadsheets.index')">Nomina</Link>
            </MyTransition>
            <MyTransition :transitiondemonstration="showingRecursosHumanos">
                <Link class="w-full"  :href="route('management.employees.formation_development')">Formacion y Desarrollo</Link>
            </MyTransition>
            <MyTransition :transitiondemonstration="showingRecursosHumanos">
                <Link class="w-full" :href="route('users.index')">Gestion de Vacaciones</Link>
            </MyTransition>

            <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                @click="showingFinance = !showingFinance">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>

                <span class="mx-3">Finanzas</span>
            </a>

            <MyTransition :transitiondemonstration="showingFinance">
                <Link class="w-full" :href="route('users.index')">Contabilidad</Link>
            </MyTransition>

            <MyTransition :transitiondemonstration="showingFinance">
                <Link class="w-full" :href="route('users.index')">Presupuestos</Link>
            </MyTransition>

            <MyTransition :transitiondemonstration="showingFinance">
                <Link class="w-full" :href="route('managementexpense.index')">Gestion de Gastos</Link>
            </MyTransition>

            <MyTransition :transitiondemonstration="showingFinance">
                <Link class="w-full" :href="route('users.index')">Informe de Analisis Financiero</Link>
            </MyTransition>

            <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                @click="showingInventory = !showingInventory">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75zM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-8.25zM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-2.25z" />
                </svg>
                <span class="mx-3">Inventario</span>
            </a>
            <MyTransition :transitiondemonstration="showingInventory">
                <Link class="w-full" :href="route('users.index')">Control de Inventario</Link>
            </MyTransition>
            <MyTransition :transitiondemonstration="showingInventory">
                <Link class="w-full" :href="route('users.index')">Historial de Movimientos</Link>
            </MyTransition>
            <MyTransition :transitiondemonstration="showingInventory">
                <Link class="w-full" :href="route('users.index')">Informe de Inventario</Link>
            </MyTransition>
            <MyTransition :transitiondemonstration="showingInventory">
                <Link class="w-full" :href="route('users.index')">Integracion de Compras</Link>
            </MyTransition>
            <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                @click="showingProyectArea = !showingProyectArea">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                </svg>

                <span class="mx-3">Area de Proyectos</span>
            </a>
            <MyTransition :transitiondemonstration="showingProyectArea">
                <Link class="w-full" :href="route('projectmanagement.index')">Gestion de Proyectos</Link>
            </MyTransition>
            <MyTransition :transitiondemonstration="showingProyectArea">
                <Link class="w-full" :href="route('projectschedule.index')">Calendario de Proyectos</Link>
            </MyTransition>
            <MyTransition :transitiondemonstration="showingProyectArea">
                <Link class="w-full" :href="route('users.index')">Seguimiento de Tareas</Link>
            </MyTransition>
            <MyTransition :transitiondemonstration="showingProyectArea">
                <Link class="w-full" :href="route('users.index')">Documentacionn de Proyectos</Link>
            </MyTransition>
            <MyTransition :transitiondemonstration="showingProyectArea">
                <Link class="w-full" :href="route('projectreports.index')">Informe de Proyecto</Link>
            </MyTransition>

            <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                @click="showingShoppingArea = !showingShoppingArea">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <span class="mx-3">Area de Compras</span>
            </a>
            <MyTransition :transitiondemonstration="showingShoppingArea">
                <Link class="w-full" :href="route('purchasesrequest.index')">Solicitud de Cotizaciones</Link>
            </MyTransition>
            <MyTransition :transitiondemonstration="showingShoppingArea">
                <Link class="w-full" :href="route('purchaseorders.index')">Ordenes de compra</Link>
            </MyTransition>
            <MyTransition :transitiondemonstration="showingShoppingArea">
                <Link class="w-full" :href="route('providersmanagement.index')">Gestion de Proveedores</Link>
            </MyTransition>
            <MyTransition :transitiondemonstration="showingShoppingArea">
                <Link class="w-full" :href="route('purchasereports.index')">Informe de Compras</Link>
            </MyTransition>
        </nav>
    </div>
</template>

<script>
import NavLink from '@/Components/NavLink.vue';
import MyTransition from '@/Components/MyTransition.vue'
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue'

export default {
    components: {
        NavLink,
        Link,
        MyTransition,
    },

    setup() {
        let showingRecursosHumanos = ref(false)
        let showingFinance = ref(false)
        let showingInventory = ref(false)
        let showingProyectArea = ref(false)
        let showingShoppingArea = ref(false)
        return {
            showingRecursosHumanos,
            showingFinance,
            showingInventory,
            showingProyectArea,
            showingShoppingArea
        }
    },

}
</script>

<style scoped></style>
