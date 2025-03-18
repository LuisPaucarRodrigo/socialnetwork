<template>
  <div class="overflow-x-auto rounded-lg shadow" :class="style">
    <table class="w-full bg-white">
      <thead class="sticky top-0 z-20">
        <slot name="thead"></slot>
      </thead>
      <tbody>
        <slot name="tbody"></slot>
      </tbody>
    </table>
  </div>
</template>
<script setup>
const { style } = defineProps({
  style: {
    type: String,
    default:''
  }
})
</script>