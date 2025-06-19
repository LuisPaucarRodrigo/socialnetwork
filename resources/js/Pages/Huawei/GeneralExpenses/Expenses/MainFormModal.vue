<template>

</template>

<script setup>
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm, router } from "@inertiajs/vue3";
import { ref } from 'vue';
import { setAxiosErrors } from "@/utils/utils";

const { show, close, editForm } = defineProps({
    show: Boolean,
    close: Function,
    editForm: Object
});
</script>