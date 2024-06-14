<template>

    <Head title="Anteproyectos" />
    <AuthenticatedLayout :redirectRoute="'preprojects.index'">
        <template #header>
            Proyecto y Anteproyecto Claro CICSA
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <form @submit.prevent="submit">
                <div class="pt-1">
                    <div class="border-b border-gray-900/10 mb-2">
                    </div>

                    <div class="border-b border-gray-900 pb-12">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">
                            Registro de Anteproyecto-Proyecto CLARO CICSA
                        </h2>
                        <br>
                        <div class="border-b grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-4  border-gray-900/10 pb-12">

                            <div>
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    Plantilla de Proyecto
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.template"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Selecciona una plantilla</option>
                                        <option v-for="item, i in templates" :key="i">
                                            {{ item }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.template" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    Fecha de Inicio
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.date" id="date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.date" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    CPE
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="number" v-model="form.cpe" id="cpe"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.cpe" />
                                </div>
                            </div>



                           
                            

                            <div>
                                <InputLabel for="title" class="font-medium leading-6 text-gray-900">Títulos</InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.title_id" id="title_id"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Selecciona un título</option>
                                        <option v-for="title in titles" :key="title.id" :value="title.id">{{ title.title
                                            }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.title_id" />
                                </div>
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
                <div class="mt-3 flex items-center justify-end gap-x-6">
                    <button type="submit" :class="{ 'opacity-25': form.processing }"
                        class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
                </div>
            </form>
            <Modal :show="showContactModal">
                <div class="p-6">
                    <h2 class="text-base font-medium leading-7 text-gray-900">
                        Agregando Productos de Almacen
                    </h2>
                    <p class="text-sm text-indigo-500">Primero seleccinar un cliente o cliente final</p>
                    <form @submit.prevent="submitContact">
                        <div class="mt-2">
                            <select v-model="contactItem" required
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled value="">Seleccione</option>
                                <option v-for=" item  in contactsList" :value="item.id">
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-3">
                            <SecondaryButton @click="closeContactModal">Cerrar</SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">Agregar
                            </PrimaryButton>
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
import { ref, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { TrashIcon } from '@heroicons/vue/24/outline';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const showModal = ref(false)
const showModalUpdate = ref(false)
const showErrorContact = ref(false)

const { preproject, customers, titles } = defineProps({
    preproject: Object,
    customers: Object,
    titles: Object
})

const templates = [
    'Mantenimiento',
    'Instalación'
]

const initial_state = {
    template: '',
    date: '',
    cpe: '',


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

const customerBusinnes = ref('')

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
    form.cpe = '';
    const ruc = e.target.value;
    let matchedClient = customers.find(item => item.ruc == ruc)
    if (matchedClient) {
        customerBusinnes.value = matchedClient.business_name
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
            closeContactModal()
        }, 1500)
    } else {
        form.contacts.push(contactItem.value)
        closeContactModal()
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

const updateProjectCode = () => {
    const customerName = customerBusinnes.value.replace(' ', '').substring(0, 5);
    const description = form.description.replace(' ', '').substring(0, 5);

    form.code = `${customerName}-${description}`;
};

watch(() => [customerBusinnes.value, form.description], updateProjectCode);

</script>