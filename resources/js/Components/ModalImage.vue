<template>
    <button @click="openModal" type="button" class="bg-blue-500 text-white my-3 py-2 px-4 rounded">
        Subir Foto
      </button>
  
      <!-- Modal -->
      <div v-show="modalVisible" class="py-2 fixed inset-0 z-50 flex items-center justify-center">
        <div class="modal-container flex items-center justify-center">
          <div class="bg-white rounded p-10" >
            <h2 class="text-lg font-semibold">Seleccionar Perfil</h2>

            <input type="hidden" name="imagen_recortada" id="imagen_recortada">

            <div ref="imageContainer" class="mt-4"></div>

            <label for="imageInput" class="bg-whie text-gray border py-2 px-4 rounded mt-4 cursor-pointer">
              Browse
            </label>
            <input type="file" id="imageInput" @change="loadImage" accept="image/*" class="hidden">

  
            <div class="mt-10 flex justify-end">
              <button @click="cropAndSave" type="button" class="bg-blue-500 text-white py-2 px-4 rounded mr-2">Guardar</button>
              <button @click="closeModal" type="button" class="bg-gray-300 py-2 px-4 rounded">Close</button>
            </div>

          </div>
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
    };
  },
  methods: {
    openModal() {
      this.modalVisible = true;
      this.initCroppie();
    },
    closeModal() {
      this.modalVisible = false;
    },
    initCroppie() {
      this.croppie = new Croppie(this.$refs.imageContainer, {
        viewport: { width: 250, height: 250, type: 'circle' },
        boundary: { width: 300, height: 300 },
      });
    },
    loadImage(event) {
      const file = event.target.files[0];
      const reader = new FileReader();
      reader.onload = (e) => {
        this.imageUrl = e.target.result;
        this.croppie.bind({ url: e.target.result });
      };
      reader.readAsDataURL(file);
    },
    cropAndSave() {
        this.croppie.result('blob').then((blob) => {
            const formData = new FormData();
            formData.append('imagen_recortada', blob);

            // Agregar aquí otros datos al formData si es necesario

            // Guardar el formData en el input hidden
            document.getElementById('imagen_recortada').value = blob;

            // Cerrar el modal
            this.closeModal();
        });
    }

  },
};
</script>
