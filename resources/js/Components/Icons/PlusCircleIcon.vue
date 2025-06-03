<template>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        :class="`${size} ${color}`">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
    </svg>
</template>
<script setup>
const { size, color } = defineProps({
    size: {
        require: false,
        type: String,
        default: 'w-6 h-6'
    },
    color: {
        require: false,
        type: String,
        default: 'text-green-600'
    }
})
</script>