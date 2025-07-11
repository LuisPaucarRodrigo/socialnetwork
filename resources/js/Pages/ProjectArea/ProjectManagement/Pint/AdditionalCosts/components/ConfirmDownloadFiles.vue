<template>
    <ConfirmateModal tittle="Descarga de archivos"
        text="La descarga de archivos será en base a los filtros que están activos, si no hay filtros activos se descargarán de todos los registros. PARA AMBOS CASOS SOLO ES PARA REGISTROS ACEPTADOS"
        :showConfirm="showExportArchivesModal" :actionFunction="exportArchives"
        @closeModal="closeExportArchivesModal" />
</template>
<script setup>
import ConfirmateModal from '@/Components/ConfirmateModal.vue';
import qs from 'qs';
import { ref } from 'vue';

const { filterForm, project_id } = defineProps({
    filterForm: Object,
    project_id: Number
})

const showExportArchivesModal = ref(false)
const openExportArchivesModal = () => { showExportArchivesModal.value = true }
const closeExportArchivesModal = () => { showExportArchivesModal.value = false }

function exportArchives() {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url = route("zip.additional.descargar", { project_id: project_id }) +
        '?' + qs.stringify({ ...filterForm, uniqueParam }, { arrayFormat: 'brackets' });
    window.location.href = url;
    closeExportArchivesModal()
}

defineExpose({ openExportArchivesModal })
</script>