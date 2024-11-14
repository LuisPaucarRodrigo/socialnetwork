<template>
    <div
        :class="['relative flex justify-between items-center', widthClass]"
        ref="popup"
    >
        <p :class="labelClass">{{ label }}</p>
        <button @click="togglePopup" class="cursor-pointer">
            <BarsArrowDownIcon class="h-5 w-5" />
        </button>
        <div
            v-if="showPopup"
            :class="[
                'absolute z-40 top-8 right-0 mt-0 bg-white border border-gray-300 shadow-lg p-4 rounded-md',
                widthClass,
                'w-48' // Ancho fijo para todo el filtro
            ]"
        >
            <div class="font-normal items-center flex flex-col gap-4">
                <input
                    type="text"
                    v-model="localValue"
                    list="autocompleteOptions"
                    @input="handleAutocompleteInput"
                    class="text-sm border border-gray-300 rounded px-1 py-1 w-full"
                    placeholder="Seleccionar opción"
                />
                <datalist id="autocompleteOptions">
                    <option v-for="option in options" :key="option" :value="option">
                        {{ option }}
                    </option>
                </datalist>
                <button
                    @click="clearFilter"
                    class="text-sm text-blue-500 hover:text-blue-700 focus:outline-none"
                >
                    Limpiar
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from "vue";
import { BarsArrowDownIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    label: {
        type: String,
        default: "Seleccionar Zona",
    },
    labelClass: {
        type: String,
        default: "text-xs font-semibold",
    },
    options: {
        type: Array,
        default: () => [],
    },
    width: {
        type: String,
        default: "w-full",
    },
});

const emit = defineEmits(["update:modelValue"]);

const showPopup = ref(false);
const popup = ref(null);
const localValue = ref(""); // Inicializa en vacío

const widthClass = computed(() => props.width);

const togglePopup = () => {
    showPopup.value = !showPopup.value;
};

const closePopup = (event) => {
    if (popup.value && !popup.value.contains(event.target)) {
        showPopup.value = false;
    }
};

const handleAutocompleteInput = () => {
    emit("update:modelValue", localValue.value);
};

const clearFilter = () => {
    localValue.value = "";
    emit("update:modelValue", "");
};

onMounted(() => {
    document.addEventListener("click", closePopup);
});

onUnmounted(() => {
    document.removeEventListener("click", closePopup);
});
</script>
