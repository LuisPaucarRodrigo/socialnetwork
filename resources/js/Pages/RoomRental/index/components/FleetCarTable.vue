<template>
    <TableStructure :style="'h-[72vh]'">
        <template #thead>
            <tr>
                <th class="bg-gray-100">
                    <div class="w-2"></div>
                </th>
                <TableTitle v-permission="'room_actions_manager'" :style="'bg-gray-100 whitespace-nowrap'">
                    <TableHeaderCicsaFilter label="Linea de Negocio" labelClass="text-gray-600" :options="cost_line"
                        v-model="formSearch.cost_line" />
                </TableTitle>
                <TableTitle>Proveedor</TableTitle>
                <TableTitle>Contacto</TableTitle>
                <TableTitle>Zona</TableTitle>
                <TableTitle>Teléfono</TableTitle>
                <TableTitle>Tipo</TableTitle>
                <TableTitle>Dirección</TableTitle>
                <TableTitle>Fotos</TableTitle>
                <TableTitle :colspan="2" v-permission-or="[
                    'room_actions_manager',
                    'room_actions',
                ]">Acciones</TableTitle>
            </tr>
        </template>
        <template #tbody>
            <template v-for="(car, i) in cars.data || cars" :key="car.id">
                <tr>
                    <th>
                        <div class="w-2"></div>
                    </th>
                    <TableRow v-permission="'room_actions_manager'">
                        {{ car.costline?.name }}
                    </TableRow>
                    <TableRow>{{ car.provider.company_name }}</TableRow>
                    <TableRow>{{ car.provider.contact_name }}</TableRow>
                    <TableRow>{{ car.provider.zone }}</TableRow>
                    <TableRow>{{ [car.phone1, car.phone2].join(', ') }}</TableRow>
                    <TableRow>{{ car.rental_type }}</TableRow>
                    <TableRow>{{ car.address }}</TableRow>
                    <TableRow>
                        <button v-if="car.room_images_count > 0" @click="openImagesModal(car.id)">
                            <ShowIcon />
                        </button>
                    </TableRow>
                    <TableRow :colspan="2" v-permission-or="[
                        'room_actions_manager',
                        'room_actions'
                    ]">
                        <div class="flex space-x-3 justify-center">
                            <button v-permission="'room_actions_manager'" @click="openImagesForm(car.id)">
                                <ImagesIcon />
                            </button>
                            <button v-permission-or="['room_actions', 'room_actions_manager']"
                                @click="openformDocument(car)">
                                <DocumentsIcon :color="'text-blue-400'" />
                            </button>
                            <button v-permission="'room_actions_manager'" type="button" @click="openEditFormCar(car)">
                                <EditIcon />
                            </button>
                            <button v-permission="'room_actions_manager'" type="button"
                                @click="openModalDeleteCars(car.id)">
                                <DeleteIcon />
                            </button>
                            <button v-if="car.room_documents.length > 0" type="button" @click="toogleDocument(car)">
                                <DownArrowIcon v-if="carId !== car.id" />
                                <UpArrowIcon v-else />
                            </button>
                        </div>
                    </TableRow>
                </tr>
                <template v-if="carId == car.id">
                    <tr>
                        <td colspan="100%">
                            <table class="w-full">
                                <thead>
                                    <TableTitle :style="'bg-gray-200'">Fecha de expiración</TableTitle>
                                    <TableTitle :style="'bg-gray-200'">Archivo</TableTitle>
                                    <TableTitle :style="'bg-gray-200'">Observaciones</TableTitle>
                                    <TableTitle :style="'bg-gray-200'"></TableTitle>
                                </thead>
                                <tbody>
                                    <template v-for="document in car.room_documents" :key="document.id">
                                        <tr>
                                            <TableRow>{{ formattedDate(document.expiration_date) }}</TableRow>
                                            <TableRow>
                                                <div class="flex justify-center ">
                                                    <a target="_blank" :href="route('room.rental.show_documents', {
                                                        car_document: document.id,
                                                        fieldName: 'archive',
                                                    }) + '?' + uniqueParam
                                                        ">
                                                        <ShowIcon />
                                                    </a>
                                                </div>
                                            </TableRow>
                                            <TableRow>{{ document.observations }}</TableRow>
                                            <TableRow>
                                                <div class="flex justify-center items-center gap-2">
                                                    <button type="button" v-permission="'room_actions_manager'" @click="
                                                        openformDocument(car, document)">
                                                        <EditIcon />
                                                    </button>
                                                    <button type="button" v-permission="'room_actions_manager'" @click="
                                                        openModalDeleteChangelog(document.id)">
                                                        <DeleteIcon />
                                                    </button>
                                                </div>
                                            </TableRow>
                                        </tr>
                                    </template>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </template>
            </template>
        </template>
    </TableStructure>
    <div v-if="cars.data" class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <Pagination :links="cars.links" />
    </div>
</template>
<script setup>
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableRow from '@/Components/TableRow.vue';
import Pagination from '@/Components/Pagination.vue';
import TableHeaderCicsaFilter from '@/Components/TableHeaderCicsaFilter.vue';
import { ref } from 'vue';
import { formattedDate } from '@/utils/utils';
import { ShowIcon, EditIcon, DeleteIcon, DownArrowIcon, UpArrowIcon, DocumentsIcon, ImagesIcon } from '@/Components/Icons';

const { formSearch, cars, cost_line, openEditFormCar, openformDocument, openModalDeleteCars, openModalDeleteChangelog, openImagesModal, openImagesForm
} = defineProps({
    formSearch: Object,
    cars: Object,
    cost_line: Object,
    openEditFormCar: Function,
    openformDocument: Function,
    openModalDeleteCars: Function,
    openModalDeleteChangelog: Function,
    openImagesModal: Function,
    openImagesForm: Function
})

const carId = ref(null);
const uniqueParam = ref(`timestamp=${new Date().getTime()}`);


function toogleDocument(item) {
    if (carId.value === item.id) {
        carId.value = null;
    } else {
        carId.value = item.id;
    }
}

</script>