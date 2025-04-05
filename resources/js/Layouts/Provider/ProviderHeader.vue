<template>
    <div class="flex justify-between items-center gap-4">
        <button @click="add_information()" type="button"
            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
            + Agregar
        </button>
        <div class="flex items-center">
            <TextInput data-tooltip-target="search_fields" type="text" placeholder="Buscar..."
                @keyup.enter="search($event.target.value)" />
            <div id="search_fields" role="tooltip"
                class="absolute z-10 invisible inline-block px-2 py-2 text-xs font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                Compañia,Contacto,Ruc
                <div class="tooltip-arrow" data-popper-arrow></div>
            </div>
        </div>
    </div>
</template>
<script setup>
import TextInput from '@/Components/TextInput.vue';

const { permissions, add_information } = defineProps({
    permissions: Array,
    add_information: Function
})

const providers = defineModel('providers')

function hasPermission(permission) {
    return permissions.includes(permission)
}

async function search(search) {
    let url = route('providersmanagement.index')
    try {
        let response = await axios.post(url, { searchQuery: search })
        providers.value = response.data
    } catch (error) {
        notifyError(error)
    }
}
</script>