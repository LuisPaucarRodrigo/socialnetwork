<template>

    <Head title="Clientes" />
    <AuthenticatedLayout :redirectRoute="'preprojects.titles'">
        <template #header>
            Códigos
        </template>
        <Toaster richColors />
        <TableHeader :openModal="openModal" />

        <CodesTable :codes="codes" :openImagesForm="openImagesForm" :openModal="openModal"
            :openImagesModal="openImagesModal" :confirmDeleteCode="confirmDeleteCode" />

        <CodesForm ref="codesForm" :codes="codes" />
        <ImagesForm ref="imagesForm" />
        <ImagesModal ref="imagesModal" />
        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Código" :deleteFunction="deleteCode"
            @closeModal="closeModalDoc" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import CodesTable from './components/CodesTable.vue';
import TableHeader from './components/TableHeader.vue';
import CodesForm from './components/CodesForm.vue';
import { Toaster } from 'vue-sonner';
import ImagesForm from './components/ImagesForm.vue';
import ImagesModal from './components/ImagesModal.vue';

const props = defineProps({
    code: Object,
    userPermissions: Array
})

const codesForm = ref(null);
const imagesForm = ref(null)
const imagesModal = ref(null)

const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);

const codes = ref({ ...props.code })

function openModal(item) {
    codesForm.value.openModal(item)
}

function openImagesForm(code_id) {
    imagesForm.value.openImagesForm(code_id)
}

function openImagesModal(code_id) {
    imagesModal.value.openImagesModal(code_id)
}

const confirmDeleteCode = (codeId) => {
    docToDelete.value = codeId;
    confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};

async function deleteCode() {
    const docId = docToDelete.value;
    if (docId) {
        try {
            await axios.delete(route('preprojects.codes.delete', { code: docId }))
            updateCode(docId)
            closeModalDoc()
        } catch (error) {
            if (error.response) {
                alert(error.response.data.message);
            } else {
                console.error(error)
            }
        }
    }
};

function updateCode(code, boolean) {
    const index = codes.value.data.findIndex(item => item.id === code.id ?? code)
    if (code.id) {
        if (boolean) {
            if (codes.value.data.length < codes.value.per_page) {
                codes.value.data.push(code)
            }
        } else {
            codes.value.data[index] = code
        }
    } else {
        codes.value.data.splice(index, 1)
    }
}

</script>
