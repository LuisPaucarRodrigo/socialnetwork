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
                <TableTitle v-permission-or="[
                    'management_employees_show',
                    'management_employees_edit',
                    'management_employees_reentry',
                    'management_employees',
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
                <td v-permission-or="[
                    'management_employees_show',
                    'management_employees_edit',
                    'management_employees_reentry',
                    'management_employees',
                    'management_employees_fired',
                ]" class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                    <div v-if="employee.contract.fired_date == null" class="flex space-x-3 justify-center">
                        <Link v-permission="'management_employees_show'"
                            :href="route('management.employees.show', { id: employee.id })">
                        <ShowIcon />
                        </Link>
                        <Link v-permission="'management_employees_edit'"
                            :href="route('management.employees.edit', { id: employee.id })">
                        <EditIcon />
                        </Link>
                        <button v-permission-and="[
                            'management_employees_reentry',
                            'management_employees'
                        ]" type="button" @click="confirmFired(employee.id)">
                            <UnsubscribeIcon />
                        </button>
                    </div>
                    <button v-permission-and="[
                        'management_employees_reentry',
                        'management_employees'
                    ]" v-if="employee.contract.fired_date" type="button" @click="employee_fired_date(employee.id)">
                        <SuscribeIcon />
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
import UnsubscribeIcon from '@/Components/Icons/UnsubscribeIcon.vue';
import SuscribeIcon from '@/Components/Icons/SuscribeIcon.vue';
import EditIcon from '@/Components/Icons/EditIcon.vue';
import ShowIcon from '@/Components/Icons/ShowIcon.vue';

const { form, employees, costLine, userPermission, confirmFired, employee_fired_date } = defineProps({
    form: Object,
    employees: Object,
    costLine: Array,
    userPermission: Array,
    confirmFired: Function,
    employee_fired_date: Function
})
</script>