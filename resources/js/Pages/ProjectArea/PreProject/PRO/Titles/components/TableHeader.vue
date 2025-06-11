<template>
    <div class="mt-6 flex items-center justify-start gap-x-3">
        <PrimaryButton v-permission="'add_pro_title'" @click="openModal" type="button">
            + Agregar
        </PrimaryButton>
        <Link v-permission="'see_pro_codes_table'" :href="route('preprojects.codes')"
            class="rounded-md px-4 py-2 text-center text-sm text-white bg-indigo-600 hover:bg-indigo-500">
        Codes
        </Link>
    </div>
</template>
<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Link } from '@inertiajs/vue3';

const { openModal } = defineProps({
    openModal: Function
})
</script>