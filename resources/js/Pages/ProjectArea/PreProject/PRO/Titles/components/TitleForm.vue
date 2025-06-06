<template>
    <Modal :show="create_title">
        <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">
                {{ create_title ? 'Agregar tìtulo' : 'Actualizar tìtulo' }}
            </h2>
            <form @submit.prevent="create_title ? submit() : submitEdit()">
                <div class="space-y-12">
                    <div class="border-b border-gray-900/10 pb-12">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-y-4 gap-x-8">
                            <div>
                                <InputLabel for="title" class="font-medium leading-6 text-gray-900">Título
                                </InputLabel>
                                <div class="mt-2">
                                    <input v-model="form.title" id="title"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors.title" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="type" class="font-medium leading-6 text-gray-900">Tipo
                                </InputLabel>
                                <div class="mt-2">
                                    <select v-model="form.type" id="type"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option value="">Selecciona etapa</option>
                                        <option v-for="stage in stages" :key="stage.id" :value="stage.name">
                                            {{ stage.name }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.type" />
                                </div>
                            </div>

                            <div>
                                <InputLabel for="codes" class="font-medium leading-6 text-gray-900">Códigos
                                </InputLabel>
                                <div class="mt-2">
                                    <select multiple v-model="form.code_id_array" id="codes" size="15"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option v-for="code in props.codes" :key="code.id" :value="code.id">
                                            {{ code.code }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div>
                                <InputLabel for="codes" class="font-medium leading-6 text-gray-900">Codigos
                                    Seleccionados
                                </InputLabel>
                                <div class="mt-2 grid grid-cols-3 gap-2">
                                    <p v-for="cod in form.code_id_array" class="text-xs whitespace-nowrap">
                                        - {{codes.find(item => item.id == cod).code}}
                                    </p>
                                </div>
                            </div>
                        </div>


                        <div class="mt-6 flex items-center justify-end gap-x-6">
                            <SecondaryButton @click="create_title ? close_add_title() : close_edit_title()">
                                Cancelar
                            </SecondaryButton>
                            <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                                {{ create_title ? 'Guardar' : 'Actualizar' }}</PrimaryButton>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </Modal>
</template>
<script setup>
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';

const create_title = ref(false)

const initialStatusForm = {
    id: '',
    title: '',
    type: '',
    code_id_array: [],
}

const form = useForm({ ...initialStatusForm });

function toogleModal() {
    create_title.value = !create_title.value
}

function openModal(item) {
    form.defaults({ ...item })
    form.reset()
    toogleModal()
}

function closeModal() {
    toogleModal()
    form.defaults({ ...initialStatusForm })
    form.reset()
}

const submit = () => {
    form.post(route('preprojects.titles.post'), {
        onSuccess: () => {
            close_add_title();
            form.reset();
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                router.get(route('preprojects.titles'))
            }, 2000);
        },
    });
};

const submitEdit = () => {
    form.put(route('preprojects.titles.put', { title: form.id }), {
        onSuccess: () => {
            close_edit_title();
            form.reset();
            showModalEdit.value = true
            setTimeout(() => {
                showModalEdit.value = false;
                router.get(route('preprojects.titles'))
            }, 2000);
        }
    });
};

defineExpose({ openModal })
</script>