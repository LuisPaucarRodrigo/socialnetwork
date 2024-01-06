<template>
    <Modal :show="show_file_modal" :maxWidth="'md'">
        <div class="p-6">
            <h1>Importar datos de Excel</h1>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                <button type="button"
                    class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto"
                    @click="closeModal('file')" ref="cancelButtonRef">Cancel</button>
            </div>
        </div>
    </Modal>
    <!--#############################-->

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
  