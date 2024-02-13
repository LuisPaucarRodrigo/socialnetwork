<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout>
        <template #header>
            Asignación de Activos
        </template>
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
            <div class="p-3 lg:col-span-2 rounded-lg shadow">
                <div class="sm:col-span-3">
                    <div class="flex gap-2">
                        <h3 class="text-lg leading-6 text-gray-900 font-bold">Añadir Activos
                        </h3>
                        <button @click="showToAddEmployee" type="button">
                            <PlusCircleIcon class="text-lg text-indigo-800 h-7 w-7 hover:text-purple-400" />
                        </button>
                    </div>

                    <div class="mt-7">
                        <div v-for="(resource, index) in project.resources" :key="index"
                            class="grid grid-cols-8 items-center my-3">
                            <p class="text-md col-span-7 line-clamp-2">{{ resource.unique_identification }} - {{
                                resource.name
                            }} / Cantidad: {{ resource.pivot.quantity }}</p>
                            <button type="button" @click="delete_resource(resource.id, resource.pivot.id)"
                                class="col-span-1 flex justify-end">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="#3540A7" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m0-3-3-3m0 0-3 3m3-3v11.25m6-2.25h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75" />
                                </svg>

                            </button>
                            <div class="border-b col-span-8 border-gray-900/10"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-1 lg:col-span-3 rounded-lg shadow">
                <div class="sm:col-span-3">
                    <div class="flex gap-2">
                        <h3 class="text-lg leading-6 text-gray-900 font-bold"> Historial
                        </h3>
                    </div>

                    <div class="mt-7">
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
                                            formatFecha(resource_historial.created_at) }}</p>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
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
                            <InputLabel for="input_rent" class="font-medium leading-6 text-gray-900">Precio de Alquiler
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput id="input_rent" type="number" min="1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form.total_price" />
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

const showModal = ref(false);
const input_rent = ref(false);
// const showModal2 = ref(false);
// const showModal3 = ref(false);

const { project, resources, network_equipments, components_or_materials } = defineProps({
    project: Object,
    resources: Object,
    network_equipments: Object,
    components_or_materials: Object
})

//Recursos
const initialState = {
    project_id: project.id,
    resource_id: '',
    quantity: '',
    total_price: '',
    observation: '',
}
const form = useForm(
    { ...initialState }
)
const submit = () => {
    form.post(route('projectmanagement.resources.store'), {
        onSuccess: () => {
            form.reset();
            alert('Recurso agregado')
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
const returned_submit = () => {
    form_to_returned.post(route('projectmanagement.resources.return', { id: resource_project_id.value }), {
        onSuccess: () => {
            form_to_returned.reset();
            alert('recursos retornados')
        }
    })
}

function formatFecha(fecha) {
    const options = { day: '2-digit', month: 'numeric', year: 'numeric' };
    return new Date(fecha).toLocaleDateString(undefined, options);
}

const conditional_rent = (event) => {
    resources.find(item => item.id == event.target.value).conditional_rent == true ? input_rent.value = true : input_rent.value = false;
}





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