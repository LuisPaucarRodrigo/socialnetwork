<template>
    <Head title="Visita de clientes" />

    <AuthenticatedLayout>
        <template #header>
            Visita de Cliente
        </template>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informacion</h2>

                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <InputLabel for="customer" value="customer" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <TextInput id="customer" type="text"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.customer" required autofocus autocomplete="customer" />
                                <InputError class="mt-2" :message="form.errors.customer" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="phone" value="phone" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <TextInput id="phone" type="number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.phone" required autocomplete="phone" />
                                <InputError class="mt-2" :message="form.errors.phone" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="description" value="description" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <TextInput id="description" type="text" maxlength="8"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.description" required autocomplete="description" />
                                <InputError class="mt-2" :message="form.errors.description" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="address" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <TextInput id="address" type="text" maxlength="8"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.address" required autocomplete="address" />
                                <InputError class="mt-2" :message="form.errors.address" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="date" class="font-medium leading-6 text-gray-900">Roles</InputLabel>
                            <div class="mt-2">
                                <TextInput id="date" type="date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.date" required autocomplete="date" />
                                <InputError class="mt-2" :message="form.errors.date" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="observation" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <TextInput id="observation" type="text"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.observation" required autocomplete="observation" />
                                <InputError class="mt-2" :message="form.errors.observation" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="facade" class="font-medium leading-6 text-gray-900" />
                            <div class="mt-2">
                                <InputFile id="facade" type="file"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.facade" required autocomplete="facade" />
                                <InputError class="mt-2" :message="form.errors.facade" />
                            </div>
                        </div>


                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-between">
                <a :href="route('users.index')"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Atras
                </a>
                <button
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    {{ customer ? 'Actualizar' : 'Crear' }}
                </button>
            </div>
        </form>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="visita de cliente" />
    </AuthenticatedLayout>
</template>

<script setup>
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/InputFile.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    customer: {
        type: Object,
        required: false
    }
})

const showModal = ref(false);

const form = useForm({
    customer: '',
    phone: '',
    description: '',
    address: '',
    date: '',
    observation: '',
    facade: '',
});

if (props.customer) {
    form.customer = props.customer.customer;
    form.phone = props.customer.phone;
    form.description = props.customer.description;
    form.address = props.customer.address;
    form.date = props.customer.date;
    form.observation = props.customer.observation;
    form.facade = props.customer.facade;
}

const submit = () => {
    if (props.customer) {
        form.put(route('customervisitmanagement.update', props.customer.id), form);
    } else {
        form.post(route('customervisitmanagement.store'), {
            onSuccess: () => {
                showModal.value = true;
                setTimeout(() => {
                    showModal.value = false;
                    router.visit(route('customervisitmanagement.index'));
                }, 2000);
            },
            onError: () => {
                alert('Ha ocurrido un error. Por favor, inténtelo de nuevo.');
            },
        });
    }
};
</script>
