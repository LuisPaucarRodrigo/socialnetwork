<template>

    <Head title="Clientes" />

    <AuthenticatedLayout :redirectRoute="'customers.index'">
        <template #header>
            Clientes
        </template>
        <div class="mt-6 flex items-center justify-between gap-x-6">
            <button v-if="hasPermission('ProjectManager')" @click="add_customer" type="button"
                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                + Agregar
            </button>
            <input type="text" @input="search($event.target.value)" placeholder="Buscar...">
        </div>

        <div class="overflow-x-auto rounded-lg shadow mt-2">
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr
                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Ruc
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Razón Social
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Categoría
                        </th>
                        <th
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            Dirección
                        </th>
                        <th v-if="hasPermission('ProjectManager')"
                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="customer in customers.data" :key="customer.id" class="text-gray-700">
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ customer.ruc }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ customer.business_name }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ customer.category }}</p>
                        </td>
                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <p class="text-gray-900 whitespace-no-wrap">{{ customer.address }}</p>
                        </td>
                        <td v-if="hasPermission('ProjectManager')"
                            class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                            <div class="flex justify-center space-x-3">
                                <button type="button" @click="openEditCustomerModal(customer)"
                                    class="text-yellow-600 whitespace-no-wrap">
                                    <PencilIcon class="h-5 w-5 ml-1" />
                                </button>
                                <button type="button" @click="add_contact(customer.id)"
                                    class="text-blue-600 whitespace-no-wrap">
                                    <DocumentArrowUpIcon class="h-5 w-5 ml-1" />
                                </button>
                                <button type="button" @click="confirmDeleteCustomer(customer.id)"
                                    class="text-red-600 whitespace-no-wrap">
                                    <TrashIcon class="h-5 w-5 ml-1" />
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="props.customers.links" />
        </div>
        <Modal :show="create_customer || edit_customer">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ create_customer ? 'Agregar cliente' : 'Actualizar cliente' }}
                </h2>
                <form @submit.prevent="create_customer ? submit() : submitEdit()">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">

                            <div>
                                <InputLabel for="ruc" class="font-medium leading-6 text-gray-900 mt-3">RUC</InputLabel>
                                <div class="mt-2">
                                    <input v-model="form.ruc" id="ruc" maxlength="11" minlength="11" pattern="(\d)*"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.ruc" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="business_name" class="font-medium leading-6 text-gray-900 mt-3">Razón
                                    Social
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.business_name" id="business_name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.business_name" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="address" class="font-medium leading-6 text-gray-900 mt-3">Dirección
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.address" id="address"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.address" />
                                </div>
                            </div>
                            <br>
                            <template v-if="create_customer">
                                <div class="ring-1 p-3 text-sm ring-gray-300 rounded-md">
                                    <b>Contacto:</b>
                                    <div class="grid sm:grid-cols-2 gap-4">
                                        <div>
                                            <InputLabel class="font-medium leading-6 text-gray-900 mt-3">
                                                Nombre
                                            </InputLabel>
                                            <div class="mt-2">
                                                <input type="text" v-model="form.contact.name"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                <InputError :message="form.errors['contact.name']" />
                                            </div>
                                        </div>
                                        <div>
                                            <InputLabel class="font-medium leading-6 text-gray-900 mt-3">
                                                Teléfono
                                            </InputLabel>
                                            <div class="mt-2">
                                                <input type="text" v-model="form.contact.phone"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                <InputError :message="form.errors['contact.phone']" />
                                            </div>
                                        </div>
                                        <div>
                                            <InputLabel class="font-medium leading-6 text-gray-900 mt-3">
                                                Información adicional
                                            </InputLabel>
                                            <div class="mt-2">
                                                <input type="text" v-model="form.contact.additional_information"
                                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                                <InputError :message="form.errors['contact.additional_information']" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <SecondaryButton
                                    @click="create_customer ? close_add_customer() : close_edit_customer()">
                                    Cancelar </SecondaryButton>
                                <button type="submit" :class="{ 'opacity-25': form.processing }"
                                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">{{
        create_customer ? 'Guardar' : 'Actualizar' }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Cliente" />
        <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Cliente" />
        <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Cliente"
            :deleteFunction="deleteCustomer" @closeModal="closeModalDoc" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { TrashIcon, PencilIcon, DocumentArrowUpIcon } from '@heroicons/vue/24/outline';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

const create_customer = ref(false);
const showModal = ref(false);
const showModalEdit = ref(false);
const editingCustomer = ref(null);
const edit_customer = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);

const props = defineProps({
    customers: Object,
    userPermissions: Array
})

const hasPermission = (permission) => {
    return props.userPermissions.includes(permission);
}

const customers = ref(props.customers);

const add_customer = () => {
    create_customer.value = true;
}

const close_add_customer = () => {
    form.reset();
    create_customer.value = false;
}

const close_edit_customer = () => {
    form.reset();
    edit_customer.value = false;
}

const form = useForm({
    id: '',
    ruc: '',
    business_name: '',
    address: '',
    contact: {
        name: '',
        phone: '',
        additional_information: '',
    }
});

const submit = () => {
    form.post(route('customers.store'), {
        onSuccess: () => {
            close_add_customer();
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('customers.index'))
            }, 2000);
        },
    });
};

const submitEdit = () => {
    form.put(route('customers.update', { customer: form.id }), {
        onSuccess: () => {
            close_edit_customer();
            form.reset();
            showModalEdit.value = true
            setTimeout(() => {
                showModalEdit.value = false;
                router.visit(route('customers.index'))
            }, 2000);
        },
        onError: () => {
            form.reset();
        },
        onFinish: () => {
            form.reset();
        }
    });
};

const openEditCustomerModal = (customer) => {
    editingCustomer.value = JSON.parse(JSON.stringify(customer));
    form.id = editingCustomer.value.id;
    form.ruc = editingCustomer.value.ruc;
    form.business_name = editingCustomer.value.business_name;
    form.address = editingCustomer.value.address;

    edit_customer.value = true;
};

const confirmDeleteCustomer = (customerId) => {
    docToDelete.value = customerId;
    confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};

const deleteCustomer = () => {
    const docId = docToDelete.value;
    if (docId) {
        router.delete(route('customers.destroy', { customer: docId }), {
            onSuccess: () => closeModalDoc()
        });
    }
};

const add_contact = (id) => {
    router.visit(route('customers.contacts.index', { customer: id }));
}

const search = async ($search) => {
    try {
        const response = await axios.post(route('customers.index'), { searchQuery: $search });
        customers.value = response.data.customers;
    } catch (error) {
        console.error('Error searching:', error);
    }
};
</script>
