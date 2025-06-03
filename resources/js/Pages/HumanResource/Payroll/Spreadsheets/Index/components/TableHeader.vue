<template>
    <div class="mt-6 flex flex-col sm:flex-row sm:items-center justify-between sm:gap-x-3 gap-y-4">
        <div class="flex items-center justify-between gap-x-6 w-full">
            <div class="hidden sm:flex sm:items-center sm:space-x-2 pl-3">
                <button v-if="!payrolls.state" @click="openPayrollApprove()"
                    class="rounded-md px-1 py-2 text-center text-sm text-white hover:bg-green-400">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" fill="#228b22" />
                    </svg>
                </button>
                <button @click="openPaySpreadsheet()"
                    class="rounded-md px-1 py-2 text-blue-600 text-center text-sm hover:bg-blue-200">
                       <DolarIcon class="h-6 w-6"/>
                </button>

                <a :href="route('spreadsheets.payroll.export', { payroll_id: payrolls.id })"
                    class="rounded-md px-1 py-2 text-center text-sm text-white hover:bg-green-400">
                    <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"
                        stroke-width="1.5">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M9.29289 1.29289C9.48043 1.10536 9.73478 1 10 1H18C19.6569 1 21 2.34315 21 4V9C21 9.55228 20.5523 10 20 10C19.4477 10 19 9.55228 19 9V4C19 3.44772 18.5523 3 18 3H11V8C11 8.55228 10.5523 9 10 9H5V20C5 20.5523 5.44772 21 6 21H7C7.55228 21 8 21.4477 8 22C8 22.5523 7.55228 23 7 23H6C4.34315 23 3 21.6569 3 20V8C3 7.73478 3.10536 7.48043 3.29289 7.29289L9.29289 1.29289ZM6.41421 7H9V4.41421L6.41421 7ZM19 12C19.5523 12 20 12.4477 20 13V19H23C23.5523 19 24 19.4477 24 20C24 20.5523 23.5523 21 23 21H19C18.4477 21 18 20.5523 18 20V13C18 12.4477 18.4477 12 19 12ZM11.8137 12.4188C11.4927 11.9693 10.8682 11.8653 10.4188 12.1863C9.96935 12.5073 9.86526 13.1318 10.1863 13.5812L12.2711 16.5L10.1863 19.4188C9.86526 19.8682 9.96935 20.4927 10.4188 20.8137C10.8682 21.1347 11.4927 21.0307 11.8137 20.5812L13.5 18.2205L15.1863 20.5812C15.5073 21.0307 16.1318 21.1347 16.5812 20.8137C17.0307 20.4927 17.1347 19.8682 16.8137 19.4188L14.7289 16.5L16.8137 13.5812C17.1347 13.1318 17.0307 12.5073 16.5812 12.1863C16.1318 11.8653 15.5073 11.9693 15.1863 12.4188L13.5 14.7795L11.8137 12.4188Z"
                            fill="#228b22" />
                    </svg>
                </a>
                <Link :href="route('payroll.index.payroll.external.detail', {payroll_id: payrolls.id})"
                    class="bg-indigo-600 hover:bg-indigo-500 rounded-md px-4 py-2 text-center text-sm text-white">
                    PS 4ta categoría
                </Link>
                <Link :href="route('payroll.detail.expense.index', {payroll_id: payrolls.id})"
                    class="bg-gray-600 hover:bg-gray-500 rounded-md px-4 py-2 text-center text-sm text-white">
                    Pagos
                </Link>
            </div>
            <div class="sm:hidden">
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
                                    <a :href="route('spreadsheets.payroll.export', { payroll_id: payrolls.id })"
                                        class="dropdown-item block w-full text-left px-4 py-2 text-sm text-black-700 hover:bg-indigo-600 hover:text-white focus:outline-none focus:bg-gray-100 transition duration-150 ease-in-out">
                                        Exportar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </template>
                </dropdown>
            </div>
            <div>
                <TextInput type="text" placeholder="Buscar..." @input="search($event.target.value)" />
            </div>
        </div>
    </div>
</template>
<script setup>
import Dropdown from '@/Components/Dropdown.vue';
import { notifyError } from '@/Components/Notification';
import TextInput from '@/Components/TextInput.vue';
import { Link } from '@inertiajs/vue3';
import { DolarIcon } from '@/Components/icons';

const { payrolls, openPayrollApprove, openPaySpreadsheet } = defineProps({
    payrolls: Object,
    openPayrollApprove: Function,
    openPaySpreadsheet: Function,
})

const spreadsheets = defineModel('spreadsheets')
const totals = defineModel('totals')

async function search(employee) {
    let url = route('spreadsheets.index', { payroll_id: payrolls.id })
    try {
        let response = await axios.post(url, { searchQuery: employee })
        spreadsheets.value = response.data.spreadsheet
        totals.value = response.data.total
    } catch (error) {
        notifyError(error)
    }
}
</script>