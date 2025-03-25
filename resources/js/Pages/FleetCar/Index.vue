<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'fleet.cars.index'">
        <Toaster richColors />
        <template #header> Vehiculos </template>
        <div class="w-full">
            <GeneralOpciones :form="formSearch" :openModalCreate="openModalCreate" :userPermission="userPermissions" />
            <FleetCarTable v-model:cars="cars" :userPermissions="userPermissions" v-model:formSearch="formSearch"
                :cost_line="cost_line" :openModalCreateDocument="openModalCreateDocument" :openModalEdit="openModalEdit"
                :openCreateModalChangelog="openCreateModalChangelog" :openEditChangelog="openEditChangelog" />
        </div>
        <Modal :show="showModalCar">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ form.id ? "Editar UM" : "Nueva UM" }}
                </h2>
                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div v-if="!form.id && hasPermission('CarManager')" class="mt-2">
                            <InputLabel for="user_id">Proveedores de UM
                            </InputLabel>
                            <div class="mt-2">
                                <select id="user_id" v-model="form.user_id" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">
                                        Seleccionar Usuario
                                    </option>
                                    <option v-for="item in users" :value="item.id">
                                        {{ item.name }} - {{ item.dni }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.user_id" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="cost_line_id">Linea de Negocio
                            </InputLabel>
                            <div class="mt-2">
                                <select id="cost_line_id" v-model="form.cost_line_id" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="">
                                        Seleccionar Linea de Costo
                                    </option>
                                    <option v-for="item in costLine" :value="item.id">
                                        {{ item.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.cost_line_id" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="plate">Placa </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="plate" v-model="form.plate" />
                                <InputError :message="form.errors.plate" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="model">Modelo </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="model" v-model="form.model" />
                                <InputError :message="form.errors.model" />
                            </div>
                        </div>

                        <div class="mt-6">
                            <InputLabel for="brand">Marca </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="brand" v-model="form.brand" />
                                <InputError :message="form.errors.brand" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="year">Año </InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" id="year" min="1900" :max="new Date().getFullYear()"
                                    v-model="form.year" />
                                <InputError :message="form.errors.year" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="type">Tipo </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="type" v-model="form.type" />
                                <InputError :message="form.errors.type" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="photo">Foto </InputLabel>
                            <div class="mt-2">
                                <InputFile id="photo" accept=".jpeg, .jpg, .png" v-model="form.photo" />
                                <InputError :message="form.errors.photo" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="openModalCar">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                            {{ form.id ? "Actualizar" : "Crear" }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
        <Modal :show="showModalDocumentCar">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ formDocument.id ? "Editar " : "Nueva " }}Documentaciòn UM
                </h2>
                <p>Dueño: {{ show_owner }} Placa: {{ show_plate }}</p>
                <form @submit.prevent="submitDocument">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="mt-2">
                            <InputLabel for="ownership_card">Tarjeta de Propiedad
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile id="ownership_card" accept=".pdf" v-model="formDocument.ownership_card" />
                                <InputError :message="formDocument.errors.ownership_card
                                    " />
                            </div>
                            <div v-if="archivesDocument.ownership_card" class="flex items-center">
                                <span>Archivo: </span>
                                <a target="_blank" :href="route('fleet.cars.show_documents', {
                                    car_document: formDocument.id,
                                    fieldName: 'ownership_card',
                                }) + '?' + uniqueParam
                                    ">
                                    <EyeIcon class="w-5 h-5 text-green-600" />
                                </a>
                            </div>
                        </div>

                        <div class="mt-2">
                            <InputLabel for="technical_review">Revision Tecnica
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile id="technical_review" accept=".pdf"
                                    v-model="formDocument.technical_review" />
                                <InputError :message="formDocument.errors.technical_review
                                    " />
                            </div>
                            <div v-if="archivesDocument.technical_review" class="flex items-center">
                                <span>Archivo: </span>
                                <a target="_blank" :href="route('fleet.cars.show_documents', {
                                    car_document: formDocument.id,
                                    fieldName: 'technical_review',
                                }) + '?' + uniqueParam
                                    ">
                                    <EyeIcon class="w-5 h-5 text-green-600" />
                                </a>
                            </div>
                            <template v-if="date_technical_review">
                                <InputLabel for="technical_review">
                                    Fecha de Revision Tecnica
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput id="technical_review_date" type="date" v-model="formDocument.technical_review_date
                                        " />
                                    <InputError :message="formDocument.errors
                                        .technical_review_date
                                        " />
                                </div>
                            </template>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="soat">SOAT </InputLabel>
                            <div class="mt-2">
                                <InputFile id="soat" accept=".pdf" v-model="formDocument.soat" />
                                <InputError :message="formDocument.errors.soat" />
                            </div>

                            <div v-if="archivesDocument.soat" class="flex items-center">
                                <span>Archivo: </span>
                                <a target="_blank" :href="route('fleet.cars.show_documents', {
                                    car_document: formDocument.id,
                                    fieldName: 'soat',
                                }) + '?' + uniqueParam
                                    ">
                                    <EyeIcon class="w-5 h-5 text-green-600" />
                                </a>
                            </div>
                            <InputLabel for="soat_date">
                                Fecha de Soat
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput id="soat_date" type="date" v-model="formDocument.soat_date" />
                                <InputError :message="formDocument.errors.soat_date" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="insurance">Seguro </InputLabel>
                            <div class="mt-2">
                                <InputFile id="insurance" accept=".pdf" v-model="formDocument.insurance" />
                                <InputError :message="formDocument.errors.insurance" />
                            </div>

                            <div v-if="archivesDocument.insurance" class="flex items-center">
                                <span>Archivo: </span>
                                <a target="_blank" :href="route('fleet.cars.show_documents', {
                                    car_document: formDocument.id,
                                    fieldName: 'insurance',
                                }) + '?' + uniqueParam
                                    ">
                                    <EyeIcon class="w-5 h-5 text-green-600" />
                                </a>
                            </div>
                            <InputLabel for="insurance_date">
                                Fecha de Seguro
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" id="insurance_date" v-model="formDocument.insurance_date" />
                                <InputError :message="formDocument.errors.insurance_date" />
                            </div>
                        </div>
                        <div class="mt-6">
                            <InputLabel for="GPS">GPS</InputLabel>
                            <div class="mt-2">
                                <InputLabel for="address_web">Direccion Web </InputLabel>
                                <TextInput type="text" id="address_web" v-model="formDocument.address_web" />
                                <InputError :message="formDocument.errors.address_web" />

                                <InputLabel for="gps_user">Usuario </InputLabel>
                                <TextInput type="text" id="gps_user" v-model="formDocument.user" />
                                <InputError :message="formDocument.errors.user" />

                                <InputLabel for="gps_password">Password </InputLabel>
                                <TextInput type="text" id="password" v-model="formDocument.password" />
                                <InputError :message="formDocument.errors.password" />
                            </div>
                        </div>

                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="openModalDocument">
                            Cancel
                        </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': formDocument.processing }">
                            {{ formDocument.id ? "Actualizar" : "Crear" }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="showChangelogModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ formDocument.id ? "Editar " : "Nuevo " }}Registro de
                    Cambios
                </h2>
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Dueño: {{ show_owner }}
                </h2>
                <h2 class="text-base font-medium leading-7 text-gray-900 mb-2">
                    Placa: {{ show_plate }}
                </h2>
                <form @submit.prevent="submitChangelog">
                    <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                        <div class="mt-2">
                            <InputLabel for="date">Fecha </InputLabel>
                            <div class="mt-2">
                                <input type="date" id="date" v-model="formChangelog.date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="formChangelog.errors.date" />
                            </div>
                        </div>

                        <div class="mt-2">
                            <InputLabel for="mileage">Kilometraje </InputLabel>
                            <div class="mt-2">
                                <input type="number" step="0.01" id="mileage"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="formChangelog.mileage" />
                                <InputError :message="formChangelog.errors.mileage" />
                            </div>
                        </div>

                        <div class="mt-2">
                            <InputLabel for="type">Tipo </InputLabel>
                            <div class="mt-2">
                                <input type="text" id="type"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="formChangelog.type" />
                                <InputError :message="formChangelog.errors.type" />
                            </div>
                        </div>

                        <div class="mt-2">
                            <InputLabel for="invoice">Factura </InputLabel>
                            <div class="mt-2">
                                <InputFile id="invoice" accept=".pdf" v-model="formChangelog.invoice"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="formChangelog.errors.invoice" />
                            </div>

                            <div v-if="formChangelog.invoice && formChangelog.id" class="flex items-center">
                                <span>Archivo: </span>
                                <a target="_blank" :href="route('fleet.cars.show_invoice', {
                                    car_changelog: formChangelog.id,
                                }) + uniqueParam
                                    ">
                                    <EyeIcon class="w-5 h-5 text-green-600" />
                                </a>
                            </div>
                        </div>

                        <div class="mt-2">
                            <InputLabel for="workshop">Taller </InputLabel>
                            <div class="mt-2">
                                <input type="text" id="workshop"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="formChangelog.workshop" />
                                <InputError :message="formChangelog.errors.workshop" />
                            </div>
                        </div>

                        <div class="mt-2">
                            <InputLabel for="contact_name">Nombre de Contacto </InputLabel>
                            <div class="mt-2">
                                <input type="text" id="contact_name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="formChangelog.contact_name" />
                                <InputError :message="formChangelog.errors.contact_name" />
                            </div>
                        </div>

                        <div class="mt-2">
                            <InputLabel for="contact_phone">Teléfono de Contacto </InputLabel>
                            <div class="mt-2">
                                <input type="text" maxlength="9" minlength="9" id="contact_phone"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="formChangelog.contact_phone" />
                                <InputError :message="formChangelog.errors.contact_phone" />
                            </div>
                        </div>

                        <div class="mt-2">
                            <InputLabel for="observation">Observación
                            </InputLabel>
                            <div class="mt-2">
                                <textarea id="observation"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="formChangelog.observation" />
                                <InputError :message="formChangelog.errors.observation" />
                            </div>
                        </div>

                        <div class="col-span-1 md:col-span-2">
                            <InputLabel class="mb-1" for="new_item">Agregar Item</InputLabel>
                            <div class="flex items-center">
                                <input type="text" v-model="newItem"
                                    class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
                                <button type="button" @click="addItem" class="ml-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </div>
                            <InputError :message="formChangelog.errors.items" />
                        </div>

                        <div class="col-span-1 sm:col-span-2 overflow-x-auto mt-3">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr
                                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Nº
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Nombre
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in formChangelog.items" :key="index">
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            {{ index + 1 }}
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            {{ item }}
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-right">
                                            <button @click.prevent="() => {
                                                formChangelog.items.splice(
                                                    index,
                                                    1
                                                );
                                            }
                                            ">
                                                <TrashIcon class="h-5 w-5 text-red-600" />
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="openModalChangelog">
                            Cancelar
                        </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': formDocument.processing }">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="itemModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Items del Registro de Cambios
                </h2>
                <div class="overflow-x-auto mt-3 col-span-2">
                    <table class="w-full whitespace-no-wrap">
                        <thead>
                            <tr
                                class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <th
                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                    N°
                                </th>
                                <th
                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                                    Nombre
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, index) in showitems" :key="index" class="text-gray-700">
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                    {{ index + 1 }}
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                                    {{ item.name }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-span-2 mt-6 flex items-center justify-end gap-x-6">
                    <SecondaryButton @click="closeItemModal">Cerrar</SecondaryButton>
                </div>
            </div>
        </Modal>

        <ConfirmDeleteModal :confirmingDeletion="showModalDeleteCars" itemType="vehiculo" :deleteFunction="deleteCars"
            @closeModal="openModalDeleteCars(null)" />

        <ConfirmDeleteModal :confirmingDeletion="showModalDeleteChangelog" itemType="registro de cambios"
            :deleteFunction="deleteChangelog" @closeModal="openModalDeleteChangelog(null)" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import Modal from "@/Components/Modal.vue";
import { Head, useForm } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import InputError from "@/Components/InputError.vue";
import {
    setAxiosErrors,
    toFormData,
} from "@/utils/utils";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { notify, notifyError } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import InputFile from "@/Components/InputFile.vue";
import {
    EyeIcon,
    TrashIcon,
} from "@heroicons/vue/24/outline";
import GeneralOpciones from "@/Layouts/FleetCar/GeneralOpciones.vue";
import FleetCarTable from "@/Layouts/FleetCar/FleetCarTable.vue";

const props = defineProps({
    car: Object,
    userPermissions: Array,
    costLine: Object,
    users: Object,
});

const cars = ref(props.car);

const showModalCar = ref(false);
const showModalDeleteCars = ref(false);
const car_id = ref(null);
const showModalDocumentCar = ref(false);
const showChangelogModal = ref(false);
const newItem = ref("");
const carId = ref(null);
const itemModal = ref(false);
const showitems = ref([]);
const changelogToDelete = ref(null);
const showModalDeleteChangelog = ref(false);
const archivesDocument = ref({});
const date_technical_review = ref(null);
const show_plate = ref(null);
const show_owner = ref(null);
// const visibleChangelogs = ref(new Set());


const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
};

