<template>
    <Modal :show="confirming" :maxWidth="'sm'">
        <div class="p-6">
            <div class="text-center">
                <div class="bg-green-200 rounded-full h-12 w-12 flex mx-auto items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                </div>
                <h2 class="pt-5 text-lg font-medium text-gray-900">
                     {{ title }}
                </h2>
                <p class="mt-1 text-sm text-gray-600">
                    {{ message }}
                </p>
            </div>
        </div>
    </Modal>
</template>
<script>
import Modal from '@/Components/Modal.vue';
export default {
    components: {
        Modal
    },
    props: {
        confirming: {
            type: Boolean,
            required: true
        },
        title: {
            type: String,
            required: true
        },
        message: {
            type: String,
            required: true
        },
    },
};
</script>
  