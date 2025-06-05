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

        <Modal :show="create_title || edit_title">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ create_title ? 'Agregar tìtulo' : 'Actualizar tìtulo' }}
                </h2>
                <form @submit.prevent="create_title ? submit() : submitEdit()">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8">
                                <div>
                                    <InputLabel for="title" class="font-medium leading-6 text-gray-900">Título
                                    </InputLabel>
                                    <div class="mt-2">
                                        <input v-model="form.title" id="title"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                        <InputError :message="form.errors.title" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="type" class="font-medium leading-6 text-gray-900">Tipo
                                    </InputLabel>
                                    <div class="mt-2">
                                        <select v-model="form.type" id="type"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            <option value="">Selecciona etapa</option>
                                            <option v-for="stage in stages" :key="stage.id" :value="stage.name">
                                                {{ stage.name }}
                                            </option>
                                        </select>
                                        <InputError :message="form.errors.type" />
                                    </div>
                                </div>

                                <div>
                                    <InputLabel for="codes" class="font-medium leading-6 text-gray-900">Códigos
                                    </InputLabel>
                                    <div class="mt-2">
                                        <select multiple v-model="form.code_id_array" id="codes" size="15"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            <option v-for="code in props.codes" :key="code.id" :value="code.id">
                                                {{ code.code }}
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div>
                                    <InputLabel for="codes" class="font-medium leading-6 text-gray-900">Codigos
                                        Seleccionados
                                    </InputLabel>
                                    <div class="mt-2 grid grid-cols-3 gap-2">
                                        <p v-for="cod in form.code_id_array" class="text-xs whitespace-nowrap">
                                            - {{codes.find(item => item.id == cod).code}}
                                        </p>
                                    </div>
                                </div>
                            </div>


                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <SecondaryButton @click="create_title ? close_add_title() : close_edit_title()">
                                    Cancelar
                                </SecondaryButton>
                                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                                    {{ create_title ? 'Guardar' : 'Actualizar' }}</PrimaryButton>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>


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
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import { DeleteIcon, EditIcon } from "@/Components/Icons/Index";

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

const close_add_title = () => {
    form.reset();
    create_title.value = false;
}

const close_edit_title = () => {
    form.reset();
    edit_title.value = false;
}

const form = useForm({
    id: '',
    title: '',
    type: '',
    code_id_array: [],
});

const submit = () => {
    form.post(route('preprojects.titles.post'), {
        onSuccess: () => {
            close_add_title();
            form.reset();
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                router.get(route('preprojects.titles'))
            }, 2000);
        },
    });
};

const submitEdit = () => {
    form.put(route('preprojects.titles.put', { title: form.id }), {
        onSuccess: () => {
            close_edit_title();
            form.reset();
            showModalEdit.value = true
            setTimeout(() => {
                showModalEdit.value = false;
                router.get(route('preprojects.titles'))
            }, 2000);
        }
    });
};

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
