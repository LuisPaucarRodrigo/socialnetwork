<template>
    <Modal :show="showQuickQuote">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                Cotización Rápida
            </h2>
            <form @submit.prevent="submitQuickQuote">
                <div class="space-y-12 mt-4">
                    <div class="grid sm:grid-cols-2 gap-6 pb-6">
                        <div>
                            <InputLabel for="delivery_place">
                                Lugar de Entrega
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="formQuote.delivery_place" id="delivery_place" />
                                <InputError :message="formQuote.errors.delivery_place" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="delivery_time">
                                Tiempo de Entrega
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" v-model="formQuote.delivery_time" id="delivery_time" />
                                <InputError :message="formQuote.errors.delivery_time" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="observations">
                                Observaciones
                            </InputLabel>
                            <div class="mt-2">
                                <textarea class="w-full rounded-xl" v-model="formQuote.observations"
                                    id="observations" />
                                <InputError :message="formQuote.errors.observations" />
                            </div>
                        </div>
                        <div>
                            <InputLabel for="observations">
                                Tiene Fee?
                            </InputLabel>
                            <div class="mt-2 class flex gap-4">
                                <label class="flex gap-2 items-center">
                                    Sí
                                    <input type="radio" v-model="formQuote.fee" id="discount_sctr" :value="true"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <label class="flex gap-2 items-center">
                                    No
                                    <input type="radio" v-model="formQuote.fee" id="discount_sctr" :value="false"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <InputError :message="formQuote.errors.fee" />
                            </div>
                        </div>
                    </div>

                    <div class="pb-6">
                        <div class="flex items-center gap-x-2">
                            <InputLabel for="valuation">
                                Valoración
                            </InputLabel>
                            <button class="text-green-500" type="button" @click="openValuation">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-4 overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr
                                        cclass="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                            Descripción</th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                            Unidad</th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                            Cantidad</th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                            Metrado</th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                            Valor Unitario</th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                            Valor Total</th>
                                        <th
                                            class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in formQuote.project_quote_valuations" :key="index">
                                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                                            {{
                                                item.description }}</td>
                                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                                            {{
                                                item.unit }}</td>
                                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                                            {{
                                                item.days }}</td>
                                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                                            {{
                                                item.metrado }}</td>
                                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                                            {{
                                                item.unit_value }}</td>
                                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                                            {{
                                                item.days * item.metrado * item.unit_value }}</td>
                                        <td class="border-b border-gray-200 bg-white px-2 py-2 text-center text-[13px]">
                                            <button type="button" @click="deleteValoration(index)">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                    stroke-width="1.5" stroke="currentColor"
                                                    class="w-6 h-6 text-red-500">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                </svg>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <InputError :message="formQuote.errors.project_quote_valuations" />
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end gap-x-3">
                        <SecondaryButton @click="closeQuickQuote">
                            Cancelar
                        </SecondaryButton>
                        <PrimaryButton type="submit" :class="{ 'opacity-25': formQuote.processing }"
                            :disabled="formQuote.processing">
                            Guardar
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
    <FormValuation ref="formValuation" :auth="auth" v-model:formQuote="formQuote"/>
</template>
<script setup>
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify, notifyError } from '@/Components/Notification';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { ref } from 'vue';
import FormValuation from './FormValuation.vue';
import { useForm } from '@inertiajs/vue3';
import { setAxiosErrors } from '@/utils/utils';

const { projects, auth } = defineProps({
    projects: Object,
    auth: Object,
})

const showQuickQuote = ref(false)
const formValuation = ref(null)
const initialStateQuote = {
    project_id: '',
    delivery_place: '',
    delivery_time: null,
    user_id: auth.user.id,
    observations: '',
    fee: '',
    project_quote_valuations: [],
}

const formQuote = useForm({ ...initialStateQuote })

function toogleQuickQuote() {
    showQuickQuote.value = !showQuickQuote.value
}

function openQuickQuote(project) {
    const defaultData = project.project_quote || initialStateQuote;
    defaultData.fee = defaultData.fee ? true : false
    formQuote.defaults({ ...defaultData, project_id: project.id });
    formQuote.reset();
    toogleQuickQuote()
}

function closeQuickQuote() {
    formQuote.clearErrors();
    formQuote.defaults({ ...initialStateQuote });
    formQuote.reset();
    toogleQuickQuote()
}

function deleteValoration(index) {
    formQuote.project_quote_valuations.splice(index, 1)
}

async function submitQuickQuote() {
    let url = route('projectmanagement.pext.store.quote', { project_quote_id: formQuote.id })
    try {
        let response = await axios.post(url, formQuote)
        closeQuickQuote()
        updatePext(response.data, 'updateQuote')
    } catch (error) {
        console.log(error)
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, formQuote)
            } else {
                notifyError("Server error:", error.response.data)
            }
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

function updatePext(pext, action) {
    const validations = projects.data || projects
    if (action === "updateQuote") {
        let index = validations.findIndex(item => item.project_id === pext.project_id)
        validations[index].project.project_quote = pext
        notify('Cotizacion Exitosa')
    }
}

function openValuation() {
    formValuation.value.openValuation()
}

defineExpose({ openQuickQuote })
</script>