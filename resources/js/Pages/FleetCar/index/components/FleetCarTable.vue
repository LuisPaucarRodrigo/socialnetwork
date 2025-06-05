<template>
    <TableStructure :style="'h-[72vh]'">
        <template #thead>
            <tr>
                <th class="bg-gray-100">
                    <div class="w-2"></div>
                </th>
                <TableTitle v-if="hasPermission('CarManager') || role_id === 1"
                    :style="'bg-gray-100 whitespace-nowrap'">
                    <TableHeaderCicsaFilter label="Linea de Negocio" labelClass="text-gray-600" :options="cost_line"
                        v-model="formSearch.cost_line" />
                </TableTitle>
                <TableTitle>Placa</TableTitle>
                <TableTitle>Modelo</TableTitle>
                <TableTitle>Marca</TableTitle>
                <TableTitle>Año</TableTitle>
                <TableTitle>Tipo</TableTitle>
                <TableTitle>Foto</TableTitle>
                <TableTitle>Dueño</TableTitle>
                <TableTitle :colspan="2">Acciones</TableTitle>
            </tr>
        </template>
        <template #tbody>
            <template v-for="(car, i) in cars.data || cars" :key="car.id">
                <tr>
                    <th>
                        <div class="w-2"></div>
                    </th>
                    <TableRow v-if="hasPermission('CarManager') || role_id === 1">
                        {{ car.costline?.name }}
                    </TableRow>
                    <TableRow>{{ car.plate }}</TableRow>
                    <TableRow>{{ car.model }}</TableRow>
                    <TableRow>{{ car.brand }}</TableRow>
                    <TableRow>{{ car.year }}</TableRow>
                    <TableRow>{{ car.type }}</TableRow>
                    <TableRow>
                        <a v-if="car.photo" :href="route('fleet.cars.show.image', { car: car.id }) + '?' + uniqueParam"
                            target="_blank">
                            <ShowIcon />
                        </a>
                    </TableRow>
                    <TableRow>{{ car.user.name }}</TableRow>
                    <TableRow :colspan="2">
                        <div class="flex space-x-3 justify-center">
                            <button v-if="hasPermission('Car')" @click="openformDocument(car)">
                                <DocumentsIcon
                                    :color="car.car_document?.approvel_car_document.length > 0 ? 'text-red-400' : 'text-blue-400'" />
                            </button>
                            <button v-if="hasPermission('Car')" @click="openFormChangeLog(null, car)" type="button">
                                <svg viewBox="0 0 1024 1024" class="w-6 h-6 icon" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M550.208 960H209.28A81.792 81.792 0 0 1 128 877.76V146.24A81.92 81.92 0 0 1 209.344 64h613.632a81.92 81.92 0 0 1 81.28 82.432v405.76a29.824 29.824 0 1 1-59.584 0V146.56a22.272 22.272 0 0 0-21.76-22.656H209.408a22.08 22.08 0 0 0-21.696 22.528v731.52a21.76 21.76 0 0 0 21.44 22.464h341.056a29.824 29.824 0 0 1 0.064 59.584z m196.352-600.96H285.824a29.824 29.824 0 1 1 0-59.712h460.8a29.824 29.824 0 1 1 0 59.712z m-204.8 156.8H285.824a29.824 29.824 0 1 1 0-59.712h255.936a29.824 29.824 0 1 1 0 59.648z m179.2 391.936c-101.12 0-183.424-83.84-183.424-186.624a29.824 29.824 0 1 1 59.712 0c0 70.016 55.552 126.976 123.584 126.976 17.408 0 34.24-3.712 50.048-10.88a29.888 29.888 0 0 1 24.768 54.336c-23.552 10.688-48.64 16.192-74.688 16.192z m153.6-156.8a29.824 29.824 0 0 1-29.824-29.824c0-70.016-55.552-126.976-123.648-126.976-16.32 0-32.384 3.2-47.36 9.6a29.888 29.888 0 0 1-23.424-54.912 180.224 180.224 0 0 1 70.784-14.336c101.12 0 183.424 83.84 183.424 186.624a30.016 30.016 0 0 1-29.952 29.824z m-204.8-104.576h-51.264a29.76 29.76 0 0 1-25.28-14.08 30.144 30.144 0 0 1-1.536-28.928l25.6-52.352a29.696 29.696 0 0 1 53.632 0l25.6 52.352a29.696 29.696 0 0 1-1.472 28.928 29.504 29.504 0 0 1-25.28 14.08z m127.552 269.568h-1.024a29.696 29.696 0 0 1-24.896-14.848l-25.6-44.288a29.888 29.888 0 0 1 23.808-44.672l58.048-4.032c11.392-0.704 22.144 5.12 27.904 14.848a30.016 30.016 0 0 1-1.024 31.616l-32.448 48.256a29.824 29.824 0 0 1-24.768 13.12z"
                                        fill="#044d14" />
                                </svg>
                            </button>
                            <button v-if="hasPermission('CarManager') || role_id === 1" type="button"
                                @click="openEditFormCar(car)">
                                <EditIcon />
                            </button>

                            <a v-if="hasPermission('Car') && car.checklist"
                                :href="route('fleet.cars.show_checklist', { car: car.id })">
                                <ListIcon />
                            </a>

                            <button v-if="hasPermission('CarManager') || role_id === 1" type="button"
                                @click="openModalDeleteCars(car.id)" class="text-blue-900">
                                <DeleteIcon />
                            </button>
                            <button v-if="car.car_changelogs.length > 0" type="button" @click="toogleChangelog(car)"
                                class="text-blue-900 whitespace-no-wrap">
                                <DownArrowIcon v-if="carId !== car.id" />
                                <UpArrowIcon v-else />
                            </button>
                        </div>
                    </TableRow>
                </tr>
                <template v-if="carId == car.id">
                    <tr>
                        <th class="sticky left-0 z-10 bg-gray-200 border-b-2 border-gray-20">
                            <div class="w-2"></div>
                        </th>
                        <TableTitle :style="'bg-gray-200'">Fecha</TableTitle>
                        <TableTitle :style="'bg-gray-200'">Kilometraje</TableTitle>
                        <TableTitle :style="'bg-gray-200'">Tipo</TableTitle>
                        <TableTitle :style="'bg-gray-200'">Taller</TableTitle>
                        <TableTitle :style="'bg-gray-200'">Nombre de Contacto</TableTitle>
                        <TableTitle :style="'bg-gray-200'">Teléfono de Contacto</TableTitle>
                        <TableTitle :style="'bg-gray-200'">Observación</TableTitle>
                        <TableTitle :style="'bg-gray-200'">Factura</TableTitle>
                        <TableTitle :style="'bg-gray-200'">Items</TableTitle>
                        <TableTitle :style="'bg-gray-200'"></TableTitle>
                    </tr>
                    <template v-for="changelog in car.car_changelogs" :key="changelog.id">
                        <tr>
                            <td :class="[
                                'sticky left-0 z-10 border-b border-gray-200',
                                {
                                    'bg-indigo-500':
                                        changelog.is_accepted === null,
                                    'bg-green-500':
                                        changelog.is_accepted == 1,
                                    'bg-red-500':
                                        changelog.is_accepted == 0,
                                },
                            ]"></td>
                            <TableRow>{{ formattedDate(changelog.date) }}</TableRow>
                            <TableRow>{{ changelog.mileage }}</TableRow>
                            <TableRow>{{ changelog.type }}</TableRow>
                            <TableRow>{{ changelog.workshop }}</TableRow>
                            <TableRow>{{ changelog.contact_name }}</TableRow>
                            <TableRow>{{ changelog.contact_phone }}</TableRow>
                            <TableRow>{{ changelog.observation }}</TableRow>
                            <TableRow>
                                <div class="flex justify-center ">
                                    <a target="_blank" :href="route(
                                        'fleet.cars.show_invoice', { car_changelog: changelog.id }
                                    )">
                                        <ShowIcon />
                                    </a>
                                </div>
                            </TableRow>
                            <TableRow>
                                <div class="flex flex-col justify-center items-center">
                                    <button type="button" @click="toggleVisibility(changelog.id)">
                                        <DownArrowIcon v-if="!visibleChangelogs.has(changelog.id)" />
                                        <UpArrowIcon v-else />
                                    </button>
                                </div>
                            </TableRow>
                            <TableRow>
                                <div class="flex justify-center items-center gap-2">
                                    <button v-if="changelog.is_accepted === null && hasPermission('CarManager')"
                                        @click="() => validateRegister(changelog.id, 1)">
                                        <AcceptIcon />
                                    </button>
                                    <button v-if="changelog.is_accepted === null && hasPermission('CarManager')"
                                        @click="() => validateRegister(changelog.id, 0)" type="button">
                                        <RejectIcon />
                                    </button>
                                    <button type="button" v-if="hasPermission('CarManager')" @click="
                                        openEditFormChangeLog(changelog, car)">
                                        <EditIcon />
                                    </button>
                                    <button type="button" v-if="hasPermission('CarManager')" @click="
                                        openModalDeleteChangelog(changelog.id)">
                                        <DeleteIcon />
                                    </button>
                                </div>
                            </TableRow>
                        </tr>
                        <tr v-if="visibleChangelogs.has(changelog.id)" class="border-b bg-gray-50">
                            <td colspan="11" class="py-1 px-2">
                                <table class="w-full">
                                    <thead>
                                        <tr
                                            class="border-b text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                                N°
                                            </th>
                                            <th
                                                class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600">
                                                Nombre
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="(item, index) in changelog.car_changelog_items" :key="item.id">
                                            <td class="border-b px-2 py-2 text-center text-[11px] text-gray-600">
                                                {{ index + 1 }}</td>
                                            <td class="border-b px-2 py-2 text-center text-[11px] text-gray-600">
                                                {{ item.name }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </template>
                </template>
            </template>
        </template>
    </TableStructure>
    <div v-if="cars.data" class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <Pagination :links="cars.links" />
    </div>

    <ConfirmDeleteModal :confirmingDeletion="showModalDeleteCars" itemType="vehiculo" :deleteFunction="deleteCars"
        @closeModal="openModalDeleteCars(null)" />

    <ConfirmDeleteModal :confirmingDeletion="showModalDeleteChangelog" itemType="registro de cambios"
        :deleteFunction="deleteChangelog" @closeModal="openModalDeleteChangelog(null)" />

    <FormDocument :cars="cars" ref="formDocument" />
    <FormChangeLog :cars="cars" ref="formChangeLog" />
</template>
<script setup>
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableRow from '@/Components/TableRow.vue';
import Pagination from '@/Components/Pagination.vue';
import TableHeaderCicsaFilter from '@/Components/TableHeaderCicsaFilter.vue';
import { ref } from 'vue';
import { formattedDate } from '@/utils/utils';
import FormChangeLog from './FormChangeLog.vue';
import FormDocument from './FormDocument.vue';
import { notify } from '@/Components/Notification';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { ShowIcon, EditIcon, DeleteIcon, DownArrowIcon, UpArrowIcon, ListIcon, DocumentsIcon, AcceptIcon, RejectIcon } from '@/Components/Icons/Index';

const { userPermissions, formSearch, cars, cost_line, openEditFormCar, role_id
} = defineProps({
    userPermissions: Array,
    formSearch: Object,
    cars: Object,
    cost_line: Object,
    openEditFormCar: Function,
    role_id: Function,
})

const carId = ref(null);
const visibleChangelogs = ref(new Set());
const uniqueParam = ref(`timestamp=${new Date().getTime()}`);
const formChangeLog = ref(null)
const formDocument = ref(null)
const showModalDeleteChangelog = ref(false);
const showModalDeleteCars = ref(false);
const changelogToDelete = ref(null);
const car_id = ref(null);

function toogleChangelog(item) {
    if (carId.value === item.id) {
        carId.value = null;
    } else {
        carId.value = item.id;
    }
}

const toggleVisibility = (id) => {
    if (visibleChangelogs.value.has(id)) {
        visibleChangelogs.value.delete(id);
    } else {
        visibleChangelogs.value.add(id);
    }
    visibleChangelogs.value = new Set(visibleChangelogs.value);
};

async function validateRegister(changelog_id, is_accepted) {
    const url = route("fleet.cars.show_checklist.accept_or_decline", {
        changelog: changelog_id,
        is_accepted: is_accepted,
    });
    try {
        const response = await axios.put(url);
        updateCar(response.data, "validateChangelog");
    } catch (e) {
        console.log(e);
    }
}

function openFormChangeLog(e, car) {
    formChangeLog.value.openCreateModalChangelog(e, car)
}

function openEditFormChangeLog(e, car) {
    formChangeLog.value.openEditChangelog(e, car)
}

function openformDocument(item) {
    formDocument.value.openModalCreateDocument(item)
}

async function deleteCars() {
    let url = route("fleet.cars.destroy", { car: car_id.value });
    try {
        await axios.delete(url);
        updateCar(car_id.value, "delete");
    } catch (error) {
        console.log(error);
    }
}

function openModalDeleteCars(id) {
    car_id.value = id;
    showModalDeleteCars.value = !showModalDeleteCars.value;
}

function openModalDeleteChangelog(id) {
    changelogToDelete.value = id ?? null;
    showModalDeleteChangelog.value = !showModalDeleteChangelog.value;
}


function hasPermission(permission) {
    return userPermissions.includes(permission)
}

async function deleteChangelog() {
    const docId = changelogToDelete.value;
    if (docId) {
        const response = await axios.delete(
            route("fleet.cars.destroy_changelog", { car_changelog: docId })
        );
        if (response.data) {
            updateCar(response.data, "deleteChangelog");
        } else {
            notifyError("Error al eliminar el registro de cambios");
        }
    }
}

function updateCar(data, action) {
    const validations = cars.data || cars;
    if (action === "delete") {
        let index = validations.findIndex((item) => item.id === data);
        validations.splice(index, 1);
        openModalDeleteCars(null);
        notify("Eliminacion Exitosa");
    } else if (action === "deleteChangelog") {
        let index = validations.findIndex((item) => item.id === data.id);
        validations[index] = data;
        openModalDeleteChangelog(null);
        if (validations[index].car_changelogs.length === 0) {
            carId.value = null;
        }
        notify("Eliminación Exitosa");
    } else if (action === "validateChangelog") {
        let index = validations.findIndex((item) => item.id === data.id);
        validations[index] = data;
        notify("Acción Exitosa");
    }
}
</script>