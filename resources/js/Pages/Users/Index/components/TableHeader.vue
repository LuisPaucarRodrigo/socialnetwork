<template>
    <div class="flex justify-between">
        <Link v-permission="'add_user'" :href="route('register')"
            class="rounded-md px-4 py-2 text-center text-sm text-white bg-indigo-600 hover:bg-indigo-500">
        + Agregar
        </Link>
        <Search v-model:search="formSearch.searchQuery" fields="Nombre, Dni" />
    </div>
</template>
<script setup>
import Search from '@/Components/Search.vue';
import { Link } from '@inertiajs/vue3';
import { watch } from 'vue';

const users = defineModel('users')
const formSearch = defineModel('formSearch')


watch(formSearch, searchAdvance, { deep: true })

async function searchAdvance() {
    let url = route('users.search')
    try {
        let res = await axios.post(url, formSearch.value);
        users.value = res.data;
    } catch (error) {
        console.error(error)
    }
}
</script>