const uniqueParam = `timestamp=${new Date().getTime()}`;

const initialForm = {
    id: "",
    brand: "",
    model: "",
    plate: "",
    year: "",
    type: "",
    photo: "",
    user_id: "",
    cost_line_id: "",
};

const initialFormDocument = {
    id: "",
    ownership_card: "",
    technical_review: "",
    technical_review_date: "",
    soat: "",
    soat_date: "",
    insurance: "",
    insurance_date: "",
    address_web: "",
    user: "",
    password: "",
    car_id: "",
};

const initialFormChangelog = {
    id: "",
    date: "",
    mileage: "",
    type: "",
    workshop: "",
    contact_name: "",
    contact_phone: "",
    invoice: "",
    observation: "",
    items: [],
};

const form = useForm({
    ...initialForm,
});

const formDocument = useForm({
    ...initialFormDocument,
});

const formChangelog = useForm({
    ...initialFormChangelog,
});

const cost_line = props.costLine.map((item) => item.name);

const initialFormSearch = {
    cost_line: [...cost_line],
    search: "",
};

const formSearch = ref({ ...initialFormSearch });

function openModalCar() {
    showModalCar.value = !showModalCar.value;
    form.clearErrors();
}

function openModalCreate() {
    openModalCar();
    form.defaults({ ...initialForm });
    form.reset();
}

