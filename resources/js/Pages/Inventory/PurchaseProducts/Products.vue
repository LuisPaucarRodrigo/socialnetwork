<template>

    <Head title="Productos" />
    <AuthenticatedLayout :redirectRoute="'inventory.purchaseproducts'">
        <template #header>
            Productos
        </template>
        <div class="min-w-full p-3 rounded-lg shadow">
            <div class="flex justify-between items-center gap-4">
                <div class="flex items-center flex-grow min-w-0">
                    <!-- Aplicamos flex-grow para que este div tome el espacio disponible -->
                    <button @click="openCreateProduct" type="button"
                        class="inline-flex items-center px-4 py-2 min-w-[115px] border-2 border-gray-700 rounded-md font-semibold text-xs hover:text-gray-700 uppercase tracking-widest bg-gray-700 hover:underline hover:bg-gray-200 focus:border-indigo-600 focus:outline-none focus:ring-2 text-white">
                        + Agregar
                    </button>
                </div>
                <div class="flex items-center">
                    <form @submit.prevent="search" class="flex items-center">
                        <input type="text" placeholder="Buscar..."
                            class="block w-full ml-2 rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            v-model="searchForm.searchTerm" />
                        <button type="submit" :class="{ 'opacity-25': searchForm.processing }"
                            class="ml-2 rounded-md bg-indigo-600 px-2 py-2  whitespace-no-wrap text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            <svg width="30px" height="21px" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M15.7955 15.8111L21 21M18 10.5C18 14.6421 14.6421 18 10.5 18C6.35786 18 3 14.6421 3 10.5C3 6.35786 6.35786 3 10.5 3C14.6421 3 18 6.35786 18 10.5Z"
                                    stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </button>
                    </form>
                </div>
            </div>


            <br>

            <div class="min-w-full overflow-x-auto rounded-lg shadow">
                <table class="w-full table-auto">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Nombre
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Código
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Unidad
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Tipo/Tipo de Producto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Descripción
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">

                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="item in (props.search ? props.products : props.products.data)" :key="item.id"
                            class="text-gray-700 border-b">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.code }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.unit }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">
                                    {{ item.type }} 
                                    {{ item.type === 'Producto' 
                                            ? '/' + item.type_product : item.resource_type 
                                            ? '/' + item.resource_type.name 
                                            : ''}}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ item.description }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="inline-flex justify-end gap-x-0">
                                    <button v-if="auth.user.role_id == 1" @click="openEditProductModal(item)"
                                        class="text-yellow-600 hover:underline mr-3">
                                        <PencilIcon class="h-4 w-4" />
                                    </button>
                                    <button v-if="auth.user.role_id == 1" @click="confirmDeleteProduct(item.id)"
                                        class="text-red-600 hover:underline">
                                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <circle cx="12" cy="12" r="9" stroke="red" stroke-width="2" />
                                            <path d="M18 18L6 6" stroke="red" stroke-width="2" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div v-if="props.search === undefined"
                class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="products.links" />
            </div>
        </div>

        <Modal :show="create_product || showModalEdit">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    {{ create_product ? 'Agregar Producto' : 'Actualizar Producto' }}
                </h2>
                <form @submit.prevent="create_product ? submit() : submitEdit()">
                    <div class="space-y-12">
                        <div class="border-b border-gray-900/10 pb-12">

                            <div>
                                <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                                <div class="mt-2">
                                    <TextInput :to-uppercase="true" type="text" v-model="form.name" id="name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.name" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="unit" class="font-medium leading-6 text-gray-900 mt-3">Unidad
                                </InputLabel>
                                <div class="mt-2">
                                    <select :to-uppercase="true" v-model="form.unit" id="unit"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="" disabled>Seleccione una opción</option>
                                        <option value="Unidad">Unidad</option>
                                        <option value="Metros">Metros</option>
                                        <option value="Kilos">Kilos</option>
                                        <option value="GLB">GLB</option>
                                    </select>
                                    <InputError :message="form.errors.unit" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="type" class="font-medium leading-6 text-gray-900 mt-3">
                                    Tipo
                                </InputLabel>
                                <div class="mt-2">
                                    <select :to-uppercase="true" v-model="form.type" id="type"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="" disabled>Seleccione una opción</option>
                                        <option value="Producto">Producto</option>
                                        <option value="Servicio">Servicio</option>
                                        <option value="Activo">Activo</option>
                                    </select>
                                    <InputError :message="form.errors.type" />
                                </div>
                            </div>

                            <div v-if="form.type === 'Producto'">
                                <div class="flex mt-3 gap-2">
                                    <InputLabel for="type_product" class="font-medium leading-6 text-gray-900">
                                        Tipo de Producto
                                    </InputLabel>
                                    <button type="button" @click="add_product" class="item-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="mt-2">
                                    <select :to-uppercase="true" v-model="form.type_product" id="type_product"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="" disabled>Seleccione un tipo</option>
                                        <option v-for="item in props.type_product" :key="item.id" :value="item.name">
                                            {{ item.name }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.type_product" />
                                </div>
                            </div>

                            <div v-if="form.type === 'Activo'">
                                <div class="flex mt-3 gap-2">
                                    <InputLabel for="resource_type" class="font-medium leading-6 text-gray-900">
                                        Tipo de Activo
                                    </InputLabel>
                                    <button type="button" @click="add_resource" class="item-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>

                                <div class="mt-2">
                                    <select :to-uppercase="true" v-model="form.resource_type_id" id="type_product"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="" disabled>Seleccione un tipo</option>
                                        <option v-for="item in props.resource_type" :key="item.id" :value="item.id">
                                            {{ item.name }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.resource_type" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="description" class="font-medium leading-6 text-gray-900 mt-3">
                                    Descripción
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="form.description" id="description"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.description" />
                                </div>
                            </div>

                            <div class="mt-6 flex items-center justify-end gap-x-6">
                                <SecondaryButton @click="create_product ? closeModal() : closeEditModal()"> Cancelar
                                </SecondaryButton>
                                <button type="submit" :class="{ 'opacity-25': form.processing }"
                                    class="rounded-md bg-indigo-600 px-6 py-2 whitespace-no-wrap text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                                    {{ create_product ? 'Guardar' : 'Actualizar' }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>

        <Modal :show="showModalAdd">
            <form class="p-6" @submit.prevent="submitTypeProduct">
                <h2 class="text-lg font-medium text-gray-900">
                    Nuevo tipo de {{ form.type === 'Producto' ? 'producto' : 'activo' }}
                </h2>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                    <div class="sm:col-span-6">
                        <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                        <div class="mt-2">
                            <TextInput id="name" required :to-uppercase="true"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                v-model="formname.name" />
                            <InputError :message="formname.errors.name" />
                        </div>
                    </div>

                </div>
                <div v-if="form.type === 'Activo'">
                    <InputLabel for="depreciation_value" class="font-medium leading-6 text-gray-900 mt-3">
                        Valor de Depreciacion
                    </InputLabel>
                    <div class="mt-2">
                        <input type="number" v-model="formname.depreciation_value" id="depreciation_value" 
                            min="0"
                            step="0.01"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        <InputError :message="formname.errors.depreciation_value" />
                    </div>

                    <InputLabel for="timelife" class="font-medium leading-6 text-gray-900 mt-3">
                        Tiempo de vida
                    </InputLabel>
                    <div class="mt-2">
                        <TextInput type="text" v-model="formname.timelife" id="timelife"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        <InputError :message="formname.errors.timelife" />
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="closeAddModal"> Cerrar </SecondaryButton>
                    <PrimaryButton type="submit"> Agregar </PrimaryButton>
                </div>
            </form>
        </Modal>

        <ConfirmDisableModal :confirmingDeletion="confirmingDocDeletion" itemType="Producto"
            :deleteFunction="deleteProduct" @closeModal="closeModalDoc" />
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="Producto" />
        <ConfirmUpdateModal :confirmingupdate="showConfirmEdit" itemType="Producto" />
        <SuccessOperationModal :confirming="addSuccess" title="" message="" />

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import ConfirmDisableModal from '@/Components/ConfirmDisableModal.vue';
import { ref } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { PencilIcon } from '@heroicons/vue/24/outline';
import Modal from '@/Components/Modal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const props = defineProps({
    products: Object,
    type_product: Object,
    resource_type: Object,
    auth: Object,
    search: String
});

