<template>

    <Head title="Agregar Solicitud" />
    <AuthenticatedLayout
        :redirectRoute="project ? { route: 'projectmanagement.purchases_request.index', params: { project_id: project.id } } : 'purchasesrequest.index'">
        <template #header>
            {{ purchase ? purchase.code : (project ? 'Nueva solicitud de compra para:' : 'Nueva solicitud de compra') }}
        </template>
        <p class="mb-5 text-xl" v-if="project">{{ project.code }}</p>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12 shadow-sm p-4 ring-1 ring-gray-200 rounded-lg">
                    <h2 class="text-base font-semibold leading-7 text-gray-900 mb-6 border-b border-gray-300">
                        Informacion básica
                    </h2>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6 ">

                        <div class="sm:col-span-3">
                            <InputLabel for="title">Titulo de Solicitud</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.title" id="title" autocomplete="off" />
                                <InputError :message="form.errors.title" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="due_date">Fecha Limite de Compra</InputLabel>
                            <div class="mt-2">
                                <TextInput type="date" v-model="form.due_date" id="due_date" maxlength="9"
                                    autocomplete="off" />
                                <InputError :message="form.errors.due_date" />
                            </div>
                        </div>

                        <div v-if="purchase" class="sm:col-span-3 sm:col-start-1">
                            <InputLabel for="code">Estado</InputLabel>
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
                                    <InputLabel for="resorceOrProduct">¿Productos o Activos?</InputLabel>
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
                                    <InputLabel for="code" class="font-bold">El código se generará de forma automática
                                    </InputLabel>
                                </div>
                            </div>
                        </div>

                        <div class="col-span-1 sm:col-span-6 xl:col-span-4 mt-2">
                            <div class="flex gap-2 items-center mt-2">
                                <h2 class="text-base font-bold leading-6 text-gray-900 ">
                                    Añadir {{ resorceOrProduct === true ? 'Producto' : 'Activo' }}
                                </h2>
                                <button v-if="auth.user.role_id === 1 || !purchase || purchase.purchase_quotes === null"
                                    type="button" @click="openModal()">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="indigo" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M9 3.75H6.912a2.25 2.25 0 0 0-2.15 1.588L2.35 13.177a2.25 2.25 0 0 0-.1.661V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18v-4.162c0-.224-.034-.447-.1-.661L19.24 5.338a2.25 2.25 0 0 0-2.15-1.588H15M2.25 13.5h3.86a2.25 2.25 0 0 1 2.012 1.244l.256.512a2.25 2.25 0 0 0 2.013 1.244h3.218a2.25 2.25 0 0 0 2.013-1.244l.256-.512a2.25 2.25 0 0 1 2.013-1.244h3.859M12 3v8.25m0 0-3-3m3 3 3-3" />
                                    </svg>
                                </button>
                            </div>
                            <TableStructure>
                                <template #thead>
                                    <tr>
                                        <TableTitle>#</TableTitle>
                                        <TableTitle>Código</TableTitle>
                                        <TableTitle>Nombre del producto</TableTitle>
                                        <TableTitle>Cantidad</TableTitle>
                                        <TableTitle>Unidad</TableTitle>
                                        <TableTitle></TableTitle>
                                    </tr>
                                </template>
                                <template #tbody>
                                    <tr v-for="(item, index) in (form.products)" :key="index">
                                        <TableRow>{{ index + 1 }}</TableRow>
                                        <TableRow>{{ item.code }}</TableRow>
                                        <TableRow>{{ item.name }}</TableRow>
                                        <TableRow>{{ purchase ? item.pivot?.quantity : item.quantity }}</TableRow>
                                        <TableRow>{{ item.unit }}</TableRow>
                                        <TableRow
                                            v-if="auth.user.role_id === 1 || !purchase || purchase.purchase_quotes === null">
                                            <button @click=" deleteProduct(index, item.pivot?.id)" type="button">
                                                <DeleteIcon />
                                            </button>
                                        </TableRow>
                                    </tr>
                                </template>
                            </TableStructure>
                        </div><br>
                    </div>
                    <br>
                    <InputError :message="form.errors.products" />
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                    Guardar
                </PrimaryButton>
            </div>
        </form>
        <AddProducts ref="addProducts" v-model:resorceOrProduct="resorceOrProduct"
            v-model:product_selected="product_selected" :allProducts="allProducts" :form="form" :purchase="purchase" />
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="solicitud" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import InputError from '@/Components/InputError.vue'
import { Head, useForm, router } from '@inertiajs/vue3';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import { ref, watch } from 'vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import AddProducts from './components/AddProducts.vue';
import { notify } from '@/Components/Notification';
import { DeleteIcon } from "@/Components/Icons/Index";

const showModal = ref(false);
// const type_product = ref('')
const product_selected = ref([]);
const resorceOrProduct = ref(true);
const addProducts = ref(null)

const { purchase, allProducts, project, typeProduct, resourceType } = defineProps({
    purchase: {
        type: Object,
        requerid: false
    },
    allProducts: Object,
    auth: Object,
    project: {
        type: Object,
        requerid: false
    },
    typeProduct: Object,
    resourceType: Object
})

if (purchase) {
    product_selected.value = allProducts
    resorceOrProduct.value = allProducts[0].type === "Activo" ? false : true
}

// function handleTypeProduct(product_value) {
//     product_selected.value = allProducts.filter(product => product.type_product === product_value);
// }

// function handleTypeResource(resource_id) {
//     product_selected.value = allProducts.filter(resource => resource.resource_type_id == resource_id);
// }

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
function deleteProduct(index, id = null) {
    if (id) router.delete(route('purchasing_request_product.delete', { purchasing_request_product_id: id }), {
        onSuccess: () => {
            notify("Se quitó el producto de la solicitud")
            // showModal3.value = true
            // setTimeout(() => {
            //     showModal3.value = false
            // }, 1000)
        }
    })
    form.products.splice(index, 1)
}

// Observador para form.products
watch(form.products, (newProducts) => {
    // Obtener los IDs de todos los productos
    const productIds = newProducts.map(product => product.id);
    axios.post('/shopping_area/existing/products', { product_ids: productIds })
        .then(response => {
            // Manejar la respuesta si es necesario
        })
        .catch(error => {
            console.error('Error al hacer la petición:', error);
        });
});

function openModal() {
    addProducts.value.openModal()
}
</script>