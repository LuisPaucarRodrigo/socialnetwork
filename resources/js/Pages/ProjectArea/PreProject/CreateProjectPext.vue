<template>

    <Head title="Anteproyectos" />
    <AuthenticatedLayout :redirectRoute="{
        route: 'preprojects.index',
        params: { type: 2 }
    }">
        <template #header>
            Anteproyecto y Proyecto PEXT CICSA GTD
        </template>
        <Toaster richColors />
        <div class="min-w-full p-3 rounded-lg shadow">
            <form @submit.prevent="submit">
                <div class="pt-1">
                    <div class="border-b border-gray-900/10 mb-2">
                    </div>
                    <div class="pb-12">
                        <br>
                        <div
                            class="border-b grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-4  border-gray-900/10 pb-12">

                            <!-- <div>
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
                            </div> -->
                            <div>
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    Centro de Costos
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.cost_center_id"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Selecciona un centro de costos</option>
                                        <option v-for="item, i in cost_centers" :value="item.id" :key="i">
                                            {{ item.name }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.cost_center_id" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    Fecha de Inicio
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="date" v-model="form.date" id="date" :min="minDate"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.date" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    CPE
                                </InputLabel>
                                <div class="mt-2">
                                    <input @input="handleProductCPE($event.target.value)" type="number"
                                        v-model="form.cpe" id="cpe"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.cpe" />
                                </div>
                            </div>

                            <div class="col-start-1">
                                <InputLabel class="font-medium leading-6 text-gray-900">
                                    Contactos de CICSA
                                </InputLabel>
                                <div class="mt-2">
                                    <div v-for="(item, i) in form.contacts" :key="i" class="">
                                        <div
                                            class="border-b col-span-8 border-gray-900/10 grid grid-cols-8 items-center my-2">
                                            <p class=" text-sm col-span-7 line-clamp-2">
                                                {{ item.name }}: {{ item.phone }} </p>
                                            <!-- @click="delete_already_employee(member.pivot.id, index)" -->
                                            <button type="button" @click="deleteContactItem(i)">
                                                <DeleteIcon />
                                            </button>
                                        </div>
                                    </div>
                                    <InputError :message="form.errors.contacts" />
                                </div>
                            </div>
                            <div class="flex flex-col justify-between h-full">
                                <div>
                                    <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                        Servicios
                                    </InputLabel>
                                    <div class="mt-2">
                                        <div class="overflow-x-auto ring-1 ring-gray-300 rounded-md">
                                            <table class="w-full whitespace-no-wrap">
                                                <thead>
                                                    <tr
                                                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                        <th
                                                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                            Nombre</th>
                                                        <th
                                                            class="border-b-2 border-gray-200 bg-gray-100 pl-2 pr-12     py-2 text-right text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                            Monto</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr v-for="(item, index) in form.services" :key="item.id"
                                                        class="text-gray-700">
                                                        <td
                                                            class="border-b border-gray-200 bg-white px-2 py-2 text-sm whitespace-nowrap">
                                                            {{ item.name }}</td>
                                                        <td class="border-b border-gray-200 bg-white px-4 py-2 text-sm">
                                                            <div class="flex justify-end items-center space-x-2">
                                                                <span>S/.</span> <input min="1"
                                                                    @change="handleProfitMargin(item)" type="number"
                                                                    v-model="form.services[index].rent_price"
                                                                    class="w-28 text-center rounded-md border-0 py-1 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-400 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />

                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                        <InputError :message="form.errors.services" />
                                    </div>
                                </div>

                                <div class="flex justify-between bg-white py-2 px-4 ring-1 ring-gray-300 rounded-md">
                                    <InputLabel class="font-medium leading-6 text-indigo-900">
                                        TOTAL
                                    </InputLabel>
                                    <p class="font-medium text-base tracking-wider mr-8 text-indigo-900">
                                        S/. {{form.services.reduce((a, item) => a + parseFloat(item.rent_price ?
                                            item.rent_price :
                                            0), 0)}}
                                    </p>
                                </div>
                            </div>
                            <div class="sm:col-span-1">
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    Empleados
                                </InputLabel>
                                <div class="mt-2">
                                    <div v-for="(item, i) in form.employees" :key="i" class="">
                                        <div
                                            class="border-b col-span-8 border-gray-900/10 grid grid-cols-8 items-center my-2">
                                            <p class=" text-sm col-span-7 line-clamp-2 whitespace-nowrap">
                                                {{ item?.name }} {{ item?.lastname }} - {{ item.charge }}
                                            </p>
                                            <button type="button" @click="deleteEmployee(i)">
                                                <DeleteIcon />
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <InputError :message="form.errors.employees" />
                            </div>
                            <div class="sm:col-span-1">
                                <InputLabel for="customer" class="font-medium leading-6 text-gray-900">
                                    Productos
                                </InputLabel>
                                <div class="mt-2">
                                    <div v-if="form.cpe !== '' && productsCPE.length > 0"
                                        v-for="(item, i) in productsCPE" :key="i" class="">
                                        <div
                                            class="border-b col-span-8 border-gray-900/10 grid grid-cols-8 items-center my-2">
                                            <p class=" text-sm col-span-7 line-clamp-2 whitespace-nowrap">
                                                {{ item.purchase_product?.name }} - {{ item.product_serial_number }} -
                                                {{ item.quantity }}
                                            </p>

                                        </div>
                                    </div>
                                    <div v-else class="">
                                        <div class="text-sm items-center my-2">
                                            No hay productos asociados a este CPE
                                        </div>
                                    </div>
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
            <!-- <Modal :show="showContactModal">
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
            </Modal> -->
            <ErrorOperationModal :showError="showErrorContact" title="Error"
                message="El contacto ya fue añadido o es inválido" />

        </div>
        <ConfirmUpdateModal :confirmingupdate="showModalUpdate" itemType="Anteproyecto" />


    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { computed, ref, watch } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';
import axios from 'axios';
import { setAxiosErrors } from '@/utils/utils';
import { notify, notifyError } from '@/Components/Notification';
import { Toaster } from 'vue-sonner';
import DeleteIcon from '@/Components/Icons/DeleteIcon.vue';

const showModal = ref(false)
const showModalUpdate = ref(false)
const showErrorContact = ref(false)

const { contacts_cicsa, employees, services, cost_centers, type } = defineProps({
    contacts_cicsa: Object,
    services: Object,
    employees: Object,
    cost_centers: Array,
    type: String
})


const productsCPE = ref([])

const handleProductCPE = async (cpe) => {
    let res = await axios.post(route('pext_project.products.cpe'), { cpe })
    productsCPE.value = res.data
}

const minDate = computed(() => {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, "0"); // Mes en formato "MM"
    return `${year}-${month}-01`; // YYYY-MM-01
});

