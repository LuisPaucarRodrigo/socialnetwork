<template>

    <Head title="Proveedores" />
    <AuthenticatedLayout :redirectRoute="'providersmanagement.index'">
        <template #header>
            Proveedores
        </template>
        <Toaster richColors />
        <div class="min-w-full overflow-hidden">
            <div class="flex justify-between items-center gap-4">
                <button v-if="hasPermission('PurchasingManager')" @click="add_information(initialStateForm)"
                    type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    + Agregar
                </button>
                <div class="flex items-center">
                    <TextInput data-tooltip-target="search_fields" type="text" placeholder="Buscar..."
                        @keyup.enter="search($event.target.value)"
                        class="block w-full ml-2 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" />
                    <div id="search_fields" role="tooltip"
                        class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Compañia,Contacto,Ruc
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                </div>
            </div>
            <TableStructure>
                <template #thead>
                    <tr>
                        <TableTitle>RUC</TableTitle>
                        <TableTitle>Compańia</TableTitle>
                        <TableTitle>Contacto</TableTitle>
                        <TableTitle>Zona</TableTitle>
                        <TableTitle>Dirección</TableTitle>
                        <TableTitle>Telefono</TableTitle>
                        <TableTitle>Email</TableTitle>
                        <TableTitle>Categoria</TableTitle>
                        <TableTitle>Segmento</TableTitle>
                        <TableTitle v-if="auth.user.role_id == 1"></TableTitle>
                    </tr>
                </template>
                <template #tbody>
                    <tr v-for="item in providers.data ?? providers" :key="item.id">
                        <TableRow>{{ item.ruc }}</TableRow>
                        <TableRow>{{ item.company_name }}</TableRow>
                        <TableRow>{{ item.contact_name }}</TableRow>
                        <TableRow>{{ item.zone }}</TableRow>
                        <TableRow :width="'w-[500px]'">{{ item.address }}</TableRow>
                        <TableRow>{{ item.phone1 }} {{ item.phone2 }}</TableRow>
                        <TableRow>{{ item.email }}</TableRow>
                        <TableRow>{{ item.category?.name }}</TableRow>
                        <TableRow :width="'w-[500px]'">
                            <p v-for="(i, index) in item.segments" :key="index" class="text-gray-900 inline">
                                {{ i.name }}<span v-if="index < item.segments.length - 1">, </span>
                            </p>
                        </TableRow>
                        <TableRow v-if="auth.user.role_id == 1">
                            <div class="flex space-x-3 justify-center">
                                <button type="button" @click="add_information(item)">
                                    <PencilSquareIcon class="w-6 h-6 text-yellow-400" />
                                </button>
                                <button type="button" @click="confirmProviderDeletion(item)">
                                    <TrashIcon class="w-6 h-6 text-red-400" />
                                </button>
                            </div>
                        </TableRow>
                    </tr>
                </template>
            </TableStructure>
            <div v-if="providers.data"
                class="flex flex-col items-center border-t bg-white px-3 py-2 xs:flex-row xs:justify-between">
                <pagination :links="providers.links" />
            </div>
        </div>

        <Modal :show="showModalStoreOrUpdate">
            <div class="p-6">
                <h2 class="text-base font-semibold leading-7 text-gray-900">
                    {{ form.id ? 'Actualizar ' : 'Crear ' }} Proveedor
                </h2>

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
        <ConfirmDeleteModal :confirmingDeletion="confirmingProviderDeletion" itemType="proveedor" :nameText="name"
            :deleteFunction="deleteProvider" @closeModal="closeModal" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { setAxiosErrors } from '@/utils/utils';
import { notify, notifyError } from '@/Components/Notification';
import { Toaster } from 'vue-sonner';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import { PencilSquareIcon, TrashIcon } from '@heroicons/vue/24/outline';

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
    const index = validations.findIndex(item => item.id == provider?.id)
    if (operation === 'update') {
        validations[index] = provider
        notify('Proveedor Actualizado')
    } else if (operation === 'store') {
        validations.unshift(provider);
        notify('Proveedor Creado')
    } else if (operation === 'delete') {
        validations.splice(index, 1)
        notify('Proveedor Eliminado')
    }
}

</script>
