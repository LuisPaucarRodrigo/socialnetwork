<template>

    <Head title="Anteproyectos" />
    <AuthenticatedLayout :redirectRoute="'preprojects.index'">
        <template v-if="preproject" #header>
            Edición de Anteproyecto
        </template>
        <template v-else #header>
            Creación de Anteproyecto
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <form @submit.prevent="submit">
                <div class="pt-1">
                    <div class="border-b border-gray-900/10 mb-2">
                    </div>

                    <div class="border-b border-gray-900 pb-12">
                        <h2 v-if="preproject" class="text-base font-semibold leading-7 text-gray-900">
                            {{ preproject.code }}
                        </h2>
                        <h2 v-else class="text-base font-semibold leading-7 text-gray-900">Registrar nuevo Anteproyecto
                        </h2>
                        <br>
                        <div
                            class="border-b grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-4  border-gray-900/10 pb-12">

                            <div>
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    Cliente
                                </InputLabel>
                                <div class="mt-2">
                                    <input id="unit" list="options" @input="(e) => handleAutocomplete(e, 'customer_id')"
                                        autocomplete="off" v-model="customerRuc"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />

                                    <datalist id="options">
                                        <option v-for="item in customers" :value="item.ruc" :data-value="item">
                                            {{ item.business_name }}
                                        </option>
                                    </datalist>
                                    <InputError :message="form.errors.customer_id" />
                                </div>
                            </div>
                            <div>
                                <div class="flex gap-3 items-center">
                                    <InputLabel for="customer" class="font-medium leading-6 text-gray-900">¿Cliente
                                        final?
                                    </InputLabel>

                                    <div class="flex gap-4 text-sm">
                                        <label class="flex gap-2 items-center">
                                            Sí
                                            <input type="radio" v-model="form.hasSubcustomer" @input="handleSubClient"
                                                :value="true"
                                                class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                        </label>
                                        <label class="flex gap-2 items-center">
                                            No
                                            <input type="radio" v-model="form.hasSubcustomer" @input="handleSubClient"
                                                :value="false"
                                                class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                        </label>
                                    </div>

                                </div>
                                <div v-if="form.hasSubcustomer" class="mt-2">
                                    <input id="unit" list="options"
                                        @input="(e) => handleAutocomplete(e, 'subcustomer_id')" autocomplete="off"
                                        v-model="subCustomerRuc"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />

                                    <datalist id="options">
                                        <option v-for="item in customers" :value="item.ruc" :data-value="item">
                                            {{ item.business_name }}
                                        </option>
                                    </datalist>
                                    <InputError :message="form.errors.subcustomer_id" />
                                </div>
                            </div>

                            <div class="col-span-1 sm:col-span-2">
                                <p class="border-b-2 border-gray-300 text-sm text-indigo-600">
                                    Datos del <b> cliente {{ form.hasSubcustomer ? 'final' : '' }}</b>
                                </p>
                            </div>
                            <div>
                                <InputLabel class="font-medium leading-6 text-gray-900">Nombre:
                                </InputLabel>
                                <InputLabel class="leading-6 text-gray-900">
                                    {{ customers.find(item =>
        item.id == (form.hasSubcustomer
            ? form.subcustomer_id
            : form.customer_id)
    )?.business_name }}
                                </InputLabel>
                                <InputLabel class="font-medium leading-6 mt-2 text-gray-900">Dirección:
                                </InputLabel>
                                <InputLabel class="leading-6 text-gray-900">{{ customers.find(item =>
        item.id == (form.hasSubcustomer
            ? form.subcustomer_id
            : form.customer_id)
    )?.address }}
                                </InputLabel>

                            </div>
                            <div>
                                <div class="flex gap-2 items-end">
                                    <InputLabel for="description" class="font-medium leading-6 text-gray-900">Contactos
                                    </InputLabel>
                                    <button @click="openContactModal" type="button">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>


                                <div v-for="(item, i) in contactsList" :key="i" class="">
                                    <div v-if="form.contacts.includes(item.id)"
                                        class="border-b col-span-8 border-gray-900/10 grid grid-cols-8 items-center my-2">
                                        <p class=" text-sm col-span-7 line-clamp-2">
                                            {{ item.name }}: {{ item.phone }} </p>
                                        <!-- @click="delete_already_employee(member.pivot.id, index)" -->
                                        <button type="button" class="col-span-1 flex justify-end"
                                            @click="deleteContactItem(item.id)">
                                            <TrashIcon class=" text-red-500 h-4 w-4" />
                                        </button>
                                    </div>
                                </div>
                                <InputError :message="form.errors.contacts" />
                            </div>

                            <div class="col-span-1 border-t-2 border-gray-300 sm:col-span-2">
                            </div>

                            <div>
                                <InputLabel for="description" class="font-medium leading-6 text-gray-900">Descripción
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.description" id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.description" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="date" class="font-medium leading-6 text-gray-900">Fecha de Visita
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.date" id="date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.date" />
                                </div>
                            </div>
                            <div>
                                <InputLabel for="date" class="font-medium leading-6 text-gray-900">Código de proyecto
                                </InputLabel>

                                <div class="mt-2 flex justify-center items-center gap-2">
                                    <input type="text" v-model="form.code" id="name" pattern="[a-zA-Z]{5}-[a-zA-Z]{5}"
                                        maxlength="11"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 uppercase" />
                                </div>
                                <p class="text-gray-400">Ejemplo: CCCCC-PPPPP </p>
                                <p class="text-gray-400">CCCCC -> 5 iniciales cliente | PPPPP -> 5 iniciales proyecto
                                </p>
                                <InputError :message="form.errors.code" />
                            </div>

                            <div>
                                <InputLabel for="observation" class="font-medium leading-6 text-gray-900">Observaciones
                                </InputLabel>
                                <div class="mt-2">
                                    <textarea type="text" v-model="form.observation" id="observation"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.observation" />
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="mt-3 flex items-center justify-between gap-x-6">
                    <button type="submit" :class="{ 'opacity-25': form.processing }"
                        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                </div>
            </form>
            <Modal :show="showContactModal">
                <div class="p-6">
                    <h2 class="text-base font-medium leading-7 text-gray-900">
                        Contactos del cliente {{ form.subcustomer_id && 'final' }}
                    </h2>
                    <p class="text-sm text-indigo-500">Primero seleccinar un cliente o cliente final</p>
                    <form @submit.prevent="submitContact">
                        <div class="mt-2">
                            <select v-model="contactItem" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">

                                <option disabled value="">Seleccione</option>
                                <option v-for="item in contactsList" :value="item.id">
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeContactModal">
                                Cerrar </SecondaryButton>
                            <button type="submit" :class="{ 'opacity-25': form.processing }"
                                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Agregar</button>

                        </div>

                    </form>


                </div>
            </Modal>
            <ErrorOperationModal :showError="showErrorContact" title="Error"
                message="El contacto ya fue añadido o es inválido" />


        </div>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Anteproyecto" />
        <ConfirmUpdateModal :confirmingupdate="showModalUpdate" itemType="Anteproyecto" />


    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/24/outline';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';

