<template>
    <Modal :show="confirmingUserDeletion" @close="closeModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-900">
                ¿Estás seguro de que quieres eliminar la cuenta?
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Una vez que se elimine la cuenta, todos sus recursos y datos se eliminarán permanentemente. Por
                favor
                ingrese su contraseña para confirmar que desea eliminar permanentemente la cuenta.
            </p>

            <div class="mt-6">
                <InputLabel for="password" value="Password" class="sr-only" />
                <TextInput id="password" ref="passwordInput" v-model="form.password" type="password"
                    class="mt-1 block w-3/4" placeholder="Password" @keyup.enter="deleteUser" />

                <InputError :message="form.errors.password" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end">
                <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>

                <DangerButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                    @click="deleteUser">
                    Eliminar Cuenta
                </DangerButton>
            </div>
        </div>
    </Modal>
</template>
<script setup>
import DangerButton from '@/Components/DangerButton.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import Modal from '@/Components/Modal.vue';
import { notify, notifyError } from '@/Components/Notification';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { setAxiosErrors } from '@/utils/utils';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const confirmingUserDeletion = defineModel('confirmingUserDeletion')
const usersToDelete = defineModel('usersToDelete')
const users = defineModel('users')
const passwordInput = ref(null);

const form = useForm({
    password: '',
});

async function deleteUser() {
    const userId = usersToDelete.value;
    const url = route('users.destroy', { id: userId })
    try {
        await axios.post(url, form)
        updateFrontEnd('delete', userId)
    } catch (error) {
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, form)
            } else {
                notifyError(`Server error: ${error.response.data}`)
            }
        } else {
            notifyError(`Network or other error: ${error}`)
        }
    }
};

const closeModal = () => {
    confirmingUserDeletion.value = false;
    form.reset();
};

function updateFrontEnd(action, data) {
    if (action === "delete") {
        let validations = users.value.data || users.value
        const index = validations.findIndex(item => item.id === data)
        validations.splice(index, 1)
        closeModal()
        notify("Usuario eliminado con exito.")
    }
}

</script>