<template>
    <div class="flex justify-between">
        <div class="flex items-center mt-4 space-x-3 sm:mt-0">
            <a :href="route('assignation.export', { type }) + '?' + uniqueParam"
                class="rounded-md bg-green-600 px-4 py-2 text-center text-sm text-white hover:bg-green-500">Exportar</a>
        </div>

        <div class="flex items-center mt-4 space-x-3 sm:mt-0">
            <Search v-model:search="searchForm.searchTerm" :searchFunction="search" fields="Nombre,Codigo,Cpe" />
            <SelectCicsaComponent currentSelect="Asignación" :type="type" />
        </div>
    </div>
</template>
<script setup>
import Search from '@/Components/Search.vue';
import SelectCicsaComponent from '@/Components/SelectCicsaComponent.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { type } = defineProps({
    type: String
})

const assignations = defineModel('assignations')
const uniqueParam = ref(`timestamp=${new Date().getTime()}`);

const searchForm = useForm({
    searchTerm: '',
})

async function search() {
    try {
        const response = await axios.post(route('assignation.index', { type }), { searchQuery: searchForm.searchTerm });
        assignations.value = response.data.assignation;
    } catch (error) {
        console.error('Error searching:', error);
    }
};
</script>