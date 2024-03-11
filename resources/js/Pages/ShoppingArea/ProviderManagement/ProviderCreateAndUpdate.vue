<template>

    <Head title="Crear Proveedor" />
    <AuthenticatedLayout :redirectRoute="'providersmanagement.index'">
        <template #header>
            Proveedor
        </template>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informacion</h2>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <InputLabel for="ruc" class="font-medium leading-6 text-gray-900">RUC</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.ruc" id="ruc" pattern="\d*"
                                    autocomplete="given-name" maxlength="11"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.ruc" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="company_name" class="font-medium leading-6 text-gray-900">Compañia
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.company_name" id="company_name"
                                    :to-uppercase="true" autocomplete="given-name"
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
                                    :to-uppercase="true" autocomplete="family-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.contact_name" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="zone" class="font-medium leading-6 text-gray-900">Zona
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.zone" id="zone" :to-uppercase="true"
                                    autocomplete="family-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.zone" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="address" class="font-medium leading-6 text-gray-900">Direccion</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.address" id="address" autocomplete="address-level1"
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
                            <InputLabel for="phone1" class="font-medium leading-6 text-gray-900">Telefono 1</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.phone1" id="phone1" maxlength="9"
                                    autocomplete="address-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.phone1" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="phone2" class="font-medium leading-6 text-gray-900">Telefono 2</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.phone2" id="phone2" maxlength="9"
                                    autocomplete="address-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.phone2" />
                            </div>
                        </div>
                        <div class="sm:col-span-2 sm:col-start-1">
                            <div class="flex items-center gap-2">
                                <InputLabel for="category" class="font-medium leading-6 text-gray-900">Categoria
                                </InputLabel>
                                <button type="button" @click="category">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-2">
                                <select v-model="form.category" id="category"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                                    <option disabled value="">Seleccione</option>
                                    <option v-for="item in props.category" :key="item.id" :value="item.name">{{
        item.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.category" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <div class="flex items-center gap-2">
                                <InputLabel for="segment" class="font-medium leading-6 text-gray-900">Segmento
                                </InputLabel>
                                <button type="button" @click="segment">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                    </svg>
                                </button>
                            </div>

                            <div class="mt-2">
                                <select v-model="form.segment" id="segment"
                                    class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                                    <option disabled value="">Seleccione</option>
                                    <option v-for="item in props.segment" :key="item.id" :value="item.name">{{ item.name
                                        }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.segment" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    {{ props.providers ? "Actualizar" : "Crear" }}
                </button>
            </div>
        </form>
        <Modal :show="showModalAdd">
            <form class="p-6" @submit.prevent="submitName">
                <h2 class="text-lg font-medium text-gray-900">
                    Nueva {{ categoryAndSegment == false ? 'categoria' : 'segmento' }}
                </h2>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">

                    <div class="sm:col-span-6">
                        <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                        <div class="mt-2">
                            <TextInput id="name" :to-uppercase="true" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                v-model="formname.name" />
                            <InputError :message="formname.errors.name" />
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
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
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

const props = defineProps({
    providers: {
        type: Object,
        required: false
    },
    category: {
        type: Object,
        required: true
    },
    segment: {
        type: Object,
        required: true
    }
});


const showModalAdd = ref(false);
const showModal = ref(false);
const categoryAndSegment = ref(false);
const addSuccess = ref(false)

const form = useForm({
    company_name: '',
    contact_name: '',
    address: '',
    phone1: '',
    phone2: '',
    email: '',
    category: '',
    segment: '',
    zone: '',
    ruc: '',
});

const formname = useForm({
    name: ''
})

if (props.providers) {
    form.company_name = props.providers.company_name,
        form.contact_name = props.providers.contact_name,
        form.address = props.providers.address,
        form.phone1 = props.providers.phone1,
        form.phone2 = props.providers.phone2,
        form.email = props.providers.email,
        form.category = props.providers.category,
        form.segment = props.providers.segment,
        form.zone = props.providers.zone,
        form.ruc = props.providers.ruc
}

const submit = () => {
    if (props.providers) {
        form.put(route('providersmanagement.update', props.providers.id), form)
    } else {
        form.post(route('providersmanagement.store'), {
            onSuccess: () => {
                showModal.value = true;
                setTimeout(() => {
                    showModal.value = false;
                    router.visit(route('providersmanagement.index'))
                }, 2000);
            },
        })
    }
};

const submitName = () => {
    const url = categoryAndSegment.value == false ? route('provider.category') : route('provider.segment')
    axios.post(url, { ...formname.data() })
        .then(response => {
            if (response.status === 200) {
                let newItem = response.data.new
                categoryAndSegment.value == false
                    ? props.category.push({ ...newItem })
                    : props.segment.push({ ...newItem })
                closeAddModal()
                addSuccess.value = true
                setTimeout(() => {
                    addSuccess.value = false
                }, 600)
                formname.reset()
            } else {
                throw new Error('Fallo en el servidor con status ' + response.status)
            }
        })
        .catch(error => {
            console.error(error)
        })
};

const category = () => {
    showModalAdd.value = true
}

const segment = () => {
    showModalAdd.value = true
    categoryAndSegment.value = true
}

const closeAddModal = () => {
    showModalAdd.value = false
}
</script>