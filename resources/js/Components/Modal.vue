<script setup>
import { computed, onMounted, onUnmounted, watch } from 'vue';
import { Toaster } from 'vue-sonner';

const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: '2xl',
    },
    closeable: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits(['close']);

watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = 'hidden';
        } else {
            document.body.style.overflow = null;
        }
    }
);

const close = () => {
    if (props.closeable) {
        emit('close');
    }
};

const closeOnEscape = (e) => {
    if (e.key === 'Escape' && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener('keydown', closeOnEscape));

onUnmounted(() => {
    document.removeEventListener('keydown', closeOnEscape);
    document.body.style.overflow = null;
});

const maxWidthClass = computed(() => {
    return {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
        '4xl': 'sm:max-w-4xl',
        '6xl': 'sm:max-w-6xl',
    }[props.maxWidth];
});
</script>

<template>
    
    <Teleport to="body">
        <Transition
            enter-active-class="ease-out duration-300 transition-opacity"
            leave-active-class="ease-in duration-200 transition-opacity"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div v-if="show" class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-40" scroll-region>
                <Toaster richColors/>
                <div class="fixed inset-0 transform transition-all" @click="close">
                    <div class="absolute inset-0 bg-gray-500 opacity-75" />
                </div>

                <div
                    class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:mx-auto"
                    :class="maxWidthClass"
                >
                    <slot />
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
