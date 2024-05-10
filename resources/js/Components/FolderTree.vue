<template>
    <ul>
      <li v-for="(item, itemName, i) in folderStructure" :key="i">
        <span :style="{ marginLeft: (level * 20) + 'px' }">{{ typeof item === 'object' ? itemName + ' (' + item.path + ')' : item }}</span>
        <template v-if="typeof item === 'object'">
          <folder-tree :folder-structure="item" :level="level + 1"></folder-tree>
        </template>
      </li>
    </ul>
  </template>
  
  <script>
  export default {
    name: 'FolderTree',
    props: {
      folderStructure: Object,
      level: {
        type: Number,
        default: 0
      }
    }
  }
  </script>
  