<template>
    <div v-if="links.length > 3">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, key) in links" :key="key">
                <!-- Botón deshabilitado -->
                <div v-if="link.url === null" class="px-2 py-1 mr-1 mb-1 text-sm leading-4 text-gray-400 rounded border"
                    v-html="link.label" />

                <!-- Botón activo -->
                <button v-else @click="fetchExpensesByUrl(link.url)"
                    class="px-2 py-1 mr-1 mb-1 text-sm leading-4 rounded border hover:bg-indigo-500 hover:text-white focus:border-indigo-500 focus:text-indigo-500"
                    :class="{ 'text-white bg-indigo-500': link.active }" v-html="link.label" />
            </template>
        </div>
    </div>
</template>

<script setup>
const props = defineProps({
    links: {
        type: Array,
        default: []
    }
});

const loading = defineModel('loading')
const dataToRender = defineModel('dataToRender')

async function fetchExpensesByUrl(url) {
    loading.value = true;
    try {
        const res = await axios.get(url);
        dataToRender.value = res.data;
    } catch (err) {
        console.error(err);
    } finally {
        loading.value = false;
    }
}
</script>