function openModalEdit(item) {
    openModalCar();
    form.defaults({ ...item });
    form.reset();
}

function openModalDocument() {
    formDocument.clearErrors();
    formDocument.defaults({ ...initialFormDocument });
    formDocument.reset();
    showModalDocumentCar.value = !showModalDocumentCar.value;
}

function openModalCreateDocument(item) {
    show_owner.value = item.user.name
    show_plate.value = item.plate
    date_technical_review.value = item.year + 4 <= new Date().getFullYear();
    archivesDocument.value = {};
    openModalDocument();
    archivesDocument.value = { ...(item.car_document ?? initialFormDocument) };
    formDocument.defaults({
        ...(item.car_document ?? initialFormDocument),
        car_id: item.id,
    });
    formDocument.reset();
}

function openModalChangelog() {
    formChangelog.clearErrors();
    formChangelog.defaults({ ...initialFormChangelog });
    formChangelog.reset();
    car_id.value = null;
    show_plate.value = null;
    show_owner.value = null;
    showChangelogModal.value = !showChangelogModal.value;
}

function openCreateModalChangelog(item, car) {
    openModalChangelog();
    formChangelog.defaults({ ...(item ?? initialFormDocument) });
    car_id.value = car.id;
    show_plate.value = car.plate;
    show_owner.value = car.user.name;
    formChangelog.reset();
}

