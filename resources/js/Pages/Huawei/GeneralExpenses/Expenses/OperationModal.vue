<template>
            <Modal :show="show" @close="close" :closeable="true">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Actualización Masiva
                </h2>
                <form @submit.prevent="submitOpNuDatModal(true)">
                    <div class="space-y-12">
                        <div
                            class="grid grid-cols-1 gap-6 border-gray-900/10"
                        >
                            <div>
                                <InputLabel
                                    for="operation_date"
                                    class="font-medium leading-6 text-gray-900"
                                    >Fecha de Operación E.C.
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        type="date"
                                        v-model="opNuDateForm.ec_expense_date"
                                        id="operation_date"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="
                                            opNuDateForm.errors.ec_expense_date
                                        "
                                    />
                                </div>
                            </div>

                            <div>
                                <InputLabel
                                    for="operation_number"
                                    class="font-medium leading-6 text-gray-900"
                                    >Numero de Operación E.C.
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        type="text"
                                        v-model="opNuDateForm.ec_op_number"
                                        id="operation_number"
                                        min="6"
                                        maxlength="6"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="
                                            opNuDateForm.errors.ec_op_number
                                        "
                                    />
                                </div>
                            </div>

                            <div>
                                <InputLabel
                                    for="ec_amount"
                                    class="font-medium leading-6 text-gray-900"
                                    >Monto en E.C.
                                </InputLabel>
                                <div class="mt-2">
                                    <input
                                        type="text"
                                        v-model="opNuDateForm.ec_amount"
                                        id="ec_amount"
                                        min="6"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="
                                            opNuDateForm.errors.ec_amount
                                        "
                                    />
                                </div>
                            </div>
                        </div>
                        <div
                            class="mt-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between"
                        >
                            <button
                                type="button"
                                :disabled="isFetching"
                                @click="submitOpNuDatModal(false)"
                                :class="{ 'opacity-25': isFetching }"
                                class="w-full md:w-auto rounded-md border border-red-600 bg-transparent px-6 py-2 text-sm font-semibold text-red-600 hover:bg-red-50 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600"
                            >
                                Rechazar
                            </button>

                            <div
                                class="flex flex-col gap-4 md:flex-row md:gap-5"
                            >
                                <SecondaryButton
                                    @click="handleClose"
                                    class="w-full md:w-auto justify-center"
                                >
                                    Cancelar
                                </SecondaryButton>
                                <button
                                    type="submit"
                                    :disabled="isFetching"
                                    :class="{ 'opacity-25': isFetching }"
                                    class="w-full rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600 md:w-auto"
                                >
                                    Guardar
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </Modal>
</template>

<script setup>
import Modal from "@/Components/Modal.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { useForm, router } from "@inertiajs/vue3";
import { ref } from 'vue';
import { setAxiosErrors } from "@/utils/utils";

const { show, close, actionForm} = defineProps({
    show: Boolean,
    close: Function,
    actionForm: Object,
});

const emit = defineEmits(["update"]);
const isFetching = ref(false);

const opNuDateForm = useForm({
    ec_expense_date: "",
    ec_op_number: "",
    ec_amount: ""
});

function handleClose(){
    opNuDateForm.reset();
    close();
}

const submitOpNuDatModal = async (mode) => {
    isFetching.value = true;
    try {
        const res = await axios
        .post(route("huawei.projects.general.expenses.massiveupdate", {mode: mode}), {
            ...opNuDateForm.data(),
            ...actionForm,
        });
        isFetching.value = false;
        emit("update", res.data);
    } catch (error) {
        isFetching.value = false;
            if (e.response?.data?.errors) {
                setAxiosErrors(e.response.data.errors, opNuDateForm);
            } else {
                notifyError("Server Error");
            }
    }
};

</script>
