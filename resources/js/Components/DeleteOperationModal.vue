<template>
  <Modal :show="confirmingDeletion">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900">
        {{ title }}
      </h2>
      <p class="mt-1 text-sm text-gray-600">
        {{ message }}
      </p>
      <div class="mt-6 flex justify-end">
        <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
        <DangerButton class="ml-3"
            :class="{ 'opacity-25': processing }"
                    :disabled="processing" @click="deleteItem">{{ deleteText }}</DangerButton>
      </div>
    </div>
  </Modal>
</template>

<script>
import Modal from '@/Components/Modal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
export default {
  components: {
    Modal,
    DangerButton,
    SecondaryButton
  },
  props: {
    confirmingDeletion: {
      type: Boolean,
      required: true
    },
    title: {
      type: String,
      required: true
    },
    message: {
      type: String,
      required: true
    },
    deleteText: {
      type: String,
      default: 'Eliminar'
    },
    nameText: {
      type: String,
      default: null
    },
    deleteFunction: {
      type: Function,
      required: true
    },
    processing: {
      type: Boolean,
      required: false,
      default: false
    }
  },
  emits: ['closeModal'],
  methods: {
    closeModal() {
      this.$emit('closeModal');
    },
    deleteItem() {
      this.deleteFunction();
    }
  }
};
</script>
