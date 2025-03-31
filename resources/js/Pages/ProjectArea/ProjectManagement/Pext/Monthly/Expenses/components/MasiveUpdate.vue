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
                        <button type="submit" :disabled="opNuDateForm.processing"
                            :class="{ 'opacity-25': opNuDateForm.processing }"
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
import SecondaryButton from "@/Components/SecondaryButton.vue";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Modal from "@/Components/Modal.vue";
import { setAxiosErrors } from "@/utils/utils";
import { notify, notifyError } from "@/Components/Notification";
import { useForm } from "@inertiajs/vue3";

const { expenses } = defineProps({
    expenses: Object
})

const showOpNuDatModal = defineModel('showOpNuDatModal')

const opNuDateForm = useForm({
    operation_date: '',
    operation_number: '',
})

async function submitOpNuDatModal() {
    let url = route("projectmanagement.pext.massiveUpdate")
    try {
        let response = await axios.post(url, {
            ...opNuDateForm.data(),
            ...actionForm.value
        })
        updateExpense(response.data, 'masiveUpdate')
    } catch (error) {
        // isFetching.value = false;
        if (error.response?.data?.errors) {
            setAxiosErrors(error.response.data.errors, opNuDateForm);
        } else {
            notifyError("Server Error");
        }
    }
}

function updateExpense(expense, action) {
    let listDate = expenses.data || expenses
    if (action === "masiveUpdate") {
        const originalMap = new Map(listDate.map(item => [item.id, item]));
        expense.forEach(update => {
            if (originalMap.has(update.id)) {
                originalMap.set(update.id, update);
            }
        });
        const updatedArray = Array.from(originalMap.values());
        listDate = updatedArray
        closeOpNuDatModal();
        notify("Registros Seleccionados Actualizados");
    }
}

const closeOpNuDatModal = () => {
    showOpNuDatModal.value = false
    // isFetching.value = false
    opNuDateForm.reset()
    opNuDateForm.clearErrors()
}

</script>