<template>

    <Head title="Clientes" />

    <AuthenticatedLayout :redirectRoute="'preprojects.titles'">
        <template #header>
            Títulos
        </template>
        <Toaster richColors />
        <TableHeader :openModal="openModal" />
        <TitleTable :titles="titles" :openModal="openModal" :confirmDeleteTitle="confirmDeleteTitle" />

        <TitleForm ref="titleForm" :stages="stages" :codes="codes" :titles="titles" />
        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Título" :deleteFunction="deleteTitle"
            @closeModal="closeModalDoc" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import TitleForm from './components/TitleForm.vue';
import { Toaster } from 'vue-sonner';
import { notify, notifyError } from '@/Components/Notification';
import TitleTable from './components/TitleTable.vue';
import TableHeader from './components/TableHeader.vue';


const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const titleForm = ref(null)

const { titles, codes, stages } = defineProps({
    titles: Object,
    codes: Object,
    stages: Object,
})

const confirmDeleteTitle = (titleId) => {
    docToDelete.value = titleId;
    confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};

async function deleteTitle() {
    const docId = docToDelete.value;
    let url = route('preprojects.titles.delete', { title: docId })
    try {
        await axios.delete(url)
        updateFrontEnd(docId)
    } catch (error) {
        console.error(error)
        notifyError("Ocurrio error al eliminar")
    }
};

function openModal(item) {
    titleForm.value.openModal(item)
}

function updateFrontEnd(id) {
    let data = titles.data || titles
    let index = data.findIndex(item => item.id === id)
    data.splice(index, 1)
    notify("Eliminacion exitosa")
    closeModalDoc()
}
</script>
