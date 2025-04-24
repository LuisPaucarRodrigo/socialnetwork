<template>
    <div class="flex justify-end items-center gap-4">
        <div class="flex items-center">
            <Search fields="dd,ee" v-model:search="searchForm.searchTerm" :search-function="searcch" />
        </div>
    </div>
</template>
<script setup>
import Search from '@/Components/Search.vue';
import { useForm, router } from '@inertiajs/vue3';

const { search } = defineProps({
    search: String
})

const searchForm = useForm({
    searchTerm: search ? search : '',
})

function searcch() {
    if (searchForm.searchTerm == '') {
        router.visit(route('purchaseorders.history'));
    } else {
        router.visit(route('purchaseorders.search', { request: searchForm.searchTerm, history: 'history' }));
    }
}
</script>