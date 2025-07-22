<template>
  <div class="overflow-x-auto rounded-lg shadow min-h-[60vh]" :class="style">
    <table class="w-full bg-white">
      <thead class="sticky top-0 z-20">
        <slot name="thead"></slot>
      </thead>
      <tbody>
        <template v-if="info">
          <tr v-if="loading">
            <td colspan="100%" class="text-center py-4 text-gray-500">Cargando datos...</td>
          </tr>

          <tr v-else-if="(info.data || info).length === 0">
            <td colspan="100%" class="text-center py-4 text-gray-500">No hay datos disponibles.</td>
          </tr>
        </template>
        <slot name="tbody"></slot>
      </tbody>
    </table>
  </div>
</template>
<script setup>
const { style, info, loading } = defineProps({
  style: {
    type: String,
    default: ''
  },
  info: {
    type: Object,
    default: null,
    required: false
  },
  loading: {
    type: Boolean,
    default: false,
    required: false
  }
})
</script>