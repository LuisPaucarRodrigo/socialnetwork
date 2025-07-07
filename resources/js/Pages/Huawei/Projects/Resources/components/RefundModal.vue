<template>
    <Modal :show="refund_modal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Devolver {{ props.equipment ? "Equipo" : "Material" }} a Almacén
            </h2>
            <form @submit.prevent="refund" class="grid grid-cols-2 gap-3">
                <!-- Tercera Fila -->
                <div class="col-span-2 grid grid-cols-2 gap-3">
                    <div v-if="!props.equipment" class="col-span-2">
                        <InputLabel class="mb-1" for="quantity"
                            >Cantidad</InputLabel
                        >
                        <input
                            type="number"
                            min="0"
                            v-model="refundForm.quantity"
                            class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600"
                        />
                        <InputError :message="refundForm.errors.quantity" />
                    </div>
                    <div v-else class="col-span-2">
                        <div
                            class="inline-flex items-center p-2 mt-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
                            role="alert"
                        >
                            <svg
                                class="flex-shrink-0 inline w-4 h-4 me-3"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="currentColor"
                                viewBox="0 0 20 20"
                            >
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"
                                />
                            </svg>
                            <span class="sr-only">Info</span>
                            <div>
                                <span class="font-small"
                                    >El número de serie del equipo seleccionado
                                    será devuelto a inventario.</span
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de Acción -->
                <div
                    class="col-span-2 mt-2 flex items-center justify-end gap-x-6"
                >
                    <SecondaryButton @click="closeRefundModal"
                        >Cancelar</SecondaryButton
                    >
                    <PrimaryButton
                        type="submit"
                        :class="{ 'opacity-25': refundForm.processing }"
                        >Aceptar</PrimaryButton
                    >
                </div>
            </form>
        </div>
    </Modal>
</template>

<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import { setAxiosErrors } from "@/utils/utils";
import Modal from "@/Components/Modal.vue";
import axios from "axios";

const { show, close } = defineProps({
    show: Boolean,
    close: Function
});

const emit = defineEmits(["close"]);

const handleClose = () => {
    
}
</script>
