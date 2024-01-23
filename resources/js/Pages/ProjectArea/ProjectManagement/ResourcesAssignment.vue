<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout>
        <template #header>
            Asignación de Recursos
        </template>
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
            <div class="p-3 lg:col-span-2 rounded-lg shadow">
                <div class="sm:col-span-3">
                    <div class="flex gap-2">
                        <h3 class="text-lg leading-6 text-gray-900 font-bold">Añadir Recursos
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
                                            resource_historial.resource.unique_identification }} - {{ resource_historial.resource.name }}</p>
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

            <!-- <div class="sm:col-span-3 mt-10">
                <div class="flex gap-2">
                    <h3 class="text-lg leading-6 text-gray-900 font-bold">Añadir Equipos
                    </h3>
                    <button @click="showToAddEmployee2" type="button">
                        <PlusCircleIcon class="text-lg text-indigo-800 h-7 w-7 hover:text-purple-400" />
                    </button>
                </div>

                <div class="mt-7">
                    <div v-for="(network_equipment, index) in project.network_equipments" :key="index"
                        class="grid grid-cols-8 items-center my-3">
                        <p class="text-md col-span-7 line-clamp-2">{{ network_equipment.model }} - {{ network_equipment.serie_number
                        }} / {{ network_equipment.name }}</p>
                        <button type="button" @click="delete_network_equipment(network_equipment.pivot.id)"
                            class="col-span-1 flex justify-end">
                            <TrashIcon class="text-red-500 h-5 w-5" />
                        </button>
                        <div class="border-b col-span-8 border-gray-900/10"></div>
                    </div>
                </div>
            </div>


            <div class="sm:col-span-3 mt-10">
                <div class="flex gap-2">
                    <h3 class="text-lg leading-6 text-gray-900 font-bold">Añadir Materiales
                    </h3>
                    <button @click="showToAddEmployee3" type="button">
                        <PlusCircleIcon class="text-lg text-indigo-800 h-7 w-7 hover:text-purple-400" />
                    </button>
                </div>

                <div class="mt-7">
                    <div v-for="(component_or_material, index) in project.components_or_materials" :key="index"
                        class="grid grid-cols-8 items-center my-3">
                        <p class="text-md col-span-7 line-clamp-2">{{ component_or_material.name
                        }} / Cantidad: {{ component_or_material.pivot.quantity }}</p>
                        <button type="button" @click="delete_components_materials(component_or_material.pivot.id)"
                            class="col-span-1 flex justify-end">
                            <TrashIcon class="text-red-500 h-5 w-5" />
                        </button>
                        <div class="border-b col-span-8 border-gray-900/10"></div>
                    </div>
                </div>
            </div> -->

            <!-- Agregar -->
            <Modal :show="showModal">
                <form class="p-6" @submit.prevent="submit">
                    <h2 class="text-lg font-medium text-gray-900">
                        Solo recursos disponibles
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                        <div class="sm:col-span-3">
                            <InputLabel for="resource_id" class="font-medium leading-6 text-gray-900">Recurso
                            </InputLabel>
                            <div class="mt-2">
                                <select required id="resource_id" v-model="form.resource_id"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione uno</option>
                                    <option v-for="item in resources" :key="item.id" :value="item.id">{{
                                        item.unique_identification }} - {{ item.name }} ({{ item.leftover }})</option>
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
                        <button
                            class="inline-flex items-center p-2 rounded-md font-semibold bg-red-500 text-white hover:bg-red-400"
                            type="button" @click="closeModal"> Cerrar </button>
                        <button
                            class="inline-flex items-center p-2 rounded-md font-semibold bg-indigo-500 text-white hover:bg-indigo-400"
                            type="submit"> Agregar </button>
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
                        <button
                            class="inline-flex items-center p-2 rounded-md font-semibold bg-red-500 text-white hover:bg-red-400"
                            type="button" @click="returned_closeModal"> Cerrar </button>
                        <button
                            class="inline-flex items-center p-2 rounded-md font-semibold bg-indigo-500 text-white hover:bg-indigo-400"
                            type="submit"> Agregar </button>
                    </div>
                </form>
            </Modal>


            <!-- <Modal :show="showModal2">
                <form class="p-6" @submit.prevent="submit2">
                    <h2 class="text-lg font-medium text-gray-900">
                        Solo equipos de red disponibles
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                        <div class="sm:col-span-3">
                            <InputLabel for="network_equipment_id" class="font-medium leading-6 text-gray-900">Equipo de red
                            </InputLabel>
                            <div class="mt-2">
                                <select required id="network_equipment_id" v-model="form2.network_equipment_id"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione uno</option>
                                    <option v-for="item in network_equipments" :key="item.id" :value="item.id">{{
                                        item.model }} - {{ item.name }}</option>
                                </select>
                            </div>
                        </div>

                
                        <div class="sm:col-span-3">
                            <InputLabel for="observation" class="font-medium leading-6 text-gray-900">Observaciones
                            </InputLabel>
                            <div class="mt-2">
                                <textarea id="observation" type="text"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form2.observation" />
                            </div>
                        </div>

                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <button
                            class="inline-flex items-center p-2 rounded-md font-semibold bg-red-500 text-white hover:bg-red-400"
                            type="button" @click="closeModal2"> Cerrar </button>
                        <button
                            class="inline-flex items-center p-2 rounded-md font-semibold bg-indigo-500 text-white hover:bg-indigo-400"
                            type="submit"> Agregar </button>
                    </div>
                </form>
            </Modal>

            <Modal :show="showModal3">
                <form class="p-6" @submit.prevent="submit3">
                    <h2 class="text-lg font-medium text-gray-900">
                        Solo componentes o materiales
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                        <div class="sm:col-span-3">
                            <InputLabel for="component_or_material_id" class="font-medium leading-6 text-gray-900">Componente o Material
                            </InputLabel>
                            <div class="mt-2">
                                <select required id="component_or_material_id" v-model="form3.component_or_material_id"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccione uno</option>
                                    <option v-for="item in components_or_materials" :key="item.id" :value="item.id">{{ item.name }} ({{ item.leftover }})</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="quantity" class="font-medium leading-6 text-gray-900">Cantidad</InputLabel>
                            <div class="mt-2">
                                <TextInput id="quantity" type="number" min="1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form3.quantity" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="observation" class="font-medium leading-6 text-gray-900">Observaciones
                            </InputLabel>
                            <div class="mt-2">
                                <textarea id="observation" type="text"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    v-model="form3.observation" />
                            </div>
                        </div>

                    </div>
                    <div class="mt-6 flex gap-3 justify-end">
                        <button
                            class="inline-flex items-center p-2 rounded-md font-semibold bg-red-500 text-white hover:bg-red-400"
                            type="button" @click="closeModal3"> Cerrar </button>
                        <button
                            class="inline-flex items-center p-2 rounded-md font-semibold bg-indigo-500 text-white hover:bg-indigo-400"
                            type="submit"> Agregar </button>
                    </div>
                </form>
            </Modal> -->

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
import { Head, router, useForm } from '@inertiajs/vue3';
import { PlusCircleIcon, TrashIcon } from '@heroicons/vue/24/outline';

const showModal = ref(false);
// const showModal2 = ref(false);
// const showModal3 = ref(false);

const { project, resources, network_equipments, components_or_materials } = defineProps({
    project: Object,
    resources: Object,
    network_equipments: Object,
    components_or_materials: Object
})

console.log(project)
// console.log(components_or_materials)

//Recursos
const initialState = {
    project_id: project.id,
    resource_id: '',
    quantity: '',
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