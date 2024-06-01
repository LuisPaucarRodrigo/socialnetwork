<template>

    <Head title="Activos" />
    <AuthenticatedLayout :redirectRoute="'warehouses.resource.active.index'">
        <template #header>
            Registrar Activo
        </template>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <InputLabel for="purchase_resource_id">Nombre</InputLabel>
                            <!-- <select v-model="form.purchase_product_id" id="purchase_resource_id"
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300">
                                <option disabled value="">Seleccione Activo</option>
                                <option v-for="item in products" :key="item.id" :value="item.id">
                                    {{ item.name }}
                                </option>
                            </select> -->

                            <TextInput 
                                    @input="(e) => 
                                        handleAutocomplete(e.target.value)"  
                                    list="options"
                                    type="text" 
                                    id="purchase_product_id" 
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <datalist id="options">
                                    <option v-for="item in products" :value="item.name">
                                        {{ item.name }}
                                    </option>
                                </datalist>

                            <InputError :message="form.errors.purchase_product_id" />
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="entry_date">
                                Fecha de Ingreso
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.entry_date" id="entry_date" :to-uppercase="true"
                                    autocomplete="family-name" />
                                <InputError :message="form.errors.entry_date" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="entry_price">Precio de Entrada</InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" v-model="form.entry_price" id="entry_price"
                                    :to-uppercase="true" autocomplete="family-name" />
                                <InputError :message="form.errors.entry_price" />
                            </div>
                        </div>

                        <div class="sm:col-span-3 sm:col-start-1">
                            <InputLabel for="serial_number">Numero de Serie
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" v-model="form.serial_number" id="serial_number" pattern="\d*"
                                    autocomplete="given-name" maxlength="11" />
                                <InputError :message="form.errors.serial_number" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="referral_guide">Numero de Guia
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" v-model="form.referral_guide" id="referral_guide"
                                    :to-uppercase="true" autocomplete="given-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.referral_guide" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="description">Descripcion</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.description" id="accidents"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.description" />
                            </div>
                            <InputError :message="form.errors.description" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                    Crear
                </PrimaryButton>
            </div>
        </form>
        <SuccessOperationModal :confirming="addSuccess" title="Activo" message="Activo creado Exitosamente" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue'
import { Head, router, useForm } from '@inertiajs/vue3';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import { ref } from 'vue';

const props = defineProps({
    products: {
        type: Object,
        required: false
    },
});

const addSuccess = ref(false);

const form = useForm({
    condition: '',
    entry_date: '',
    serial_number: '',
    referral_guide: '',
    entry_price: '',
    purchase_product_id: '',
    condition: 'Disponible',
    description: '',
    state: 1
});

function submit() {
    form.post(route('warehouses.resource.store'), {
        onSuccess: () => {
            addSuccess.value = true
            setTimeout(() => {
                addSuccess.value = false
                router.get(route('warehouses.resource.active.index'))
            }, 2000)
        }
    })
};

const handleAutocomplete = (e) => {
    let findedProduct = props.products.find(item => item.name === e)
    if (findedProduct) {
        form.purchase_product_id = findedProduct.id
    }
}
</script>