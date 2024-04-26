<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout redirectRoute="projectmanagement.index">
        <template #header>
            Servicios
        </template>
        <div class="grid grid-cols-1 lg:grid-cols-1 gap-4">
            <div class="p-3 lg:col-span-1 rounded-lg shadow">
                <div class="sm:col-span-3">
                    <div class="mt-7">
                        <div class="overflow-x-auto ">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr
                                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Servicio
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Cantidad de Activos
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Días
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Precio Unitario de Renta
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Margen
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Valor total
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v v-for="(resource, index) in servicesArrayMaker(project.preproject.quote.preproject_quote_services)" :key="index" class="text-gray-700">
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p>
                                                {{ resource.service_info.name }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                {{ resource.resource_entries.length === 0 ? '-' : resource.resource_entries.length }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">{{ resource.days
                                                }}</p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">S/. {{ resource.rent_price.toFixed(2) }}</p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">{{ resource.profit_margin }} %</p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                S/. {{
                                            (resource.service_info.rent_price *
                                                (resource.resource_entries.length === 0 ? 1 :
                                                resource.resource_entries.length)
                                                * resource.days * (1 + resource.profit_margin / 100)).toFixed(2) }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p v-if="resource.has_liquidation"
                                                class="col-span-2 text-indigo-500 flex justify-end">
                                                Liquidado
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <button @click="open_service_liquidate(resource.id)"
                                                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 ">
                                                Liquidar
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <SuccessOperationModal :confirming="successAsignation" title="Recurso asignado"
                message="La asignación fue exitosa" />

            <!-- Devolver -->
            <Modal :show="returned_showModal">
                <form class="p-6" @submit.prevent="returned_submit">
                    <h2 class="text-lg font-medium text-gray-900">
                        Devolución de recursos
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                        <div class="sm:col-span-3">
                            <InputLabel for="quantity" class="font-medium leading-6 text-gray-900">Cantidad</InputLabel>
                            <div class="mt-2">
                                <TextInput id="quantity" type="number" min="1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form_to_returned.quantity" />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="observation" class="font-medium leading-6 text-gray-900">Observaciones
                            </InputLabel>
                            <div class="mt-2">
                                <textarea id="observation" type="text"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form_to_returned.observation" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <SecondaryButton type="button" @click="returned_closeModal"> Cerrar </SecondaryButton>
                        <PrimaryButton type="submit"> Devolver </PrimaryButton>
                    </div>
                </form>
            </Modal>

            <Modal :show="service_liquidate">
                <form class="p-6" @submit.prevent="liquidate">
                    <h2 class="text-lg font-medium text-gray-900">
                        Liquidación de Servicio
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                        <div class="sm:col-span-6 mt-2">
                            <InputLabel for="observation" class="font-medium leading-6 text-gray-900">Observaciones
                            </InputLabel>
                            <div class="mt-2">
                                <textarea id="observation" type="text"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="formLiquidate.observations" />
                            </div>
                        </div>
                        <div class="sm:col-span-6 mt-2">
                            <InputLabel for="observation" class="font-medium leading-6 text-gray-900">¿El activo fue consumido?</InputLabel>
                            <div class="mt-2 space-y-2">
                                <label class="inline-flex items-center">
                                    <input type="radio" class="form-radio text-indigo-600" :value="true" v-model="formLiquidate.state">
                                    <span class="ml-1">Si</span>
                                </label>
                                <label class="inline-flex items-center ml-3">
                                    <input type="radio" class="form-radio text-indigo-600" :value="false" v-model="formLiquidate.state">
                                    <span class="ml-1">No</span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <SecondaryButton type="button" @click="close_service_liquidate"> Cerrar </SecondaryButton>
                        <PrimaryButton type="submit"> Liquidar </PrimaryButton>
                    </div>
                </form>
            </Modal>

            <SuccessOperationModal :confirming="successReturn" title="Recurso devuelto"
                message="La devolución exitosa" />
            <SuccessOperationModal :confirming="successAsignationLiquidate" title="Recurso liquidado"
                message="La liquidación fue exitosa" />
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';

const { project, liquidations, services, auth } = defineProps({
    project: Object,
    services: Object,
    liquidations: Object,
    auth: Object
})

const service_liquidate = ref(false);
console.log(project.preproject.quote.preproject_quote_services)

const open_service_liquidate = (id) => {
    service_liquidate.value = true;
    formLiquidate.preproject_quote_service_id = id;
}

const close_service_liquidate = () => {
    service_liquidate.value = false;
}

const liquidate = () => {
    console.log(observations.value, preproject_quote_service_id.value)
}

const formLiquidate = useForm({
    observations: '',
    preproject_quote_service_id: null,
    state: false
});

//Recursos
const initialState = {
    project_id: project.id,
    resource_id: '',
    quantity: '',
    unit_price: '',
    observation: '',
}
const form = useForm(
    { ...initialState }
)
const successAsignation = ref(false)

const returned_showModal = ref(false)
const returned_initialState = {
    quantity: '',
    observation: '',
    resource_id: '',
    project_id: project.id
}
const form_to_returned = useForm({ ...returned_initialState })

const returned_closeModal = () => {
    returned_showModal.value = false;
};
const successReturn = ref(false)


// --------------------------liquidation process-------------------------- //


const successAsignationLiquidate = ref(false);

function servicesArrayMaker(data) {

    let result = []
    data.forEach((item) => {
        let fo = result.find((x) => x.service_id === item.service_id)
        if (fo) {
            fo.resource_entries.push(item.resource_entry)
            fo.ids.push(item.id)
        } else {
            result.push({
                service_id: item.service_id,
                days: item.days,
                profit_margin: item.profit_margin,
                service_info: item.service,
                resource_entries: item.resource_entry_id
                    ? [item.resource_entry]
                    : [],
                rent_price: item.rent_price,
                ids: [item.id]
            })
        }
    })
    return result
}

</script>