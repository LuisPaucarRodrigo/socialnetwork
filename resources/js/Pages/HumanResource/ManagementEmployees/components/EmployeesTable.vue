<template>
    <TableStructure :style="'h-[72vh]'">
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
                <TableTitle  v-permission-or="[
                    'see_employee',
                    'edit_employee',
                    'fired_reentry_employee',
                ]"></TableTitle>
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
                <td
                v-permission-or="[
                    'see_employee',
                    'edit_employee',
                    'fired_reentry_employee',
                ]"
                class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                    <div v-if="employee.contract.fired_date == null" class="flex space-x-3 justify-center">
                        <Link 
                            v-permission="'see_employee'"
                            :href="route('see_employee', { id: employee.id })">
                            <EyeIcon class="w-6 h-6 text-green-600" />
                        </Link>
                        <Link 
                            v-permission="'edit_employee'"
                            :href="route('edit_employee', { id: employee.id })">
                            <PencilSquareIcon class="w-6 h-6 text-yellow-400" />
                        </Link>
                        <button  v-permission-and="[
                            'fired_reentry_employee',
                        ]"
                        type="button" @click="confirmFired(employee.id)">
                            <ArrowDownTrayIcon class="w-6 h-6"/>
                        </button>
                    </div>
                    <button
                        v-permission-and="[
                            'fired_reentry_employee',
                        ]"
                        v-if="employee.contract.fired_date" type="button" @click="employee_fired_date(employee.id)">
                        <ArrowUpTrayIcon class="w-6 h-6"/>
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
import TableStructure from '@/Layouts/TableStructure.vue';
import { ArrowDownTrayIcon, ArrowUpTrayIcon, EyeIcon, PencilSquareIcon } from "@heroicons/vue/24/outline";


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