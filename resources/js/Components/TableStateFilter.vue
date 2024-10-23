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
            ]"
        >
            <div class="font-normal items-center flex flex-col gap-4">

                <label class="flex justify-between items-center w-full" for="accepted_option">Aceptado
                    <input
                        id="accepted_option"
                        type="radio"
                        name="status"
                        v-model="localState"
                        value="1"
                        class="text-sm border border-gray-300 rounded-full ring-inset focus:ring-0"
                    />
                </label>

                <label class="flex justify-between items-center w-full" for="rejected_option"> Rechazado
                    <input
                        id="rejected_option"
                        type="radio"
                        name="status"
                        v-model="localState"
                        value="0"
                        class="text-sm border border-gray-300 rounded-full ring-inset focus:ring-0"
                    />
                </label>

                <label class="flex justify-between items-center w-full" for="pending_option"> Pendiente
                    <input
                        id="pending_option"
                        type="radio"
                        name="status"
                        v-model="localState"
                        value="pending"
                        class="text-sm border border-gray-300 rounded-full ring-inset focus:ring-0"
                    />
                </label>

            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch, computed, onMounted, onUnmounted } from "vue";
import { BarsArrowDownIcon } from "@heroicons/vue/24/outline";

const props = defineProps({
    label: {
        type: String,
        default: "Fecha",
    },
    labelClass: {
        type: String,
        default: "text-xs font-semibold",
    },
    modelValue: {
        type: String,
        default: "",
    },
    width: {
        type: String,
        default: "w-full",
    },
});

const emit = defineEmits([
    "update:modelValue",
]);

const showPopup = ref(false);
const popup = ref(null);

const localState = ref(props.modelValue);

const widthClass = computed(() => props?.width);

const togglePopup = () => {
    showPopup.value = !showPopup.value;
};

const closePopup = (event) => {
    if (popup.value && !popup.value.contains(event.target)) {
        showPopup.value = false;
    }
};

// Sincronizamos modelValue con localState y viceversa
watch(
    () => props.modelValue,
    (newValue) => {
        localState.value = newValue;
    }
);

watch(
    () => localState.value,
    (newValue) => {
        emit('update:modelValue', newValue);
    }
);

onMounted(() => {
    document.addEventListener("click", closePopup);
});

onUnmounted(() => {
    document.removeEventListener("click", closePopup);
});
</script>
