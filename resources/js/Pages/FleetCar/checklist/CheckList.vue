<template>

    <Head title="Gestion de Empleados" />
    <AuthenticatedLayout :redirectRoute="'fleet.cars.index'">
        <template #header> CheckList </template>
        <div class="flex flex-space gap-2">
            <span class="font-black">Dueño: </span><span>{{ props.car.user.name }}</span>
        </div>
        <div class="flex flex-space gap-2">
            <span class="font-black">Placa: </span><span>{{ props.car.plate }}</span>
        </div>
        <CheckListTable :checklist="checklist" :openImages="openImages" :openChecklist="openChecklist" />
        <CarouselModal :images="images" :show="isModalOpen" @close="closeModal" />
        <ModalCheckList :car="car" ref="checklists" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import axios from "axios";
import { ref } from "vue";
import CarouselModal from "@/Components/CarouselModal.vue";
import ModalCheckList from "./components/ModalCheckList.vue";
import CheckListTable from "./components/CheckListTable.vue";

const props = defineProps({
    car: Object,
    checklist: Object,
});

const images = ref([]);
const isModalOpen = ref(false);
const checklists = ref(null)

async function openImages(id) {
    await axios
        .get(route("fleet.cars.show_checklist.send_images", { checklist: id }))
        .then((res) => {
            images.value = res.data;
            isModalOpen.value = true;
        })
        .catch((e) => {
            console.error(e);
        });
}

function closeModal() {
    images.value = [];
    isModalOpen.value = false;
}

function openChecklist(item) {
    checklists.value.openChecklist(item)
}
</script>