const showModal = ref(false)
const showModalUpdate = ref(false)
const showErrorContact = ref(false)

const { preproject, customers } = defineProps({
    preproject: Object,
    customers: Object
})

const initial_state = {
    customer_id: '',
    subcustomer_id: '',
    code: '',
    description: '',
    date: '',
    observation: '',
    contacts: [],
    hasSubcustomer: false
}

const update_state = {
    ...preproject,
    code: preproject?.code?.substring(9),
    contacts: preproject?.contacts?.map(item => item.id),
    hasSubcustomer: preproject?.subcustomer_id ? true : false
}


const form = useForm({
    ...(preproject ? update_state : initial_state)
});


const contactsList = ref([])
const contactItem = ref("")
const helperContactList = (customer_id, subcustomer_id, hasSC) => {
    const matchCustomer = customers.find(item => item.id == customer_id)
    const matchSubCustomer = customers.find(item => item.id == subcustomer_id)
    if (matchSubCustomer) {
        contactsList.value = matchSubCustomer.customer_contacts
    } else if (matchCustomer && !hasSC) {
        contactsList.value = matchCustomer.customer_contacts
    } else {
        contactsList.value = []
    }
}

const customerRuc = ref('')
const subCustomerRuc = ref('')
if (preproject) {
    helperContactList(form.customer_id, form.subcustomer_id, form.hasSubcustomer)
    if (form.customer_id) {
        customerRuc.value = customers?.find(item => item.id == form.customer_id)?.ruc
    }
    if (form.subcustomer_id) {
        subCustomerRuc.value = customers?.find(item => item.id == form.subcustomer_id)?.ruc
    }
}


const submit = () => {
    let url = preproject ? route('preprojects.update', { preproject: preproject.id })
        : route('preprojects.store')
    form.post(url, {
        onSuccess: () => {
            if (preproject) {
                showModalUpdate.value = true
            } else {
                showModal.value = true
            }
            setTimeout(() => {
                if (preproject) {
                    showModalUpdate.value = false
                } else {
                    showModal.value = false
                }
                router.visit(route('preprojects.index'))
            }, 2000);
        },
        onError: (e) => {
            console.log(e);
        }
    })
}


const handleAutocomplete = (e, model) => {
    const ruc = e.target.value;
    form.contacts = []
    let matchedClient = customers.find(item => item.ruc == ruc)
    if (matchedClient) {
        form[model] = matchedClient.id
    } else {
        form[model] = ''
    }
    helperContactList(form.customer_id, form.subcustomer_id, form.hasSubcustomer)
}





const showContactModal = ref(false)
function openContactModal() {
    showContactModal.value = true
}
function closeContactModal() {
    showContactModal.value = false
}


function submitContact() {
    if (form.contacts.includes(contactItem.value)) {
        showErrorContact.value = true
        setTimeout(() => {
            showErrorContact.value = false
        }, 1500)
    } else {
        form.contacts.push(contactItem.value)
    }
    contactItem.value = ''
}

function deleteContactItem(id) {
    const index = form.contacts.indexOf(id);
    form.contacts.splice(index, 1)
}


const handleSubClient = (e) => {
    form.subcustomer_id = ''
    subCustomerRuc.value = ''
    contactItem.value = ''
    form.contacts = []
    helperContactList(form.customer_id, form.subcustomer_id, JSON.parse(e.target.value))
}

</script>