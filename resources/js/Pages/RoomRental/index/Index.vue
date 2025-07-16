<template>

    <Head title="Alquileres" />
    <AuthenticatedLayout :redirectRoute="'room.rental.index'">
        <template #header> Alquileres </template>
        <Toaster richColors />
        <div class="w-full">
            <TableHeader :form="formSearch" :openCreateFormCar="openCreateFormCar" />
            <FleetCarTable :cars="cars" v-model:formSearch="formSearch" :cost_line="cost_line"
                :openEditFormCar="openEditFormCar" :openFormChangeLog="openFormChangeLog"
                :openEditFormChangeLog="openEditFormChangeLog" :openformDocument="openformDocument"
                :openModalDeleteCars="openModalDeleteCars" :openModalDeleteChangelog="openModalDeleteChangelog" />
        </div>
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

        <SuspenseWrapper :when="showFormChangeLog">
            <template #component>
                <FormChangeLog :cars="cars" ref="formChangeLog" />
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

const FormCar = defineAsyncComponent(() => import('./components/FormCar.vue'));
const FormDocument = defineAsyncComponent(() => import('./components/FormDocument.vue'));
const FormChangeLog = defineAsyncComponent(() => import('./components/FormChangeLog.vue'));

const DeleteCar = defineAsyncComponent(() => import('./components/DeleteCar.vue'));
const DeleteChangeLog = defineAsyncComponent(() => import('./components/DeleteChangeLog.vue'));

const props = defineProps({
    car: Object,
    costLine: Object,
    users: Object,
    providers: Object,
});

const showFormCar = ref(false)
const showFormDocument = ref(false)
const showFormChangeLog = ref(false)
const showDeleteCar = ref(false)
const showChangeLog = ref(false)


const cars = ref(props.car);
const formCar = ref(null)
const formChangeLog = ref(null)
const formDocument = ref(null)
const deleteCar = ref(null)
const changeLog = ref(null)

const cost_line = props.costLine.map((item) => item.name);

const { invokeWhenReady: invokeFormCar } = useLazyRefInvoker(formCar, showFormCar);
const { invokeWhenReady: invokeFormDocument } = useLazyRefInvoker(formDocument, showFormDocument);
const { invokeWhenReady: invokeFormChangeLog } = useLazyRefInvoker(formChangeLog, showFormChangeLog);

const { invokeWhenReady: invokeDeleteCar } = useLazyRefInvoker(deleteCar, showDeleteCar);
const { invokeWhenReady: invokeChangeLog } = useLazyRefInvoker(changeLog, showChangeLog);

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
        console.log(error);
        if (error.response.data) {
            notifyError("Server error", error.response.data);
        } else {
            notifyError("Network or other error:", error);
        }
    }
}

function openCreateFormCar() {
    invokeFormCar('openModalCreate')
}

function openEditFormCar(item) {
    invokeFormCar('openModalEdit', item)
}

function openFormChangeLog(e, car) {
    invokeFormChangeLog('openCreateModalChangelog', e, car)
}

function openEditFormChangeLog(e, car) {
    invokeFormChangeLog('openEditChangelog', e, car)
}

function openformDocument(room, room_document=null) {
    invokeFormDocument('openModalCreateDocument', room, room_document)
}

function openModalDeleteCars(id) {
    invokeDeleteCar('openModalDeleteCars', id)
}

function openModalDeleteChangelog(id) {
    invokeChangeLog('openModalDeleteChangelog', id)
}
</script>