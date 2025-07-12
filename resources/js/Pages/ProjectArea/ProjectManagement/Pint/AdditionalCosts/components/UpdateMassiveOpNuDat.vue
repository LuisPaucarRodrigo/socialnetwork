<template>
    <Modal :show="showOpNuDatModal" @close="closeOpNuDatModal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900 mb-2">
                Actualización Masiva
            </h2>
            <h4 class="text-sm font-light text-green-900 bg-green-500/10 rounded-lg p-3 ">
                Los registros con fecha de operación y número de operación, pasarán a automáticamente estar
                aceptados.
            </h4>
            <form @submit.prevent="submitOpNuDatModal">
                <div class="space-y-12">
                    <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                        <div>
                            <InputLabel for="operation_number" class="font-medium leading-6 text-gray-900">Numero de
                                Operación
                            </InputLabel>
                            <div class="mt-2">
                                <input type="text" v-model="opNuDateForm.operation_number" id="operation_number"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="opNuDateForm.errors.operation_number" />
                            </div>
                        </div>

                        <div>
                            <InputLabel for="operation_date" class="font-medium leading-6 text-gray-900">Fecha de
                                Operación
                            </InputLabel>
                            <div class="mt-2">
                                <input type="date" v-model="opNuDateForm.operation_date" id="operation_date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="opNuDateForm.errors.operation_date" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="closeOpNuDatModal">
                            Cancelar
                        </SecondaryButton>
                        <button type="submit" :disabled="isFetching" :class="{ 'opacity-25': isFetching }"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Guardar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
</template>
<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify, notifyError, notifyWarning } from '@/Components/Notification';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useAxiosErrorHandler } from '@/utils/axiosError';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { actionForm } = defineProps({
    actionForm: Object
})
const dataToRender = defineModel('dataToRender')
const showOpNuDatModal = ref(false)
const isFetching = ref(false)

const opNuDateForm = useForm({
    operation_date: '',
    operation_number: '',
})

const closeOpNuDatModal = () => {
    showOpNuDatModal.value = false
    isFetching.value = false
    opNuDateForm.reset()
    opNuDateForm.clearErrors()
}
const openOpNuDaModal = () => {
    if (actionForm.ids.length === 0) {
        notifyWarning("No hay registros selccionados");
        return;
    }
    showOpNuDatModal.value = true
}

const submitOpNuDatModal = async () => {
    isFetching.value = true;
    const res = await axios
        .post(route("projectmanagement.additionalCosts.massiveUpdate"), {
            ...opNuDateForm.data(),
            ...actionForm
        })
        .catch((e) => {
            isFetching.value = false;
            useAxiosErrorHandler(e, opNuDateForm)
        });
    const resIds = res.data.map(item => item.id);
    const rgsToRemove = actionForm.ids.filter(id => !resIds.includes(id))
    const originalMap = new Map(dataToRender.value.filter(item => !rgsToRemove.includes(item.id)).
        map(item => [item.id, item]));
    res.data.forEach(update => {
        if (originalMap.has(update.id)) {
            originalMap.set(update.id, update);
        }
    });
    const updatedArray = Array.from(originalMap.values());
    dataToRender.value = updatedArray
    actionForm.ids = resIds
    closeOpNuDatModal();
    notify("Registros Seleccionados Actualizados")
    setTimeout(() => {
        if (rgsToRemove.length > 0) notify("Algunos fueron movidos a gastos fijos y/o proyecto GEP")
    }, 1000)
}

defineExpose({ openOpNuDaModal })
</script>