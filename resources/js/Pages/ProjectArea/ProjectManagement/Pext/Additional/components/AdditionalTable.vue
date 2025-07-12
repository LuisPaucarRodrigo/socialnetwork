<template>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3">
        <div class="bg-blue-200 p-3 rounded-md shadow-sm border border-gray-300 items-center">
            <div class="grid grid-cols-2">
                <p class="col-start-1 col-span-1 text-sm font-semibold mb-3">
                    Nombre: General
                </p>
                <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                    Descripcion: Indefinida
                </p>
                <p class="col-start-1 col-span-2 text-sm font-semibold mb-3">
                    Centro de Costos: Sin Centro de Costo
                </p>
            </div>
            <div>
                <div class="grid grid-cols-1 gap-y-1">
                    <Link :href="route('pext.additional.expense.general.index', { fixedOrAdditional: false, type })"
                        class="text-blue-600 underline whitespace-no-wrap hover:text-purple-600">
                    Compras y Gastos
                    </Link>
                </div>
            </div>
        </div>
        <ProjectCard v-for="item in projects.data || projects" :key="item.id" :item="item"
            :rejectOrReturnAdditionalProject="rejectAdditionalProject" :type="type" :openQuickQuote="openQuickQuote"
            :editProject="editProject" />
        <div v-if="projects.data"
            class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
            <pagination :links="projects.links" />
        </div>
    </div>
</template>
<script setup>
import Pagination from '@/Components/Pagination.vue'
import { Link } from '@inertiajs/vue3';
import ProjectCard from './ProjectCard.vue';

const { projects, type, openQuickQuote, editProject } = defineProps({
    projects: Object,
    type: String,
    openQuickQuote: Function,
    editProject: Function
})

async function rejectAdditionalProject(id) {
    try {
        const res = await axios.post(route('projectmanagement.pext.additional.reject', { pa_id: id }), { action: 'rejected' })
        if (res.data.msg) {
            const validations = projects.data || projects
            let index = validations.findIndex(item => item.project.id === id)
            validations.splice(index, 1)
        }
    } catch (e) {
        console.error(e)
        notifyError('Server Error')
    }
}
</script>