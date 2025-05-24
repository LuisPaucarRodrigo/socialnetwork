<template>
    <Modal :show="showModal">
        <div class="p-6">
            <h1>Dias Subsidiados</h1>
            <button @click="toogleModalForm" class="pb-1 text-green-500">
                + Agregar
            </button>

                <TableStructure>
                    <template #thead>
                        <tr>
                            <TableTitle>Tipo de Suspensión</TableTitle>
                            <TableTitle>Cantidad de Dias</TableTitle>
                            <TableTitle></TableTitle>
                        </tr>
                    </template>
                    <template #tbody>
                        <tr v-for="(item, index) in form.subsidized_days" :key="item.id">
                            <TableRow>{{ item.name }}</TableRow>
                            <TableRow>{{ item.quantity }}</TableRow>
                            <TableRow>
                                <button @click="deleteMaterial(index)">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-red-500">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                    </svg>
                                </button>
                            </TableRow>
                        </tr>
                    </template>
                </TableStructure>
                <div class="mt-6 flex gap-3 justify-end">
                    <SecondaryButton type="button" @click="toogleModal">
                        Cerrar
                    </SecondaryButton>
                </div>
        </div>
    </Modal>
    <Modal :show="showModalForm">
        <div class="p-6">
            <h2></h2>
            <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                <div class="sm:col-span-1">
                    <InputLabel for="unit">Tipo de Suspension</InputLabel>
                    <div class="mt-2">
                        <select id="unit" required v-model="materialObject.id"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            <option disabled value="">Seleccionar Suspension</option>
                            <option v-for="item in listSuspensionType" :key="item.id" :value="item.id">{{ item.name
                                }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="sm:col-span-1">
                    <InputLabel for="pick_date">Cantidad de Dias</InputLabel>
                    <div class="mt-2">
                        <input type="number" id="pick_date" v-model="materialObject.quantity"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                    </div>
                </div>
            </div>
            <div class="mt-6 flex gap-3 justify-end">
                <SecondaryButton type="button" @click="toogleModalForm">
                    Cerrar
                </SecondaryButton>
                <PrimaryButton type="button" @click="addMaterial">
                    Agregar
                </PrimaryButton>
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
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import { useAxiosErrorHandler } from '@/utils/axiosError';
import axios from 'axios';
import { ref, watch } from 'vue';

const { form } = defineProps({
    form:Object
})

const showModal = ref(false)
const showModalForm = ref(false)

function toogleModal() {
    showModal.value = !showModal.value
}

function toogleModalForm() {
    showModalForm.value = !showModalForm.value
}

const listSuspensionType = [
    { id: 1, name: "09-SP. MATERNIDAD - PRE Y POST NATAL" },
    { id: 2, name: "21-SP. INCAP TEMPORAL (SUBSIDIADO)" },
    { id: 3, name: "22-SI. MATERNIDAD - PRE Y POST NATAL" },
]

const materialObject = ref({
    id: "",
    name: "",
    quantity: 0,
});

watch(() => materialObject.value.id, (newVal) => {
    const foundItem = listSuspensionType.find(item => item.id === Number(newVal));
    materialObject.value.name = foundItem ? foundItem.name : "";
});


function addMaterial() {
    if (
        materialObject.value.id &&
        materialObject.value.name &&
        materialObject.value.quantity
    ) {
        const newMaterial = {
            id: materialObject.value.id,
            name: materialObject.value.name,
            quantity: materialObject.value.quantity,
        };
        form.subsidized_days.push(newMaterial);

        materialObject.value.id = "";
        materialObject.value.name = "";
        materialObject.value.quantity = "";
        toogleModalForm()
    } else {
        notifyError("Completar todos los campos")
        console.error("Por favor completa todos los campos del formulario.");
    }
}

function deleteMaterial(index) {
    if (index !== -1) {
        form.subsidized_days.splice(index, 1);
    } else {
        console.error(`No se encontró ningún material con el nombre '${materialName}'.`);
    }
}


defineExpose({ toogleModal })
</script>