function openEditChangelog(item, car) {
    openModalChangelog();
    formChangelog.defaults({ ...item });
    formChangelog.reset();
    formChangelog.items = item.car_changelog_items?.map((i) => i.name) ?? [];
    show_plate.value = car.plate;
    show_owner.value = car.user.name;
}

function openModalDeleteCars(id) {
    car_id.value = id;
    showModalDeleteCars.value = !showModalDeleteCars.value;
}

function addItem() {
    if (newItem.value.trim() === "") {
        return;
    }
    formChangelog.items.push(newItem.value);
    newItem.value = "";
}

// function openItemsModal(items) {
//     showitems.value = items;
//     itemModal.value = true;
// }

function closeItemModal() {
    showitems.value = [];
    itemModal.value = false;
}

function openModalDeleteChangelog(id) {
    changelogToDelete.value = id ?? null;
    showModalDeleteChangelog.value = !showModalDeleteChangelog.value;
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

async function deleteCars() {
    let url = route("fleet.cars.destroy", { car: car_id.value });
    try {
        await axios.delete(url);
        updateCar(car_id.value, "delete");
    } catch (error) {
        console.log(error);
    }
}

watch(
    () => [formSearch.value.cost_line, formSearch.value.search],
    () => {
        search();
    },
    { deep: true }
);

async function submit() {
    let url = form.id
        ? route("fleet.cars.update", { car: form.id })
        : route("fleet.cars.store");
    let data = toFormData(form);
    try {
        let response = await axios.post(url, data);
        let action = form.id ? "edit" : "create";
        updateCar(response.data, action);
    } catch (error) {
        console.log(error);
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form);
            } else {
                notifyError("Server error:", error.response.data);
            }
        } else {
            notifyError("Network or other error:", error);
        }
    }
}

