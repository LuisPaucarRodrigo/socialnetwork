<template>
    <div :class="['relative flex justify-center items-center gap-x-3', widthClass]" ref="popup">
        <p :class="labelClass" v-html="reverse ? reverseWordsWithBreaks(label) : label"></p>
        <template v-if="options">
            <button @click="togglePopup">
                <SortDateIcon />
            </button>
            <div v-if="showPopup"
                :class="['absolute z-40 top-8 right-0 mt-0 bg-white border border-gray-300 rounded shadow-lg', widthClass]">
                <div class="">
                    <label class="border-b-2 border-gray-100 px-2 py-2 flex space-x-1 items-center">
                        <input type="checkbox" v-model="selectAll" @change="toggleAll" class="mr-2" /> Todos
                    </label>
                    <div class='max-h-48 overflow-y-auto'>
                        <label v-for="option in options" :key="option"
                            class="border-b-2 border-gray-100 px-2 py-2 flex space-x-1 items-center">
                            <input type="checkbox" :value="option" v-model="selectedOptions" class="mr-2" />
                            <p>
                                {{ option }}
                            </p>
                        </label>
                    </div>
                </div>
            </div>
        </template>
        <template v-else>
            <button @click="toggleDate">
                <CalendarIcon />
            </button>
            <div v-if="showDate"
                :class="['absolute z-40 top-8 right-0 mt-0 bg-white border border-gray-300 rounded shadow-lg', widthClass]">
                <div class='max-h-48 '>
                    <TextInput type="Date" @input="handleInput" class="mr-2" />
                </div>
            </div>
        </template>
    </div>
</template>

<script setup>
import { ref, watch, computed, onMounted, onUnmounted } from 'vue';
import { CalendarIcon } from "@/Components/Icons/Index";
import TextInput from '@/Components/TextInput.vue';
import SortDateIcon from './Icons/SortDateIcon.vue';


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
        required: false
    },
    modelValue: {
        type: Array,
        required: false,
        default: () => []
    },
    width: {
        type: String,
        default: 'w-full'
    },
    reverse: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update:modelValue']);

const showPopup = ref(false);
const showDate = ref(false);
const selectedOptions = ref([...props.modelValue]);
const selectAll = ref(true);
const popup = ref(null);

const widthClass = computed(() => props.width);

const togglePopup = () => {
    showPopup.value = !showPopup.value;
};

const toggleDate = () => {
    showDate.value = !showDate.value;
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

// Expose the checkAll function to the parent component
defineExpose({ checkAll });

const handleInput = (event) => {
    emit('update:modelValue', event.target.value);
};

function reverseWordsWithBreaks(columnTitle) {
    return columnTitle.split(" ").reverse().join("<br>");
}
</script>