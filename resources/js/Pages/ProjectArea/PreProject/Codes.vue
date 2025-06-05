<template>

    <Head title="Clientes" />

    <AuthenticatedLayout :redirectRoute="'preprojects.titles'">
        <template #header>
            Códigos
        </template>
        <div class="mt-6 flex items-center justify-between gap-x-6">
            <button @click="add_code" type="button"
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
                        <th
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
                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-xs">
                            <div class="flex justify-center space-x-3">
                                <button @click="modalStoreImage(code.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                                <button @click="openModal(code.id)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-blue-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                </button>
                                <button type="button" @click="openEditCodeModal(code)">
                                    <EditIcon />
                                </button>
                                <button type="button" @click="confirmDeleteCode(code.id)">
                                    <DeleteIcon />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="codes.links" />
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
                            <div class="mt-6 flex items-center justify-end gap-x-3">
                                <SecondaryButton @click="create_code ? close_add_code() : close_edit_code()"> Cerrar
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
        <Modal :show="showStoreImage">
            <div class="p-6">
                <div class="flex justify-start space-x-3">
                    <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                        Agregar Imagenes
                    </h2>
                    <button type="button" @click="addimage">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-green-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitImage">
                    <div v-for="(image, index) in formImage.images" :key="index">
                        <div class="flex justify-between space-x-3 w-full">
                            <div class="mt-2">
                                <InputFile v-model="image.image" id="image" />
                                <InputError :message="formImage.errors['images.' + index + '.image']" />
                            </div>
                            <button type="button" @click="removeimage(index)"
                                class="font-medium text-red-600 hover:text-red-500">
                                <DeleteIcon />
                            </button>
                        </div>
                    </div>
                    <div class="mt-6 flex justify-end gap-x-3">
                        <SecondaryButton @click="modalStoreImage(null)">Cerrar</SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': formImage.processing }">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="showModalViewImage">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Imagenes
                </h2>
                <div class="overflow-x-auto">
                    <table class="w-full whitespace-nowrap">
                        <thead>
                            <tr
                                class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                <th
                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Nombre
                                </th>
                                <th
                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(item, i) in listImageView" :key="i" class="text-gray-700">
                                <td class="border-b border-gray-200 bg-white px-5 py-3]">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ item.image }}</p>
                                </td>
                                <td class="flex border-b border-gray-200 bg-white px-5 py-3 justify-center">
                                    <button type="button" @click="show_image(item.id)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-green-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </button>
                                    <button type="button" @click="delete_image(item.id)">
                                        <DeleteIcon />
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex justify-end gap-x-3">
                    <SecondaryButton @click="openModal">Cerrar</SecondaryButton>
                </div>
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
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { EditIcon, DeleteIcon } from '@/Components/Icons/Index';

const create_code = ref(false);
const showModal = ref(false);
const showModalEdit = ref(false);
const editingCode = ref(null);
const edit_code = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const showModalViewImage = ref(null);
const listImageView = ref([])
const showStoreImage = ref(false)

const props = defineProps({
    code: Object,
    userPermissions: Array
})

const codes = ref(props.code)

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
    code_id: null,
    images: []
})

function addimage() {
    formImage.images.push({
        image: '',
    });
}

function removeimage(index) {
    formImage.images.splice(index, 1);
}

function modalStoreImage(id = null) {
    formImage.code_id = id ?? null;
    if (formImage.code_id) {
        formImage.images.length = 0
    }
    showStoreImage.value = !showStoreImage.value
}

async function openModal(code_id) {
    try {
        const response = await axios.get(route('preprojects.code.images.index', { code_id: code_id }));
        listImageView.value = response.data
        showModalViewImage.value = !showModalViewImage.value
    } catch (error) {
        console.error('Error al cargar las imágenes:', error);
        listImageView.value = [];
    }

}

function show_image(imageId) {
    if (imageId) {
        const url = route('preprojects.code.images.show', { image_id: imageId });
        axios.get(url)
            .then(response => {
                const imageUrl = response.data.url;
                window.open(imageUrl, '_blank');
            })
            .catch(error => {
                console.error('Error fetching image URL:', error);
            });
    } else {
        console.error('No se proporcionó un ID de imagen válido');
    }
}

async function delete_image(image_id) {
    const url = route('preprojects.code.images.delete', { image_id: image_id });
    try {
        await axios.delete(url)
        showModalViewImage.value = !showModalViewImage.value
        visuallyChangeImage(image_id)
    } catch (error) {
        console.error('Error fetching image URL:', error);
    }
}

function visuallyChangeImage(image_id) {
    const index = listImageView.value.findIndex(item => item.id === image_id);
    listImageView.splice(index, 1)
}

async function submit() {
    try {
        const response = await axios.post(route('preprojects.codes.post'), form)
        updateCode(response.data, true)
        close_add_code();
        form.reset();
        showModal.value = true
        setTimeout(() => {
            showModal.value = false;
        }, 2000);
    } catch (error) {
        console.error(e)
    }
};

async function submitImage() {
    try {
        await axios.post(route('preprojects.code.images.store'), formImage)
        showStoreImage.value = false;
    } catch (error) {
        console.error(error);
    }
};

async function submitEdit() {
    try {
        const response = await axios.put(route('preprojects.codes.put', { code: form.id }), form)
        updateCode(response.data, false)
        close_edit_code();
        form.reset();
        showModalEdit.value = true
        setTimeout(() => {
            showModalEdit.value = false;
        }, 2000);
    } catch (error) {

    }
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
