<template>
    <Modal :show="showExportModal">
        <div class="p-6 flex flex-col gap-6">
            <h2
                class="text-lg font-medium text-gray-800 border-b-2 border-gray-100"
            >
                Exportar Excel Detallado
            </h2>
            <div class="flex flex-col gap-3">
                <div v-for="item in registerTypes" >
                    <label :for="`check-${item}`" class="flex items-center gap-3 px-2 py-1">
                        <input v-model="form.registerSelected" :value="item" :id="`check-${item}`"
                            type="checkbox" />
                        {{ item }}
                    </label>

                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeExportModal">
                    Cancelar
                </SecondaryButton>
                <button type="button" @click="submitExport()"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Exportar
                </button>
            </div>
        </div>
    </Modal>
</template>
<script setup>
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { EyeOutlineIcon, EyeSlashSolidIcon } from "@/Components/Icons";
import InputError from "@/Components/InputError.vue";
import { Link, useForm } from "@inertiajs/vue3";
import { formattedDate } from "@/utils/utils";
import { setAxiosErrors } from "@/utils/utils";
import Modal from "@/Components/Modal.vue";
import { ref } from "vue";
import { notify, notifyError, notifyWarning } from "@/Components/Notification";
import qs from 'qs';

const { payroll } = defineProps({
    payroll: Object
})

const registerTypes = [
    'Ingresos',
    'Descuentos',
    'Tributos',
    'Aportes',
]

const initState = {registerSelected: []}
const form = useForm({...initState})
const showExportModal = ref(false)

function openExportModal(){
    showExportModal.value = true
}

function closeExportModal(){
    form.reset()
    showExportModal.value = false
}

function submitExport(){
    if(form.registerSelected.length<=0) notifyWarning('Seleccione al menos una opción')
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url = route('payroll.detail.export.details', {payroll_id: payroll.id} ) + 
        '?' + qs.stringify(form.data()) + "&" + uniqueParam;
    window.location.href = url;
}


defineExpose({ openExportModal });
</script>
