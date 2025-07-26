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
                        :error-message="form.errors.company_name" col-span="sm:col-span-2" to-uppercase />

                    <FormInput id="contact_name" label="Nombre de Contacto" v-model="form.contact_name"
                        :error-message="form.errors.contact_name" col-span="sm:col-span-2" to-uppercase />

                    <FormInput id="account_number" label="Numero de Cuenta" v-model="form.account_number"
                        :error-message="form.errors.account_number" col-span="sm:col-span-3" to-uppercase />

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
                        <InputLabel>Categoria</InputLabel>
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
                        <InputLabel>Segmentos</InputLabel>
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
                    <SecondaryButton type="button" @click="closeModal"> Cerrar </SecondaryButton>
                    <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        {{ form.id ? "Actualizar" : "Crear" }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>

<script setup>
import Modal from '@/Components/Modal.vue';
import { notify } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref, watch } from 'vue';
import FormInput from '@/Layouts/FormInput.vue';
import { useAxiosErrorHandler } from '@/utils/axiosError';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';

const { category } = defineProps({
    category: Object
})

const providers = defineModel('providers')

const segmentsList = ref([]);
const showModalStoreOrUpdate = ref(false)

const initialStateForm = {
    company_name: '',
    contact_name: '',
    address: '',
    phone1: '',
    phone2: '',
    email: '',
    category_id: '',
    segments: [],
    account_number: '',
    zone: '',
    ruc: '',
    id: '',
}

const form = useForm({});

watch(() => form.category_id, (newValue) => {
    handleCategoryChange(form.category_id)
});

function toogleModal() {
    showModalStoreOrUpdate.value = !showModalStoreOrUpdate.value
}

const createProviderForm = () => {
    form.defaults({ ...initialStateForm })
    form.reset()
    toogleModal()
};

const editProviderForm = (item) => {
    form.defaults({ ...item, segments: item.segments.map(item => item.id) })
    form.reset()
    toogleModal()
};

function closeModal() {
    form.clearErrors()
    form.defaults({ ...initialStateForm })
    form.reset()
    toogleModal()
}

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
        closeModal();
        updateProvider(response.data, operation)
    } catch (error) {
        useAxiosErrorHandler(error, form)
    }
};

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

defineExpose({ createProviderForm, editProviderForm })
</script>