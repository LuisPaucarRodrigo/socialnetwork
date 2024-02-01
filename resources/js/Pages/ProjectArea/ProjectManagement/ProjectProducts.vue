<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout>
        <template #header>
            Productos asignados
        </template>

        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <div>
                <button type="button" @click="showToAddProduct"
                    class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 ">
                    + Agregar
                </button>
            </div>
            <div class="talwing mt-4">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Descripcion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad Asignada
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad Enviada
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Observaciones
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in assigned_products.data" :key="item.id" :class="[
                            'text-gray-700',
                            { 
                                'border-l-4': true, 
                                'border-green-500': item.state === 'Completo', 
                                'border-red-500': item.state === 'Incompleto' 
                            }
                        ]">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.product.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.quantity }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.total_output_project_product }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.observation }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ formatearFecha(item.created_at) }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button v-if="item.is_deletable" type="button" @click="confirmDelete(item.id)"
                                        class="text-red-900 whitespace-no-wrap">
                                        <TrashIcon class="text-red-500 h-4 w-4" />
                                    </button>

                                    <button type="button" @click="showToLiquidate(item)"
                                        class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 ">
                                        Liquidar
                                    </button>

                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="assigned_products.links" />
        </div>

        <Modal :show="showModal" >
                <form class="p-6" @submit.prevent="submit">
                    <h2 class="text-lg font-medium text-gray-900">
                        Solo productos disponibles
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                        <div class="sm:col-span-3">
                            <InputLabel for="resource_id" class="font-medium leading-6 text-gray-900">Almacenes
                            </InputLabel>
                            <div class="mt-2">
                                <select required id="resource_id" @change="handleWarehouseChange"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled selected value="">Seleccione uno</option>
                                    <option v-for="item in warehouses" :key="item.id" :value="item.id">{{ item.name }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="product_id" class="font-medium leading-6 text-gray-900">Productos
                            </InputLabel>
                            <div class="mt-2">
                                <select required id="product_id" v-model="form.product_id"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled  value="">Seleccione uno</option>
                                    <option v-for="item in warehouseProducts" :key="item.id" :value="item.id">
                                        {{ item.name }} - 
                                        {{ 
                                            item.total_available
                                        }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="quantity" class="font-medium leading-6 text-gray-900">Cantidad</InputLabel>
                            <div class="mt-2">
                                <TextInput id="quantity" type="number" min="1" v-model="form.quantity"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                     />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="observation" class="font-medium leading-6 text-gray-900">Observaciones
                            </InputLabel>
                            <div class="mt-2">
                                <textarea id="observation" type="text" v-model="form.observation"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   />
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


            <Modal :show="showModalLiquidate" >
                <form class="p-6" @submit.prevent="submitLiquidate">
                    <h2 class="text-lg font-medium text-gray-900">
                        Liquidacion del producto
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">

                        <div class="sm:col-span-3">
                            <InputLabel for="resource_id" class="font-medium leading-6 text-gray-900">Seleccionar salida
                            </InputLabel>
                            <div class="mt-2">
                                <select required id="resource_id"
                                    v-model="form.output_project_product_id"
                                    @change="handleOutputProjectProductChange"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled selected value="">Seleccione uno</option>
                                    <option v-for="(item, index) in selectValues.output_project_product" :key="item.id" :value="item.id">Cantidad de Salida: {{ item.quantity }}</option>
                                </select>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="liquidated_quantity" class="font-medium leading-6 text-gray-900">Cantidad liquidada</InputLabel>
                            <div class="mt-2">
                                <TextInput id="liquidated_quantity" type="number" readonly min="1" v-model="formLiquidate.liquidated_quantity"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                     />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="refund_quantity" class="font-medium leading-6 text-gray-900">Cantidad devuelta</InputLabel>
                            <div class="mt-2">
                                <TextInput id="refund_quantity" type="number" min="1" :max="formLiquidate.liquidated_quantity" v-model="formLiquidate.refund_quantity"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                     />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="observations" class="font-medium leading-6 text-gray-900">Observaciones
                            </InputLabel>
                            <div class="mt-2">
                                <textarea id="observations" type="text" v-model="formLiquidate.observations"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                   />
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
            <ConfirmDeleteModal :confirmingDeletion="confirmingDeletion" itemType="Asignación de producto" :deleteFunction="deleteAssigned"
            @closeModal="closeModalDoc" />

            <SuccessOperationModal :confirming="successAsignation" title="Producto asignado" message="La asignación fue exitosa" />
            <SuccessOperationModal :confirming="successAsignationLiquidate" title="Producto liquidado" message="La liquidación fue exitosa" />
            <ErrorOperationModal :showError="errorAsignation" title="Cantidad Excedida"
            message="No es una cantidad válida" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import Pagination from '@/Components/Pagination.vue'
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/outline';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';
import axios from 'axios';



const {assigned_products, warehouses, project_id} = defineProps({
    assigned_products: Object,
    warehouses: Object,
    project_id: String
})
console.log(assigned_products.data)

//Modal functions
const showModal = ref(false);
const showModalLiquidate = ref(false);

const showToAddProduct = () => {
    showModal.value = true;
}

const selectValues = ref([])

const showToLiquidate = (item) => {
    selectValues.value = item;  
    showModalLiquidate.value = true;
}

const selectedOutputProjectProductQuantity = ref(null);

const handleOutputProjectProductChange = (event) => {
    const selectedOutputProductId = event.target.value;
    formLiquidate.output_project_product_id = selectedOutputProductId;
    const selectedOutputProductArray = Object.values(selectValues.value.output_project_product);
    const selectedOutputProduct = selectedOutputProductArray.find(item => item.id == selectedOutputProductId);
    if (selectedOutputProduct) {
        selectedOutputProjectProductQuantity.value = selectedOutputProduct.quantity;
        formLiquidate.liquidated_quantity = selectedOutputProjectProductQuantity.value;      
    }
};

const closeModal = () => {
    showModal.value = false;
    form.reset()
};

const closeModalLiquidate = () => {
    showModalLiquidate.value = false;
};

//Load warehouse's product
const warehouseProducts = ref([])
const handleWarehouseChange = (e) => {
  let warehouse_id = e.target.value
  axios.get(route('projectmanagement.warehouse_products',{warehouse_id}))
    .then(response => {
        warehouseProducts.value = response.data; 
    })
    .catch(error => {
      console.error('Error al cargar la data:', error);
    });
};

//form
const successAsignation = ref(false)
const errorAsignation = ref(false)
const initialState = {
    project_id: project_id,
    product_id:'',
    quantity:'',
    observation:''
}
const form = useForm({ ...initialState })

const formLiquidate = useForm({
    output_project_product_id: '',
    liquidated_quantity: null,
    refund_quantity: null,
    observations: '',
    selectedOutputProduct: null,
})

const submit = () => {
    if (sufficientQuantity(form)){
        form.post(route('projectmanagement.products.store'), {
            onSuccess: () => {
                form.reset();
                successAsignation.value = true
                setTimeout(() => {
                    successAsignation.value = false
                },2000)
            }
        })
    } else{
        errorAsignation.value = true
        setTimeout(() => {
            errorAsignation.value = false
        },1500)
    }
}
const successAsignationLiquidate = ref(false);

const submitLiquidate = () => {
    formLiquidate.post(route('projectmanagement.productsLiquidate', { project_id: project_id }), {
        onSuccess: () => {
            formLiquidate.reset();
            successAsignationLiquidate.value = true
            setTimeout(() => {
                successAsignationLiquidate.value = false
                closeModalLiquidate();
            },2000)
         }
    })
}

//formatear fecha and check quantity
function formatearFecha(fecha) {
  const date = new Date(fecha);
  const dia = date.getDate().toString().padStart(2, '0');
  const mes = (date.getMonth() + 1).toString().padStart(2, '0');
  const año = date.getFullYear();
  return `${dia}/${mes}/${año}`;
}

const sufficientQuantity = (form) => {
    let product = warehouseProducts.value.find((i) => i.id == form.product_id)
    return form.quantity <= product.total_available;
}


//delete
const confirmingDeletion = ref(false);
const assignedToDelete = ref(null);
const confirmDelete= (assigned_id) => {
    assignedToDelete.value = assigned_id;
    confirmingDeletion.value = true;
};
const closeModalDoc = () => {
    confirmingDeletion.value = false;
};
const deleteAssigned = () => {
    const assigned_id = assignedToDelete.value;
    if (assigned_id) {
      router.delete(route('projectmanagement.products.delete', { assigned: assigned_id }), {
        onSuccess: () => {
            closeModalDoc()
        }
      });
    }
  };

</script>