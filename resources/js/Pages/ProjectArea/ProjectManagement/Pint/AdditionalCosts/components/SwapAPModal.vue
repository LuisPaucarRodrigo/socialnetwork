<template>
    <Modal :show="showSwapAPModal" @close="closeSwapAPModal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900 mb-2">
                Gastos Variables a Proyecto Adicional
            </h2>
            <h4 class="text-sm font-light text-green-900 bg-green-500/10 rounded-lg p-3 ">
                Los registros pasarán al proyecto especificado, según el tipo de gasto se insertaran en fijos o
                variables.
                Solo se listan los proyectos adicionales que proceden
            </h4>
            <form @submit.prevent="submitSwapAPModal">
                <div class="space-y-12">
                    <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                        <div class="mt-4">
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
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify, notifyWarning } from '@/Components/Notification';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { useAxiosErrorHandler } from '@/utils/axiosError';
import { useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const actionForm = defineModel('actionForm')
const dataToRender = defineModel('dataToRender')

const additional_projects = ref([])
const isFetching = ref(false);
const showSwapAPModal = ref(false)

const openSwapAPModal = () => {
    if (actionForm.value.ids.length === 0) {
        notifyWarning("No hay registros seleccionados");
        return;
    }
    showSwapAPModal.value = true
}

const additionalProjectForm = useForm({
    project_id: '',
})

const closeSwapAPModal = () => {
    showSwapAPModal.value = false
    isFetching.value = false
    additionalProjectForm.reset()
    additionalProjectForm.clearErrors()
}

const submitSwapAPModal = async () => {
    isFetching.value = true;
    let url = route("projectmanagement.addctoaddproject.swapCosts")
    try {
        let res = await axios.post(url,
            {
                ...additionalProjectForm.data(),
                ...actionForm.value
            }
        );
        if (res?.status === 207) {
            const ids = res.data.idsList;
            dataToRender.value = dataToRender.value.filter(
                item => !ids.includes(item.id)
            );
            notifyWarning(`Algunos registros no fueron movidos`);
        } else {
            dataToRender.value = dataToRender.value.filter(
                (item) => !actionForm.value.ids.includes(item.id)
            );
            notify("Registros movidos con éxito");
        }

    } catch (e) {
        isFetching.value = false;
        useAxiosErrorHandler(error, additionalProjectForm)
    } finally {
        actionForm.value.ids = [];
        closeSwapAPModal();
    }
};

onMounted(async () => {
    const res = await axios.get(route('additionalProjects'));
    additional_projects.value = res.data;
});

defineExpose({ openSwapAPModal })
</script>