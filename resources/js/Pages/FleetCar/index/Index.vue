<template>
    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'fleet.cars.index'">
        <Toaster richColors />
        <template #header> Vehiculos </template>
        <div class="w-full">
            <TableHeader :form="formSearch" :openCreateFormCar="openCreateFormCar"
                :userPermission="userPermissions" />
            <FleetCarTable :cars="cars" :userPermissions="userPermissions" v-model:formSearch="formSearch"
                :cost_line="cost_line" :openEditFormCar="openEditFormCar" />
        </div>
        <FormCar :cars="cars" :users="users" :costLine="costLine" :userPermissions="userPermissions" ref="formCar" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, watch } from "vue";
import { notifyError } from "@/Components/Notification";
import { Toaster } from "vue-sonner";
import TableHeader   from "./components/TableHeader.vue";
import FleetCarTable from "./components/FleetCarTable.vue";
import FormCar from "./components/FormCar.vue";

const props = defineProps({
    car: Object,
    userPermissions: Array,
    costLine: Object,
    users: Object,
});

const cars = ref(props.car);
const formCar = ref(null)
const cost_line = props.costLine.map((item) => item.name);

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

function openCreateFormCar() {
    formCar.value.openModalCreate()
}

function openEditFormCar(item) {
    formCar.value.openModalEdit(item)
}

</script>