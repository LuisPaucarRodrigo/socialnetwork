<template>
    <div class="my-3 sm:flex sm:gap-4 sm:justify-between">
        <div class="flex items-center justify-between gap-x-3 w-full">
            <div v-permission="'add_employee'" class="hidden sm:flex">
                <Link :href="route('management.employees.create')"
                    class="bg-indigo-600 hover:bg-indigo-500 rounded-md px-4 py-2 text-center text-sm text-white">
                + Agregar
                </Link>
            </div>

            <div v-permission="'add_employee'" class="sm:hidden">
                <dropdown align='left'>
                    <template #trigger>
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                            <MenuIcon/>
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
            <PrimaryButton v-permission="'see_inactive_employees_table'" @click="$emit('reentry')" type="button">
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
import { MenuIcon } from '@/Components/Icons';

const { form } = defineProps({
    form: Object
})

</script>