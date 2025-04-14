<template>
    <div class="flex h-screen items-center justify-center px-6">
        <div class="w-full max-w-sm rounded-md bg-white p-6 shadow-md">
            <slot />
        </div>
    </div>
</template>
<script setup>

import { resetAppAuth } from '@/Store/auth';

resetAppAuth();

</script>
