<template>

    <Head title="Productos" />
    <AuthenticatedLayout :redirectRoute="'inventory.purchaseproducts'">
        <template #header>
            Productos
        </template>
        <Toaster richColors />
        <div class="min-w-full p-3">
            <TableHeader v-model:products="products" :userPermissions="userPermissions"
                :openCreateProduct="openCreateProduct" />

            <br>

            <ProductsTable :products="products" :userPermissions="userPermissions"
                :openEditProductModal="openEditProductModal" :auth="auth"
                :confirmDeleteProduct="confirmDeleteProduct" />
        </div>

        <ProductsForm ref="productForm" :userPermissions="userPermissions" :type_product="type_product"
            :resource_type="resource_type" :products="products"/>

        <ConfirmDisableModal :confirmingDeletion="confirmingDocDeletion" itemType="Producto"
            :deleteFunction="deleteProduct" @closeModal="closeModalDoc" />

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmDisableModal from '@/Components/ConfirmDisableModal.vue';
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import ProductsTable from './components/ProductsTable.vue';
import TableHeader from './components/TableHeader.vue';
import ProductsForm from './components/ProductsForm.vue';
import { Toaster } from 'vue-sonner';

const props = defineProps({
    product: Object,
    type_product: Object,
    resource_type: Object,
    auth: Object,
    search: String,
    userPermissions: Object
});

const products = ref({ ...props.product })
const productForm = ref(null)

const docToDelete = ref(null);
const confirmingDocDeletion = ref(false);



function openEditProductModal(item) {
    productForm.value.openEditProductModal(item)
}

function openCreateProduct() {
    productForm.value.openCreateProduct()
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
        router.put(route('inventory.purchaseproducts.disable', { purchase_product: docId }), null, {
            onSuccess: () => closeModalDoc()
        });
    }
};

</script>