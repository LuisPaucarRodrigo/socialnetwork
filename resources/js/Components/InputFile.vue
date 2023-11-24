<template>
  <input
    type="file"
    class="block mt-1 w-full rounded-md form-input focus:border-indigo-600"
    @change="handleChange"
    ref="input"
    accept=".pdf, .doc, .docx"
  />
</template>

<script>
import { ref } from 'vue';

export default {
  setup(_, { emit }) {
    const inputRef = ref(null);

    const handleChange = (event) => {
      const file = event.target.files[0];
      emit('update:modelValue', file);
    };

    return {
      handleChange,
      inputRef,
    };
  },
  emits: ['update:modelValue'],
};
</script>
