<template>

    <Head title="Clientes" />

    <AuthenticatedLayout :redirectRoute="'preprojects.titles'">
        <template #header>
            Tìtulos
        </template>
        <div class="mt-6 flex items-center justify-start gap-x-3">
            <PrimaryButton @click="add_title" type="button">
                + Agregar
            </PrimaryButton>
            <PrimaryButton @click="management_codes" type="button">
                Codes
            </PrimaryButton>
        </div>

        <div class="overflow-x-auto rounded-lg shadow mt-2">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Tìtulo
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Códigos
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="title in titles.data" :key="title.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ title.title }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900">{{ title.codes.map((item) => item.code).join(', ') }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <div class="flex justify-center space-x-3">
                                <button type="button" @click="openEditTitleModal(title)"
                                    class="text-yellow-600 whitespace-no-wrap">
                                    <PencilIcon class="h-5 w-5 ml-1" />
                                </button>
                                <button type="button" @click="confirmDeleteTitle(title.id)"
                                    class="text-red-600 whitespace-no-wrap">
                                    <TrashIcon class="h-5 w-5 ml-1" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

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

                            <div>
                                <InputLabel for="title" class="font-medium leading-6 text-gray-900 mt-3">Título
                                </InputLabel>
                                <div class="mt-2">
                                    <input v-model="form.title" id="title"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.title" />
                                </div>
                            </div>


                            <div>
                                <InputLabel for="codes" class="font-medium leading-6 text-gray-900">Códigos</InputLabel>
                                <div class="mt-2">
                                    <select multiple v-model="form.code_id_array" id="codes"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option v-for="code in props.codes" :key="code.id" :value="code.id">
                                            {{ code.code }}
                                        </option>
                                    </select>
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
import { TrashIcon, PencilIcon, DocumentArrowUpIcon } from '@heroicons/vue/24/outline';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const create_title = ref(false);
const showModal = ref(false);
const showModalEdit = ref(false);
const editingTitle = ref(null);
const edit_title = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);

const props = defineProps({
    titles: Object,
    codes: Object
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
                router.visit(route('preprojects.titles'))
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
                router.visit(route('preprojects.titles'))
            }, 2000);
        },
        onError: () => {
            form.reset();
        },
        onFinish: () => {
            form.reset();
        }
    });
};

const openEditTitleModal = (title) => {
    editingTitle.value = JSON.parse(JSON.stringify(title));
    form.id = editingTitle.value.id;
    form.title = editingTitle.value.title;
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
