<template>
    <Modal :show="confirmingDeletion">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
          ¿Estás seguro de deshabilitar el {{ itemType }}?
        </h2>
        <p class="mt-1 text-sm text-gray-600">
          Se deshabilitará el {{ nameText == null ? itemType : nameText }}. Esta accion podrá ser revertida mas adelante.
        </p>
        <div class="mt-6 flex justify-end">
          <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
          <DangerButton class="ml-3" @click="deleteItem">{{ deleteText }}</DangerButton>
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
      itemType: {
        type: String,
        required: true
      },
      deleteText: {
        type: String,
        default: 'Deshabilitar'
      },
      nameText: {
        type: String,
        default: null
      },
      deleteFunction: {
        type: Function,
        required: true
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
  