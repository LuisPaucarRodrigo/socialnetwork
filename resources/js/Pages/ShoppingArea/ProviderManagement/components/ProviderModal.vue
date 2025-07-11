<template>
    <Modal :show="showModalStoreOrUpdate">
        <div class="p-6">
            <h2 class="text-base font-semibold leading-7 text-gray-900">
                {{ form.id ? 'Actualizar ' : 'Crear ' }} Proveedor
            </h2>

            <form @submit.prevent="submit">
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
                    <FormInput id="ruc" label="RUC" v-model="form.ruc" pattern="\d*" maxlength="11"
                        :error-message="form.errors.ruc" col-span="sm:col-span-2" />

                    <FormInput id="company_name" label="Compañía" v-model="form.company_name"
                        :error-message="form.errors.company_name" to-uppercase />

                    <FormInput id="contact_name" label="Nombre de Contacto" v-model="form.contact_name"
                        :error-message="form.errors.contact_name" col-span="sm:col-span-2" to-uppercase />

                    <FormInput id="account_number" label="Numero de Cuenta" v-model="form.account_number" :error-message="form.errors.account_number"
                        col-span="sm:col-span-3" to-uppercase />

                    <FormInput id="zone" label="Zona" v-model="form.zone" :error-message="form.errors.zone"
                        col-span="sm:col-span-3" to-uppercase />

                    <FormInput id="address" label="Dirección" v-model="form.address"
                        :error-message="form.errors.address" col-span="sm:col-span-2" />

                    <FormInput id="email" label="Email" type="email" v-model="form.email"
                        :error-message="form.errors.email" col-span="sm:col-span-2" />

                    <FormInput id="phone1" label="Teléfono 1" v-model="form.phone1" maxlength="9"
                        :error-message="form.errors.phone1" col-span="sm:col-span-2" />

                    <FormInput id="phone2" label="Teléfono 2" v-model="form.phone2" maxlength="9"
                        :error-message="form.errors.phone2" col-span="sm:col-span-2" />

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
                    <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
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
                        <TextInput id="name" :to-uppercase="true" require v-model="formCategory.name" />
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
                        <TextInput id="name" :to-uppercase="true" require v-model="formSegment.name" />
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
</template>

<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify, notifyError } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { setAxiosErrors } from '@/utils/utils';
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import FormInput from '@/Layouts/FormInput.vue';

const { showModalStoreOrUpdate, form, category, closeModalStoreOrUpdate } = defineProps({
    showModalStoreOrUpdate: Boolean,
    form: Object,
    category: Object,
    closeModalStoreOrUpdate: Function
})
const providers = defineModel('providers')

const showModalAddCategory = ref(false);
const showModalAddSegment = ref(false);
const segmentsList = ref([]);
const categoryAndSegment = ref(false)

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



function createCategory() {
    showModalAddCategory.value = true
    categoryAndSegment.value = false
}

function createSegment() {
    showModalAddSegment.value = true
    categoryAndSegment.value = true
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