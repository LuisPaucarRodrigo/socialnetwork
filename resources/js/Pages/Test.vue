<template>
    <div>
      <h1>Árbol de carpetas y archivos</h1>
      <folder-tree :folder-structure="folderStructure" :level="0"></folder-tree>
    </div>
  </template>
  
  <script>
  import FolderTree from '../Components/FolderTree.vue';
  
  export default {
    name: 'App',
    components: {
      FolderTree
    },
    data() {
      return {
        folderStructure: {
          hola: {
            "hola-llll": {
              "0": "OTROARC O.txt",
              "TEST": []
            },
            "0": "test.txt"
          },
          hola2: []
        }
      };
    }
  }
  </script>
  