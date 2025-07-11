<template>
    <Modal :show="showValuation">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                Agregar Valoraciòn
            </h2>
            <br>

            <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                <div class="">
                    <InputLabel for="days">Dias</InputLabel>
                    <div class="mt-2">
                        <input type="number" v-model="valuation.days" autocomplete="off" id="days"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    </div>
                </div>
                <div class="">
                    <InputLabel for="unit">Unidad</InputLabel>
                    <div class="mt-2">
                        <select id="unit" v-model="valuation.unit"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option value="">Seleccionar Unidad</option>
                            <option>UNIDAD</option>
                            <option>METROS</option>
                            <option>CAJA</option>
                            <option>GLB</option>
                            <option>METROS 2</option>
                            <option>METROS 3</option>
                            <option>PIEZA</option>
                            <option>LATA</option>
                            <option>PAQUETE</option>
                            <option>ROLLO</option>
                        </select>
                    </div>
                </div>
                <div class="">
                    <InputLabel for="metrado">Cantidad</InputLabel>
                    <div class="mt-2">
                        <input type="number" v-model="valuation.metrado" autocomplete="off" id="metrado"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />

                    </div>
                </div>
                <div class="">
                    <InputLabel for="unit_value">Valor Unitario</InputLabel>
                    <div class="mt-2">
                        <input type="number" v-model="valuation.unit_value" autocomplete="off" id="unit_value"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />

                    </div>
                </div>
                <div class="">
                    <InputLabel for="description">Descripción</InputLabel>
                    <div class="mt-2">
                        <textarea class="w-full rounded-xl" v-model="valuation.description" />
                    </div>
                </div>
            </div>
            <br>
            <div class="mt-6 flex gap-3 justify-end">
                <SecondaryButton type="button" @click="closeValuation"> Cerrar </SecondaryButton>
                <PrimaryButton type="button" @click="addValuation"> Agregar </PrimaryButton>
            </div>
        </div>
    </Modal>
</template>
<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notifyError } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref } from 'vue';

const { formQuote, auth } = defineProps({
    formQuote: Object,
    auth: Object
})

const showValuation = ref(false)

const initialStateValuation = {
    days: '',
    unit: '',
    metrado: '',
    unit_value: 0,
    user_id: auth.user.id,
    description: ''
}

const valuation = ref({
    ...initialStateValuation
});

function toogleValuation() {
    showValuation.value = !showValuation.value
}

function openValuation() {
    toogleValuation()
}

function closeValuation() {
    toogleValuation()
    valuation.value = { ...initialStateValuation }
}

function addValuation() {
    if (valuation.value.description && valuation.value.metrado && valuation.value.unit && valuation.value.unit_value && valuation.value.days) {
        const newvaluation = {
            description: valuation.value.description,
            metrado: valuation.value.metrado,
            unit: valuation.value.unit,
            unit_value: valuation.value.unit_value,
            days: valuation.value.days
        };
        formQuote.project_quote_valuations.push(newvaluation);
        valuation.value.description = '';
        valuation.value.metrado = '';
        valuation.value.unit = '';
        valuation.value.days = '';
        valuation.value.unit_value = '';
    } else {
        notifyError('Por favor completa todos los campos del formulario.');
    }
}

defineExpose({ openValuation })

</script>