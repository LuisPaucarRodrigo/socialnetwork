<template>
    <Modal :show="showModalDocumentCar">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{ formDocument.id ? "Editar " : "Nueva " }}Documentaciòn de alquiler
            </h2>
            <p>Propietario: {{ show_owner }} </p>
            <form @submit.prevent="submitDocument">
                <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-1">
                    <div>
                        <InputLabel for="archive">
                            Contrato de alquiler
                        </InputLabel>
                        <div class="mt-2">
                            <InputFile id="archive" accept=".pdf" v-model="formDocument.archive" />
                            <InputError :message="formDocument.errors.archive
                                " />
                        </div>
                        <div v-if="archivesDocument.archive" class="flex items-center">
                            <span>Archivo: </span>
                            <a target="_blank" :href="route('room.rental.show_documents', {
                                car_document: formDocument.id,
                                fieldName: 'archive',
                            }) + '?' + uniqueParam
                                ">
                                <ShowIcon />
                            </a>
                        </div>
                    </div>

                    <div>
                        <InputLabel for="expiration_date">
                            Fecha de Expiración
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput type="date" id="expiration_date" v-model="formDocument.expiration_date" />
                            <InputError :message="formDocument.errors.expiration_date" />
                        </div>
                    </div>
                    <div>
                        <InputLabel for="observations">
                            Observaciones
                        </InputLabel>
                        <div class="mt-2">
                            <textarea
                                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                rows="3" v-model="formDocument.observations" autocomplete="off" />
                            <InputError :message="formDocument.errors.observations" />
                        </div>
                    </div>

  
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-3">
                    <SecondaryButton @click="closeModalDocument">
                        Cancel
                    </SecondaryButton>
                    <PrimaryButton type="submit" :class="{ 'opacity-25': formDocument.processing }">
                        {{ formDocument.id ? "Actualizar" : "Crear" }}
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </Modal>
</template>

<script setup>
import { ShowIcon } from '@/Components/Icons';
import InputError from '@/Components/InputError.vue';
import InputFile from '@/Components/InputFile.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify, notifyError } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { setAxiosErrors, toFormData } from '@/utils/utils';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineExpose({ openModalCreateDocument })

const { cars } = defineProps({
    cars: Object
})

const showModalDocumentCar = ref(false);
const show_plate = ref(null);
const show_owner = ref(null);
const archivesDocument = ref({});
const date_technical_review = ref(null);
const uniqueParam = `timestamp=${new Date().getTime()}`;

const initialFormDocument = {
    id: "",
    archive: "",
    observations: "",
    expiration_date: "",
    room_id: "",
};

const formDocument = useForm({
    ...initialFormDocument,
});

async function submitDocument() {
    let url = formDocument.id
        ? route("room.rental.update.document", { car_document: formDocument.id })
        : route("room.rental.store_document");
    let formData = toFormData(formDocument);
    try {
        let response = await axios.post(url, formData);
        updateCar(response.data, 'storeRoomDocument')
    } catch (error) {
        console.log(error);
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, formDocument);
            } else {
                notifyError("Server error:", error.response.data);
            }
        } else {
            notifyError("Network or other error:", error);
        }
    }
}

function openModalCreateDocument(room, room_document=null) {
    show_owner.value = room.provider.company_name
    archivesDocument.value = {};
    closeModalDocument();
    archivesDocument.value = { ...(room_document ?? initialFormDocument) };
    formDocument.defaults({
        ...(room_document ?? initialFormDocument),
        room_id: room.id,
    });
    formDocument.reset();
}

function closeModalDocument() {
    formDocument.clearErrors();
    formDocument.defaults({ ...initialFormDocument });
    formDocument.reset();
    showModalDocumentCar.value = !showModalDocumentCar.value;
}

function updateCar(data, action) {
    const validations = cars.data || cars;
    if (action === "storeRoomDocument") {
        let index = validations.findIndex((item) => item.id === data.id);
        validations[index] = data;
        closeModalDocument();
        notify("Acción Exitosa");
    }
}


// function updateCar(data, action) {
//     const validations = cars.data || cars;
//     if (action === "udpateDocument") {
//         const index = validations.findIndex(item => item.id === formDocument.car_id);
//         validations[index].room_document = data;
//         closeModalDocument();
//         notify("Acciòn Exitosa");
//     } else if (action === "changeEntry") {
//         closeModalDocument();
//         notify("Solicitud de actualizacion exitosa");
//     }
// }
</script>