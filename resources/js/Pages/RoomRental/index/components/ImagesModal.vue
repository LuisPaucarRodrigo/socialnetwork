<template>
    <Modal :show="showImageModal">
        <div class="p-6">
            <h2 class="text-lg font-medium text-gray-800 border-b-2 border-gray-100">
                Imagenes
            </h2>
            <TableStructure>
                <template #thead>
                    <tr>
                        <TableTitle>
                            Nombre
                        </TableTitle>
                        <TableTitle>
                            Acciones
                        </TableTitle>
                    </tr>
                </template>
                <template #tbody>
                    <tr v-for="(item, i) in listImageView" :key="i" class="text-gray-700">
                        <TableRow width="w-[300px]">{{ item.photo }}</TableRow>
                        <TableRow>
                            <div class="flex gap-x-2">
                                <a :href="route('room.rental.show.image', { image: item.id }) + '?' + uniqueParam"
                                    target="_blank">
                                    <ShowIcon />
                                </a>
                                <button v-permission="'room_actions_manager'" type="button"
                                    @click="delete_image(item.id)">
                                    <DeleteIcon />
                                </button>
                            </div>
                        </TableRow>
                    </tr>
                </template>
            </TableStructure>
            <div class="mt-6 flex justify-end gap-x-3">
                <SecondaryButton @click="closeImageModal()">Cerrar</SecondaryButton>
            </div>
        </div>
    </Modal>
</template>
<script setup>
import { DeleteIcon, ShowIcon } from '@/Components/Icons';
import Modal from '@/Components/Modal.vue';
import { notify, notifyError } from '@/Components/Notification';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TableRow from '@/Components/TableRow.vue';
import TableTitle from '@/Components/TableTitle.vue';
import TableStructure from '@/Layouts/TableStructure.vue';
import { ref } from 'vue';

const uniqueParam = `timestamp=${new Date().getTime()}`;
const showImageModal = ref(null)

const listImageView = ref([])

function toogleModal() {
    showImageModal.value = !showImageModal.value
}

async function openImagesModal(room_id) {
    let url = route('room.rental.getImages', { room_id: room_id })
    try {
        let response = await axios.get(url)
        listImageView.value = response.data
        toogleModal()
    } catch (error) {
        listImageView.value = [];
        notifyError(`Error: ${error}`)
    }
}

async function delete_image(image_id) {
    const url = route('room.rental.destroy_image', { image: image_id });
    try {
        await axios.delete(url)
        updateFrontEnd(image_id)
    } catch (error) {
        console.error('Error fetching image URL:', error);
    }
}

function closeImageModal() {
    toogleModal()
}

function updateFrontEnd(image_id) {
    const index = listImageView.value.findIndex(item => item.id === image_id);
    listImageView.value.splice(index, 1)
    closeImageModal()
    notify("Eliminación exitosa")
}

defineExpose({ openImagesModal })
</script>