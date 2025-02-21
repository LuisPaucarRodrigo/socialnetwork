<template>
    <TableStructure>
        <template #thead>
            <tr>
                <TableTitle>Perfil</TableTitle>
                <TableTitle>
                    <div class="w-[190px]">
                        <TableHeaderCicsaFilter label="Linea de Negocio" labelClass="text-gray-600" :options="costLine"
                            v-model="form.cost_line" />
                    </div>
                </TableTitle>
                <TableTitle>Nombre</TableTitle>
                <TableTitle>Apellido</TableTitle>
                <TableTitle>DNI</TableTitle>
                <TableTitle>Telefono</TableTitle>
                <TableTitle>Fecha de Ingreso</TableTitle>
                <TableTitle></TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="employee, i in employees.data || employees" :key="employee.id" class="text-gray-700">
                <TableRow>
                    <img :src="employee.cropped_image" alt="Empleado" class="w-12 h-13 rounded-full">
                </TableRow>
                <TableRow>{{ employee.contract?.cost_line?.name }}</TableRow>
                <TableRow>{{ employee.name }}</TableRow>
                <TableRow>{{ employee.lastname }}</TableRow>
                <TableRow>{{ employee.dni }}</TableRow>
                <TableRow>{{ employee.phone1 }}</TableRow>
                <TableRow>{{ formattedDate(employee.contract.hire_date) }}</TableRow>
                <td class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                    <div v-if="employee.contract.fired_date == null" class="flex space-x-3 justify-center">
                        <Link class="text-blue-900" :href="route('management.employees.show', { id: employee.id })">
                        <EyeIcon class="w-6 h-6 text-green-600" />
                        </Link>
                        <Link v-if="hasPermission('HumanResourceManager')" class="text-blue-900"
                            :href="route('management.employees.edit', { id: employee.id })">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-amber-400">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                        </svg>
                        </Link>
                        <button v-if="hasPermission('UserManager')" type="button" @click="confirmFired(employee.id)"
                            class="text-blue-900">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                            </svg>
                        </button>
                    </div>
                    <button v-if="employee.contract.fired_date" type="button" @click="employee_fired_date(employee.id)"
                        class="text-blue-900">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5m-13.5-9L12 3m0 0 4.5 4.5M12 3v13.5" />
                        </svg>
                    </button>
                </td>
            </tr>
        </template>
    </TableStructure>
    <div v-if="employees.data"
        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <Pagination :links="employees.links" />
    </div>
</template>
<script setup>
import TableHeaderCicsaFilter from '@/Components/TableHeaderCicsaFilter.vue';
import { formattedDate } from '@/utils/utils';
import TableRow from '@/Components/TableRow.vue';
import Pagination from '@/Components/Pagination.vue';
import TableTitle from '@/Components/TableTitle.vue';
import { Link } from '@inertiajs/vue3';
import TableStructure from '../TableStructure.vue';
import { EyeIcon } from "@heroicons/vue/24/outline";


const { form, employees, costLine, userPermission, confirmFired, employee_fired_date } = defineProps({
    form: Object,
    employees: Object,
    costLine: Array,
    userPermission: Array,
    confirmFired: Function,
    employee_fired_date: Function
})

const hasPermission = (permission) => {
    return userPermission.includes(permission);
}
</script>