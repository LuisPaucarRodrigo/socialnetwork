<template>
    <div class="mt-6 sm:flex sm:gap-4 sm:justify-between">
        <div class="flex items-center justify-between gap-x-3 w-full">
            <div class="hidden sm:flex sm:justify-between space-x-4">
                <PrimaryButton v-permission="'add_external_employee'" @click="openExternal()" type="button">
                    + Agregar
                </PrimaryButton>
            </div>

            <div class="sm:hidden">
                <Dropdown align='left'>
                    <template #trigger>
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="relative block overflow-hidden rounded-md bg-gray-200 px-2 py-2 text-center text-sm text-white hover:bg-gray-100">
                            <MenuIcon />
                        </button>
                    </template>

                    <template #content class="origin-left">
                        <div>
                            <div class="dropdown">
                                <div class="dropdown-menu">
                                    <button type="button" v-permission="'add_external_employee'" @click="openExternal()"
                                        class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        + Agregar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </Dropdown>
            </div>
            <Search fields="Nombre,Apellido,Dni" v-model:search="formSearch.searchQuery" />
        </div>

    </div>
</template>
<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Search from '@/Components/Search.vue';
import Dropdown from "@/Components/Dropdown.vue";
import { MenuIcon } from '@/Components/Icons/Index';

const { openExternal, formSearch } = defineProps({
    openExternal: Function,
    formSearch: Object
})

</script>