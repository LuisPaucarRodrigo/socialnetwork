<template>
    <Modal :show="showImportModal" @close="closeImportModal">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Importar Excel
            </h2>
            <form @submit.prevent="submitImport">
                <div class="space-y-12">
                    <div class="border-b grid grid-cols-1 gap-6 border-gray-900/10 pb-12">
                        <div>
                            <InputLabel class="font-medium leading-6 text-gray-900">
                                Archivo
                            </InputLabel>
                            <div class="mt-2">
                                <InputFile type="file" v-model="importForm.import_file" accept=".xlsx, .csv"
                                    class="block w-full h-24 rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="importForm.errors.import_file" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="closeImportModal">
                            Cancelar
                        </SecondaryButton>
                        <button type="submit" :class="{ 'opacity-25': importForm.processing }"
                            class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                            Importar
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
</template>
<script setup>
import InputFile from '@/Components/InputFile.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify } from '@/Components/Notification';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const { project_id } = defineProps({
    project_id: Number
})

const showImportModal = ref(false);
const importForm = useForm({
    import_file: undefined,
});

function openImportModal() {
    showImportModal.value = true;
}

function closeImportModal() {
    importForm.reset();
    showImportModal.value = false;
}

function submitImport() {
    importForm.post(
        route("projectmanagement.importAdditionalCost", {
            project_id: project_id,
        }),
        {
            onSuccess: () => {
                closeImportModal();
                notify("Los datos fueron importados con éxito")
                setTimeout(() => {
                    router.visit(
                        route("projectmanagement.additionalCosts", {
                            project_id: project_id,
                        })
                    );
                }, 2000);
            },
        }
    );
}

defineExpose({ openImportModal })
</script>