<template>
    <div class="flex justify-end items-center gap-4">
        <div class="flex items-center">
            <Search fields="Codigo Orden,Codigo Solicitud,Titulo Solicitud" v-model:search="searchForm.searchTerm" :search-function="search" />
        </div>
    </div>
</template>
<script setup>
import Search from '@/Components/Search.vue';
import { useForm } from '@inertiajs/vue3';

const orders = defineModel('orders')

const searchForm = useForm({
    searchTerm: '',
})

async function search() {
    let url = route('purchaseorders.search')
    try {
        let response = await axios.post(url, { form: searchForm, history: "history" })
        orders.value = response.data
    } catch (error) {
        console.log(error)
    }
}
</script>