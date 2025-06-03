<template>
    <div :class="['relative flex justify-between items-center', widthClass]" ref="popup">
        <p :class="{ labelClass, 'text-purple-700 border border-purple-700 px-1': isActive }">{{ label }}</p>
        <button @click="togglePopup">
            <SortDateIcon />
        </button>
        <div v-if="showPopup"
            :class="['absolute z-40 top-8 right-0 mt-0 bg-white border border-gray-300 shadow-lg rounded-sm', widthClass]">
            <div class="p-4">
                <!-- Campo de búsqueda -->
                <input type="text" v-model="searchQuery" @input="filterOptions"
                    class="text-sm border border-gray-300 rounded px-1 py-1 w-full mb-4" placeholder="Buscar opción" />

                <!-- Opciones -->
                <label class="border-b-2 border-gray-100 px-2 py-2 flex space-x-1 items-center">
                    <input type="checkbox" v-model="selectAll" @change="toggleAll" class="mr-2" /> Todos
                </label>

                <!-- Lista de opciones con filtro -->
                <div class="max-h-48 overflow-y-auto">
                    <label v-for="option in filteredOptions" :key="option"
                        class="border-b-2 border-gray-100 px-2 py-2 flex space-x-1 items-center">
                        <input type="checkbox" :value="option" v-model="selectedOptions" class="mr-2" />
                        <p class="text-left">{{ option }}</p>
                    </label>
                    <!-- <label v-if="empty" class="border-b-2 border-gray-100 px-2 py-2 flex space-x-1 items-center">
                        <input type="checkbox" :value="'(vacio)'" v-model="selectedOptions" class="mr-2" />
                        <p class="text-left">(vacio)</p>
                    </label> -->
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';
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
    empty: {
        type: Boolean,
        default: false
    }
});

if (props.empty) {
    props.options.push('(vacio)')
}

const emit = defineEmits(['update:modelValue']);
const showPopup = ref(false);
const searchQuery = ref('');
const selectedOptions = ref([...props.modelValue]);
const selectAll = ref(true);
const filteredOptions = ref([...props.options]);
const popup = ref(null);
const isActive = ref(false);

watch(selectAll, (newVal) => {
    if (newVal === true) isActive.value = false
    else isActive.value = true
})

const widthClass = computed(() => props.width);

// Agregar 'EMPTY_VALUE' por defecto si empty es true
if (props.empty && !selectedOptions.value.includes('(vacio)')) {
    selectedOptions.value.push('(vacio)');
}

// Sincronizar modelo inicial
emit('update:modelValue', selectedOptions.value);

const togglePopup = () => {
    showPopup.value = !showPopup.value;
};

const closePopup = (event) => {
    if (popup.value && !popup.value.contains(event.target)) {
        showPopup.value = false;
    }
};

// Filtrar opciones basadas en la búsqueda
const filterOptions = () => {
    selectedOptions.value = [];

    // Filter options based on the search query
    filteredOptions.value = props.options.filter(option =>
        option.toLowerCase().includes(searchQuery.value.toLowerCase())
    );

    if (searchQuery.value === '') {
        selectedOptions.value = [...filteredOptions.value];
    } else {
        selectedOptions.value = filteredOptions.value;
    }

    emit('update:modelValue', selectedOptions.value);
};

// Seleccionar/Deseleccionar todas las opciones
const toggleAll = () => {
    if (selectAll.value) {
        selectedOptions.value = [...filteredOptions.value];
    } else {
        selectedOptions.value = [];
    }
    emit('update:modelValue', selectedOptions.value);
};

// Watch para sincronizar los cambios en las opciones seleccionadas
watch(selectedOptions, (newSelectedOptions) => {
    emit('update:modelValue', newSelectedOptions);

    const allOptions = filteredOptions.value;
    selectAll.value = newSelectedOptions.length === allOptions.length;
});

onMounted(() => {
    document.addEventListener('click', closePopup);
});

onUnmounted(() => {
    document.removeEventListener('click', closePopup);
});
</script>
