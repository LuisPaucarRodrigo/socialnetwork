<template>
    <div
        :class="[
            'relative flex justify-center items-center gap-x-3',
            widthClass,
        ]"
        ref="popup"
    >
        <p
            :class="{
                labelClass,
                'text-purple-700 border border-purple-700 px-1': isActive,
            }"
            v-html="reverse ? reverseWordsWithBreaks(label) : label"
        ></p>
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
                <input
                    type="date"
                    v-model="localStartDate"
                    @input="handleDateInput('start')"
                    class="text-sm border border-gray-300 rounded px-1 py-1"
                />
                <input
                    type="date"
                    v-model="localEndDate"
                    @input="handleDateInput('end')"
                    class="text-sm border border-gray-300 rounded px-1 py-1"
                />
                <label class="flex gap-2 items-center">
                    <input
                        type="checkbox"
                        v-model="localNoDate"
                        @change="handleNoDate"
                        class="focus:ring-0 outline-none"
                    />
                    Sin Fecha
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
        default: "text-xs font-semibold px-1",
    },
    startDate: {
        type: String,
        default: "",
    },
    endDate: {
        type: String,
        default: "",
    },
    noDate: {
        type: Boolean,
        default: false,
    },
    width: {
        type: String,
        default: "w-full",
    },
    reverse: {
        type: Boolean,
        default: false,
    },
});

const emit = defineEmits([
    "update:startDate",
    "update:endDate",
    "update:noDate",
]);

const showPopup = ref(false);
const popup = ref(null);
const localStartDate = ref(props.startDate);
const localEndDate = ref(props.endDate);
const localNoDate = ref(props.noDate);
const isActive = ref(false);
const widthClass = computed(() => props.width);

const togglePopup = () => {
    showPopup.value = !showPopup.value;
};

const closePopup = (event) => {
    if (popup.value && !popup.value.contains(event.target)) {
        showPopup.value = false;
    }
};

const handleDateInput = (type) => {
    if (type === "start") {
        emit("update:startDate", localStartDate.value);
    } else if (type === "end") {
        emit("update:endDate", localEndDate.value);
    }
    if (localStartDate.value || localEndDate.value) {
        localNoDate.value = false;
        emit("update:noDate", false);
    }
};

const handleNoDate = () => {
    if (localNoDate.value) {
        localStartDate.value = "";
        localEndDate.value = "";
        emit("update:startDate", "");
        emit("update:endDate", "");
    }
    emit("update:noDate", localNoDate.value);
};

watch(
    () => props.startDate,
    (newValue) => {
        localStartDate.value = newValue;
    }
);

watch(
    () => props.endDate,
    (newValue) => {
        localEndDate.value = newValue;
    }
);

watch(
    () => props.noDate,
    (newValue) => {
        localNoDate.value = newValue;
    }
);

watch([localStartDate, localEndDate, localNoDate], () => {
    if (
        localStartDate.value ||
        localEndDate.value ||
        localNoDate.value === true
    ) isActive.value = true;
    else isActive.value = false;
});

onMounted(() => {
    document.addEventListener("click", closePopup);
});

onUnmounted(() => {
    document.removeEventListener("click", closePopup);
});

function reverseWordsWithBreaks(columnTitle) {
    return columnTitle.split(" ").reverse().join("<br>");
}
</script>
