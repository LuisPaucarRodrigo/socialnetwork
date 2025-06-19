<template>
    <Modal :show="showFotocheck" :closeable="true" @close="closeFotoCheck" :maxWidth="'sm'">
        <div class="flex items-center justify-center bg-gray-100">
            <div class="py-2">
                <div ref="fotocheck">
                    <div class="w-[300px] h-[450px] relative bg-white shadow-md rounded overflow-hidden">
                        <img :src="backgroundImage" class="absolute inset-0 w-full h-full object-cover " />
                        <div v-if="isConproco" class="relative mt-32">
                            <img :src="employee.cropped_image"
                                class="w-40 h-40 rounded-full mx-auto border-4 border-white" />
                            <div class="mt-10">
                                <h2 class="text-center text-lg mt-2 text-white">{{ employee.name }} {{ employee.lastname
                                }}
                                </h2>
                                <p class="text-center text-sm text-white">DNI: {{ employee.dni }}</p>
                                <p class="text-center text-sm text-white">{{ employee.position }}</p>
                            </div>
                        </div>
                        <div v-else class="relative mt-44">
                            <div class="flex">
                                <img :src="employee.cropped_image"
                                    class="w-40 h-40 rounded-full mx-auto border-4 border-white" />
                                <div class="px-4">
                                    <h2 class="text-center text-lg mt-2 text-black">{{ employee.name }} {{
                                        employee.lastname }}
                                    </h2>
                                    <p class="text-center text-sm text-black">DNI: {{ employee.dni }}</p>
                                    <p class="text-center text-sm text-red">{{ employee.position }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="flex gap-x-2">
                    <button @click="switchImages()" :class="isConproco ? 'bg-red-500' : 'bg-blue-500'"
                        class="mt-4 text-white px-4 py-2 rounded w-1/3">
                        {{ isConproco ? 'Claro' : 'Conproco' }}
                    </button>
                    <button @click="downloadFotocheck" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded w-2/3">
                        Descargar

                    </button>
                </div>

            </div>
        </div>
    </Modal>
</template>
<script setup>
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import html2canvas from 'html2canvas'

const showFotocheck = ref(false)
const fotocheck = ref(null)
const isConproco = ref(true)
const backgroundImage = ref('/image/projectimage/fotocheckConproco.jpeg')
const employee = ref({
    name: '',
    lastname: '',
    dni: '',
    position: '',
    cropped_image: ''
})

function switchImages() {
    backgroundImage.value = isConproco.value ? '/image/projectimage/fotocheckClaro.jpeg' : '/image/projectimage/fotocheckConproco.jpeg'
    isConproco.value = !isConproco.value
}

function toogleModal() {
    showFotocheck.value = !showFotocheck.value
}

function openFotoCheck(item) {
    toogleModal()
    employee.value = { ...item }
}

function closeFotoCheck() {
    toogleModal()
    employee.value = { ...{} }
}

async function downloadFotocheck() {
    const canvas = await html2canvas(fotocheck.value)
    const link = document.createElement('a')
    link.download = `${employee.value.name}-fotocheck.png`
    link.href = canvas.toDataURL()
    link.click()
}

defineExpose({ openFotoCheck })
</script>