<template>
    <div class="flex justify-between items-center gap-4">
        <PrimaryButton @click="createCategoryForm()">
            + Agregar
        </PrimaryButton>
        <div class="flex items-center">
            <Search fields="Categorias,Segmentos" :searchFunction="search" v-model:search="searchForm.searchQuery" />
        </div>
    </div>
</template>
<script setup>
import { notifyError } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Search from '@/Components/Search.vue';
import { ref } from 'vue';

const { add_information, createCategoryForm } = defineProps({
    add_information: Function,
    createCategoryForm: Function
})

const dataToRender = defineModel('dataToRender')

const searchForm = ref({
    searchQuery: ''
})

async function search() {
    let url = route('providersmanagement.MCSManagement.search')
    try {
        let response = await axios.post(url, searchForm.value)
        dataToRender.value = response.data
    } catch (error) {
        notifyError(error)
    }
}
</script>