const form = useForm({
    id: '',
    name: '',
    unit: '',
    type: '',
    type_product: '',
    description: '',
    resource_type_id: ''
});

const formname = useForm({
    name: '',
    depreciation_value: '',
    timelife: ''
})

const create_product = ref(false);
const showModal = ref(false);
const docToDelete = ref(null);
const confirmingDocDeletion = ref(false);
const showModalEdit = ref(false);
const editingProduct = ref(null);
const showConfirmEdit = ref(false);
const addSuccess = ref(false)
const showModalAdd = ref(false);

const openCreateProduct = () => {
    create_product.value = true;
}

const closeModal = () => {
    create_product.value = false;
    form.reset();
}

const openEditProductModal = (product) => {
    editingProduct.value = JSON.parse(JSON.stringify(product));
    form.id = editingProduct.value.id;
    form.name = editingProduct.value.name;
    form.description = editingProduct.value.description;
    form.unit = editingProduct.value.unit;
    form.type = editingProduct.value.type;
    form.type_product = editingProduct.value.type_product;
    form.resource_type_id = editingProduct.value.resource_type_id;
    showModalEdit.value = true;
};

const searchForm = useForm({
    searchTerm: props.search ? props.search : '',
})

const closeEditModal = () => {
    showModalEdit.value = false;
    form.reset();
}

