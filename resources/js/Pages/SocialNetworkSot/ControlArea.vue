<template>

    <Head title="Area de Control" />
    <AuthenticatedLayout :redirectRoute="'socialnetwork.sot'">
        <template #header>
            Area de Control
        </template>

        <div class="min-w-full rounded-lg shadow">
            <div class="mt-6 sm:flex sm:gap-4 sm:justify-end">
                <div class="mt-2">
                    <SelectSNSotComponent currentSelect="Área de Control" />
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full whitespace-no-wrap">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cod SOT
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Instalacion
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                RF
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Rechazos
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="control in controls.data" :key="control.id" class="text-gray-700">
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ control.name }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ control.sot_control?.p_bad_installation
                                    }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ control.sot_control?.p_no_rf }}
                                </p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <p class="text-gray-900 whitespace-no-wrap">{{ control.sot_control?.p_rejections }}</p>
                            </td>
                            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                <div class="flex space-x-3 justify-center">
                                    <button @click="actionShowStore(control.sot_control ?? control.id)" type="button"
                                        class="text-blue-900 whitespace-no-wrap">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="controls.links" />
            </div>
        </div>
        <Modal :show="showStoreControl">
            <div class="p-6">
                <h2 class="text-base font-medium leading-7 text-gray-900">
                    Area de Control
                </h2>
                <form @submit.prevent="submit">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="mt-2">
                            <InputLabel for="p_bad_installation">Instalacion
                            </InputLabel>
                            <div class="mt-2">
                                <select id="p_bad_installation" v-model="form.p_bad_installation"
                                    :disabled="form.p_bad_installation && !hasPermission('UserManager')"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Estado</option>
                                    <option>OK</option>
                                    <option>Penalidad</option>
                                </select>
                                <InputError :message="form.errors.p_bad_installation" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="p_no_rf">RF
                            </InputLabel>
                            <div class="mt-2">
                                <select id="p_no_rf" v-model="form.p_no_rf"
                                    :disabled="form.p_no_rf && !hasPermission('UserManager')"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Estado</option>
                                    <option>OK</option>
                                    <option>Penalidad</option>
                                </select>
                                <InputError :message="form.errors.p_no_rf" />
                            </div>
                        </div>
                        <div class="mt-2">
                            <InputLabel for="p_rejections">Rechazos
                            </InputLabel>
                            <div class="mt-2">
                                <select id="p_rejections" v-model="form.p_rejections"
                                    :disabled="form.p_rejections && !hasPermission('UserManager')"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Estado</option>
                                    <option>OK</option>
                                    <option>Penalidad</option>
                                </select>
                                <InputError :message="form.errors.p_rejections" />
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-x-6">
                        <SecondaryButton @click="closeShowStoreControl"> Cancel </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                            Guardar
                        </PrimaryButton>
                    </div>
                </form>
            </div>
        </Modal>
        <SuccessOperationModal :confirming="sotControlUpdate" title="Registrado" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SelectSNSotComponent from '@/Components/SelectSNSotComponent.vue';
import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';

const showStoreControl = ref(false);
const sotControlUpdate = ref(false);

const props = defineProps({
    controls: Object,
    userPermissions: Array
})

function hasPermission(permission) {
    return props.userPermissions.includes(permission)
}

const form = useForm({
    s_n_sot_id: null,
    p_bad_installation: null,
    p_no_rf: null,
    p_rejections: null,
})

function submit() {
    form.put(route('sn.controlArea.update', { sot_id: form.s_n_sot_id }), {
        onSuccess: () => {
            sotControlUpdate.value = true
            setTimeout(() => {
                sotControlUpdate.value = false
                router.visit(route('sn.controlArea.index'));
            }, 2000)
        }
    })
}

function initializeForm(control) {
    form.s_n_sot_id = control.s_n_sot_id;
    form.p_bad_installation = control.p_bad_installation;
    form.p_no_rf = control.p_no_rf;
    form.p_rejections = control.p_rejections;
}

function actionShowStore(control) {
    if (typeof control === "object") {
        initializeForm(control);
    } else {
        form.s_n_sot_id = control
    }
    showStoreControl.value = !showStoreControl.value;
}

function closeShowStoreControl() {
    form.reset()
    showStoreControl.value = !showStoreControl.value
}

</script>