<template>
    <TableStructure :style="'h-[72vh]'" :info="employees">
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
                <TableTitle v-if="form.state === 'Inactive'">Dias Tomados</TableTitle>
                <TableTitle v-if="form.state === 'Inactive'">Documento de Alta</TableTitle>
                <TableTitle v-permission-or="[
                    'see_employee',
                    'edit_employee',
                    'fired_reentry_employee',
                ]">Acciones</TableTitle>
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
                <TableRow v-if="form.state === 'Inactive'">{{ employee.contract.days_taken }}</TableRow>
                <TableRow v-if="form.state === 'Inactive'">
                    <button v-if="employee.contract.discharge_document" @click="handlerPreview(employee.contract.id)">
                        <ShowIcon />
                    </button>
                </TableRow>
                <td v-permission-or="[
                    'see_employee',
                    'edit_employee',
                    'fired_reentry_employee',
                ]" class="border-b border-gray-200 bg-white px-5 py-2 text-sm">
                    <div v-if="employee.contract.fired_date == null" class="flex space-x-3 justify-center">
                        <button v-if="employee.cropped_image" @click="openFotoCheck(employee)">
                            <IdentificationIcon />
                        </button>

                        <Link v-permission="'see_employee'"
                            :href="route('management.employees.show', { id: employee.id })">
                        <ShowIcon />
                        </Link>
                        <Link v-permission="'edit_employee'"
                            :href="route('management.employees.edit', { id: employee.id })">
                        <EditIcon />
                        </Link>
                        <button v-permission="'fired_reentry_employee'" type="button"
                            @click="openFiredModal(employee.id)">
                            <UnsubscribeIcon />
                        </button>
                    </div>
                    <button v-permission="'fired_reentry_employee'" v-if="employee.contract.fired_date" type="button"
                        @click="openReentryModal(employee.id)">
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
import { UnsubscribeIcon, SuscribeIcon, EditIcon, ShowIcon, IdentificationIcon } from '@/Components/Icons';
import TableStructure from '@/Layouts/TableStructure.vue';


const { form, employees, costLine, openFiredModal, openReentryModal, openFotoCheck } = defineProps({
    form: Object,
    employees: Object,
    costLine: Array,
    openFiredModal: Function,
    openReentryModal: Function,
    openFotoCheck: Function
})

function handlerPreview(contract_id) {
    window.open(
        route("management.employees.show.preview.doc_alta", { id: contract_id }),
        '_blank'
    );
}




</script>