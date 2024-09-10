<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="backUrl">
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
                                            Días
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Precio Unitario de Renta
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
                                    <tr v v-for="(resource, index) in project.preproject.quote.preproject_quote_services" :key="index" class="text-gray-700">
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p>
                                                {{ resource.service.name }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                {{ resource.days }}</p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">S/. {{ resource.rent_price.toFixed(2) }}</p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-nowrap">S/. {{ (resource.rent_price * resource.days).toFixed(2) }}</p>
                                        </td>
                                        
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p v-if="resource.has_liquidation"
                                                class="col-span-2 text-indigo-500 flex justify-end">
                                                Liquidado
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <button v-if="resource.state === null" @click="open_service_liquidate(resource.id, resource.resource_entry_id)"
                                                class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 ">
                                                Liquidar
                                            </button>
                                            <p v-else>
                                                Liquidado {{ resource.state ? ' / Consumido' : '' }}
                                            </p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
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
                        <div v-if="hasResource" class="sm:col-span-6 mt-2">
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

            <SuccessOperationModal :confirming="showModal" title="Servicio liquidado"
                message="La liquidación fue exitosa" />

        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
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

let backUrl = project.status === null 
                ? 'projectmanagement.index' 
                : project.status == true 
                    ? 'projectmanagement.historial'
                    : 'projectmanagement.index' 

const service_liquidate = ref(false);
const showModal = ref(false);
const hasResource = ref(false);

const open_service_liquidate = (id, entry) => {
    service_liquidate.value = true;
    formLiquidate.preproject_quote_service_id = id;
    if (entry){
        hasResource.value = true;
    }
}

const close_service_liquidate = () => {
    formLiquidate.reset();
    hasResource.value = false;
    service_liquidate.value = false;
}

const liquidate = () => {
    formLiquidate.post(route('projectmanagement.resources.liquidate'), {
      onSuccess: () => {
        close_service_liquidate();
        showModal.value = true
        setTimeout(() => {
          showModal.value = false;
        }, 2000);
      },
    });
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


</script>