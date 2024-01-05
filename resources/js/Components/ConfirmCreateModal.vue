<template>
    <Modal :show="confirmingcreation">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                Creacion de nuevo {{ itemType }}.
            </h2>
            <p class="mt-1 text-sm text-gray-600">
                ¿Desea continuar con la creacion {{ nameText == null ? itemType : nameText
                }}?
            </p>
            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                <PrimaryButton class="ml-3" @click="createItem">{{ createText }}</PrimaryButton>
            </div>
        </div>
    </Modal>
</template>
  
<script>
import Modal from '@/Components/Modal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
export default {
    components: {
        Modal,
        PrimaryButton,
        SecondaryButton
    },
    props: {
        confirmingcreation: {
            type: Boolean,
            required: true
        },
        itemType: {
            type: String,
            required: true
        },
        createText: {
            type: String,
            default: 'Continuar'
        },
        nameText: {
            type: String,
            default: null
        },
        createFunction: {
            type: Function,
            required: true
        }
    },
    emits: ['closeModal'],
    methods: {
        closeModal() {
            this.$emit('closeModal');
        },
        createItem() {
            this.createFunction();
        }
    }
};
</script>
  