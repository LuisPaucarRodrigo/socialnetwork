<template>
    <input
      type="file"
      class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600"
      @change="handleChange"
      ref="input"
      :accept="accept"
    />
  </template>

<script>
import { ref } from 'vue';

export default {
  props: {
    accept: {
      type: String,
      default: '.pdf, .png, .jpeg, .jpg, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .txt' // Valor por defecto si no se proporciona
    }
  },
  setup(props, { emit }) {
    const inputRef = ref(null);

    const handleChange = (event) => {
      const file = event.target.files[0];
      emit('update:modelValue', file);
    };

    return {
      handleChange,
      inputRef,
      accept: props.accept // Usar la propiedad aceptada
    };
  },
  emits: ['update:modelValue'],
};
</script>
