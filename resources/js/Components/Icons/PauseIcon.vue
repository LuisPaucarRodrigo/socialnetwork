<template>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        :class="`${size} ${color}`">
        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25v13.5m-7.5-13.5v13.5" />
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
        default: 'text-red-600'
    }
})
</script>