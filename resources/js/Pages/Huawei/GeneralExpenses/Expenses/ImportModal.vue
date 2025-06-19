<template>
    <Modal :show="show" @close="close" :closeable="true">
            <div class="p-6">
                <h2 class="text-base font-black leading-7 text-gray-900">
                    Importar Excel
                </h2>
                <h2 class="font-black text-lg mt-4 text-indigo-700">
                    Consideraciones:
                </h2>
                <div
                    class="bg-indigo-50 border-l-4 text-sm border-indigo-500 p-4 rounded-lg shadow-sm mt-2"
                >
                    <p class="text-gray-700">
                        <span class="font-semibold text-indigo-600">•</span>
                        Descargue la
                        <a
                            :href="
                                route(
                                    'huawei.projects.general.expenses.donwloadtemplate'
                                )
                            "
                            class="font-black text-indigo-600 hover:underline"
                            >ESTRUCTURA DE DATOS.</a
                        >
                    </p>
                    <p class="text-gray-700">
                        <span class="font-semibold text-indigo-600">•</span>
                        La importación
                        <span class="font-black text-indigo-600"
                            >NO VA A SOBREESCRIBIR</span
                        >
                        los datos actuales, solo
                        <span class="font-black text-indigo-600"
                            >CREARÁ NUEVOS REGISTROS</span
                        >.
                    </p>
                    <p class="text-gray-700">
                        <span class="font-semibold text-indigo-600">•</span> El
                        signo (<span class="text-indigo-600 font-semibold"
                            >*</span
                        >) indica que el campo es obligatorio.
                    </p>
                    <!-- <p class="text-gray-700">
                        <span class="font-semibold text-indigo-600">•</span> Las
                        columnas
                        <span class="font-semibold text-indigo-600"
                            >A, D y N</span
                        >
                        tienen validación de datos (usar los datos de la lista
                        preferiblemente).
                    </p> -->
                    <p class="text-gray-700">
                        <span class="font-semibold text-indigo-600">•</span>
                        Escribir las fechas en formato regular, e.j.
                        <span class="font-mono text-gray-900">20-02-2025</span>.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-5 text-sm">
                    <div
                        class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                    >
                        <span class="font-black text-indigo-700"
                            >* Columna A:</span
                        >
                        <span class="text-gray-700">Personal</span>
                    </div>
                    <div
                        class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                    >
                        <span class="font-black text-indigo-700"
                            >Columna B:</span
                        >
                        <span class="text-gray-700">DU del Proyecto</span>
                    </div>
                    <div
                        class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                    >
                        <span class="font-black text-indigo-700"
                            >* Columna C:</span
                        >
                        <span class="text-gray-700">Fecha del gasto</span>
                    </div>
                    <div
                        class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                    >
                        <span class="font-black text-indigo-700"
                            >* Columna D:</span
                        >
                        <span class="text-gray-700">Tipo de CDP</span>
                    </div>
                    <div
                        class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                    >
                        <span class="font-black text-indigo-700"
                            >Columna E:</span
                        >
                        <span class="text-gray-700">N° de Serie</span>
                    </div>
                    <div
                        class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                    >
                        <span class="font-black text-indigo-700"
                            >Columna F:</span
                        >
                        <span class="text-gray-700">Correlativo</span>
                    </div>
                    <div
                        class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                    >
                        <span class="font-black text-indigo-700"
                            >Columna G:</span
                        >
                        <span class="text-gray-700">RUC</span>
                    </div>
                    <div
                        class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                    >
                        <span class="font-black text-indigo-700"
                            >* Columna H:</span
                        >
                        <span class="text-gray-700">Monto</span>
                    </div>
                    <div
                        class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                    >
                        <span class="font-black text-indigo-700"
                            >* Columna I:</span
                        >
                        <span class="text-gray-700">Descripción</span>
                    </div>
                    <div
                        class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                    >
                        <span class="font-black text-indigo-700"
                            >* Columna J:</span
                        >
                        <span class="text-gray-700">Tipo de gasto</span>
                    </div>
                    <div
                        class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                    >
                        <span class="font-black text-indigo-700"
                            >Columna K:</span
                        >
                        <span class="text-gray-700">Tipo de cuenta</span>
                    </div>
                    <div
                        class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                    >
                        <span class="font-black text-indigo-700"
                            >Columna L:</span
                        >
                        <span class="text-gray-700"
                            >Fecha de Depósito E.C.</span
                        >
                    </div>
                    <div
                        class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                    >
                        <span class="font-black text-indigo-700"
                            >Columna M:</span
                        >
                        <span class="text-gray-700">N° Operación E.C.</span>
                    </div>
                    <div
                        class="flex items-center gap-2 bg-gray-100 p-3 rounded-lg shadow-sm"
                    >
                        <span class="font-black text-indigo-700"
                            >Columna N:</span
                        >
                        <span class="text-gray-700">Monto E.C.</span>
                    </div>
                </div>

                <form @submit.prevent="importExcel">
                    <div class="space-y-12 mt-4">
                        <div class="grid sm:grid-cols-2 gap-6 pb-6">
                            <div class="md:col-span-2 col-span-1">
                                <InputLabel
                                    for="file"
                                    class="font-medium leading-6 text-gray-900"
                                    >Archivo</InputLabel
                                >
                                <div class="mt-2">
                                    <InputFile
                                        type="file"
                                        v-model="formFile.file"
                                        accept=".xlsx"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                    />
                                    <InputError
                                        :message="formFile.errors.file"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end gap-4">
                        <SecondaryButton @click="close">
                            Cancelar
                        </SecondaryButton>
                        <button
                            type="submit"
                            :disabled="isFetching"
                            :class="{ 'opacity-25': isFetching }"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600"
                        >
                            Guardar
                        </button>
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
import InputFile from "@/Components/InputFile.vue";
import { notify, notifyError } from "@/Components/Notification";

const { show, isFetching, close } = defineProps({
    show: Boolean,
    isFetching: Boolean,
    close: Function
});

const emit = defineEmits(["close", "update"]);

const formFile = useForm({
    file: "",
});

const importExcel = () => {
    const url = route("huawei.projects.general.expenses.import");
    formFile.post(url, {
        onSuccess: () => {
            close();
            notify("Se importó el archivo correctamente");
            setTimeout(() => {
                router.visit(route("huawei.projects.general.expenses"));
            }, 2000);
        },
        onError: (e) => {
            notifyError(e.message);
        },
    });
};

</script>