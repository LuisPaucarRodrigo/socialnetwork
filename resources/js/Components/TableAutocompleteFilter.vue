<template>
    <div :class="['relative flex justify-between items-center', widthClass]" ref="popup">
        <p :class="labelClass">{{ label }}</p>
        <button @click="togglePopup">
            <BarsArrowDownIcon class="h-5 w-5" />
        </button>
        <div v-if="showPopup" :class="['absolute z-40 top-8 right-0 mt-0 bg-white border border-gray-300 shadow-lg rounded-sm', widthClass]">
            <div class="p-4">
                <!-- Campo de búsqueda -->
                <input
                    type="text"
                    v-model="searchQuery"
                    @input="filterOptions"
                    class="text-sm border border-gray-300 rounded px-1 py-1 w-full mb-4"
                    placeholder="Buscar opción"
                />

                <!-- Opciones -->
                <label class="border-b-2 border-gray-100 px-2 py-2 flex space-x-1 items-center">
                    <input type="checkbox" v-model="selectAll" @change="toggleAll" class="mr-2" /> Todos
                </label>

                <!-- Lista de opciones con filtro -->
                <div class='max-h-48 overflow-y-auto'>
                    <label v-for="option in filteredOptions" :key="option" class="border-b-2 border-gray-100 px-2 py-2 flex space-x-1 items-center">
                        <input type="checkbox" :value="option" v-model="selectedOptions" class="mr-2" />
                        <p class="text-left">{{ option }}</p>
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
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
const searchQuery = ref('');
const selectedOptions = ref([...props.modelValue]);
const selectAll = ref(true);
const filteredOptions = ref([...props.options]);
const popup = ref(null);

const widthClass = computed(() => props.width);

// Toggle the popup visibility
const togglePopup = () => {
    showPopup.value = !showPopup.value;
};

// Close the popup if the click is outside
const closePopup = (event) => {
    if (popup.value && !popup.value.contains(event.target)) {
        showPopup.value = false;
    }
};

// Watch for changes in the search query
const filterOptions = () => {
    // Reset selected options when the search query changes
    selectedOptions.value = [];

    // Filter options based on the search query
    filteredOptions.value = props.options.filter(option =>
        option.toLowerCase().includes(searchQuery.value.toLowerCase())
    );

    // Automatically mark the options that match the search query
    if (searchQuery.value === '') {
        // If the search query is empty, select all options
        selectedOptions.value = [...filteredOptions.value];
    } else {
        // Mark only the options that match the search query
        selectedOptions.value = filteredOptions.value;
    }

    // Sync the selected options with the parent
    emit('update:modelValue', selectedOptions.value);
};

// Select/Deselect all options from the filtered list
const toggleAll = () => {
    if (selectAll.value) {
        selectedOptions.value = [...filteredOptions.value]; // Seleccionar todas las opciones filtradas
    } else {
        selectedOptions.value = []; // Desmarcar todas
    }
    emit('update:modelValue', selectedOptions.value);
};

// Watch for changes in selected options to update the model
watch(selectedOptions, (newSelectedOptions) => {
    emit('update:modelValue', newSelectedOptions);
    selectAll.value = newSelectedOptions.length === filteredOptions.value.length;
});

onMounted(() => {
    document.addEventListener('click', closePopup);
});

onUnmounted(() => {
    document.removeEventListener('click', closePopup);
});
</script>
