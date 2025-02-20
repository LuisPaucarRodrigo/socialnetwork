<template>
    <div class="overflow-x-auto h-[72vh] rounded-lg shadow">
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
  