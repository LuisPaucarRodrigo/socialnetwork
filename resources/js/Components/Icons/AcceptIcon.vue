<template>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        :class="`${size} ${color}`">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
    </svg>
</template>
<script setup>
const { size, color } = defineProps({
    size: {
        require: false,
        type: String,
        default: 'w-5 h-5'
    },
    color: {
        require: false,
        type: String,
        default: 'text-green-500'
    }
})
</script>