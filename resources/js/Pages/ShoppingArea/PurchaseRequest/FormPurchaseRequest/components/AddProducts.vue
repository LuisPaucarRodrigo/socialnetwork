<template>
    <Modal :show="showProductModal">
        <form class="p-6" @submit.prevent="addProduct">
            <h2 class="text-lg font-medium text-gray-900">
                Añadir {{ resorceOrProduct ? 'Producto' : 'Activo' }}
            </h2>
            <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                <div class="sm:col-span-3">
                    <InputLabel for="unit">{{ resorceOrProduct ? 'Producto' : 'Activo' }}</InputLabel>
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
                    <InputLabel for="quantity">Cantidad</InputLabel>
                    <div class="mt-2">
                        <TextInput required type="number" v-model="productToAdd.quantity" min="1" id="quantity" />
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <InputLabel class="leading-6 text-gray-100">Nombre:</InputLabel>
                    <div class="mt-2">
                        <InputLabel class="leading-6 text-gray-100">{{ productToAdd.name }}
                        </InputLabel>
                    </div>
                </div>

                <div class="sm:col-span-3">
                    <InputLabel class="leading-6 text-gray-100">Unidad:</InputLabel>
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
</template>
<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify, notifyError } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { allProducts, form, purchase } = defineProps({
    allProducts: Object,
    form: Object,
    purchase: {
        type: Object,
        requerid: false
    },
})

const showProductModal = ref(false)
const resorceOrProduct = defineModel('resorceOrProduct')
const product_selected = defineModel('product_selected')

function openModal() {
    product_selected.value = resorceOrProduct.value ? allProducts.filter(product => product.type_product !== null) : allProducts.filter(resource => resource.type === 'Activo')
    showProductModal.value = true
}

const formProduct = useForm({
    purchase_product_id: '',
    purchasing_request_id: '',
    quantity: '',
})

const initialStateProduct = {
    id: '',
    code: '',
    name: '',
    unit: '',
    quantity: '',
    pivot: {}
}

const productToAdd = ref(JSON.parse(JSON.stringify(initialStateProduct)))


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
                        notify('Se añadió un nuevo producto a la solicitud')
                    }
                })
                .catch(error => console.error(error));
        } else {
            form.products.push(JSON.parse(JSON.stringify(productToAdd.value)))
            closeModal()
        }
    } else {
        notifyError("El producto ya fue añadido o es inválido")
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

function closeModal() {
    showProductModal.value = false
    productToAdd.value = JSON.parse(JSON.stringify(initialStateProduct))
}

defineExpose({ openModal })
</script>