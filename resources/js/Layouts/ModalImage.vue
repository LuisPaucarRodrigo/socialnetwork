<template>
  <div>
    <button @click="openModal" type="button"
      class="rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
      Subir Foto
    </button>
    <!-- Modal -->
    <div v-show="modalVisible" class="py-2 fixed inset-0 z-50 flex items-center justify-center">
      <div class="modal-container flex items-center justify-center">
        <div class="bg-white rounded p-10">
          <h2 class="text-lg font-semibold">Seleccionar Perfil</h2>

          <div ref="imageContainer" class="mt-4"></div>

          <label for="imageInput" class="bg-white text-gray border py-2 px-4 rounded mt-4 cursor-pointer">
            Browse
          </label>
          <input type="file" id="imageInput" @change="loadImage" accept="image/*" class="hidden">

          <div class="mt-10 flex justify-end">
            <button @click="cropAndSave" type="button"
              class="bg-blue-500 text-white py-2 px-4 rounded mr-2">Guardar</button>
            <button @click="closeModal" type="button" class="bg-gray-300 py-2 px-4 rounded">Close</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Mensaje de éxito -->
    <div v-if="successMessage" class="mt-2 text-green-500">
      {{ successMessage }}
    </div>
  </div>
</template>

<script>
import 'croppie/croppie.css';
import Croppie from 'croppie';

export default {
  mounted() {
    this.initCroppie();
  },
  data() {
    return {
      modalVisible: false,
      croppie: null,
      imageUrl: null,
      successMessage: null, // Nuevo estado para el mensaje de éxito
    };
  },
  methods: {
    openModal() {
      this.modalVisible = true;
    },
    closeModal() {
      this.modalVisible = false;
      this.successMessage = null; // Restablecer el mensaje al cerrar el modal
    },
    initCroppie() {
      this.croppie = new Croppie(this.$refs.imageContainer, {
        viewport: { width: 250, height: 250, type: 'circle' },
        boundary: { width: 300, height: 300 },
      });
    },
    loadImage(event) {
      const file = event.target.files[0];
      this.imageUrl = URL.createObjectURL(file);
      this.croppie.bind({ url: this.imageUrl });
    },
    cropAndSave() {
      this.croppie.result('blob').then((blob) => {
        const file = new File([blob], 'imagen_recortada.jpg', { type: 'image/jpeg' });

        // Asigna el valor directamente a modelValue
        this.$emit('update:modelValue', file);
        //Cerrar modal
        this.closeModal();
        // Muestra el mensaje de éxito
        this.successMessage = 'La imagen se ha guardado correctamente.';

      });
    },
  },
};
</script>
