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

const { filterForm, project_id, fixedOrAdditional } = defineProps({
    filterForm: Object,
    project_id: String,
    fixedOrAdditional: Boolean,
})

const showExportArchivesModal = ref(false)
const openExportArchivesModal = () => {
    showExportArchivesModal.value = true
}
const closeExportArchivesModal = () => { showExportArchivesModal.value = false }

async function exportArchives() {
    const uniqueParam = `timestamp=${new Date().getTime()}`;
    const url = route("projectmanagement.pext.expenses.download.zip", { project_id: project_id, fixedOrAdditional: fixedOrAdditional }) +
        '?' + qs.stringify({ ...filterForm, uniqueParam }, { arrayFormat: 'brackets' });
    window.location.href = url;
    closeExportArchivesModal()
}

defineExpose({ openExportArchivesModal })
</script>