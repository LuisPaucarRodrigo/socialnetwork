<template>
    <Link :href="route(href)" class="bg-white p-4 rounded-md shadow-md text-left h-full w-full">
    <h3 class="text-lg font-semibold">
        {{ title }}
    </h3>
    <div class="mb-2">
        <slot name="icon" />
    </div>
    </Link>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    title: String,
    href: String
});
</script>