const templates = [
    'Mantenimiento',
    'Instalación'
]

const initial_state = {
    cost_center_id: '',
    template: '',
    date: '',
    cpe: '',
    contacts: contacts_cicsa,
    services: [],
    // services: services.map(item => {
    //     item.original_price = item.rent_price
    //     if ([8].includes(item.id)) {
    //         item.profit_margin = 0
    //         item.rent_price = 0
    //         item.days = 1
    //     } 
    //     // else if ([7].includes(item.id)) {
    //     //     item.profit_margin = (((22300 / item.rent_price) - 1) * 100).toFixed(5)
    //     //     item.rent_price = 44600
    //     //     item.days = 2
    //     // }
    //     return item
    // }),
    employees: employees,
}


const form = useForm({
    ...initial_state
});

const getEmployees = async (cc_id) => {
    const res = await axios.get(route('project.auto.pint.getEmployees', { cc_id }))
    form.employees = res.data
}
watch(() => form.cost_center_id, () => {
    getEmployees(form.cost_center_id)
})

async function submit() {
    let url = route('project.auto_store.pext')
    try {
        await axios.post(url, form)
        notify('Creacion de nuevo preproyecto')
        setTimeout(() => {
            router.visit(route('preprojects.index', { type: type }))
        }, 2000);
    } catch (error) {
        console.log(error)
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form)
            } else {
                notifyError(`Server error: ${error.response.data}`);
            }
        } else {
            notifyError("Network or other error:", error)
        }
    }
}



const deleteContactItem = (i) => {
    form.contacts.splice(i, 1)
}

const deleteServiceItem = (i) => {
    form.services.splice(i, 1)
}

const deleteEmployee = (i) => {
    form.employees.splice(i, 1)
}

const serviceReferencePrices = [
    {
        groupName: 'group1',
        ids: [3, 4, 5, 6, 7],
        expectedPrice: 21300,
        originalPrice: 15000
    },
    {
        groupName: 'group2',
        ids: [7],
        expectedPrice: 44600,
        originalPrice: 15000
    }
];

function findGroupById(id) {
    for (const group of serviceReferencePrices) {
        if (group.ids.includes(id)) {
            return group;
        }
    }
    return null;
}

const handleProfitMargin = (item) => {
    let group = findGroupById(item.id)
    item.profit_margin = (((item.rent_price / 15000) - 1) * 100).toFixed(5)
}

</script>