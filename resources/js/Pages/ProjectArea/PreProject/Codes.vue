<template>

    <Head title="Clientes" />

    <AuthenticatedLayout :redirectRoute="'preprojects.titles'">
        <template #header>
            Códigos
        </template>
        <div class="mt-6 flex items-center justify-between gap-x-6">
            <button v-if="hasPermission('ProjectManager')" @click="add_code" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                + Agregar
            </button>
        </div>

        <div class="overflow-x-auto rounded-lg shadow mt-2">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Código
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Descripción
                        </th>
                        <th v-if="hasPermission('ProjectManager')"
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="code in codes.data" :key="code.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <p class="text-gray-900 whitespace-no-wrap">{{ code.code }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <p class="text-gray-900 whitespace-no-wrap">{{ code.description }}</p>
                        </td>
                        <td v-if="hasPermission('ProjectManager')"
                            class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <div class="flex justify-center space-x-3">
                                <button @click="openModal(code.code_images)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                                <button type="button" @click="openEditCodeModal(code)"
                                    class="text-yellow-600 whitespace-no-wrap">
                                    <PencilIcon class="h-5 w-5 ml-1" />
                                </button>
                                <button type="button" @click="confirmDeleteCode(code.id)"
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
            <pagination :links="props.codes.links" />
        </div>

        <Modal :show="create_code || edit_code">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ create_code ? 'Agregar código' : 'Actualizar código' }}
                </h2>
                <form @submit.prevent="create_code ? submit() : submitEdit()">
                    <div class="space-y-12">
                        <div>
                            <div>
                                <InputLabel for="code" class="font-medium leading-6 text-gray-900 mt-3">Código
                                </InputLabel>
                                <div class="mt-2">
                                    <input v-model="form.code" id="code"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.code" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="description" class="font-medium leading-6 text-gray-900 mt-3">
                                    Descripción
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.description" id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.description" />
                                </div>
                            </div>

                            <div class="pt-3">
                                <button type="button" @click="addimage"
                                    class="font-medium text-indigo-600 hover:text-indigo-500 self-start sm:self-end">Agregar
                                    imagenes
                                </button>

                                <div v-for="(image, index) in form.images" :key="index">
                                    <div class="flex justify-end mt-5">
                                        <button type="button" @click="removeimage(index)"
                                            class="font-medium text-red-600 hover:text-indigo-500">Eliminar</button>
                                    </div>
                                    <div class="sm:col-span-1">
                                        <div class="mt-2">
                                            <InputFile v-model="image.image" id="image" />
                                            <InputError :message="form.errors['images.' + index + '.image']" />
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <SecondaryButton @click="create_code ? close_add_code() : close_edit_code()"> Cancelar
                                </SecondaryButton>
                                <button type="submit" :class="{ 'opacity-25': form.processing }"
                                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{
        create_code ? 'Guardar' : 'Actualizar' }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Código" />
        <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Código" />
        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Código" :deleteFunction="deleteCode"
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
import InputFile from '@/Components/InputFile.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { TrashIcon, PencilIcon } from '@heroicons/vue/24/outline';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const create_code = ref(false);
const showModal = ref(false);
const showModalEdit = ref(false);
const editingCode = ref(null);
const edit_code = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const showModalViewImage = ref(null);

const props = defineProps({
    codes: Object,
    userPermissions: Array
})

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission)
}

const add_code = () => {
    create_code.value = true;
}

const close_add_code = () => {
    form.reset();
    create_code.value = false;
}

const close_edit_code = () => {
    form.reset();
    edit_code.value = false;
}

const form = useForm({
    id: '',
    code: '',
    description: '',
    
});

const formImage = useForm({
    images: []
})

function addimage() {
    form.images.push({
        image: '',
    });
}

function removeimage(index) {
    form.images.splice(index, 1);
}

function openModal(images){
    showModalViewImage.value = !showModalViewImage.value


}

const submit = () => {
    form.post(route('preprojects.codes.post'), {
        onSuccess: () => {
            close_add_code();
            form.reset();
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
            }, 2000);
        },
    });
};

const submitEdit = () => {
    form.put(route('preprojects.codes.put', { code: form.id }), {
        onSuccess: () => {
            close_edit_code();
            form.reset();
            showModalEdit.value = true
            setTimeout(() => {
                showModalEdit.value = false;
            }, 2000);
        }
    });
};

const openEditCodeModal = (code) => {
    editingCode.value = JSON.parse(JSON.stringify(code));
    form.id = editingCode.value.id;
    form.code = editingCode.value.code;
    form.description = editingCode.value.description;

    edit_code.value = true;
};

const confirmDeleteCode = (codeId) => {
    docToDelete.value = codeId;
    confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};

const deleteCode = () => {
    const docId = docToDelete.value;
    if (docId) {
        router.delete(route('preprojects.codes.delete', { code: docId }), {
            onSuccess: () => {
                closeModalDoc()
            }
        });
    }
};


</script>
