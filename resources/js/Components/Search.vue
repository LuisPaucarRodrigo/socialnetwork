<template>
    <div>
        <TextInput data-tooltip-target="search_fields" type="text" placeholder="Buscar..." v-model="searchQuery"
            @keyup.enter="applySearch" />
        <div id="search_fields" role="tooltip"
            class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
            {{ fields }}
            <div class="tooltip-arrow" data-popper-arrow></div>
        </div>
    </div>
</template>
<script setup>
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';

const { fields } = defineProps({
    fields: String,
})

const search = defineModel('search')

const searchQuery = ref('');

function applySearch() {
    search.value = searchQuery.value
}
</script>