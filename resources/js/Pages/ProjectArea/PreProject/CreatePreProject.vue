<template>

    <Head title="Anteproyectos" />
    <AuthenticatedLayout :redirectRoute="backUrls">
        <template v-if="preproject" #header>
            Edición de Anteproyecto {{ cost_line.name }}
        </template>
        <template v-else #header>
            Creación de Anteproyecto {{ cost_line.name }}
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
                        <h2 v-else class="text-base font-semibold leading-7 text-gray-900">
                            Registrar nuevo Anteproyecto
                        </h2>
                        <br>
                        <div class="border-b grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-4 border-gray-900/10 pb-12">
                            <div>
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    Cliente
                                </InputLabel>
                                <div class="mt-2">
                                    <input id="unit" list="options" @input="(e) => handleAutocomplete(e, 'customer_id')"
                                        placeholder="Nombre o RUC" autocomplete="off" v-model="customerRuc"
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
                                    <InputLabel for="customer" class="font-medium leading-6 text-gray-900">¿Tiene
                                        cliente
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
                                    <input id="unit" list="options" placeholder="Nombre o RUC"
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
                                    {{ customers.find(item => item.id == (form.hasSubcustomer
                                        ? form.subcustomer_id : form.customer_id)
                                    )?.business_name }}
                                </InputLabel>

                                <InputLabel class="font-medium leading-6 mt-2 text-gray-900">Dirección:
                                </InputLabel>
                                <InputLabel class="leading-6 text-gray-900">
                                    {{ customers.find(item => item.id == (form.hasSubcustomer
                                        ? form.subcustomer_id : form.customer_id))?.address }}
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


                                <div v-for="( item, i ) in contactsList " :key="i" class="">
                                    <div v-if="form.contacts.includes(item.id)"
                                        class="border-b col-span-8 border-gray-900/10 grid grid-cols-8 items-center my-2">
                                        <p class=" text-sm col-span-7 line-clamp-2">
                                            {{ item.name }}: {{ item.phone }} </p>
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
                                    <TextInput type="text" v-model="form.description" id="description"
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
                                    <input type="text" v-model="form.code" id="name" maxlength="11" disabled
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6 uppercase" />
                                </div>
                                <!-- <p class="text-gray-400">Ejemplo: CCCCC-PPPPP </p>
                                <p class="text-gray-400">CCCCC -> 5 iniciales cliente | PPPPP -> 5 iniciales proyecto
                                </p> -->
                                <InputError :message="form.errors.code" />
                            </div>

                            <div>
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    Centro de Costos
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.cost_center_id"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="" disabled>Selecciona un centro de costos</option>
                                        <option v-for="item, i in cost_line.cost_center" :value="item.id" :key="i">
                                            {{ item.name }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.cost_center_id" />
                                </div>
                            </div>

                            <div v-if="[1, 2].includes(form.customer_id)">
                                <label for="cpe" class="font-medium leading-6 text-gray-900">CPE</label>
                                <div class="mt-2 flex justify-center items-center gap-2">
                                    <input requirede="text" pattern="[A-Z0-9]+" v-model="form.cpe" id="cpe"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                </div>
                                <!-- <p class="text-gray-400">Ejemplo: CCCCC-PPPPP </p>
                                <p class="text-gray-400">CCCCC -> 5 iniciales cliente | PPPPP -> 5 iniciales proyecto </p> -->
                                <InputError :message="form.errors.cpe" />
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
                            <template v-if="!preproject">
                                <div class="col-span-1 border-t-2 border-gray-300 sm:col-span-2">
                                </div>
                                <div class="flex space-x-3 justify-start sm:col-span-2">
                                    <h2 class="text-base font-semibold leading-7 text-gray-900 items-center">
                                        Agregar etapas de reporte
                                    </h2>
                                    <button type="button" @click="addReportStage"
                                        class="font-medium text-indigo-600 hover:text-indigo-500 self-start sm:self-end items-center">Agregar
                                        etapas</button>
                                </div>

                                <div v-for="(reportStage, index) in form.reportStages" :key="index">
                                    <div class="flex justify-end mt-5">
                                        <button type="button" @click="removeReportStage(index)"
                                            class="font-medium text-red-600 hover:text-indigo-500">Eliminar</button>
                                    </div>
                                    <InputLabel for="stage">
                                        Etapas
                                    </InputLabel>
                                    <div class="mt-2">
                                        <select v-model="reportStage.type" id="stage"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            <option value="">Selecciona etapa</option>
                                            <option v-for="stage in stages" :key="stage.id" :value="stage.name">
                                                {{ stage.name }}
                                            </option>
                                        </select>
                                        <InputError :message="form.errors['reportStages.' + index + '.type']" />
                                    </div>

                                    <InputLabel for="emergency_lastname">
                                        Titulo
                                    </InputLabel>
                                    <div class="mt-2">
                                        <select v-model="reportStage.title_id" id="title_id"
                                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                            <option value="">Selecciona un título</option>
                                            <option v-for="title in titles" :key="title.id" :value="title.id">
                                                {{ title.title }}
                                            </option>
                                        </select>
                                        <InputError :message="form.errors['reportStages.' + index + '.title_id']" />
                                    </div>
                                </div>
                                <InputError :message="form.errors.reportStages" />
                            </template>
                        </div>
                    </div>
                </div>
                <div class="mt-3 flex items-center justify-end gap-x-6">
                    <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">Guardar</PrimaryButton>
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
                                <option v-for=" item in contactsList" :value="item.id">
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
import TextInput from '@/Components/TextInput.vue';

const showModal = ref(false)
const showModalUpdate = ref(false)
const showErrorContact = ref(false)

const { preproject, customers, titles, stages, type, cost_line } = defineProps({
    preproject: Object,
    customers: Object,
    titles: Object,
    stages: Object,
    type: String,
    cost_line: Object,
})

let backUrls = (preproject?.status === undefined || preproject?.status === null)
    ? { route: 'preprojects.index', params: { type } }
    : preproject?.status == true
        ? { route: 'preprojects.index', params: { type, preprojects_status: 1 } }
        : { route: 'preprojects.index', params: { type, preprojects_status: 0 } }

const initial_state = {
    customer_id: '',
    subcustomer_id: '',
    code: '',
    description: '',
    date: '',
    cost_center_id: '',
    observation: '',
    reportStages: [],
    cost_line_id: type,
    // title_factibilidad_id: '',
    // title_implementation_id: '',
    contacts: [],
    hasSubcustomer: false,
    cpe: ''
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

const customerBusinnes = ref(preproject ? preproject.customer?.business_name : '')

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
                router.visit(preproject?.status === undefined
                    ? route('preprojects.index', { type })
                    : preproject?.status == true
                        ? route('preprojects.index', { type, preprojects_status: 1 })
                        : route('preprojects.index', { type, preprojects_status: 0 }))
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
        customerBusinnes.value = ''
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
    const customerName = customerBusinnes.value.replaceAll(' ', '').substring(0, 5);
    const description = form.description.replaceAll(' ', '').substring(0, 9);

    form.code = `${customerName}-${description}`;
};

watch(() => [customerBusinnes.value, form.description], updateProjectCode);

const addReportStage = () => {
    form.reportStages.push({
        type: '',
        title_id: '',
    });
}

const removeReportStage = (index) => {
    form.reportStages.splice(index, 1);
}

</script>
