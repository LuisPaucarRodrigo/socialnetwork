<template>
    <div class="my-3 sm:flex sm:gap-4 sm:justify-between">
        <div class="flex items-center justify-between gap-x-3 w-full">
            <div v-if="hasPermission('HumanResourceManager')" class="hidden sm:flex">
                <Link :href="route('management.employees.create')"
                    class="bg-indigo-600 hover:bg-indigo-500 rounded-md px-4 py-2 text-center text-sm text-white">
                + Agregar
                </Link>
            </div>

            <div v-if="hasPermission('HumanResourceManager')" class="sm:hidden">
                <dropdown align='left'>
                    <template #trigger>
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                            <svg width="25px" height="25px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M4 6H20M4 12H20M4 18H20" stroke="#000000" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </template>

                    <template #content class="origin-left">
                        <div>
                            <div class="dropdown">
                                <div class="dropdown-menu">
                                    <Link :href="route('management.employees.create')"
                                        class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                    + Agregar
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </template>
                </dropdown>
            </div>
            <PrimaryButton @click="$emit('reentry')" type="button">
                {{ form.state === 'Inactive' ? "Activos" : "Inactivos" }}
            </PrimaryButton>
        </div>
        <div class="flex items-center mt-4 sm:mt-0">
            <Search v-model:search="form.search" fields="Nombre,Apellido,Telefono,Dni" />
        </div>
    </div>
</template>

<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Search from '@/Components/Search.vue';
import { Link } from '@inertiajs/vue3';
import Dropdown from "@/Components/Dropdown.vue";

const { userPermission, form } = defineProps({
    userPermission: Array,
    form: Object
})

const hasPermission = (permission) => {
    return userPermission.includes(permission);
}

</script>