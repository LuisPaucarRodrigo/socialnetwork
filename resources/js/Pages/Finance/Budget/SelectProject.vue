<template>

    <Head title="Selección del Proyecto" />
    <AuthenticatedLayout :redirectRoute="'selectproject.index'">
        <template #header> Selección del Proyecto </template>
        <Toaster richColors />
        <div class="flex flex-col gap-6">
            <div class="grid sm:grid-cols-5">
                <div class="col-span-full flex flex-col space-y-5">
                    <select v-model="selectedProjectId"
                        class="block w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring focus:border-blue-300">
                        <option value="" selected disabled>
                            Seleccione un proyecto
                        </option>
                        <option v-for="project in projects" :key="project.id" :value="project.id">
                            {{ project.name }} - {{ project.code }}
                        </option>
                    </select>
                    <div class="flex justify-end">
                        <button @click="goToBudget" type="button"
                            class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
                            Seleccionar
                        </button>
                    </div>
                </div>
            </div>

            <div class="flex flex-col gap-6">
                <div class="flex gap-4 items-center">
                    <h3>
                        Líneas de Negocio
                    </h3>
                    <button type="button" @click="openCostLineModal">
                        <PlusCircleIcon />
                    </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div v-for="item, i in dataToRender" :key="i"
                        class="bg-white p-3 rounded-md shadow-sm border border-gray-300 items-center">
                        <div class="grid grid-cols-2">
                            <h2 class="font-semibold tex-2xl mb-3 mr-3">
                                {{ item.name }}
                            </h2>
                            <div class="flex justify-end items-start gap-x-2">
                                <button type="button" @click="openCostLineModal(item)">
                                    <EditIcon />
                                </button>
                                <button type="button" @click="openCostLineDestroyModal(item)">
                                    <DeleteIcon />
                                </button>
                            </div>
                        </div>

                        <div :class="`text-gray-500 text-sm`">
                            <div class="grid grid-cols-1 gap-y-1">
                                <Link :href="route('finance.cost_centers.index', { cl_id: item.id })"
                                    class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Centros de Costos
                                </Link>
                                <Link :href="route('finance.cost_line.employees', { cl_id: item.id })"
                                    class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Colaboradores
                                </Link>
                                <Link :href="'#'"
                                    class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                                Activos
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>



        <Modal :show="showCostLineModal" @close="closeCostLineModal">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900 mb-2">
                    {{ form.id ? "Editar" : "Agregar" }} Línea de Gasto
                </h2>
                <form @submit.prevent="submitCostLineModal">
                    <div class="space-y-12">
                        <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                            <div>
                                <InputLabel for="name" class="font-medium leading-6 text-gray-900">
                                    Nombre
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="form.name" id="name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.name" />
                                </div>
                            </div>
                        </div>
                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="closeCostLineModal">
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

        <ConfirmDeleteModal :confirmingDeletion="confirmCostLineDestroy" itemType="Línea de Gasto"
            :deleteFunction="deleteCostLine" @closeModal="closeCostLineDestroyModal" :processing="isFetching" />




    </AuthenticatedLayout>
</template>

<script setup>
import ConfirmDeleteModal from "@/Components/ConfirmDeleteModal.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { notify, notifyError } from "@/Components/Notification";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Head, router, Link, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/InputError.vue";
import InputLabel from "@/Components/InputLabel.vue";
import { setAxiosErrors } from "@/utils/utils";
import Modal from "@/Components/Modal.vue";
import { Toaster } from "vue-sonner";
import { ref } from "vue";
import { PlusCircleIcon, DeleteIcon, EditIcon } from "@/Components/Icons/Index";

const { projects, costLines } = defineProps({
    projects: Object,
    costLines: Array
});

console.log(projects)

const selectedProjectId = ref("");
const goToBudget = () => {
    if (selectedProjectId.value) {
        router.get(
            route("initialbudget.index", { project: selectedProjectId.value })
        );
    } else {
        return "#";
    }
};

//Read
const dataToRender = ref(costLines)

//Create and Update
const isFetching = ref(false)
const showCostLineModal = ref(false)
const openCostLineModal = (item = null) => {
    showCostLineModal.value = true
    if (item) { form.defaults({ ...item }); form.reset() }
}
const closeCostLineModal = () => {
    showCostLineModal.value = false;
    form.clearErrors()
    form.defaults({ ...initState })
    form.reset()
}
const initState = { name: '', id: '' }
const form = useForm({ ...initState })
const submitCostLineModal = () => {
    isFetching.value = true
    axios.post(route("finance.cost_line.store", { cl_id: form.id, }), form.data())
        .then((res) => {
            if (form.id) {
                const index = dataToRender.value.findIndex((item) => item.id == form.id);
                dataToRender.value[index] = res.data
            } else { dataToRender.value.push(res.data); }
            closeCostLineModal();
            notify("Linea de Negocio Guardada");
        })
        .catch(e => {
            if (e.response?.data?.errors) { setAxiosErrors(e.response.data.errors, form); }
            else { notifyError("Server Error"); }
        })
        .finally(() => {
            isFetching.value = false;
        });
}

//Delete
const confirmCostLineDestroy = ref(false)
const costLineToDelete = ref(null)
const openCostLineDestroyModal = (item) => {
    costLineToDelete.value = item
    confirmCostLineDestroy.value = true
}
const closeCostLineDestroyModal = () => {
    costLineToDelete.value = null
    confirmCostLineDestroy.value = false
}
const deleteCostLine = () => {
    isFetching.value = true
    axios.delete(route("finance.cost_line.destroy", { cl_id: costLineToDelete.value.id }))
        .then((res) => {
            const index = dataToRender.value.findIndex((item) => item.id == costLineToDelete.value.id);
            dataToRender.value.splice(index, 1);
            closeCostLineDestroyModal();
            notify("Linea de Negocio Eliminada");
        })
        .catch(e => {
            if (e.response?.data?.errors) { setAxiosErrors(e.response.data.errors, form); }
            else { notifyError("Server Error"); }
        })
        .finally(() => {
            isFetching.value = false;
        });
}



</script>
<!-- isFetching.value = true; -->