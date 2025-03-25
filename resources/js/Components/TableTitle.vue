<template>
    <th class="border-b-2 border-gray-200 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
        :class="[style]" :colspan="colspan">
        <slot />
    </th>
</template>
<script setup>
const { style,colspan } = defineProps({
    style: {
        type: String,
        default: 'bg-gray-100'
    },
    colspan: {
        type: Number,
        default: null
    }
})
</script>