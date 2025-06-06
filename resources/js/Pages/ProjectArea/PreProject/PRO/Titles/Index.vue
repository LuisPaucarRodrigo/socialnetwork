<template>

    <Head title="Clientes" />

    <AuthenticatedLayout :redirectRoute="'preprojects.titles'">
        <template #header>
            Títulos
        </template>
        <div class="mt-6 flex items-center justify-start gap-x-3">
            <PrimaryButton @click="add_title" type="button">
                + Agregar
            </PrimaryButton>
            <PrimaryButton @click="management_codes" type="button">
                Codes
            </PrimaryButton>
        </div>
        <TableStructure>
            <template #thead>
                <tr>
                    <TableTitle>Título</TableTitle>
                    <TableTitle>Tipo</TableTitle>
                    <TableTitle>Códigos</TableTitle>
                    <TableTitle></TableTitle>
                </tr>
            </template>
            <template #tbody>
                <tr v-for="title in titles.data" :key="title.id">
                    <TableRow>{{ title.title }}</TableRow>
                    <TableRow>{{ title.type }}</TableRow>
                    <TableRow>{{title.codes.map((item) => item.code).join(', ')}}</TableRow>
                    <TableRow>
                        <div class="flex justify-center space-x-3">
                            <button type="button" @click="openEditTitleModal(title)">
                                <EditIcon />
                            </button>
                            <button type="button" @click="confirmDeleteTitle(title.id)">
                                <DeleteIcon />
                            </button>
                        </div>
                    </TableRow>
                </tr>
            </template>
        </TableStructure>

        <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="props.titles.links" />
        </div>

        <TitleForm ref="titleForm"/>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Título" />
        <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Título" />
        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Título" :deleteFunction="deleteTitle"
            @closeModal="closeModalDoc" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import { DeleteIcon, EditIcon } from "@/Components/Icons/Index";
import TitleForm from './components/TitleForm.vue';

const create_title = ref(false);
const showModal = ref(false);
const showModalEdit = ref(false);
const editingTitle = ref(null);
const edit_title = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);

const props = defineProps({
    titles: Object,
    codes: Object,
    userPermissions: Array,
    stages: Object,
})

const add_title = () => {
    create_title.value = true;
}

const form = useForm({
    id: '',
    title: '',
    type: '',
    code_id_array: [],
});

const openEditTitleModal = (title) => {
    editingTitle.value = JSON.parse(JSON.stringify(title));
    form.id = editingTitle.value.id;
    form.title = editingTitle.value.title;
    form.type = editingTitle.value.type;
    form.code_id_array = editingTitle.value.codes.map((item) => item.id);

    edit_title.value = true;
};

const confirmDeleteTitle = (titleId) => {
    docToDelete.value = titleId;
    confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};

const deleteTitle = () => {
    const docId = docToDelete.value;
    if (docId) {
        router.delete(route('preprojects.titles.delete', { title: docId }), {
            onSuccess: () => {
                closeModalDoc(),
                    router.visit(route('preprojects.titles'))
            }
        });
    }
};

const management_codes = () => {
    router.get(route('preprojects.codes'));
}
</script>
