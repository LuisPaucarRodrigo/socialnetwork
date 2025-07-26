<template>
    <div v-if="message">
        <p :class="`text-red-600 ${textSize}`">
            {{ message }}
        </p>
    </div>
</template>

<script setup>
defineProps({
    message: String,
    textSize: {type:String, default: "text-sm"}
});
</script>
