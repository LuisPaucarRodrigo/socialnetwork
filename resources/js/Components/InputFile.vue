<template>
    <input
      type="file"
      class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600"
      @change="handleChange"
      ref="input"
      :accept="computedAccept"
    />
  </template>

  <script>
  import { ref, computed } from 'vue';

  export default {
    props: {
      accept: {
        type: String,
        default: '.pdf, .png, .jpeg, .jpg, .doc, .docx, .xls, .xlsx, .ppt, .pptx, .txt'
      }
    },
    setup(props, { emit }) {
      const inputRef = ref(null);

      const handleChange = (event) => {
        const file = event.target.files[0];
        emit('update:modelValue', file);
      };

      // Computed property to format the accept string correctly
      const computedAccept = computed(() => {
        if (props.accept === 'xls,xlsx') {
          return '.xls,.xlsx'; // Or you could use 'application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        }
        return props.accept;
      });

      return {
        handleChange,
        inputRef,
        computedAccept
      };
    },
    emits: ['update:modelValue'],
  };
  </script>
