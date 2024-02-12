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
                            <InputLabel for="title" class="font-medium leading-6 text-gray-900">Titulo de Solicitud
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.title" id="title" autocomplete="family-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.title" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="due_date" class="font-medium leading-6 text-gray-900">Fecha Limite</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.due_date" id="due_date" maxlength="9"
                                    autocomplete="product_description-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.due_date" />
                            </div>
                        </div>
                        <div class="sm:col-span-4">
                            <InputLabel for="product_description" class="font-medium leading-6 text-gray-900">Descripcion de
                                productos</InputLabel>
                            <div class="mt-2">
                                <textarea type="text" v-model="form.product_description" id="product_description"
                                    autocomplete="product_description-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.product_description" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-between gap-x-6">
                <a :href="route('projectmanagement.purchases_request.index', { id:project_id })"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Atras
                </a>
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="solicitud de compra" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue'
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';

const showModal = ref(false)

const initialState = {
    title: '',
    product_description: '',
    due_date: '',
    project_id: project_id
}

const form = useForm(purchase_request ? purchase_request : { ...initialState })

const { project_id, purchase_request } = defineProps({
    project_id: String,
    purchase_request: Object
})

const submit = () => {
    form.post(route('projectmanagement.purchases_request.store', { project_id }), {
        onSuccess: () => {
            showModal.value = true
            setTimeout(() => {
                showModal.value = false
                router.visit(route('projectmanagement.purchases_request.index', { project_id: project_id }))
            }, 2000)
        }
    })
}
</script>