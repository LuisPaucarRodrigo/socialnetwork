<template>
    <Modal :show="showError" :maxWidth="'sm'" >
        <div class="p-6">
            <div class="text-center">
                <div class="bg-red-200 rounded-full h-12 w-12 flex mx-auto items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
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
        showError: {
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
  