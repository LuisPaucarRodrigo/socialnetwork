<template>
    <Head title="Agregar Solicitud" />
    <AuthenticatedLayout>
        <template #header>
            Nueva solicitud de proyecto
        </template>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">

                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">

                        <div class="sm:col-span-3">
                            <InputLabel for="title" class="font-medium leading-6 text-gray-900">Titulo de Solicitud</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.title" id="title" autocomplete="family-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.title" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="due_date" class="font-medium leading-6 text-gray-900">Fecha Limite</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.due_date" id="due_date" maxlength="9" autocomplete="product_description-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.due_date" />
                            </div>
                        </div>

                        <div class="sm:col-span-4">
                            <InputLabel for="product_description" class="font-medium leading-6 text-gray-900">Descripcion de productos</InputLabel>
                            <div class="mt-2">
                                <textarea type="text" v-model="form.product_description" id="product_description" autocomplete="product_description-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.product_description" />
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
    title: '',
    product_description: '',
    due_date: '',
})

const submit = () => {
    form.post(route('purchasesrequest.store'))
}
</script>