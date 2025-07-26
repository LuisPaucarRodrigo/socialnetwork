<template>

    <Head title="Alquileres" />
    <AuthenticatedLayout :redirectRoute="'room.rental.index'">
        <template #header> Alquileres </template>
        <Toaster richColors />
        <TableHeader :form="formSearch" :openCreateFormCar="openCreateFormCar" />
        <FleetCarTable :cars="cars" v-model:formSearch="formSearch" :cost_line="cost_line"
            :openEditFormCar="openEditFormCar" :openformDocument="openformDocument"
            :openModalDeleteCars="openModalDeleteCars" :openModalDeleteChangelog="openModalDeleteChangelog"
            :openImagesModal="openImagesModal" :openImagesForm="openImagesForm" />
        <SuspenseWrapper :when="showFormCar">
            <template #component>
                <FormCar :cars="cars" :providers="providers" :costLine="costLine" ref="formCar" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showFormDocument">
            <template #component>
                <FormDocument :cars="cars" ref="formDocument" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showDeleteCar">
            <template #component>
                <DeleteCar :cars="cars" ref="deleteCar" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showChangeLog">
            <template #component>
                <DeleteChangeLog :cars="cars" ref="changeLog" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showImagesModal">
            <template #component>
                <ImagesModal ref="imagesModal" />
            </template>
        </SuspenseWrapper>

        <SuspenseWrapper :when="showImagesForm">
            <template #component>
                <ImagesForm ref="imagesForm" :cars="cars"/>
            </template>
        </SuspenseWrapper>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { defineAsyncComponent, ref, watch } from "vue";
import { notifyError } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import TableHeader from "./components/TableHeader.vue";
import FleetCarTable from "./components/FleetCarTable.vue";
import { useLazyRefInvoker } from "@/utils/useLazyRefInvoker";
import SuspenseWrapper from "@/Components/SuspenseWrapper.vue";
import { useAxiosErrorHandler } from "@/utils/axiosError";

const FormCar = defineAsyncComponent(() => import('./components/FormCar.vue'));
const FormDocument = defineAsyncComponent(() => import('./components/FormDocument.vue'));
const ImagesModal = defineAsyncComponent(() => import('./components/ImagesModal.vue'));
const DeleteCar = defineAsyncComponent(() => import('./components/DeleteCar.vue'));
const DeleteChangeLog = defineAsyncComponent(() => import('./components/DeleteChangeLog.vue'));
const ImagesForm = defineAsyncComponent(() => import('./components/ImagesForm.vue'));

const props = defineProps({
    car: Object,
    costLine: Object,
    users: Object,
    providers: Object,
});

const showFormCar = ref(false)
const showFormDocument = ref(false)
const showDeleteCar = ref(false)
const showChangeLog = ref(false)
const showImagesModal = ref(false)
const showImagesForm = ref(false)

const cars = ref(props.car);
const formCar = ref(null)
const formDocument = ref(null)
const deleteCar = ref(null)
const changeLog = ref(null)
const imagesModal = ref(null)
const imagesForm = ref(null)
const cost_line = props.costLine.map((item) => item.name);

const { invokeWhenReady: invokeFormCar } = useLazyRefInvoker(formCar, showFormCar);
const { invokeWhenReady: invokeFormDocument } = useLazyRefInvoker(formDocument, showFormDocument);
const { invokeWhenReady: invokeImagesModal } = useLazyRefInvoker(imagesModal, showImagesModal);
const { invokeWhenReady: invokeDeleteCar } = useLazyRefInvoker(deleteCar, showDeleteCar);
const { invokeWhenReady: invokeChangeLog } = useLazyRefInvoker(changeLog, showChangeLog);
const { invokeWhenReady: invokeImagesForm } = useLazyRefInvoker(imagesForm, showImagesForm);

const initialFormSearch = {
    cost_line: [...cost_line],
    search: "",
};

const formSearch = ref({ ...initialFormSearch });

watch(
    () => [formSearch.value.cost_line, formSearch.value.search],
    () => {
        search();
    },
    { deep: true }
);

async function search() {
    let url = route("room.rental.search");
    try {
        let response = await axios.post(url, formSearch.value);
        cars.value = response.data;
    } catch (error) {
        useAxiosErrorHandler(error, formSearch.value)
    }
}

function openCreateFormCar() {
    invokeFormCar('openModalCreate')
}

function openEditFormCar(item) {
    invokeFormCar('openModalEdit', item)
}

function openformDocument(room, room_document = null) {
    invokeFormDocument('openModalCreateDocument', room, room_document)
}

function openModalDeleteCars(id) {
    invokeDeleteCar('openModalDeleteCars', id)
}

function openModalDeleteChangelog(id) {
    invokeChangeLog('openModalDeleteChangelog', id)
}

function openImagesModal(room_id) {
    invokeImagesModal('openImagesModal', room_id)
}

function openImagesForm(room_id) {
    invokeImagesForm('openImagesForm', room_id)
}
</script>