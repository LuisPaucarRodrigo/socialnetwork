<template>
    <Modal :show="showModalDocumentCar">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{ formDocument.id ? "Editar " : "Nueva " }}Documentaciòn UM
            </h2>
            <p>Dueño: {{ show_owner }} Placa: {{ show_plate }}</p>
            <form @submit.prevent="submitDocument">
                <div class="grid grid-cols-1 gap-x-8 gap-y-4 sm:grid-cols-2">
                    <div class="mt-2">
                        <InputLabel for="ownership_card">Tarjeta de Propiedad
                        </InputLabel>
                        <div class="mt-2">
                            <InputFile id="ownership_card" accept=".pdf" v-model="formDocument.ownership_card" />
                            <InputError :message="formDocument.errors.ownership_card
                                " />
                        </div>
                        <div v-if="archivesDocument.ownership_card" class="flex items-center">
                            <span>Archivo: </span>
                            <a target="_blank" :href="route('room.rental.show_documents', {
                                car_document: formDocument.id,
                                fieldName: 'ownership_card',
                            }) + '?' + uniqueParam
                                ">
                                <ShowIcon />
                            </a>
                        </div>
                    </div>

                    <div class="mt-2">
                        <InputLabel for="technical_review">Revision Tecnica
                        </InputLabel>
                        <div class="mt-2">
                            <InputFile id="technical_review" accept=".pdf" v-model="formDocument.technical_review" />
                            <InputError :message="formDocument.errors.technical_review
                                " />
                        </div>
                        <div v-if="archivesDocument.technical_review" class="flex items-center">
                            <span>Archivo: </span>
                            <a target="_blank" :href="route('room.rental.show_documents', {
                                car_document: formDocument.id,
                                fieldName: 'technical_review',
                            }) + '?' + uniqueParam
                                ">
                                <ShowIcon />
                            </a>
                        </div>
                        <template v-if="date_technical_review">
                            <InputLabel for="technical_review">
                                Fecha de Revision Tecnica
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput id="technical_review_date" type="date" v-model="formDocument.technical_review_date
                                    " />
                                <InputError :message="formDocument.errors
                                    .technical_review_date
                                    " />
                            </div>
                        </template>
                    </div>
                    <div class="mt-6">
                        <InputLabel for="soat">SOAT </InputLabel>
                        <div class="mt-2">
                            <InputFile id="soat" accept=".pdf" v-model="formDocument.soat" />
                            <InputError :message="formDocument.errors.soat" />
                        </div>

                        <div v-if="archivesDocument.soat" class="flex items-center">
                            <span>Archivo: </span>
                            <a target="_blank" :href="route('room.rental.show_documents', {
                                car_document: formDocument.id,
                                fieldName: 'soat',
                            }) + '?' + uniqueParam
                                ">
                                <ShowIcon />
                            </a>
                        </div>
                        <InputLabel for="soat_date">
                            Fecha de Soat
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput id="soat_date" type="date" v-model="formDocument.soat_date" />
                            <InputError :message="formDocument.errors.soat_date" />
                        </div>
                    </div>
                    <div class="mt-6">
                        <InputLabel for="insurance">Seguro </InputLabel>
                        <div class="mt-2">
                            <InputFile id="insurance" accept=".pdf" v-model="formDocument.insurance" />
                            <InputError :message="formDocument.errors.insurance" />
                        </div>

                        <div v-if="archivesDocument.insurance" class="flex items-center">
                            <span>Archivo: </span>
                            <a target="_blank" :href="route('room.rental.show_documents', {
                                car_document: formDocument.id,
                                fieldName: 'insurance',
                            }) + '?' + uniqueParam
                                ">
                                <ShowIcon />
                                <ShowIcon />
                            </a>
                        </div>
                        <InputLabel for="insurance_date">
                            Fecha de Seguro
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput type="date" id="insurance_date" v-model="formDocument.insurance_date" />
                            <InputError :message="formDocument.errors.insurance_date" />
                        </div>
                    </div>

                    <div class="mt-6">
                        <InputLabel for="rental_contract">Contrato de alquiler </InputLabel>
                        <div class="mt-2">
                            <InputFile id="rental_contract" accept=".pdf" v-model="formDocument.rental_contract" />
                            <InputError :message="formDocument.errors.rental_contract" />
                        </div>

                        <div v-if="archivesDocument.rental_contract" class="flex items-center">
                            <span>Archivo: </span>
                            <a target="_blank" :href="route('room.rental.show_documents', {
                                car_document: formDocument.id,
                                fieldName: 'rental_contract',
                            }) + '?' + uniqueParam
                                ">
                                <ShowIcon />
                            </a>
                        </div>
                        <InputLabel for="rental_contract_date">
                            Fecha de Contrato de Alquiler
                        </InputLabel>
                        <div class="mt-2">
                            <TextInput type="date" id="rental_contract_date"
                                v-model="formDocument.rental_contract_date" />
                            <InputError :message="formDocument.errors.rental_contract_date" />
                        </div>
                    </div>

                    <div class="mt-6">
                        <InputLabel for="GPS">GPS</InputLabel>
                        <div class="mt-2">
                            <InputLabel for="address_web">Direccion Web </InputLabel>
                            <TextInput type="text" id="address_web" v-model="formDocument.address_web" />
                            <InputError :message="formDocument.errors.address_web" />

                            <InputLabel for="gps_user">Usuario </InputLabel>
                            <TextInput type="text" id="gps_user" v-model="formDocument.user" />
                            <InputError :message="formDocument.errors.user" />

                            <InputLabel for="gps_password">Password </InputLabel>
                            <TextInput type="text" id="password" v-model="formDocument.password" />
                            <InputError :message="formDocument.errors.password" />
                        </div>
                    </div>

                </div>
                <div class="mt-6 flex items-center justify-end gap-x-3">
                    <SecondaryButton @click="openModalDocument">
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
    ownership_card: "",
    technical_review: "",
    technical_review_date: "",
    soat: "",
    soat_date: "",
    insurance: "",
    insurance_date: "",
    rental_contract: "",
    rental_contract_date: "",
    address_web: "",
    user: "",
    password: "",
    car_id: "",
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
        if (response.data) {
            updateCar(response.data, 'udpateDocument')
        } else {
            updateCar(null, 'changeEntry')
        }
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

function openModalCreateDocument(item) {
    show_owner.value = item.user.name
    show_plate.value = item.plate
    date_technical_review.value = item.year + 4 <= new Date().getFullYear();
    archivesDocument.value = {};
    openModalDocument();
    archivesDocument.value = { ...(item.room_document ?? initialFormDocument) };
    formDocument.defaults({
        ...(item.room_document ?? initialFormDocument),
        car_id: item.id,
    });
    formDocument.reset();
}

function openModalDocument() {
    formDocument.clearErrors();
    formDocument.defaults({ ...initialFormDocument });
    formDocument.reset();
    showModalDocumentCar.value = !showModalDocumentCar.value;
}

function updateCar(data, action) {
    const validations = cars.data || cars;
    if (action === "udpateDocument") {
        const index = validations.findIndex(item => item.id === formDocument.car_id);
        validations[index].room_document = data;
        openModalDocument();
        notify("Acciòn Exitosa");
    } else if (action === "changeEntry") {
        openModalDocument();
        notify("Solicitud de actualizacion exitosa");
    }
}
</script>