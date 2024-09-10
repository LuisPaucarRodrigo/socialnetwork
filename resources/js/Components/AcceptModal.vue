<template>
  <Modal :show="confirmingAccept">
    <div class="p-6">
      <h2 class="text-lg font-medium text-gray-900">
        ¿Estás seguro de aceptar el/la {{ itemType }}?
      </h2>
      <p class="mt-1 text-sm text-gray-600">
        Se aceptará el/la {{ nameText == null ? itemType : nameText }}. Esta accion no
        se podra revertir mas adelante y permitirá crear un proyecto a partir de esta {{ nameText == null ? itemType :
    nameText }}.
      </p>
      <div class="mt-6 flex space-x-3 justify-end">
        <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
        <PrimaryButton type="button" @click="accept">{{ acceptText }}</PrimaryButton>
      </div>
    </div>
  </Modal>
</template>

<script>
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

export default {
  components: {
    Modal,
    SecondaryButton,
    PrimaryButton
  },
  props: {
    confirmingAccept: {
      type: Boolean,
      required: true
    },
    itemType: {
      type: String,
      required: true
    },
    acceptText: {
      type: String,
      default: 'Aceptar'
    },
    nameText: {
      type: String,
      default: null
    },
    acceptFunction: {
      type: Function,
      required: true
    }
  },
  emits: ['closeModal'],
  methods: {
    closeModal() {
      this.$emit('closeModal');
    },
    accept() {
      this.acceptFunction();
    }
  }
};
</script>