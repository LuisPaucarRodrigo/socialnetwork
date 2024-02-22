<template>
    <Modal :show="confirmingAccept">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900">
          ¿Estás seguro de aceptar el/la {{ itemType }}?
        </h2>
        <p class="mt-1 text-sm text-gray-600">
          Se aceptará la {{ nameText == null ? itemType : nameText }}. Esta accion no
          se podra revertir mas adelante y permitirá crear un proyecto a partir de esta {{ nameText == null ? itemType : nameText }}.
        </p>
        <div class="mt-6 flex justify-end">
          <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
          <button class="ml-3 rounded-md bg-green-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-green-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:green-indigo-600" @click="accept">{{ acceptText }}</button>
        </div>
      </div>
    </Modal>
  </template>
  
  <script>
  import Modal from '@/Components/Modal.vue';
  import DangerButton from '@/Components/DangerButton.vue';
  import SecondaryButton from '@/Components/SecondaryButton.vue';
  import PrimaryButton from '@/Components/PrimaryButton.vue';

  export default {
    components: {
    Modal,
    DangerButton,
    SecondaryButton,
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
  