const submit = () => {
    form.post(route('inventory.purchaseproducts.store'), {
        onSuccess: () => {
            closeModal();
            form.reset();
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                router.visit(route('inventory.purchaseproducts'))
            }, 2000);
        },
        onError: () => {
            form.reset();
        },
        onFinish: () => {
            form.reset();
        }
    });
}

const submitEdit = () => {
    form.type_product = form.type == 'Producto' ? form.type_product : null
    form.put(route('inventory.purchaseproducts.update', { purchase_product: form.id }), {
        onSuccess: () => {
            closeEditModal();
            form.reset();
            showConfirmEdit.value = true
            setTimeout(() => {
                showConfirmEdit.value = false;
                router.visit(route('inventory.purchaseproducts'))
            }, 2000);
        },
        onError: () => {
            form.reset();
        },
        onFinish: () => {
            form.reset();
        }
    });
}

const search = () => {
    if (searchForm.searchTerm == '') {
        router.visit(route('inventory.purchaseproducts'));
    } else {
        router.visit(route('inventory.purchaseproducts.search', { request: searchForm.searchTerm }));
    }
}


const confirmDeleteProduct = (productId) => {
    docToDelete.value = productId;
    confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
    confirmingDocDeletion.value = false;
};

const deleteProduct = () => {
    const docId = docToDelete.value;
    if (docId) {
        router.put(route('inventory.purchaseproducts.disable', { purchase_product: docId }), {
            onSuccess: () => closeModalDoc()
        });
    }
};

function submitTypeProduct() {
    const url = form.type === 'Producto' ? route('inventory.purchaseproducts.typeProduct'):route('inventory.purchaseproducts.resourceType')
    axios.post(url, { ...formname.data() })
        .then(response => {
            if (response.status === 200) {
                let newItem = response.data.new
                form.type === 'Producto' ? props.type_product.push({ ...newItem }) : props.resource_type.push({ ...newItem })
                closeAddModal()
                addSuccess.value = true
                setTimeout(() => {
                    addSuccess.value = false
                }, 600)
                formname.reset()
            } else {
                throw new Error('Fallo en el servidor con status ' + response.status)
            }
        })
        .catch(error => {
            console.error(error)
        })
}

function add_product() {
    showModalAdd.value = true
}

function closeAddModal() {
    showModalAdd.value = false
    formname.reset()
}

function add_resource() {
    showModalAdd.value = true
}

</script>