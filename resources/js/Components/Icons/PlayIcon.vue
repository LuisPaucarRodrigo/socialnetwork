<template>
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
        :class="`${size} ${color}`">
        <path stroke-linecap="round" stroke-linejoin="round"
            d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 0 1 0 1.972l-11.54 6.347a1.125 1.125 0 0 1-1.667-.986V5.653Z" />
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