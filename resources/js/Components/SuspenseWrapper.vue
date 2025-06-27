<template>
    <Suspense>
        <template #default>
            <slot v-if="when" name="component" />
        </template>
        <!-- <template #fallback>
            <div>Cargando...</div>
        </template> -->
    </Suspense>
</template>

<script setup>
defineProps({
    when: Boolean,
    default: true,
});
</script>