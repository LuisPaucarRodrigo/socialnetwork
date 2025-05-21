<template>
    <Modal :show="showSwapAPModal" @close="closeSwapAPModal">
        <div class="p-6 space-y-3">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Mover gastos
            </h2>
            <h4 class="text-sm font-light text-green-900 bg-green-500/10 rounded-lg p-3 ">
                Los gastos seleccionados serán movidos al proyecto especificado en el tipo de gasto especificado.
                Solo se listan los proyectos adicionales que proceden
            </h4>
            <form @submit.prevent="submitSwapAPModal">
                <div class="space-y-4">
                    <div>
                        <InputLabel for="project_id" class="font-medium leading-6 text-gray-900">
                            Seleccionar movimiento
                        </InputLabel>
                        <div class="w-full flex justify-start gap-4 mt-2">
                            <label class="flex gap-2 items-center text-sm">
                                <input type="radio" :value="'same_project'" v-model="additionalProjectForm.type_project"
                                    class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                Al mismo proyecto ({{ fixedOrAdditional ? 'Adicionales' : 'Fijos' }})
                            </label>
                            <label class="flex gap-2 items-center text-sm">
                                <input type="radio" :value="'different_project'"
                                    v-model="additionalProjectForm.type_project"
                                    class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                A otro proyecto adicional
                            </label>
                        </div>
                    </div>
                    <div v-if="additionalProjectForm.type_project === 'different_project'">
                        <InputLabel for="project_id" class="font-medium leading-6 text-gray-900">
                            Seleccionar a que tipo de gasto mover
                        </InputLabel>
                        <div class="w-full flex justify-start gap-4 mt-2">
                            <label class="flex gap-2 items-center text-sm">
                                <input type="radio" :value="'0'" v-model="additionalProjectForm.type_expense"
                                    class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                Gastos variables
                            </label>
                            <label class="flex gap-2 items-center text-sm">
                                <input type="radio" :value="'1'" v-model="additionalProjectForm.type_expense"
                                    class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                Gastos fijos
                            </label>
                        </div>
                    </div>

                    <div v-if="additionalProjectForm.type_project === 'different_project'"
                        class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                        <div>
                            <InputLabel for="project_id" class="font-medium leading-6 text-gray-900">
                                Proyecto Adicional
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="additionalProjectForm.project_id" id="project_id"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">
                                        Seleccionar Proyecto
                                    </option>
                                    <option v-for="item in additional_projects" :key="item.id" :value="item.id">
                                        {{ item.description }}
                                    </option>
                                </select>
                                <InputError :message="additionalProjectForm.errors.project_id" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="closeSwapAPModal">
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
import { useForm } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const { actionForm } = defineProps({
    actionForm: Object
})
const expenses = defineModel('expenses')
const showSwapAPModal = ref(false)
const isFetching = ref(false)

const additionalProjectForm = useForm({
    project_id: '',
    type_project: 'same_project',
    type_expense: '',
})

watch(() => additionalProjectForm.type_project, () => {
    additionalProjectForm.type_expense = ''
    additionalProjectForm.project_id = ''
})

const submitSwapAPModal = async () => {
    isFetching.value = true;
    await axios
        .post(route("projectmanagement.additionalToAdditional.swapCosts"), {
            ...additionalProjectForm.data(),
            ...actionForm
        })
        .catch((e) => {
            isFetching.value = false;
            if (e.response?.data?.errors) {
                setAxiosErrors(e.response.data.errors, additionalProjectForm);
            } else {
                notifyError("Server Error");
            }
        });
    if (expenses.value.data) {
        expenses.value.data = expenses.value.data.filter((item) =>
            !actionForm.ids.includes(item.id));
    }
    else {
        expenses.value = expenses.value.filter((item) =>
            !actionForm.ids.includes(item.id));
    }
    reload_flag.value = !reload_flag.value
    actionForm.ids = []

    closeSwapAPModal();
    notify("Registros Movidos con éxito")
}

const closeSwapAPModal = () => {
    showSwapAPModal.value = false
    isFetching.value = false
    additionalProjectForm.reset()
    additionalProjectForm.clearErrors()
}

const openSwapAPModal = () => {
    if (actionForm.ids.length === 0) {
        notifyWarning("No hay registros seleccionados");
        return;
    }
    showSwapAPModal.value = true
}
defineExpose({ openSwapAPModal })
</script>