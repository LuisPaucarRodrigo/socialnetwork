<template>
    <div class="mt-6 flex items-center justify-between gap-x-6">
        <button @click="openModal()" type="button"
            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
            + Agregar
        </button>
    </div>
</template>
<script setup>

const { openModal } = defineProps({
    openModal: Function
})
</script>