<template>
    <Head title="Proyectos" />
    <AuthenticatedLayout>
        <template #header>
            Productos a Liquidar
        </template>

        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <div>
            </div>
            <table class="w-full whitespace-no-wrap">
                <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
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
                    <template v-for="item in assigned_products.data">
                    <template v-if="item.state === 'Completo'  && item.output_project_product.length > 0">
                        <tr :key="item.id" :class="[
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
                                        <button type="button" @click="showToLiquidate(item)"
                                            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500 ">
                                            Liquidar
                                        </button>
                                </div>
                            </td>
                        </tr>
                    </template>
                </template>

                </tbody>
            </table>
            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="assigned_products.links" />
            </div>
        </div>
        <br>
        <br>
        <hr class="border-t-2 border-gray-400">
        <br>
        <p class="text-lg font-semibold text-gray-600">Historial de Liquidaciones</p>

        <div class="inline-block min-w-full overflow-hidden rounded-lg shadow">
            <div class="talwing mt-4">
                <table class="w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Proucto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad Liquidada
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad Devuelta
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad Usada
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Observaciones
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in liquidations.data" :key="item.id" :class="[
                            'text-gray-700',
                            {
                                'border-l-4': true,
                            }
                        ]">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.product?.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.liquidated_quantity }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.liquidated_quantity - item.refund_quantity }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.refund_quantity }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.observations }}</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>
            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="liquidations.links" />
            </div>

        <div class="mt-6 flex items-center justify-between gap-x-6">
            <a :href="route('projectmanagement.index')"
                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                Atras
            </a>
        </div>

        <Modal :show="showModalLiquidate">
            <form class="p-6" @submit.prevent="submitLiquidate">
                <h2 class="text-lg font-medium text-gray-900">
                    Liquidacion del producto
                </h2>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">

                    <div class="sm:col-span-3">
                        <InputLabel for="resource_id" class="font-medium leading-6 text-gray-900">Seleccionar salida
                        </InputLabel>
                        <div class="mt-2">
                            <select required id="resource_id" v-model="form.output_project_product_id"
                                @change="handleOutputProjectProductChange"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled selected value="">Seleccione uno</option>
                                <option v-for="(item, index) in selectValues.output_project_product" :key="item.id"
                                    :value="item.id">Cantidad de Salida: {{ item.quantity }}</option>
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <InputLabel for="liquidated_quantity" class="font-medium leading-6 text-gray-900">Cantidad liquidada
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
                            <TextInput id="refund_quantity" type="number" min="0" :max="formLiquidate.liquidated_quantity"
                                v-model="formLiquidate.refund_quantity"
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
        <SuccessOperationModal :confirming="successAsignationLiquidate" title="Producto liquidado"
            message="La liquidación fue exitosa" />
   
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Pagination from '@/Components/Pagination.vue'
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';

const { assigned_products, warehouses, project_id } = defineProps({
    assigned_products: Object,
    warehouses: Object,
    project_id: String,
    liquidations: Object,
})
const showModalLiquidate = ref(false);

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


const closeModalLiquidate = () => {
    showModalLiquidate.value = false;
};

const initialState = {
    project_id: project_id,
    product_id: '',
    quantity: '',
    observation: '',
    total_price: null
}
const form = useForm({ ...initialState })

const formLiquidate = useForm({
    output_project_product_id: '',
    liquidated_quantity: null,
    refund_quantity: null,
    observations: '',
    selectedOutputProduct: null,
})

const successAsignationLiquidate = ref(false);

const submitLiquidate = () => {
    formLiquidate.post(route('projectmanagement.productsLiquidate', { project_id: project_id }), {
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

//formatear fecha and check quantity
function formatearFecha(fecha) {
    const date = new Date(fecha);
    const dia = date.getDate().toString().padStart(2, '0');
    const mes = (date.getMonth() + 1).toString().padStart(2, '0');
    const año = date.getFullYear();
    return `${dia}/${mes}/${año}`;
}

</script>