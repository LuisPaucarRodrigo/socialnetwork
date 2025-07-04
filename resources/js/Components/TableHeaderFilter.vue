<template>
    <div :class="['relative flex justify-center items-center gap-x-3', widthClass]" ref="popup">
        <p :class="{ labelClass, 'text-purple-700 border border-purple-700 px-1': isActive }"
            v-html="reverse ? reverseWordsWithBreaks(label) : label"></p>
        <button @click="togglePopup">
            <SortDateIcon />
        </button>
        <div v-if="showPopup"
            :class="['absolute z-40 top-8 right-0 mt-0 bg-white border border-gray-300 shadow-lg rounded-sm', widthClass]">
            <div class="">
                <label class="border-b-2 border-gray-100 px-2 py-2 flex space-x-1 items-center">
                    <input type="checkbox" v-model="selectAll" @change="toggleAll" class="mr-2" /> Todos
                </label>
                <div class='max-h-48 overflow-y-auto'>
                    <label v-for="option in options" :key="option"
                        class="border-b-2 border-gray-100 px-2 py-2 flex space-x-1 items-center">
                        <input type="checkbox" :value="option" v-model="selectedOptions" class="mr-2" />
                        <p class="text-left">
                            {{ option }}
                        </p>
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed, onMounted, onUnmounted } from 'vue';
import SortDateIcon from './Icons/SortDateIcon.vue';

const props = defineProps({
    label: {
        type: String,
        default: 'Label'
    },
    labelClass: {
        type: String,
        default: 'text-xs font-semibold px-1'
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
    },
    reverse: {
        type: Boolean,
        default: false
    }
});


const emit = defineEmits(['update:modelValue']);

const showPopup = ref(false);
const selectedOptions = ref([...props.modelValue]);
const selectAll = ref(true);
const isActive = ref(false);
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
};

watch(selectedOptions, (newSelectedOptions) => {
    //si viene del padre newSelectedOptions es igual a newVal misma referencia
    emit('update:modelValue', selectedOptions.value);
    if (newSelectedOptions.length === props.options.length) {
        selectAll.value = true;
    } else {
        selectAll.value = false;
    }
});

//checkactive
watch(selectAll, (newVal) => {
    if (newVal === true) isActive.value = false
    else isActive.value = true
})

watch(() => props.modelValue, (newVal) => {
    //si viene del hijo el newVal es lo mismo que el newSelectedOptions
    selectedOptions.value = props.modelValue
});


onMounted(() => {
    document.addEventListener('click', closePopup);
});

onUnmounted(() => {
    document.removeEventListener('click', closePopup);
});

</script>
