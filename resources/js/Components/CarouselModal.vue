<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from "vue";

// Props para el modal y las imágenes del carrusel
const props = defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    maxWidth: {
        type: String,
        default: "2xl",
    },
    closeable: {
        type: Boolean,
        default: true,
    },
    images: {
        type: Array,
        required: true,
    },
});

// Emitir el evento de cierre
const emit = defineEmits(["close"]);

// Estado del índice actual del carrusel
const currentIndex = ref(0);

// Imagen y título actuales (computed)
const currentImage = computed(() => {
    const currentObject = props.images[currentIndex.value];
    const [title, src] = Object.entries(currentObject)[0];
    return { title, src };
});

// Funciones del carrusel
const nextImage = () => {
    if (currentIndex.value < props.images.length - 1) {
        currentIndex.value++;
    }
};

const prevImage = () => {
    if (currentIndex.value > 0) {
        currentIndex.value--;
    }
};

// Funciones del modal
watch(
    () => props.show,
    () => {
        if (props.show) {
            document.body.style.overflow = "hidden";
        } else {
            document.body.style.overflow = null;
        }
    }
);

const close = () => {
    if (props.closeable) {
        currentIndex.value = 0; // Reiniciar carrusel
        emit("close");
    }
};

const closeOnEscape = (e) => {
    if (e.key === "Escape" && props.show) {
        close();
    }
};

onMounted(() => document.addEventListener("keydown", closeOnEscape));

onUnmounted(() => {
    document.removeEventListener("keydown", closeOnEscape);
    document.body.style.overflow = null;
});

// Clase para manejar el ancho máximo del modal
const maxWidthClass = computed(() => {
    return {
        sm: "sm:max-w-sm",
        md: "sm:max-w-md",
        lg: "sm:max-w-lg",
        xl: "sm:max-w-xl",
        "2xl": "sm:max-w-2xl",
        "4xl": "sm:max-w-4xl",
        "6xl": "sm:max-w-6xl",
    }[props.maxWidth];
});
</script>

<template>
    <Teleport to="body">
        <Transition
            enter-active-class="ease-out duration-300 transition-opacity"
            leave-active-class="ease-in duration-200 transition-opacity"
            enter-from-class="opacity-0"
            enter-to-class="opacity-100"
            leave-from-class="opacity-100"
            leave-to-class="opacity-0"
        >
            <div
                v-if="show"
                class="fixed inset-0 overflow-y-auto px-4 py-6 sm:px-0 z-40"
                scroll-region
            >
                <!-- Fondo oscuro -->
                <div
                    class="fixed inset-0 transform transition-all"
                    @click="close"
                >
                    <div class="absolute inset-0 bg-gray-500 opacity-75" />
                </div>

                <!-- Contenedor del modal -->
                <div
                    class="mb-6 bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:mx-auto"
                    :class="maxWidthClass"
                >
                    <!-- Contenido del carrusel -->
                    <div class="relative flex items-center p-4">
                        <!-- Botón anterior -->
                        <button
                            class="absolute left-0 sm:ml-10 md:ml-20 p-2 sm:p-3 disabled:cursor-not-allowed"
                            :disabled="currentIndex === 0"
                            @click="prevImage"
                            style="top: 50%; transform: translateY(-50%)"
                        >
                            <svg
                                fill="white"
                                :stroke="currentIndex === 0 ? '#858585' : 'black'"
                                stroke-width="3"
                                class="w-6 h-8 sm:w-8 sm:h-10"
                                version="1.1"
                                id="XMLID_54_"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                viewBox="0 0 24 24"
                                xml:space="preserve"
                            >
                                <g id="previous">
                                    <g>
                                        <polygon
                                            points="17.2,23.7 5.4,12 17.2,0.3 18.5,1.7 8.4,12 18.5,22.3"
                                        />
                                    </g>
                                </g>
                            </svg>
                        </button>

                        <!-- Imagen actual -->
                        <div class="flex flex-col items-center mx-auto">
                            <p class="text-lg font-semibold mb-2 text-center">
                                {{ currentImage.title }}
                            </p>
                            <img
                                :src="currentImage.src"
                                alt="Imagen"
                                class="max-w-full max-h-96"
                            />
                        </div>

                        <!-- Botón siguiente -->
                        <button
                            class="absolute right-0 sm:mr-10 md:mr-20 p-2 sm:p-3 disabled:cursor-not-allowed"
                            :disabled="currentIndex === props.images.length - 1"
                            @click="nextImage"
                            style="top: 50%; transform: translateY(-50%)"
                        >
                            <svg
                                fill="none"
                                :stroke="currentIndex === props.images.length - 1 ? '#858585' : 'black'"
                                stroke-width="3"
                                class="w-6 h-8 sm:w-8 sm:h-10"
                                version="1.1"
                                id="XMLID_287_"
                                xmlns="http://www.w3.org/2000/svg"
                                xmlns:xlink="http://www.w3.org/1999/xlink"
                                viewBox="0 0 24 24"
                                xml:space="preserve"
                            >
                                <g id="next">
                                    <g>
                                        <polygon
                                            points="6.8,23.7 5.4,22.3 15.7,12 5.4,1.7 6.8,0.3 18.5,12"
                                        />
                                    </g>
                                </g>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>
