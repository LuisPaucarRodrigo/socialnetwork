<template>
    <Modal :show="showModalStoreOrUpdate">
        <form class="p-6" @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informacion</h2>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <InputLabel for="ruc" class="font-medium leading-6 text-gray-900">RUC</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="provider.ruc" id="ruc" pattern="\d*" autocomplete="off"
                                    maxlength="11"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="provider.errors.ruc" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="company_name" class="font-medium leading-6 text-gray-900">Compañia
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="provider.company_name" id="company_name"
                                    :to-uppercase="true" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="provider.errors.company_name" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="contact_name" class="font-medium leading-6 text-gray-900">Nombre de
                                Contacto
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="provider.contact_name" id="contact_name"
                                    :to-uppercase="true" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="provider.errors.contact_name" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Zona
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="provider.zone" id="zone" :to-uppercase="true"
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="provider.errors.zone" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="address" class="font-medium leading-6 text-gray-900">Direccion
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="provider.address" id="address" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="provider.errors.address" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="email" class="font-medium leading-6 text-gray-900">Email</InputLabel>
                            <div class="mt-2">
                                <TextInput type="email" v-model="provider.email" id="email"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="provider.errors.email" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="phone1" class="font-medium leading-6 text-gray-900">Telefono 1
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="provider.phone1" id="phone1" maxlength="9"
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="provider.errors.phone1" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="phone2" class="font-medium leading-6 text-gray-900">Telefono 2
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="provider.phone2" id="phone2" maxlength="9"
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="provider.errors.phone2" />
                            </div>
                        </div>
                        <div class="sm:col-span-2 sm:col-start-1">
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
                                <select v-model="provider.category" id="category" @change="handleCategoryChange"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                                    <option disabled value="">Seleccione</option>
                                    <option v-for="item in categories" :key="item.id" :value="item.name">
                                        {{ item.name }}
                                    </option>
                                </select>
                                <InputError :message="provider.errors.category" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
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
                                <select v-model="provider.segment" id="segment"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                                    <option disabled value="">Seleccione</option>
                                    <option v-for="item in segments" :key="item.id" :value="item.name">{{
                                        item.name
                                    }}
                                    </option>
                                </select>
                                <InputError :message="provider.errors.segment" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton type="button" @click="closeModalStoreOrUpdate"> Cerrar </SecondaryButton>
                <PrimaryButton type="submit" :class="{ 'opacity-25': provider.processing }" :disabled="provider.processing">
                    {{ provider ? "Actualizar" : "Crear" }}
                </PrimaryButton>
            </div>
        </form>
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
                <SecondaryButton type="button" @click="closeAddModal"> Cerrar </SecondaryButton>
                <PrimaryButton type="submit"> Agregar </PrimaryButton>
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
                    <select v-model="formSegment.provider_category_id" id="segment"
                        class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                        <option disabled value="">Seleccione Categoria</option>
                        <option v-for="item in categories" :key="item.id" :value="item.id">{{
                            item.name
                        }}
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
                <SecondaryButton type="button" @click="closeAddModal"> Cerrar </SecondaryButton>
                <PrimaryButton type="submit"> Agregar </PrimaryButton>
            </div>
        </form>
    </Modal>
    <SuccessOperationModal :confirming="addSuccess" title="" message="" />
    <ConfirmCreateModal :confirmingcreation="showModal" itemType="proveedor" />
</template>

<script setup>
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue'
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';

const { provider, categories, showModalStoreOrUpdate } = defineProps({
    provider: {
        type: Object,
        required: false
    },
    categories: {
        type: Object,
        required: true
    },
    showModalStoreOrUpdate: {
        type: Boolean,
        required: true
    }
});

const showModal = ref(false);
const categoryAndSegment = ref(false);
const showModalAddCategory = ref(false);
const showModalAddSegment = ref(false);
const addSuccess = ref(false)
const segments = ref([]);

const formCategory = useForm({
    name: ''
})

const formSegment = useForm({
    provider_category_id: '',
    name: ''
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
    } else {
        showModalAddCategory.value = false
    }
}


async function handleCategoryChange() {
    let url = route('provider.segments.list');
    try {
        let response = await axios.get(url)
        segments.value = response.data
    } catch (error) {

    }
}

const submitCategoryOrSegment = async () => {
    let url = categoryAndSegment.value == false ? route('provider.category') : route('provider.segment')
    let selectedForm = categoryAndSegment.value == false ? formCategory : formSegment
    try {
        let response = await axios.post(url, selectedForm)
        categoryAndSegment.value == false
            ? category.push({ ...response.data.new })
            : segments.value.push({ ...response.data.new })
        closeAddModal()
        addSuccess.value = true
        setTimeout(() => {
            addSuccess.value = false
        }, 600)
        formCategory.reset()
        formSegment.reset()
    } catch (error) {

    }
    axios.post(url, { ...formSegment.data() })
        .then(response => {
            if (response.status === 200) {
                let newItem = response.data.new
                categoryAndSegment.value == false
                    ? category.push({ ...newItem })
                    : segments.value.push({ ...newItem })
                closeAddModal()
                addSuccess.value = true
                setTimeout(() => {
                    addSuccess.value = false
                }, 600)
                formSegment.reset()
            } else {
                throw new Error('Fallo en el servidor con status ' + response.status)
            }
        })
        .catch(error => {
            console.error(error)
        })
};

const emit = defineEmits(['submit', 'close']);

function submit() {
  emit('submit', provider);
}

function closeModalStoreOrUpdate(){
    emit('close');
}

</script>