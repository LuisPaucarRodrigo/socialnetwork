<template>

    <Head title="Agregar Solicitud" />
    <AuthenticatedLayout
        :redirectRoute="project ? { route: 'projectmanagement.purchases_request.index', params: { id: project.id } } : 'purchasesrequest.index'">
        <template #header>
            {{ purchase ? purchase.code : (project ? 'Nueva solicitud de compra para:' : 'Nueva solicitud de compra') }}
        </template>
        <p class="mb-5 text-xl" v-if="project">{{ project.code }}</p>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12 shadow-sm p-4 ring-1 ring-gray-200 rounded-lg">
                    <h2 class="text-base font-semibold leading-7 text-gray-900 mb-6 border-b border-gray-300">
                        Informacion
                        básica</h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 ">
                        <div class="sm:col-span-3">
                            <InputLabel for="title" class="font-medium leading-6 text-gray-900">
                                Titulo de Solicitud
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.title" id="title" autocomplete="family-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.title" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="due_date" class="font-medium leading-6 text-gray-900">Fecha Limite de
                                Compra
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.due_date" id="due_date" maxlength="9"
                                    autocomplete="product_description-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.due_date" />
                            </div>
                        </div>

                        <div v-if="purchase" class="sm:col-span-3 sm:col-start-1">
                            <InputLabel for="code" class="font-medium leading-6 text-gray-900">
                                Estado
                            </InputLabel>
                            <div class="mt-2">
                                <span
                                    :class="`uppercase inline-flex items-center gap-x-1 py-0 px-3 text-sm rounded-full font-medium bg-indigo-600 text-white`">
                                    {{ purchase.state }}
                                </span>
                            </div>
                        </div>

                        <div v-if="!project && !purchase" class="sm:col-span-6 sm:col-start-1">
                            <div class="flex items-center justify-between w-full">
                                <div>
                                    <InputLabel for="resorceOrProduct"
                                        class="sm:text-sm font-medium leading-6 text-gray-900">
                                        ¿Producto o Activos?
                                    </InputLabel>
                                    <div class="mt-2 flex gap-4">
                                        <label class="flex gap-2 items-center">
                                            Productos
                                            <input type="radio" v-model="resorceOrProduct" id="resorceOrProduct"
                                                :value="true"
                                                class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                        </label>
                                        <label class="flex gap-2 items-center">
                                            Activos
                                            <input type="radio" v-model="resorceOrProduct" id="resorceOrProduct"
                                                :value="false"
                                                class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                        </label>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <InputLabel for="code" class="font-bold leading-6 text-indigo-700 py-2">
                                        {{ 'El código se generará de forma automática' }}
                                    </InputLabel>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-1 sm:col-span-6 xl:col-span-4 mt-2">
                            <div class="flex gap-2 items-center mt-2">
                                <h2 class="text-base font-bold leading-6 text-gray-900 ">
                                    Añadir {{ resorceOrProduct ? 'Producto' : 'Activo' }}
                                </h2>
                                <button v-if="auth.user.role_id === 1 || purchase.purchase_quotes === null"
                                    type="button" @click="showProductModal = !showProductModal">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="indigo" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                    </svg>
                                </button>
                            </div>
                            <div class="mt-2">
                                <div class="overflow-x-auto mt-8">
                                    <table class="w-full">
                                        <thead>
                                            <tr
                                                class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                    #
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                    Código
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                    Nombre del producto
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                    Cantidad
                                                </th>
                                                <th
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                    Unidad
                                                </th>
                                                <th v-if="auth.user.role_id === 1 || purchase.purchase_quotes === null"
                                                    class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                                    Acciones
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(item, index) in (form.products)" :key="index"
                                                class="text-gray-700 hover:bg-gray-200 bg-white">
                                                <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                                    <p>
                                                        {{ index + 1 }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                                    <p>
                                                        {{ item.code }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                                    <p class="text-gray-900">
                                                        {{ item.name }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                                    <p class="text-gray-900">
                                                        {{ purchase ? item.pivot?.quantity : item.quantity }}
                                                    </p>
                                                </td>
                                                <td class="border-b border-gray-200 px-5 py-5 text-sm">
                                                    <p class="text-gray-900">
                                                        {{ item.unit }}
                                                    </p>
                                                </td>
                                                <td v-if="auth.user.role_id === 1 || purchase.purchase_quotes === null"
                                                    class="border-b border-gray-200 px-5 py-5 text-sm">
                                                    <div @click=" deleteProduct(index, item.pivot?.id)"
                                                        class="flex justify-center">
                                                        <button type="button" class="col-span-1 flex justify-end">
                                                            <TrashIcon class=" text-red-500 h-4 w-4 " />
                                                        </button>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div><br>
                    </div>
                    <br>
                    <InputError :message="form.errors.products" />
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Guardar
                </button>
            </div>
        </form>
        <Modal :show="showProductModal">
            <form class="p-6" @submit.prevent="addProduct">
                <h2 class="text-lg font-medium text-gray-900">
                    Añadir {{ resorceOrProduct ? 'Producto' : 'Activo' }}
                </h2>
                <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                    <div v-if="!form.products.length" class="sm:col-span-3">
                        <InputLabel for="type_product" class="font-medium leading-6 text-gray-900">
                            Tipo de {{ resorceOrProduct ? 'Producto' : 'Activo' }}
                        </InputLabel>
                        <div v-if="resorceOrProduct" class="mt-2">
                            <select required id="type_product" v-model="type_product"
                                @change="handleTypeProduct($event.target.value)"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled value="">Seleccione tipo</option>
                                <option v-for="item in typeProduct" :key="item.id" :value="item.name">
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>

                        <div v-else class="mt-2">
                            <select required id="type_product" v-model="type_product"
                                @change="handleTypeResource($event.target.value)"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                <option disabled value="">Seleccione tipo</option>
                                <option v-for="item in resourceType" :key="item.id" :value="item.id">
                                    {{ item.name }}
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <InputLabel for="unit" class="font-medium leading-6 text-gray-900">
                            {{ resorceOrProduct ? 'Producto' : 'Activo' }}
                        </InputLabel>
                        <div class="mt-2">
                            <input required id="unit" list="options" @input="handleAutocomplete" autocomplete="off"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />

                            <datalist id="options">
                                <option v-for="item in product_selected" :value="item.code" :data-value="item">
                                    {{ item.name }}
                                </option>
                            </datalist>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <InputLabel for="quantity" class="font-medium leading-6 text-gray-900">Cantidad
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput required type="number" v-model="productToAdd.quantity" min="1" id="quantity"
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <InputLabel class="leading-6 text-gray-100">Nombre:
                        </InputLabel>
                        <div class="mt-2">
                            <InputLabel class="leading-6 text-gray-100">{{ productToAdd.name }}
                            </InputLabel>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <InputLabel class="leading-6 text-gray-100">Unidad:
                        </InputLabel>
                        <div class="mt-2">
                            <InputLabel class="leading-6 text-gray-100">{{ productToAdd.unit }}
                            </InputLabel>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="closeModal"> Cerrar </SecondaryButton>
                    <PrimaryButton type="submit"> Agregar </PrimaryButton>
                </div>
            </form>
        </Modal>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="solicitud" />
        <SuccessOperationModal :confirming="showModal2" title="Producto añadido"
            message="Se añadió un nuevo producto a la solicitud" />
        <SuccessOperationModal :confirming="showModal3" title="Producto eliminado"
            message="Se quitó el producto de la solicitud" />
        <ErrorOperationModal :showError="showErroModal" title="Error"
            message="El producto ya fue añadido o es inválido" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue'
import { Head, useForm, router } from '@inertiajs/vue3';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
import { TrashIcon } from '@heroicons/vue/24/outline';
import { ref, watch } from 'vue';

const showModal = ref(false);
const showModal2 = ref(false);
const showModal3 = ref(false);
const showErroModal = ref(false);
const type_product = ref('')
const product_selected = ref([]);
const resorceOrProduct = ref(true);

const { purchase, allProducts, project, typeProduct, resourceType } = defineProps({
    purchase: {
        type: Object,
        requerid: false
    },
    allProducts: Object,
    auth: Object,
    project: Object,
    typeProduct: Object,
    resourceType: Object
})

function handleTypeProduct(product_value) {
    product_selected.value = allProducts.filter(product => product.type_product === product_value);
}

function handleTypeResource(resource_id) {
    product_selected.value = allProducts.filter(resource => resource.resource_type_id == resource_id);
}

const initialState = {
    title: '',
    due_date: '',
    code: '',
    products: [],
    project_id: project?.id
}

const form = useForm(purchase ? JSON.parse(JSON.stringify(purchase)) : { ...initialState })

watch(resorceOrProduct, () => {
    form.products = []
});

const submit = () => {
    if (purchase) {
        form.put(route('purchasesrequest.update', purchase.id), {
            onSuccess: () => {
                project ? router.visit(route('projectmanagement.purchases_request.index', { project_id: project.id })) :
                    router.visit(route('purchasesrequest.index'))
            }
        })
    } else {
        form.post(route('purchasesrequest.store'), {
            onSuccess: () => {
                showModal.value = true
                setTimeout(() => {
                    showModal.value = false;
                    project ? router.visit(route('projectmanagement.purchases_request.index', { project_id: project.id })) :
                        router.visit(route('purchasesrequest.index'))
                }, 2000);
            },
            onError: () => {
                alert('Ha ocurrido un error. Por favor, inténtelo de nuevo.');
            }
        })
    }
}

//Modal
const showProductModal = ref(false)
const initialStateProduct = {
    id: '',
    code: '',
    name: '',
    unit: '',
    quantity: '',
    pivot: {}
}
const productToAdd = ref(JSON.parse(JSON.stringify(initialStateProduct)))


function closeModal() {
    showProductModal.value = false
    productToAdd.value = JSON.parse(JSON.stringify(initialStateProduct))
}


const formProduct = useForm({
    purchase_product_id: '',
    purchasing_request_id: '',
    quantity: '',
})

function addProduct() {
    if (productToAdd.value.id && form.products.find(item => item.id == productToAdd.value.id) == undefined) {
        if (purchase) {
            formProduct.purchase_product_id = productToAdd.value.id
            formProduct.purchasing_request_id = purchase.id
            formProduct.quantity = productToAdd.value.quantity
            axios.post(route('purchasing_request_product.store'), formProduct)
                .then((response) => {
                    if (response.status === 200) {
                        productToAdd.value.pivot.quantity = productToAdd.value.quantity
                        productToAdd.value.pivot.id = response.data.newProductId
                        form.products.push(JSON.parse(JSON.stringify(productToAdd.value)))
                        closeModal()
                        showModal2.value = true
                        setTimeout(() => {
                            showModal2.value = false
                        }, 1000)
                    }
                })
                .catch(error => console.error(error));
        } else {
            form.products.push(JSON.parse(JSON.stringify(productToAdd.value)))
            closeModal()
        }
    } else {
        showErroModal.value = true
        setTimeout(() => {
            showErroModal.value = false
        }, 1000)
    }
}




const handleAutocomplete = (e) => {
    const code = e.target.value;
    let findedProduct = product_selected.value.find(item => item.code === code)
    if (findedProduct) {
        productToAdd.value.id = findedProduct.id
        productToAdd.value.code = findedProduct.code
        productToAdd.value.name = findedProduct.name
        productToAdd.value.unit = findedProduct.unit
    }
}

function deleteProduct(index, id = null) {
    if (id) router.delete(route('purchasing_request_product.delete', { purchasing_request_product_id: id }), {
        onSuccess: () => {
            showModal3.value = true
            setTimeout(() => {
                showModal3.value = false
            }, 1000)
        }
    })
    form.products.splice(index, 1)
}

// Observador para form.products
watch(form.products, (newProducts) => {
    // Obtener los IDs de todos los productos
    const productIds = newProducts.map(product => product.id);
    console.log("Esto", form.products)
    console.log("Estos son los id", productIds)
    axios.post('/shopping_area/existing/products', { product_ids: productIds })
        .then(response => {
            // Manejar la respuesta si es necesario
        })
        .catch(error => {
            console.error('Error al hacer la petición:', error);
        });
});
</script>