<template>
    <div class="flex justify-between items-center gap-4">
        <PrimaryButton v-if="hasPermission('PurchasingManager')" @click="add_request" type="button">
            + Agregar
        </PrimaryButton>
        <div class="flex items-center">
            <Search v-model:search="searchForm.searchTerm" :searchFunction="search" fields="Titulo"/>
        </div>
    </div>
</template>
<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Search from '@/Components/Search.vue';
import { router, useForm } from '@inertiajs/vue3';

const { userPermissions } = defineProps({
    userPermissions: Array
})

const purchases = defineModel('purchases')

const searchForm = useForm({
    searchTerm: "",
})

const add_request = () => {
    router.get(route('purchasesrequest.create'))
};

async function search () {
    let url = route('purchasesrequest.search', { request: searchForm.searchTerm })
    try {
        let response  =await axios.get(url)
        purchases.value = response.data
    } catch (error) {
        console.error(error)   
    }
}

function hasPermission(permission){
    return userPermissions.includes(permission)
}
</script>