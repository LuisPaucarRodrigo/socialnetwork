<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout :redirect-route="'projectmanagement.index'">
        <template #header>
            Asignación de Activos
        </template>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <div class="p-3 lg:col-span-1 rounded-lg shadow">
                <div class="sm:col-span-3">
                    <div class="flex gap-2">
                        <h3 class="text-lg leading-6 text-gray-900 font-bold">Añadir Activos
                        </h3>
                        <button @click="showToAddEmployee" type="button">
                            <PlusCircleIcon class="text-lg text-indigo-800 h-7 w-7 hover:text-purple-400" />
                        </button>
                    </div>

                    <div class="mt-7">


                        <div class="overflow-x-auto ">
                            <table  class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr
                                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Producto
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Cantidad asignada
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Precio Original
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Precio en Presupuesto
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v v-for="(resource, index) in project.project_resources" :key="index"
                                        class="text-gray-700">
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p>
                                                {{ resource.resource.description }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                {{ resource.quantity  }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">S/.{{ resource.resource.unit_price }}</p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">S/.{{ resource.unit_price }}</p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p v-if="resource.has_liquidation" class="col-span-2 text-indigo-500 flex justify-end">
                                                Liquidado
                                            </p>
                                            <div v-if="!resource.has_liquidation" class="flex gap-2">
                                                <button  type="button"
                                                    @click="delete_resource(resource.resource.id, resource.id)"
                                                    class="flex justify-center ">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                                        stroke="#3540A7" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m0-3-3-3m0 0-3 3m3-3v11.25m6-2.25h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75" />
                                                    </svg>

                                                </button>
                                                <button type="button" @click="showToLiquidate(resource)"
                                                    class="rounded-md   bg-indigo-600 py-1 w-full text-center text-sm text-white hover:bg-indigo-500 ">
                                                    Liquidar
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-1 lg:col-span-1 rounded-lg shadow">
                <div class="sm:col-span-3">
                    <div class="flex gap-2 items-center">
                        <h3 class="text-lg leading-6 ml-2 text-gray-900 font-bold"> Historial
                        </h3>
                        <select v-model="historialSelect"
                            class="block w-auto rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">>
                            <option>Liquidación</option>
                            <option>Asignamiento</option>
                        </select>
                    </div>

                    <div class="mt-7">
                        <div v-if="historialSelect === 'Liquidación'" class="overflow-x-auto ">

                            <table  class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr
                                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Producto
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Cantidad Consumida
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Cantidad Devuelta
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Observaciones
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Fecha
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="item in liquidations" :key="item.id"
                                        class="text-gray-700">
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p>
                                                {{ item.project_resource.resource.description }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">
                                                {{ item.liquidated_quantity - item.refund_quantity }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">{{ item.refund_quantity }}</p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">{{ item.observations }}</p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">{{
                                                formattedDate(item.created_at) }}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div v-else class="overflow-x-auto ">
                            <table class="w-full whitespace-no-wrap">
                                <thead>
                                    <tr
                                        class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Tipo
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Recurso
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Cantidad
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Observaciones
                                        </th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                            Fecha
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="resource_historial in project.resource_historials" :key="resource_historial.id"
                                        class="text-gray-700">
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p
                                                :class="{ 'text-green-600': resource_historial.type === 'Asignamiento', 'text-red-600': resource_historial.type === 'Devolución' }">
                                                {{ resource_historial.type }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">{{
                                                resource_historial.resource.unique_identification }} - {{
            resource_historial.resource.description }}
                                            </p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">{{ resource_historial.quantity }}</p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">{{ resource_historial.observation }}</p>
                                        </td>
                                        <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                            <p class="text-gray-900 whitespace-no-wrap">{{
                                                formattedDate(resource_historial.created_at) }}</p>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Agregar -->
            <Modal :show="showModal">
                <form class="p-6" @submit.prevent="submit">
                    <h2 class="text-lg font-medium text-gray-900">
                        Solo activos disponibles
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                        <div class="sm:col-span-3">
                            <InputLabel for="resource_id" class="font-medium leading-6 text-gray-900">Activos
                            </InputLabel>
                            <div class="mt-2">
                                <select required id="resource_id" v-model="form.resource_id"
                                    @change="conditional_rent($event)"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione uno</option>
                                    <option v-for="item in resources" :key="item.id" :value="item.id">
                                        {{ item.unique_identification }} - {{ item.description }} ({{ item.leftover }})
                                    </option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="quantity" class="font-medium leading-6 text-gray-900">Cantidad</InputLabel>
                            <div class="mt-2">
                                <TextInput id="quantity" type="number" min="1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.quantity" />
                            </div>
                        </div>

                        <div v-if="input_rent" class="sm:col-span-3">
                            <InputLabel for="input_rent" class="font-medium leading-6 text-gray-900">Precio unitario de
                                Alquiler
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput id="input_rent" type="number" min="1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.unit_price" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="observation" class="font-medium leading-6 text-gray-900">Observaciones
                            </InputLabel>
                            <div class="mt-2">
                                <textarea id="observation" type="text"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.observation" />
                            </div>
                        </div>

                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <SecondaryButton type="button" @click="closeModal"> Cerrar </SecondaryButton>
                        <PrimaryButton type="submit"> Agregar </PrimaryButton>
                    </div>
                </form>
            </Modal>

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

            <SuccessOperationModal :confirming="successReturn" title="Recurso devuelto" message="La devolución exitosa" />


            <Modal :show="showModalLiquidate">
                <form class="p-6" @submit.prevent="submitLiquidate">
                    <h2 class="text-lg font-medium text-gray-900">
                        Liquidacion del recurso
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                        <div class="sm:col-span-3">
                            <InputLabel for="liquidated_quantity" class="font-medium leading-6 text-gray-900">Cantidad
                                liquidada
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput id="liquidated_quantity" type="number" readonly min="1"
                                    v-model="formLiquidate.liquidated_quantity"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="refund_quantity" class="font-medium leading-6 text-gray-900">Cantidad devuelta
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput id="refund_quantity" type="number" min="0"
                                    :max="formLiquidate.liquidated_quantity" v-model="formLiquidate.refund_quantity"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="observations" class="font-medium leading-6 text-gray-900">Observaciones
                            </InputLabel>
                            <div class="mt-2">
                                <textarea id="observations" type="text" v-model="formLiquidate.observations"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                            </div>
                        </div>

                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <button
                            class="inline-flex items-center p-2 rounded-md font-semibold bg-red-500 text-white hover:bg-red-400"
                            type="button" @click="closeModalLiquidate"> Cerrar </button>
                        <button
                            class="inline-flex items-center p-2 rounded-md font-semibold bg-indigo-500 text-white hover:bg-indigo-400"
                            type="submit"> Agregar </button>
                    </div>
                </form>
            </Modal>
            <SuccessOperationModal :confirming="successAsignationLiquidate" title="Recurso liquidado"
                message="La liquidación fue exitosa" />
        </div>
        <div class="mt-6 flex items-center justify-between gap-x-6">
            <a :href="route('projectmanagement.index')"
                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Atras
            </a>
        </div>
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { PlusCircleIcon } from '@heroicons/vue/24/outline';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import { formattedDate } from '@/utils/utils';



const showModal = ref(false);
const input_rent = ref(false);

const { project, resources, network_equipments, liquidations } = defineProps({
    project: Object,
    resources: Object,
    network_equipments: Object,
    liquidations: Object
})

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
const submit = () => {
    form.post(route('projectmanagement.resources.store'), {
        onSuccess: () => {
            form.reset();
            successAsignation.value = true
            setTimeout(() => {
                successAsignation.value = false
            }, 1500)
        }
    })
}
const showToAddEmployee = () => {
    showModal.value = true;
}
const closeModal = () => {
    showModal.value = false;
};



const returned_showModal = ref(false)
const resource_project_id = ref(null)
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
const delete_resource = (id, pivot_id) => {
    returned_showModal.value = true
    resource_project_id.value = pivot_id
    form_to_returned.resource_id = id
}
const successReturn = ref(false)
const returned_submit = () => {
    form_to_returned.post(route('projectmanagement.resources.return', { id: resource_project_id.value }), {
        onSuccess: () => {
            form_to_returned.reset();
            successReturn.value = true
            setTimeout(() => {
                successReturn.value = false
            }, 1500)
        }
    })
}


const conditional_rent = (event) => {
    resources.find(item => item.id == event.target.value).conditional_rent == true ? input_rent.value = true : input_rent.value = false;
}


// --------------------------liquidation process-------------------------- //

const showModalLiquidate = ref(false);
const showToLiquidate = (resource) => {
    formLiquidate.project_resource_id = resource.id;
    formLiquidate.liquidated_quantity = resource.quantity;
    showModalLiquidate.value = true;
}
const closeModalLiquidate = () => {
    showModalLiquidate.value = false;
    formLiquidate.reset()
};



const formLiquidate = useForm({
    project_resource_id: '',
    liquidated_quantity: '',
    refund_quantity: '',
    observations: '',
})


const successAsignationLiquidate = ref(false);
const submitLiquidate = () => {
    formLiquidate.post(route('projectmanagement.resourcesLiquidate'), {
        onSuccess: () => {
            formLiquidate.reset();
            successAsignationLiquidate.value = true
            setTimeout(() => {
                successAsignationLiquidate.value = false
                closeModalLiquidate();
            }, 2000)
        }
    })
}

const historialSelect = ref('Liquidación')





//Equipos
// const initialState2 = {
//     project_id: project.id,
//     network_equipment_id: '',
//     observation: '',
// }
// const form2 = useForm(
//     { ...initialState2 }
// )
// const submit2 = () => {
//     form2.post(route('projectmanagement.networkequipments.store'), {
//         onSuccess: () => {
//             form2.reset();
//             alert('Equipo de red agregado')
//         }
//     })
// }
// const showToAddEmployee2 = () => {
//     showModal2.value = true;
// }
// const closeModal2 = () => {
//     showModal2.value = false;
// };
// const delete_network_equipment = (network_equipment_id) => {
//     router.delete(route('projectmanagement.networkequipments.delete', { network_equipment_id }), {
//         onSuccess: () => {
//             alert('Equipo de red removido del proyecto')
//         },
//         onError: () => {
//             alert('SERVER ERROR')
//         },
//     })
// }

// //Components materials
// const initialState3 = {
//     project_id: project.id,
//     component_or_material_id: '',
//     quantity: '',
//     observation: '',
// }
// const form3 = useForm(
//     { ...initialState3 }
// )
// const submit3 = () => {
//     form3.post(route('projectmanagement.componentormaterials.store'), {
//         onSuccess: () => {
//             form3.reset();
//             alert('Componente o material agregado')
//         }
//     })
// }
// const showToAddEmployee3 = () => {
//     showModal3.value = true;
// }
// const closeModal3 = () => {
//     showModal3.value = false;
// };
// const delete_components_materials = (component_or_material_id) => {
//     router.delete(route('projectmanagement.componentormaterials.delete', { component_or_material_id }), {
//         onSuccess: () => {
//             alert('Equipo de red removido del proyecto')
//         },
//         onError: () => {
//             alert('SERVER ERROR')
//         },
//     })
// }


</script>