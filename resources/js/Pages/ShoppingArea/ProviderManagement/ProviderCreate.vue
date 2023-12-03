<template>
    <Head title="Crear Proveedor" />
    <AuthenticatedLayout>
        <template #header>
            Proveedor
        </template>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informacion</h2>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <InputLabel for="company_name" class="font-medium leading-6 text-gray-900">Compañia</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.company_name" id="company_name" autocomplete="given-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.company_name" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="contact_name" class="font-medium leading-6 text-gray-900">Nombre de Contacto</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.contact_name" id="contact_name" autocomplete="family-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.contact_name" />
                            </div>
                        </div>

                        <div class="sm:col-span-4">
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
                                <TextInput type="text" v-model="form.phone1" id="phone1" maxlength="9" autocomplete="address-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.phone1" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="phone2" class="font-medium leading-6 text-gray-900">Telefono 2</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.phone2" id="phone2" maxlength="9" autocomplete="address-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.phone2" />
                            </div>
                        </div>



                        <div class="sm:col-span-3">
                            <InputLabel for="category" class="font-medium leading-6 text-gray-900">Categoria</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" id="category" v-model="form.category" autocomplete="category"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.category" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="segment" class="font-medium leading-6 text-gray-900">Segmento</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.segment" id="segment" autocomplete="postal-code"
                                    maxlength="9"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.segment" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Save</button>
            </div>
        </form>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue'
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    company_name: '',
    contact_name: '',
    address: '',
    phone1: '',
    phone2: '',
    email: '',
    category: '',
    segment: '',
})

const submit = () => {
    form.post(route('providersmanagement.information.store'))
}
</script>