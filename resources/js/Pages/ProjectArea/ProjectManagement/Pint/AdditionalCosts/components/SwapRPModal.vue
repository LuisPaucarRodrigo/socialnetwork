<template>
    <Modal :show="showSwapRPModal" @close="closeSwapRPModal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900 mb-2">
                Gastos a Proyecto Mensual / GEP
            </h2>
            <h4 class="text-sm font-light text-green-900 bg-green-500/10 rounded-lg p-3 ">
                Los registros pasarán a la sección de gastos variables del proyecto seleccionado
            </h4>
            <form @submit.prevent="submitSwapRPModal">
                <div class="space-y-12">
                    <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                        <div class="mt-4">
                            <InputLabel for="project_id" class="font-medium leading-6 text-gray-900">
                                Proyecto Mensual / GEP
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="swapRPForm.project_id" id="project_id"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">
                                        Seleccionar Proyecto
                                    </option>
                                    <option v-for="item in projects_for_swap" :key="item.id" :value="item.id">
                                        {{ item.description }}
                                    </option>
                                </select>
                                <InputError :message="swapRPForm.errors.project_id" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="closeSwapRPModal">
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
import { notify, notifyWarning } from '@/Components/Notification';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useAxiosErrorHandler } from '@/utils/axiosError';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { actionForm } = defineProps({
    actionForm: Object
})

const dataToRender = defineModel('dataToRender')

const showSwapRPModal = ref(false)
const isFetching = ref(false)
const projects_for_swap = ref([])

const swapRPForm = useForm({
    project_id: '',
})

function getRegularProjects() {
    axios.get(route('projectmanagement.regularprojects.all'))
        .then(res => {
            projects_for_swap.value = res.data
        })
}

const openSwapRPModal = () => {
    if (projects_for_swap.value.length === 0) {
        getRegularProjects()
    }
    if (actionForm.ids.length === 0) {
        notifyWarning("No hay registros selccionados");
        return;
    }
    showSwapRPModal.value = true
}

const closeSwapRPModal = () => {
    showSwapRPModal.value = false
    isFetching.value = false
    swapRPForm.reset()
    swapRPForm.clearErrors()
}

const submitSwapRPModal = async () => {
    isFetching.value = true;
    await axios
        .post(route("projectmanagement.regularproject.swapCosts"), {
            ...swapRPForm.data(),
            ...actionForm
        })
        .catch((e) => {
            isFetching.value = false;
            useAxiosErrorHandler(e, swapRPForm)
        });

    dataToRender.value = dataToRender.value.filter(
        (item) => !actionForm.ids.includes(item.id)
    );
    actionForm.ids = []

    closeSwapRPModal();
    notify("Registros Movidos con éxito")
}

defineExpose({ openSwapRPModal })
</script>