async function submitDocument() {
    let url = formDocument.id
        ? route("fleet.cars.update.document", { car_document: formDocument.id })
        : route("fleet.cars.store_document");
    let formData = toFormData(formDocument);
    try {
        let response = await axios.post(url, formData);
        console.log("response", response.data)
        if (response.data.length > 0) {
            updateCar(response.data, 'udpateDocument')
        } else {
            updateCar(null, 'changeEntry')
        }
    } catch (error) {
        console.log(error);
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, formDocument);
            } else {
                notifyError("Server error:", error.response.data);
            }
        } else {
            notifyError("Network or other error:", error);
        }
    }
}

async function submitChangelog() {
    let url = formChangelog.id
        ? route("fleet.cars.update_changelog", {
            car_changelog: formChangelog.id,
        })
        : route("fleet.cars.store_changelog", { car: car_id.value });
    let method = "post";
    let formData = new FormData();
    for (const key in formChangelog) {
        if (Object.hasOwnProperty.call(formChangelog, key)) {
            if (Array.isArray(formChangelog[key])) {
                formChangelog[key].forEach((item, index) => {
                    formData.append(`${key}[${index}]`, item);
                });
            } else {
                formData.append(key, formChangelog[key]);
            }
        }
    }
    try {
        let response = await axios({
            url: url,
            method: method,
            data: formData,
        });
        updateCar(response.data, "createChangelog");
    } catch (error) {
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, formChangelog);
            } else {
                notifyError("Server error:", error.response.data);
            }
        } else {
            notifyError("Network or other error:", error);
        }
    }
}

function updateCar(data, action) {
    const validations = cars.value.data || cars.value;
    if (action === "create") {
        validations.unshift(data);
        openModalCar();
        notify("Creaciòn Exitosa");
    } else if (action === "edit") {
        let index = validations.findIndex((item) => item.id === data.id);
        validations[index] = data;
        openModalCar();
        notify("Actualización Exitosa");
    } else if (action === "delete") {
        let index = validations.findIndex((item) => item.id === data);
        validations.splice(index);
        openModalDeleteCars(null);
        notify("Eliminacion Exitosa");
    } else if (action === "udpateDocument") {
        let index = validations.findIndex(
            (item) => item.id === formDocument.car_id
        );
        validations[index].car_document = data;
        openModalDocument();
        notify("Acciòn Exitosa");
    } else if (action === "createChangelog") {
        let index = validations.findIndex((item) => item.id === data.id);
        validations[index] = data;
        openModalChangelog();
        notify("Acción Exitosa");
    } else if (action === "deleteChangelog") {
        let index = validations.findIndex((item) => item.id === data.id);
        validations[index] = data;
        openModalDeleteChangelog(null);
        if (validations[index].car_changelogs.length === 0) {
            carId.value = null;
        }
        notify("Eliminación Exitosa");
    } else if (action === "changeEntry") {
        openModalDocument();
        notify("Solicitud de actualizacion exitosa");
    } else if (action === "validateChangelog") {
        let index = validations.findIndex((item) => item.id === data.id);
        validations[index] = data;
        notify("Acción Exitosa");
    }
}

async function search() {
    let url = route("fleet.cars.search");
    try {
        let response = await axios.post(url, formSearch.value);
        cars.value = response.data;
    } catch (error) {
        console.log(error);
        if (error.response.data) {
            notifyError("Server error", error.response.data);
        } else {
            notifyError("Network or other error:", error);
        }
    }
}

// async function validateRegister(changelog_id, is_accepted) {
//     const url = route("fleet.cars.show_checklist.accept_or_decline", {
//         changelog: changelog_id,
//         is_accepted: is_accepted,
//     });
//     try {
//         const response = await axios.put(url);
//         updateCar(response.data, "validateChangelog");
//     } catch (e) {
//         console.log(e);
//     }
// }

// const toggleVisibility = (id) => {
//     if (visibleChangelogs.value.has(id)) {
//         visibleChangelogs.value.delete(id);
//     } else {
//         visibleChangelogs.value.add(id);
//     }
//     visibleChangelogs.value = new Set(visibleChangelogs.value);
// };

</script>