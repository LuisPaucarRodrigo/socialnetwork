<template>
  <Modal :show="showConfirm">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900">
        {{ tittle }}
      </h2>
      <p class="mt-1 text-sm text-gray-600">
        {{ text }}
      </p>
      <div class="mt-6 flex justify-end">
        <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
        <PrimaryButton class="ml-3 uppercase tracking-widest text-xs" @click="doAction">Aceptar</PrimaryButton>
      </div>
    </div>
  </Modal>
</template>

<script>
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components//PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

export default {
  components: {
    Modal,
    PrimaryButton,
    SecondaryButton
  },
  props: {
    showConfirm: {
      type: Boolean,
      required: true
    },
    tittle: {
      type: String,
      required: true
    },
    text: {
      type: String,
      required: true
    },
    actionFunction: {
      type: Function,
      required: true
    }
  },
  emits: ['closeModal'],
  methods: {
    closeModal() {
      this.$emit('closeModal');
    },
    doAction() {
      this.actionFunction();
    }
  }
};
</script>
