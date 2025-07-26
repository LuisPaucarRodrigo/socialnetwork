<template>
    <div class="flex justify-between items-center gap-4">
        <div class="flex gap-x-2">
            <PrimaryButton v-permission="'add_provider'" type="button" @click="createProviderForm()">
                + Agregar
            </PrimaryButton>
            <Link v-permission="'manage_categorys_segments'" data-tooltip-target="g_c_s"
                class="flex items-center bg-indigo-600 hover:bg-indigo-500 text-white text-sm rounded-md px-4 py-2"
                :href="route('providersmanagement.MCSManagement.index')">
            GCS
            </Link>
            <div id="g_c_s" role="tooltip"
                class="absolute z-10 invisible inline-block px-2 py-1 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Categorias y Segmentos
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
        <div class="flex items-center">
            <Search fields="Compañia,Contacto,Ruc" :searchFunction="search" v-model:search="searchForm.searchQuery" />
        </div>
    </div>
</template>
<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Search from '@/Components/Search.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const { createProviderForm } = defineProps({
    createProviderForm: Function
})

const providers = defineModel('providers')

const searchForm = ref({
    searchQuery: ''
})

async function search() {
    let url = route('providersmanagement.index')
    try {
        let response = await axios.post(url, searchForm.value)
        providers.value = response.data
    } catch (error) {
        notifyError(error)
    }
}
</script>