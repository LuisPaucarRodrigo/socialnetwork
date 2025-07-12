<template>
    <div class="flex justify-between items-center gap-4">
        <PrimaryButton @click="openCreateProduct" type="button">
            + Agregar
        </PrimaryButton>
        <div class="flex items-center">
            <Search fields="nombre" :searchFunction="search" v-model:search="searchForm.searchTerm" />
        </div>
    </div>
</template>
<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Search from '@/Components/Search.vue';
import { useForm } from '@inertiajs/vue3';

const { openCreateProduct } = defineProps({
    openCreateProduct: Function
})

const products = defineModel('products')
const searchForm = useForm({
    searchTerm: '',
})

async function search() {
    let url = route('inventory.purchaseproducts.search', { request: searchForm.searchTerm })
    try {
        let response = await axios.post(url, searchForm)
        products.value = response.data
    } catch (error) {
        console.error(error)
    }
}

</script>