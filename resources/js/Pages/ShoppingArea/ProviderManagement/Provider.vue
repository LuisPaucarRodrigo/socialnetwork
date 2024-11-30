<template>

    <Head title="Proveedores" />
    <AuthenticatedLayout :redirectRoute="'providersmanagement.index'">
        <template #header>
            Proveedores
        </template>

        <div class="min-w-full overflow-hidden rounded-lg shadow">
            <div class="flex justify-between items-center gap-4">
                <button v-if="hasPermission('PurchasingManager')" @click="add_information(initialStateForm)"
                    type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    + Agregar
                </button>
                <div class="flex items-center">
                    <TextInput type="text" placeholder="Buscar..." @input="search($event.target.value)"
                        class="block w-full ml-2 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full whitespace-nowrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                RUC
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Compañia
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Contacto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                zona
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Direccion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Telefono
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Email
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Categoria
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Segmento
                            </th>
                            <th v-if="auth.user.role_id == 1"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in providers.data ?? providers" :key="item.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-nowrap text-xs">{{ item.ruc }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-nowrap text-xs">{{ item.company_name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-nowrap text-xs">{{ item.contact_name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-nowrap text-xs">{{ item.zone }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-nowrap text-xs">{{ item.address }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-nowrap text-xs">{{ item.phone1 }} {{
                                    item.phone2 }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-nowrap text-xs">{{ item.email }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-nowrap text-xs">{{ item.category.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p v-for="i in item.segments" class="text-gray-900 whitespace-nowrap text-xs">
                                    {{ i.name }}
                                </p>
                            </td>
                            <td v-if="auth.user.role_id == 1" class="border-b border-gray-200 bg-white px-3 py-2">
                                <div class="flex space-x-3 justify-center">
                                    <button type="button" @click="add_information(item)"
                                        class="text-blue-900 whitespace-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                        </svg>
                                    </button>
                                    <button type="button" @click="confirmProviderDeletion(item)"
                                        class="text-blue-900 whitespace-nowrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="providers.data"
                class="flex flex-col items-center border-t bg-white px-3 py-2 xs:flex-row xs:justify-between">
                <pagination :links="providers.links" />
            </div>
        </div>

        <Modal :show="showModalStoreOrUpdate">
            <div class="p-6">
                <h2 class="text-base font-semibold leading-7 text-gray-900">Informacion</h2>

                <form @submit.prevent="submit">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <InputLabel for="ruc" class="font-medium leading-6 text-gray-900">RUC</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.ruc" id="ruc" pattern="\d*" autocomplete="off"
                                    maxlength="11"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.ruc" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="company_name" class="font-medium leading-6 text-gray-900">Compañia
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.company_name" id="company_name"
                                    :to-uppercase="true" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.company_name" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="contact_name" class="font-medium leading-6 text-gray-900">Nombre de
                                Contacto
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.contact_name" id="contact_name"
                                    :to-uppercase="true" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.contact_name" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Zona
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.zone" id="zone" :to-uppercase="true"
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.zone" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="address" class="font-medium leading-6 text-gray-900">Direccion
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.address" id="address" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.address" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="email" class="font-medium leading-6 text-gray-900">Email</InputLabel>
                            <div class="mt-2">
                                <TextInput type="email" v-model="form.email" id="email"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.email" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="phone1" class="font-medium leading-6 text-gray-900">Telefono 1
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.phone1" id="phone1" maxlength="9"
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.phone1" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="phone2" class="font-medium leading-6 text-gray-900">Telefono 2
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.phone2" id="phone2" maxlength="9"
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.phone2" />
                            </div>
                        </div>
                        <div class="sm:col-span-3 sm:col-start-1">
                            <div class="flex items-center gap-2">
                                <InputLabel for="category" class="font-medium leading-6 text-gray-900">Categoria
                                </InputLabel>
                                <button type="button" @click="createCategory">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-2">
                                <select required v-model="form.category_id" id="category"
                                    class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                                    <option disabled value="">Seleccione</option>
                                    <option v-for="item in category" :key="item.id" :value="item.id">
                                        {{ item.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.category_id" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <div class="flex items-center gap-2">
                                <InputLabel for="segment" class="font-medium leading-6 text-gray-900">Segmento
                                </InputLabel>
                                <button type="button" @click="createSegment">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </div>

                            <div class="mt-2">
                                <select multiple size="4" v-model="form.segments" id="segment"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled>
                                        Selecciona uno o varios
                                    </option>
                                    <option v-for="item in segmentsList" :key="item.id" :value="item.id"
                                        :selected="form.segments.includes(item.id)">
                                        {{ item.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.segments" />
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton type="button" @click="closeModalStoreOrUpdate"> Cerrar </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing">
                            {{ form.id ? "Actualizar" : "Crear" }}
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
        <Modal :show="showModalAddCategory">
            <form class="p-6" @submit.prevent="submitCategoryOrSegment">
                <h2 class="text-lg font-medium text-gray-900">
                    Nueva Categoria
                </h2>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                    <div class="sm:col-span-6">
                        <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                        <div class="mt-2">
                            <TextInput id="name" :to-uppercase="true" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                v-model="formCategory.name" />
                            <InputError :message="formCategory.errors.name" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="closeAddModal()"> Cerrar </SecondaryButton>
                    <PrimaryButton type="submit" :class="{ 'opacity-25': formCategory.processing }"
                        :disabled="formCategory.processing">
                        Agregar
                    </PrimaryButton>
                </div>
            </form>
        </Modal>
        <Modal :show="showModalAddSegment">
            <form class="p-6" @submit.prevent="submitCategoryOrSegment">
                <h2 class="text-lg font-medium text-gray-900">
                    Nuevo Segmento
                </h2>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-2 mt-2">
                    <div>
                        <InputLabel for="name" class="font-medium leading-6 text-gray-900">Categoria</InputLabel>
                        <select v-model="formSegment.category_id" id="segment"
                            class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                            <option disabled value="">Seleccione Categoria</option>
                            <option v-for="item in category" :key="item.id" :value="item.id">
                                {{ item.name }}
                            </option>
                        </select>
                    </div>
                    <div>
                        <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                        <div class="mt-2">
                            <TextInput id="name" :to-uppercase="true" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                v-model="formSegment.name" />
                            <InputError :message="formSegment.errors.name" />
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="closeAddModal()"> Cerrar </SecondaryButton>
                    <PrimaryButton type="submit"> Agregar </PrimaryButton>
                </div>
            </form>
        </Modal>
        <!-- <ProviderCreateAndUpdate :showModalStoreOrUpdate="showModalStoreOrUpdate" :provider="form" :categories="category"  @submit="submitCategoryOrSegment"
        @close="closeModalStoreOrUpdate"/> -->
        <ConfirmDeleteModal :confirmingDeletion="confirmingProviderDeletion" itemType="proveedor" :nameText="name"
            :deleteFunction="deleteProvider" @closeModal="closeModal" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { setAxiosErrors } from '@/utils/utils';
import { notify, notifyError } from '@/Components/Notification';

const { provider, auth, userPermissions, category } = defineProps({
    provider: Object,
    auth: Object,
    userPermissions: Array,
    category: Object
});

const providers = ref(provider)

const hasPermission = (permission) => {
    return userPermissions.includes(permission);
}

const confirmingProviderDeletion = ref(false);
const provider_id = ref(null);
const name = ref(null);
const showModalStoreOrUpdate = ref(false)
const categoryAndSegment = ref(false);
const segmentsList = ref([]);
const showModalAddCategory = ref(false);
const showModalAddSegment = ref(false);

const initialStateForm = {
    company_name: '',
    contact_name: '',
    address: '',
    phone1: '',
    phone2: '',
    email: '',
    category_id: '',
    segments: [],
    zone: '',
    ruc: '',
    id: '',
}

const form = useForm({ ...initialStateForm });

const confirmProviderDeletion = (provider_fun) => {
    provider_id.value = provider_fun.id;
    name.value = provider_fun.contact_name;
    confirmingProviderDeletion.value = true;
};

async function deleteProvider() {
    let url = route('providersmanagement.destroy', { id: provider_id.value });
    try {
        await axios.delete(url)
        updateProvider(provider_id.value, 'delete')
        closeModal()
    } catch (error) {
        notifyError(error)
    }
};

const closeModal = () => {
    confirmingProviderDeletion.value = false;
};

const add_information = (item) => {
    form.defaults({ ...item, segments: item.segments.map(item => item.id) })
    form.reset()
    showModalStoreOrUpdate.value = true
};

async function search($search) {
    let url = route('providersmanagement.index')
    try {
        let response = await axios.post(url, { searchQuery: $search })
        providers.value = response.data
    } catch (error) {
        notifyError(error)
    }
}

async function submit() {
    let url = form.id ? route('providersmanagement.update', { id: form.id }) : route('providersmanagement.store')
    try {
        let response = await axios.post(url, form);
        let operation = form.id ? 'update' : 'store'
        closeModalStoreOrUpdate();
        updateProvider(response.data, operation)
    } catch (error) {
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form)
            } else {
                console.error("Server error:", error.response.data)
            }
        } else {
            notifyError("Server Error");
        }
    }
};

function closeModalStoreOrUpdate() {
    form.clearErrors()
    form.defaults({ ...initialStateForm })
    form.reset()
    showModalStoreOrUpdate.value = false
}

const initialStateFormCategory = {
    name: ''
}

const formCategory = useForm({ ...initialStateFormCategory })

const initialStateFormSegment = {
    category_id: '',
    name: ''
}

const formSegment = useForm({
    ...initialStateFormSegment
})

function createCategory() {
    showModalAddCategory.value = true
    categoryAndSegment.value = false
}

function createSegment() {
    showModalAddSegment.value = true
    categoryAndSegment.value = true
}

function closeAddModal() {
    if (categoryAndSegment.value) {
        showModalAddSegment.value = false
        formSegment.clearErrors()
        Object.assign(formSegment, initialStateFormSegment)
    } else {
        showModalAddCategory.value = false
        formCategory.clearErrors()
        Object.assign(formCategory, initialStateFormCategory)
    }
}

watch(() => form.category_id, (newValue) => {
    handleCategoryChange(form.category_id)
});

async function handleCategoryChange(category_id) {
    let url = route('provider.segments.list', { category_id: category_id });
    try {
        let response = await axios.get(url)
        segmentsList.value = response.data
    } catch (error) {
        console.error(error)
    }
}

async function submitCategoryOrSegment() {
    let url = categoryAndSegment.value == false ? route('provider.category.post') : route('provider.segment.post')
    let selectedForm = categoryAndSegment.value == false ? formCategory : formSegment
    try {
        let response = await axios.post(url, selectedForm)
        let cs = categoryAndSegment.value == false ? 'Category' : 'Segment'
        updateCategoryOrSegment(response.data, cs);
        closeAddModal()
    } catch (error) {
        if (error.response) {
            setAxiosErrors(error.response.data.errors, selectedForm)
        } else {
            console.error('Error desconocido:', error);
        }
    }
};

function updateCategoryOrSegment(item, cs) {
    if (cs === 'Category') {
        category.push(item.new)
        notify('Categoria Creada')
    } else if (cs === 'Segment') {
        notify('Segmento Creado')
    }
}

function updateProvider(provider, operation) {
    const validations = providers.value.data || providers.value;
    const index = validations.findIndex(item => item.id = provider.id ?? provider)
    if (operation === 'update') {
        validations[index] = provider
        notify('Proveedor Actualizado')
    } else if (operation === 'store') {
        validations.push(provider);
        notify('Proveedor Creado')
    } else if (operation === 'delete') {
        validations.splice(index, 1)
        notify('Proveedor Eliminado')
    }

    if (validations.length > validations.value.per_page) {
        validations.pop();
    }
}

</script>
