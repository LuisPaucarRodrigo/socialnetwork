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
    <Modal :show="showFotocheck" :closeable="true" @close="closeFotoCheck" :maxWidth="'sm'">
        <div class="flex items-center justify-center bg-gray-100">
            <div class="py-2">
                <div ref="fotocheck">
                    <div class="w-[300px] h-[450px] relative bg-white shadow-md rounded overflow-hidden">
                        <img :src="backgroundImage" class="absolute inset-0 w-full h-full object-cover " />
                        <div v-if="isConproco" class="relative mt-32">
                            <img :src="employee.cropped_image"
                                class="w-40 h-40 rounded-full mx-auto border-4 border-white" />
                            <div class="mt-10">
                                <h2 class="text-center text-lg mt-2 text-white">{{ employee.name }} {{ employee.lastname
                                }}
                                </h2>
                                <p class="text-center text-sm text-white">DNI: {{ employee.dni }}</p>
                                <p class="text-center text-sm text-white">{{ employee.position }}</p>
                            </div>
                        </div>
                        <div v-else class="relative mt-44">
                            <div class="flex">
                                <img :src="employee.cropped_image"
                                    class="w-40 h-40 rounded-full mx-auto border-4 border-white" />
                                <div class="px-4">
                                    <h2 class="text-center text-lg mt-2 text-black">{{ employee.name }} {{
                                        employee.lastname }}
                                    </h2>
                                    <p class="text-center text-sm text-black">DNI: {{ employee.dni }}</p>
                                    <p class="text-center text-sm text-red">{{ employee.position }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-x-2">
                    <button @click="switchImages()" :class="isConproco ? 'bg-red-500' : 'bg-blue-500'"
                        class="mt-4 text-white px-4 py-2 rounded w-1/3">
                        {{ isConproco ? 'Claro' : 'Conproco' }}
                    </button>
                    <button @click="downloadFotocheck" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded w-2/3">
                        Descargar

                    </button>
                </div>

            </div>
        </div>
    </Modal>
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
import { ref } from 'vue';
import html2canvas from 'html2canvas'
import Modal from '@/Components/Modal.vue';

const { form, employees, costLine, openFiredModal, openReentryModal } = defineProps({
    form: Object,
    employees: Object,
    costLine: Array,
    openFiredModal: Function,
    openReentryModal: Function
})

function handlerPreview(contract_id) {
    window.open(
        route("management.employees.show.preview.doc_alta", { id: contract_id }),
        '_blank'
    );
}

const showFotocheck = ref(false)
const fotocheck = ref(null)
const isConproco = ref(true)
const backgroundImage = ref('/image/projectimage/fotocheckConproco.jpeg')
const employee = ref({
    name: '',
    lastname: '',
    dni: '',
    position: '',
    cropped_image: ''
})

function switchImages() {
    backgroundImage.value = isConproco.value ? '/image/projectimage/fotocheckClaro.jpeg' : '/image/projectimage/fotocheckConproco.jpeg'
    isConproco.value = !isConproco.value
}

function toogleModal() {
    showFotocheck.value = !showFotocheck.value
}

function openFotoCheck(item) {
    toogleModal()
    employee.value = { ...item }
}

function closeFotoCheck() {
    toogleModal()
    employee.value = { ...{} }
}

async function downloadFotocheck() {
    const canvas = await html2canvas(fotocheck.value)
    const link = document.createElement('a')
    link.download = `${employee.value.name}-fotocheck.png`
    link.href = canvas.toDataURL()
    link.click()
}
</script>