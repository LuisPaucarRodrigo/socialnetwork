<template>
    <div :class="['relative z-50 inline-block justify-between items-center']" ref="popup">
      <button class="p-2 bg-slate-900 rounded-md hover:bg-slate-500" @click="togglePopup">
        <Squares2X2Icon class="h-5 w-5 text-white" />
      </button>
      <div v-if="showPopup"
        :class="['absolute z-40 top-8 left-0 mt-2 bg-white border border-gray-300 rounded shadow-lg', widthClass]">
        <div>
          <label class="block border-b-2 border-gray-100 px-2 py-2 w-full">
            <input type="checkbox" v-model="selectAll" @change="toggleAll" class="mr-2" /> Todos
          </label>
          <div class='h-full overflow-y-auto'>
            <label v-for="option in options" :key="option" class="border-b-2 border-gray-100 px-2 py-2 flex items-center w-full">
              <input type="checkbox" :value="option" v-model="selectedOptions" class="mr-2" /> 
              <p class="flex-grow">
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
  import { Squares2X2Icon } from '@heroicons/vue/24/outline';
  
  const props = defineProps({
    label: {
      type: String,
      default: 'Label'
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
      default: 'w-48'
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
  };
  
  watch(selectedOptions, (newSelectedOptions) => {
    emit('update:modelValue', newSelectedOptions);
    if (newSelectedOptions.length === props.options.length) {
      selectAll.value = true;
    } else {
      selectAll.value = false;
    }
  });
  
  onMounted(() => {
    document.addEventListener('click', closePopup);
  });
  
  onUnmounted(() => {
    document.removeEventListener('click', closePopup);
  });
  </script>
  
