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
                                    <button v-if="item.is_editable" type="button" @click="doUpdateAssignation(item.id)"
                                        class="text-red-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class=" text-lime-500 w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                        </svg>

                                    </button>
                                    <button v-if="item.is_deletable" type="button" @click="confirmDelete(item.id)"
                                        class="text-red-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="text-red-500 w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>

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
                            <InputLabel for="almacen_id" class="font-medium leading-6 text-gray-900">Almacenes
                            </InputLabel>
                            <div class="mt-2">
                                <select required id="almacen_id" @change="handleWarehouseChange"
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
                            <InputLabel for="total_price" class="font-medium leading-6 text-gray-900">Precio a descontar en Proyecto</InputLabel>
                            <div class="mt-2">
                                <TextInput id="total_price" type="number" min="1" step="0.01" v-model="form.total_price"
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
                                <TextInput id="refund_quantity" type="number" min="0" :max="formLiquidate.liquidated_quantity" v-model="formLiquidate.refund_quantity"
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

            <ConfirmateModal :showConfirm="showModalUpdateAssignation" tittle="Anulación de salidas"  text="La asignación del producto no tendrá más salidas" :actionFunction="updateAssignatedProduct"
            @closeModal="closeUpdateModal" />

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
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import ConfirmateModal from '@/Components/ConfirmateModal.vue';
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
    observation:'',
    total_price:null
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
                warehouseProducts.value = []
                let almacen_select = document.getElementById('almacen_id')
                almacen_select.value = ""
                setTimeout(() => {
                    successAsignation.value = false
                },1500)
                
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

const showModalUpdateAssignation = ref(false)
const idItemToUpdate = ref(null)

const doUpdateAssignation = (id) => {
    idItemToUpdate.value = id
    showModalUpdateAssignation.value = true
}
const closeUpdateModal = () => {
    showModalUpdateAssignation.value = false
}
const updateAssignatedProduct = () => {
    router.put(route('projectmanagement.products.update',{
        project_product: idItemToUpdate.value
    }),null,{
        onSuccess: () => {
            showModalUpdateAssignation.value = false
        }
    })
}


</script>