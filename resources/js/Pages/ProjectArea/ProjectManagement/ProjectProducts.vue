<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="backUrl">
        <template #header>
            Productos asignados para Proyecto
        </template>

        <div class="min-w-full overflow-hidden rounded-lg shadow">
            <div>
                <PrimaryButton v-if="project.status === null " type="button" @click="showToAddProduct">+ Agregar</PrimaryButton>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Área
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Zona
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Almacén
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Còdigo Producto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Producto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Precio Unitario
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
                                Estado
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Observaciones
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Fecha
                            </th>
                            <th v-if="project.status === null"
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in assigned_products.data" :key="item.id" 
                            :class="[
                                'text-gray-700',
                                {
                                    'border-l-8': true,
                                    'border-green-500': item.remaining_quantity == 0,
                                    'border-red-500': item.remaining_quantity > 0
                                }
                            ]"
                        >
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ item.area
                                    }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ item.zone
                                    }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ item.entry_id
                                        ? item.entry.inventory.warehouse.name
                                        : item.special_inventory.warehouse.name
                                    }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ item.entry_id 
                                        ? item.entry.inventory.purchase_product.code 
                                        : item.special_inventory.purchase_product.code }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ item.entry_id 
                                        ? item.entry.inventory.purchase_product.name 
                                        : item.special_inventory.purchase_product.name }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-nowrap" :class="!item.unitary_price ? 'text-center' : ''">
                                    {{ item.unitary_price ? 'S/. ' + item.unitary_price : '-' }}
                                </p>
                            </td>

                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.quantity }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.current_output_quantity }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ item.state === null 
                                        ? 'Pendiente' 
                                        : (item.state == true ? 'Aprobado' : 'Rechazado') }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ formatearFecha(item.created_at) }}</p>
                            </td >
                            <td v-if="project.status === null" class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button v-if="item.is_editable" type="button" @click="doUpdateAssignation(item.id)"
                                        class="text-red-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class=" text-lime-500 w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                        </svg>

                                    </button>
                                    <button v-if="auth.user.role_id === 1 && item.is_deletable" type="button"
                                        @click="confirmDelete(item.id)" class="text-red-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="text-red-500 w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>

                                    </button>

                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="assigned_products.links" />
            </div>
        </div>
        <Modal :show="showModal">
            <form class="p-6" @submit.prevent="submit">
                <h2 class="text-lg font-medium text-gray-900">
                    Solo productos disponibles
                </h2>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">

                    <div class="sm:col-span-3">
                        <InputLabel for="warehouse">Área</InputLabel>
                        <div class="mt-2">
                            <select 
                                disabled
                                v-model="form.area"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled selected value="">Seleccione un área</option>
                                <option>PINT</option>
                                <option>PEXT</option>
                            </select>
                            <InputError :message="form.errors.area" />
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <InputLabel for="warehouse">Zona</InputLabel>
                        <div class="mt-2">
                            <select 
                                required
                                v-model="form.zone"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled selected value="">Seleccione una zona</option>
                                <option>Arequipa</option>
                                <option>Chala</option>
                                <option>Moquegua</option>
                                <option>Tacna</option>
                                <option>MDD</option>
                            </select>
                            <InputError :message="form.errors.zone" />
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <InputLabel for="warehouse">Almacenes</InputLabel>
                        <div class="mt-2">
                            <select required id="warehouse" @change="product_warehouse($event.target.value)"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled selected value="">Seleccione Almacen</option>
                                <option v-for="item in warehouses" :key="item.id" :value="item.id">{{ item.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <div class="flex justify-between items-center">
                            <InputLabel for="product_id">Producto</InputLabel>
                            <InputLabel class="font-medium leading-6 text-gray-900" v-if="form.special_inventory_id">{{
        warehouseProducts?.find(i => i.id === form.special_inventory_id)?.quantity_available }}</InputLabel>
                        </div>
                        <div class="mt-2" v-if="warehouseProductsfirst">
                            <select required id="product_id" v-model="form.special_inventory_id"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled value="">Seleccione especial</option>
                                <option v-for="item in warehouseProducts" :key="item.id" :value="item.id">
                                    {{ item.purchase_product.name }}
                                </option>
                            </select>
                        </div>


                        <div class="mt-2" v-else>
                            <select required id="product_id" v-model="form.normal_inventory_id"
                                @change="product_inventory($event.target.value)"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled value="">Seleccione normal</option>
                                <option v-for="item in warehouseProducts" :key="item.id" :value="item.id">
                                    {{ item.purchase_product.name }}
                                </option>
                            </select>
                        </div>


                    </div>

                    <div class="sm:col-span-3" v-if="warehouseInventory.length != 0 && !warehouseInventory.cpe">
                        <div class="flex justify-start items-center">
                            <InputLabel for="inventory_id">Inventario</InputLabel>
                        </div>
                        <div class="mt-2">
                            <select required id="inventory_id" v-model="form.entry_id"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled value="">Seleccione uno</option>
                                <option v-for="item in warehouseInventory" :key="item.id" :value="item.id">
                                    {{ item.inventory.purchase_product.name }} -
                                    {{ item.quantity_available }} -
                                    S/ {{ item.unitary_price }}
                                </option>
                            </select>
                            <InputError :message="form.errors.entry_id" />
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <InputLabel for="quantity">Cantidad</InputLabel>
                        <div class="mt-2">
                            <TextInput id="quantity" type="number" min="1" v-model="form.quantity"
                                :max="form.entry_id ? warehouseInventory?.find(i => i.id === form.entry_id)?.quantity_available : warehouseProducts?.find(i => i.id === form.special_inventory_id)?.quantity_available" />
                            </div>
                            <InputError :message="form.errors.quantity" />
                    </div>

                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="closeModal"> Cerrar </SecondaryButton>
                    <PrimaryButton type="submit"> Agregar </PrimaryButton>
                </div>
            </form>
        </Modal>


        <ConfirmDeleteModal :confirmingDeletion="confirmingDeletion" itemType="Asignación de producto"
            :deleteFunction="deleteAssigned" @closeModal="closeModalDoc" />

        <ConfirmateModal :showConfirm="showModalUpdateAssignation" tittle="Anulación de salidas"
            text="La asignación del producto no tendrá más salidas" :actionFunction="updateAssignatedProduct"
            @closeModal="closeUpdateModal" />

        <SuccessOperationModal :confirming="successAsignation" title="Producto asignado"
            message="La asignación fue exitosa" />
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
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import ConfirmateModal from '@/Components/ConfirmateModal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import InputError from '@/Components/InputError.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';

const { assigned_products, warehouses, project_id, project } = defineProps({
    assigned_products: Object,
    warehouses: Object,
    project_id: Number,
    project: Object,
    auth: Object
})

let backUrl = project.status === null 
                ? 'projectmanagement.index' 
                : project.status == true 
                    ? 'projectmanagement.historial'
                    : 'projectmanagement.index' 

//Modal functions
const showModal = ref(false);

const showToAddProduct = () => {
    showModal.value = true;
}

const closeModal = () => {
    form.reset()
    warehouseProducts.value = [];
    warehouseInventory.value = [];
    showModal.value = false;
    
};

//Load warehouse's product
const warehouseProductsfirst = ref(null);
const warehouseProducts = ref([])
const warehouseInventory = ref([])
const product_warehouse = async (warehouse) => {
    form.special_inventory_id = null
    form.entry_id = null
    form.normal_inventory_id = null
    warehouseProducts.value = []
    warehouseInventory.value = []
    const res = await axios.get(route('projectmanagement.warehouse_products', { project: project_id, warehouse: warehouse }))
    warehouseProducts.value = res.data.products;
    warehouseProductsfirst.value = warehouseProducts.value[0]?.cpe;
};

const product_inventory = async (inventory) => {
    const res = await axios.get(route('projectmanagement.inventory_products', { inventory: inventory }))
    warehouseInventory.value = res.data.inventory;
};

//form
const successAsignation = ref(false)
const errorAsignation = ref(false)

const form = useForm({
    project_id: project_id,
    special_inventory_id: null,
    quantity: null,
    unitary_price: null,
    entry_id: null,
    zone: "",
    area: "PINT",
    normal_inventory_id: null
});


const submit = () => {
    form.post(route('projectmanagement.products.store'), {
        onSuccess: () => {
            closeModal();
            successAsignation.value = true
            warehouseProducts.value = []

            setTimeout(() => {
                successAsignation.value = false
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



//delete
const confirmingDeletion = ref(false);
const assignedToDelete = ref(null);
const confirmDelete = (assigned_id) => {
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
    router.put(route('projectmanagement.products.update', {
        project_product: idItemToUpdate.value
    }), null, {
        onSuccess: () => {
            showModalUpdateAssignation.value = false
        }
    })
}


</script>