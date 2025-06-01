<template>
     <Modal :show="showPayModal" :maxWidth="showDetails ? '6xl' : '2xl'">
        <div class="p-6 flex gap-6">
            <div :class="showDetails ? 'w-1/2' : 'w-full'">
                <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                    Registrar Pagos
                </h2>
            </div>


            <div v-if="showDetails" class="w-1/2 bg-gray-100 rounded-lg p-4 overflow-y-auto">
               
            </div>
        </div>
     </Modal>
</template>

<script setup>
import TableTitle from '@/Components/TableTitle.vue';
import TableRow from '@/Components/TableRow.vue';
import { EyeIcon, PencilSquareIcon } from '@heroicons/vue/24/outline';
import TableStructure from '@/Layouts/TableStructure.vue';
import { formattedDate } from '@/utils/utils';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';

const { actionForm, data } = defineProps({
    actionForm: Object,
    data: Array,
})

const showPayModal = ref(false)
const showDetails = ref(false)

function openPayModal(){
    showPayModal.value = true
}

defineExpose({ openPayModal })

</script>