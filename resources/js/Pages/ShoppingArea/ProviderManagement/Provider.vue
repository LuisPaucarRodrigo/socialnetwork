<template>

    <Head title="Proveedores" />
    <AuthenticatedLayout :redirectRoute="'providersmanagement.index'">
        <template #header>
            Proveedores
        </template>

        <div class="min-w-full overflow-hidden rounded-lg shadow">
            <div class="flex justify-between items-center gap-4">
                <button v-if="hasPermission('PurchasingManager')" @click="add_information" type="button"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                    + Agregar
                </button>
                <div class="flex items-center">
                    <form @submit.prevent="search" class="flex items-center">

                        <input type="text" placeholder="Buscar..."
                            class="block w-full ml-2 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            v-model="searchForm.searchTerm" />
                        <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                            class="ml-2 rounded-md bg-indigo-600 px-2 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                RUC
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Compañia
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Contacto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                zona
                            </th>
                            <th
                                class="w-64 border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Direccion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Telefono
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Email
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Categoria
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Segmento
                            </th>
                            <th v-if="auth.user.role_id == 1"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="provider in (props.search ? providers : providers.data)" :key="provider.id"
                            class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-no-wrap text-xs">{{ provider.ruc }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-no-wrap text-xs">{{ provider.company_name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-no-wrap text-xs">{{ provider.contact_name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-no-wrap text-xs">{{ provider.zone }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-no-wrap text-xs w-64">{{ provider.address }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-no-wrap text-xs">{{ provider.phone1 }} {{
                                    provider.phone2 }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-no-wrap text-xs">{{ provider.email }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-no-wrap text-xs">{{ provider.category }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-3 py-2">
                                <p class="text-gray-900 whitespace-no-wrap text-xs">{{ provider.segment }}</p>
                            </td>
                            <td v-if="auth.user.role_id == 1" class="border-b border-gray-200 bg-white px-3 py-2">
                                <div class="flex space-x-3 justify-center">
                                    <!-- <Link class="text-blue-900 whitespace-no-wrap"
                                        :href="route('providersmanagement.edit', { id: provider.id })">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    </Link> -->
                                    <button type="button" @click="add_information(provider)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-amber-400">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                    </svg>
                                    </button>
                                    <button type="button" @click="confirmProviderDeletion(provider)"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-red-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="props.search === undefined"
                class="flex flex-col items-center border-t bg-white px-3 py-2 xs:flex-row xs:justify-between">
                <pagination :links="providers.links" />
            </div>
        </div>
        <ProviderCreateAndUpdate :showModalStoreOrUpdate="showModalStoreOrUpdate" :provider="form" :categories="category"  @submit="submitCategoryOrSegment"
        @close="closeModalStoreOrUpdate"/>
        <ConfirmDeleteModal :confirmingDeletion="confirmingProviderDeletion" itemType="proveedor" :nameText="name"
            :deleteFunction="deleteProvider" @closeModal="closeModal" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import { Head, router, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import ProviderCreateAndUpdate from './ProviderCreateAndUpdate.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    providers: Object,
    auth: Object,
    search: String,
    userPermissions: Array,
    category:Object
});

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const confirmingProviderDeletion = ref(false);
const provider = ref(null);
const name = ref(null);
const showModalStoreOrUpdate = ref(false)

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
    id: null,
});

const confirmProviderDeletion = (provider_fun) => {
    provider.value = provider_fun;
    name.value = provider_fun.contact_name;
    confirmingProviderDeletion.value = true;
};

const deleteProvider = () => {
    const providerId = provider.value.id;
    if (providerId) {
        router.delete(route('providersmanagement.destroy', { id: providerId }), {
            onSuccess: () => closeModal()
        });
    }
};

const closeModal = () => {
    confirmingProviderDeletion.value = false;
};

const add_information = (item) => {
    showModalStoreOrUpdate.value = !showModalStoreOrUpdate.value
    Object.assign(form,item)
};

const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
})

const search = () => {
    if (searchForm.searchTerm == '') {
        router.visit(route('providersmanagement.index'));
    } else {
        router.visit(route('providersmanagement.search', { request: searchForm.searchTerm }));
    }

}

const submit = () => {
    let url = showModalStoreOrUpdate.value
    if (provider) {
        form.id = provider.id
        form.put(route('providersmanagement.update', provider.id), form)
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

function closeModalStoreOrUpdate() {
    showModalStoreOrUpdate.value = false
}

</script>
