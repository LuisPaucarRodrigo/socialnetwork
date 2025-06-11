<template>
    <TableStructure :style="'h-[72vh]'">
        <template #thead>
            <tr>
                <TableTitle>Perfil</TableTitle>
                <TableTitle>
                    <div class="w-[190px]">
                        <TableHeaderCicsaFilter label="Linea de Negocio" labelClass="text-gray-600" :options="cost_line"
                            v-model="formSearch.cost_line" />
                    </div>
                </TableTitle>
                <TableTitle>Nombre</TableTitle>
                <TableTitle>Apellido</TableTitle>
                <TableTitle>DNI</TableTitle>
                <TableTitle>Telefono</TableTitle>
                <TableTitle>Fecha de Nacimiento</TableTitle>
                <TableTitle>Dirección</TableTitle>
                <TableTitle>Correo</TableTitle>
                <TableTitle>Correo Empresarial</TableTitle>
                <TableTitle>Salario</TableTitle>
                <TableTitle>Sctr</TableTitle>
                <TableTitle v-permission="'see_external_employee_document'">Curriculum</TableTitle>
                <TableTitle v-permission-or="['add_external_employee', 'delete_external_employee']">Acciones
                </TableTitle>
            </tr>
        </template>
        <template #tbody>
            <tr v-for="employee in employees.data || employees" :key="employee.id" class="text-gray-700">
                <TableRow>
                    <img :src="employee.cropped_image" alt="Empleado" class="w-12 h-13 rounded-full">
                </TableRow>
                <TableRow>{{ employee?.cost_line?.name }}</TableRow>
                <TableRow>{{ employee.name }}</TableRow>
                <TableRow>{{ employee.lastname }}</TableRow>
                <TableRow>{{ employee.dni }}</TableRow>
                <TableRow>{{ employee.phone1 }}</TableRow>
                <TableRow>{{ employee.birthdate }}</TableRow>
                <TableRow :width="'w-[250px]'">{{ employee.address }}</TableRow>
                <TableRow>{{ employee.email }}</TableRow>
                <TableRow>{{ employee.email_company }}</TableRow>
                <TableRow>{{ employee.salary }}</TableRow>
                <TableRow>{{ employee.sctr ? "Si" : "No" }}</TableRow>
                <TableRow v-permission="'see_external_employee_document'">
                    <button v-if="employee.curriculum_vitae" @click="handlerPreview(employee.id)">
                        <ShowIcon />
                    </button>
                    <span v-else>-</span>
                </TableRow>
                <TableRow v-permission-or="['add_external_employee', 'delete_external_employee']">
                    <div class="flex space-x-3 justify-center">
                        <button v-permission="'add_external_employee'" type="button" @click="openExternal(employee)">
                            <EditIcon />
                        </button>
                        <button v-permission="'delete_external_employee'" type="button"
                            @click="confirmUserDeletion(employee.id)">
                            <DeleteIcon />
                        </button>
                    </div>
                </TableRow>
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
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import Pagination from '@/Components/Pagination.vue';
import { ShowIcon, EditIcon, DeleteIcon } from "@/Components/Icons/Index";

const { employees, formSearch, cost_line, openExternal, confirmUserDeletion } = defineProps({
    employees: Object,
    formSearch: Object,
    cost_line: Array,
    openExternal: Function,
    confirmUserDeletion: Function
})

function handlerPreview(id) {
    window.open(
        route("employees.external.preview.curriculum_vitae", { external_preview_id: id }),
        '_blank'
    );
}
</script>