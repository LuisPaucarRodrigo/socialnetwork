<template>
    <div :class="['relative flex justify-between items-center', widthClass]" ref="popup">
        <p :class="labelClass">{{ label }}</p>
        <button @click="togglePopup">
            <BarsArrowDownIcon class="h-5 w-5" />
        </button>
        <div v-if="showPopup"
            :class="['absolute z-40 top-8 right-0 mt-0 bg-white border border-gray-300 rounded shadow-lg', widthClass]">
            <div class="">
                <label class="border-b-2 border-gray-100 px-2 py-2 flex space-x-1 items-center">
                    <input type="checkbox" v-model="selectAll" @change="toggleAll" class="mr-2" /> Todos
                </label>
                <div class='max-h-48 overflow-y-auto'>
                    <label v-for="option in options" :key="option" class="border-b-2 border-gray-100 px-2 py-2 flex space-x-1 items-center">
                        <input type="checkbox" :value="option" v-model="selectedOptions" class="mr-2" /> 
                        <p>
                            {{ option }}
                        </p>
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed, onMounted, onUnmounted, defineExpose } from 'vue';
import { BarsArrowDownIcon } from '@heroicons/vue/24/outline';

const props = defineProps({
    label: {
        type: String,
        default: 'Label'
    },
    labelClass: {
        type: String,
        default: 'text-xs font-semibold'
    },
    options: {
        type: Array,
        required: true
    },
    modelValue: {
        type: Array,
        default: () => []
    },
    width: {
        type: String,
        default: 'w-full'
    }
});

const emit = defineEmits(['update:modelValue']);

const showPopup = ref(false);
const selectedOptions = ref([...props.modelValue]);
const selectAll = ref(true);
const popup = ref(null);

const widthClass = computed(() => props.width);

const togglePopup = () => {
    showPopup.value = !showPopup.value;
};

const closePopup = (event) => {
    if (popup.value && !popup.value.contains(event.target)) {
        showPopup.value = false;
    }
};

const toggleAll = () => {
    if (selectAll.value) {
        selectedOptions.value = [...props.options];
    } else {
        selectedOptions.value = [];
    }
    emit('update:modelValue', selectedOptions.value);
};

const checkAll = () => {
    selectedOptions.value = [...props.options];
    selectAll.value = true;
    emit('update:modelValue', selectedOptions.value);
};

watch(selectedOptions, (newSelectedOptions) => {
    emit('update:modelValue', newSelectedOptions);
    selectAll.value = newSelectedOptions.length === props.options.length;
});

onMounted(() => {
    document.addEventListener('click', closePopup);
});

onUnmounted(() => {
    document.removeEventListener('click', closePopup);
});

// Expose the `checkAll` function to the parent component
defineExpose({ checkAll });
</script>

<style scoped>
/* No additional styles needed since Tailwind CSS is being used */
</style>
