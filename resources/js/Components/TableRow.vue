<template>
    <td class="border-b border-gray-200 px-5 py-2 text-sm text-center" :class="[style]" :colspan="colspan">
        <div class="inline-block text-gray-900 text-center tabular-nums" :class="[width]">
            <slot />
        </div>
    </td>
</template>
<script setup>
const { style, width, colspan } = defineProps({
    style: {
        type: String,
        default: 'bg-white'
    },
    width: {
        type: String,
        default: 'whitespace-nowrap'
    },
    colspan: {
        type: Number,
        default: null
    }
})
</script>