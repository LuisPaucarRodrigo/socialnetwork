<template>

    <Head title="Agregar Solicitud" />
    <AuthenticatedLayout
        :redirectRoute="{ 
            route: 'inventory.special_products.index', 
            params: { warehouse_id } 
            }"
    >
        <template #header>
            {{ special_product ? "Editando Ingreso" : "Nuevo Ingreso" }}
        </template>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12 shadow-sm p-4 ring-1 ring-gray-200 rounded-lg">
                    <div class="grid grid-cols-1 gap-x-16 gap-y-8 sm:grid-cols-6 ">
                        <div class="sm:col-span-6">
                            <p class="border-b-2 border-gray-300 text-sm text-indigo-600">
                                Datos del Producto
                            </p>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="purchase_product_id" class="font-medium leading-6 text-gray-900">
                                Producto
                            </InputLabel>
                            <div class="mt-2">
                                <input 
                                    @input="(e) => 
                                        handleAutocomplete(e,'purchase_product_id')"  
                                    list="options"
                                    v-model="selectedProduct.code"
                                    type="text" 
                                    id="purchase_product_id" 
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <datalist id="options">
                                    <option v-for="item in products" :value="item.code">
                                        {{ item.name }}
                                    </option>
                                </datalist>
                                <InputError 
                                    :message="form.errors.purchase_product_id"
                                />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="quantity" class="font-medium leading-6 text-gray-900">
                                Cantidad
                            </InputLabel>
                            <div class="mt-2">
                                <input 
                                    type="number"
                                    min="1" 
                                    v-model="form.quantity"
                                    id="quantity" 
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError 
                                    :message="form.errors.quantity"
                                />
                            </div>
                        </div>
                        
                        <div class="sm:col-span-3" v-if="selectedProduct.code !== ''">
                            <InputLabel class="font-medium leading-6 text-gray-900">Nombre:
                            </InputLabel>
                            <InputLabel class="leading-6 text-gray-900">
                                {{ selectedProduct?.name }}
                            </InputLabel>
                        </div>
                        <div class="sm:col-span-3" v-if="selectedProduct.code !== ''">
                            <InputLabel class="font-medium leading-6 mt-2 text-gray-900">Unidad:
                            </InputLabel>
                            <InputLabel class="leading-6 text-gray-900">
                                {{ selectedProduct?.unit }}
                            </InputLabel>
                        </div>
                        

                        <div class="sm:col-span-3">
                            <InputLabel for="cpe" class="font-medium leading-6 text-gray-900">
                                CPE
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput
                                    :toUppercase="true"
                                    type="text" 
                                    v-model="form.cpe"
                                    id="cpe" 
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError 
                                    :message="form.errors.cpe"
                                />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="sub_warehouse" class="font-medium leading-6 text-gray-900">
                                Sub Almacén
                            </InputLabel>
                            <div class="mt-2">
                                <select 
                                    v-model="form.sub_warehouse"
                                    id="sub_warehouse" 
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" 
                                >
                                    <option value="" disabled>Seleccione</option>
                                    <option>Mantenimiento</option>
                                    <option>Instalación</option>
                                </select>
                                <InputError 
                                    :message="form.errors.sub_warehouse"
                                />
                            </div>
                        </div>

                    
                        <div class="sm:col-span-3">
                            <InputLabel for="entry_date" class="font-medium leading-6 text-gray-900">
                                Fecha de Ingreso
                            </InputLabel>
                            <div class="mt-2">
                                <input 
                                    type="date"
                                    v-model="form.entry_date"
                                    id="entry_date" 
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError 
                                    :message="form.errors.entry_date"
                                />
                            </div>
                        </div>


                        <div class="sm:col-span-3">
                            <InputLabel for="referral_guide" class="font-medium leading-6 text-gray-900">
                                Guía de Referencia
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput 
                                    :toUppercase="true"
                                    type="text"
                                    v-model="form.referral_guide"
                                    id="referral_guide" 
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError 
                                    :message="form.errors.referral_guide"
                                />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="product_serial_number" class="font-medium leading-6 text-gray-900">
                                Número de Serie
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput 
                                    :toUppercase="true"
                                    type="text"
                                    v-model="form.product_serial_number"
                                    id="product_serial_number" 
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError 
                                    :message="form.errors.product_serial_number"
                                />
                            </div>
                        </div>
                        <div class="sm:col-span-3">
                            <InputLabel for="entry_observations" class="font-medium leading-6 text-gray-900">
                                Observaciones de Ingreso
                            </InputLabel>
                            <div class="mt-2">
                                <textarea 
                                    v-model="form.entry_observations"
                                    id="entry_observations" 
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError 
                                    :message="form.errors.entry_observations"
                                />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Guardar
                </button>
            </div>
        </form>


        <ConfirmUpdateModal :confirmingupdate="showModal2" itemType="producto" />
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="producto" />

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputError from '@/Components/InputError.vue'
import { Head, useForm, router } from '@inertiajs/vue3';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import { ref } from 'vue';

const showModal = ref(false);
const showModal2 = ref(false);


const { products, warehouse_id, special_product } = defineProps({
    products: Object,
    warehouse_id: String,
    special_product: Object,
})


const initialState = {
    purchase_product_id: "",
    warehouse_id,
    cpe: "",
    referral_guide: "",
    entry_date: "",
    sub_warehouse: "",
    quantity: "",
    product_serial_number: "",
    entry_observations: "",
}
const updateState = {
    ...special_product
}

const form = useForm( 
    special_product ? {...updateState}
                    : {...initialState}
)
const selectedProduct = ref( 
    special_product ? {...products.find(item => 
                        item.id == special_product.purchase_product_id)}
                    : {code:""}
)

const handleAutocomplete = (e, model) => {
    const code = e.target.value;
    let findedProduct = products.find(item => item.code === code)
    if (findedProduct) {
        form[model] = findedProduct.id
        selectedProduct.value = {...findedProduct}
    } else {
        form[model] = ""
        selectedProduct.value = null
    }
}

function submit () {
    form.post(route('inventory.special_products.store', {
        special_inventory_id: special_product?.id
    }), {
        onSuccess: () => {
            special_product ? showModal2.value = true
                            : showModal.value = true
            setTimeout(()=>{
                special_product ? showModal2.value = false
                                : showModal.value = false
                router.get(route('inventory.special_products.index', {
                    warehouse_id
                }))
            }, 2000)
        }
    })
}

</script>