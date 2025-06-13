<template>
    <Modal :show="create_product || showModalEdit">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{ create_product ? 'Agregar Producto' : 'Actualizar Producto' }}
            </h2>
            <form @submit.prevent="create_product ? submit() : submitEdit()">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">

                        <div class="mt-2">
                            <InputLabel for="name">Nombre</InputLabel>
                            <div class="mt-2">
                                <TextInput :to-uppercase="true" type="text" v-model="form.name" id="name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.name" />
                            </div>
                        </div>

                        <div class="mt-2">
                            <InputLabel for="unit">Unidad</InputLabel>
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

                        <div v-if="create_product" class="mt-2">
                            <InputLabel for="type">Tipo</InputLabel>
                            <div class="mt-2">
                                <select :to-uppercase="true" v-model="form.type" id="type"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Seleccione una opción</option>
                                    <option value="Producto">Producto</option>
                                    <option value="Activo">Activo</option>
                                </select>
                                <InputError :message="form.errors.type" />
                            </div>
                        </div>

                        <div class="mt-2" v-if="form.type === 'Producto'">
                            <div class="flex mt-3 gap-2">
                                <InputLabel for="type_product">
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
                                    <option v-for="item in type_product" :key="item.id" :value="item.name">
                                        {{ item.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.type_product" />
                            </div>
                        </div>

                        <div v-if="form.type === 'Activo'">
                            <div class="flex mt-3 gap-2">
                                <InputLabel for="resource_type">
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
                                    <option v-for="item in resource_type" :key="item.id" :value="item.id">
                                        {{ item.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.resource_type" />
                            </div>
                        </div>

                        <div class="mt-2">
                            <InputLabel for="description">
                                Descripción
                            </InputLabel>
                            <div class="mt-2">
                                <textarea type="text" v-model="form.description" id="description"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.description" />
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-x-3">
                            <SecondaryButton @click="create_product ? closeModal() : closeEditModal()"> Cancelar
                            </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                                {{ create_product ? 'Guardar' : 'Actualizar' }}
                            </PrimaryButton>
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
                    <InputLabel for="name">Nombre</InputLabel>
                    <div class="mt-2">
                        <TextInput id="name" required :to-uppercase="true"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                            v-model="formname.name" />
                        <InputError :message="formname.errors.name" />
                    </div>
                </div>
            </div>
            <div class="mt-2" v-if="form.type === 'Activo'">
                <InputLabel for="depreciation_value">
                    Valor de Depreciacion (%)
                </InputLabel>
                <div class="mt-2">
                    <input type="number" v-model="formname.depreciation_value" id="depreciation_value" min="0"
                        step="0.01" max="100"
                        class=",t-2 block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    <InputError :message="formname.errors.depreciation_value" />
                </div>

                <InputLabel class="mt-2" for="timelife">
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

</template>
<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify, notifyError } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { setAxiosErrors } from '@/utils/utils';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { userPermissions, type_product, resource_type, products } = defineProps({
    userPermissions: Object,
    type_product: Object,
    resource_type: Object,
    products: Object
});

const showModalEdit = ref(false);
const editingProduct = ref(null);
const showModalAdd = ref(false);
const create_product = ref(false);

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

const openCreateProduct = () => {
    create_product.value = true;
}

const closeModal = () => {
    create_product.value = false;
    form.reset();
}

const closeEditModal = () => {
    showModalEdit.value = false;
    form.reset();
}

async function submit() {
    let url = route('inventory.purchaseproducts.store')
    try {
        let response = await axios.post(url, form)
        updateFrontEnd(response.data, 'create')
    } catch (error) {
        console.error(error)
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form)
            } else {
                notifyError("Server error:", error.response.data)
            }
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

async function submitEdit() {
    form.type_product = form.type == 'Producto' ? form.type_product : null
    let url = route('inventory.purchaseproducts.update', { purchase_product: form.id })
    try {
        let response = await axios.post(url, form)
        updateFrontEnd(response.data, 'update', form.id)
    } catch (error) {
        console.error(error)
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form)
            } else {
                notifyError("Server error:", error.response.data)
            }
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

async function submitTypeProduct() {
    let url = form.type === 'Producto' ? route('inventory.purchaseproducts.typeProduct') : route('inventory.purchaseproducts.resourceType')
    try {
        let response = await axios.post(url, { ...formname.data() })
        updateFrontEnd(response.data, 'createPOA')
    } catch (error) {
        console.error(error)
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form)
            } else {
                notifyError("Server error:", error.response.data)
            }
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

function add_product() {
    showModalAdd.value = true
}

function add_resource() {
    showModalAdd.value = true
}

function closeAddModal() {
    showModalAdd.value = false
    formname.reset()
}

function updateFrontEnd(response, action, itemId = null) {
    const validations = products.data || products
    if (action === 'create') {
        validations.unshift(response)
        closeModal();
        notify("Creacion existosa")
    } else if (action === 'update') {
        let index = validations.findIndex(item => item.id == itemId)
        validations[index] = response
        closeEditModal();
        notify("Actualizacion exitosa")
    } else if (action === 'createPOA') {
        form.type === 'Producto' ? type_product.push({ ...response }) : resource_type.push({ ...response })
        closeAddModal()
        notify("Creacion de producto o activo exitoso")
    }
}

defineExpose({ openEditProductModal, openCreateProduct })
</script>