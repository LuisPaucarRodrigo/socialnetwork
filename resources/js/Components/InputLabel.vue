<template>
    <label class="block text-sm mt-2 text-gray-900">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot /></span>
    </label>
</template>

<script setup>
defineProps(['value']);
</script>
