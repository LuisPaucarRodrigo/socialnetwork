<template>
    <Modal :show="showModal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Crear Nomina
            </h2>
            <form @submit.prevent=" submit">
                <div class="grid sm:grid-cols-3 gap-3 pb-6">
                    <div class="col-span-1">
                        <InputLabel for="month" class="font-medium leading-6 text-gray-900">
                            Mes de Nomina
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput type="month" v-model="form.month" id="month" />
                            <InputError :message="form.errors.month" />
                        </div>
                    </div>
                    <div class="col-start-1 col-span-1">
                        <InputLabel for="sctr_p" class="font-medium leading-6 text-gray-900">
                            SCTR P
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput type="number" v-model="form.sctr_p" id="sctr_p" />
                            <InputError :message="form.errors.sctr_p" />
                        </div>
                    </div>
                    <div class="col-span-1">
                        <InputLabel for="sctr_s" class="font-medium leading-6 text-gray-900">
                            SCTR S
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput type="number" v-model="form.sctr_s" id="sctr_s" />
                            <InputError :message="form.errors.sctr_s" />
                        </div>
                    </div>
                    <div class="col-span-3">
                        <InputLabel for="month" class="font-medium leading-6 text-gray-900">
                            Sistema de Pensiones
                        </InputLabel>
                        <p class="mt-1 text-sm leading-6 text-gray-600">
                            Completa los valores para cada sistema de pensión.
                        </p>
                        <div class="grid sm:grid-cols-3 gap-4 mt-4">
                            <div v-for="(pension, index) in form.pension_system" :key="index">
                                <InputLabel :for="'type-' + index">
                                    {{ pension.type }}
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="number" step="0.0001" v-model="form.pension_system[index].values"
                                        :id="'values-' + index" placeholder="Valor" />
                                    <InputError :message="form.errors['pension_system.' + index + '.values']" />
                                    <TextInput type="number" step="0.0001"
                                        v-model="form.pension_system[index].values_seg" :id="'values-seg-' + index"
                                        placeholder="Valor Seg." class="mt-2" />
                                    <InputError :message="form.errors['pension_system.' + index + '.values_seg']" />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-3">
                        <p class="text-red-600">
                            Aviso Importante:<br>
                            Los Datos de los Empleados y el Sistema de Pensiones no se podran actualizar despues de
                            crear la
                            nomina.

                        </p>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-3">
                    <SecondaryButton @click="createOrEditModal">
                        Cancelar
                    </SecondaryButton>
                    <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Guardar
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>
<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify, notifyError } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { setAxiosErrors } from '@/utils/utils';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { payrolls } = defineProps({
    payrolls: Object
})

const showModal = ref(false)

const initialState = {
    id: "",
    month: "",
    state: 0,
    pension_system: [
        { type: "Habitat", values: null, values_seg: "" },
        { type: "Integra", values: "", values_seg: "" },
        { type: "Prima", values: "", values_seg: "" },
        { type: "Profuturo", values: "", values_seg: "" },
        { type: "Habitadmx", values: "", values_seg: "" },
        { type: "Integramx", values: "", values_seg: "" },
        { type: "Profuturomx", values: "", values_seg: "" },
        { type: "Primamx", values: "", values_seg: "" },
        { type: "ONP", values: "", values_seg: "" },
    ],
    sctr_p: '',
    sctr_s: ''
}

const form = useForm({ ...initialState })

const createOrEditModal = () => {
    if (showModal.value) {
        form.clearErrors()
        form.defaults({ ...initialState })
        form.reset()
    }
    showModal.value = !showModal.value
};

async function submit() {
    try {
        const url = route('payroll.store')
        const response = await axios.post(url, form)
        updatePayroll(response.data, 'create')
    } catch (error) {
        console.log(error)
        if (error.response) {
            setAxiosErrors(error.response.data.errors, form)
        } else {
            notifyError("Server Error")
        }
    }
}

function updatePayroll(payroll, action) {
    const validations = payrolls.data || payrolls
    if (action === "create") {
        validations.unshift(payroll);
        createOrEditModal()
        notify("Creacion Exitosa")
    }

    if (validations.length > payrolls.per_page) {
        validations.pop();
    }
}

defineExpose({ createOrEditModal })
</script>