<template>

    <Head title="Proyectos" />
    <AuthenticatedLayout :redirectRoute="type == 2 ? 'projectmanagement.pext.index' : 'projectmanagement.index'">
        <template #header>
            Proyectos Adicionales
        </template>
        <Toaster richColors />
        <div class="min-w-full">
            <TableHeader v-model:projects="projects" :type="type"
                :searchCondition="searchCondition" :createOrEditModal="createOrEditModal" />
            <br>

            <AdditionalTable :projects="projects"  :type="type"
                :openQuickQuote="openQuickQuote" :editProject="editProject" />
        </div>

        <FormAssignation :auth="auth" ref="formAssignation" :cost_line="cost_line" :optionZones="optionZones"
            :projects="projects" />

        <FormQuickQuote ref="formQuickQuote" :projects="projects" :auth="auth" />
    </AuthenticatedLayout>
</template>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { setAxiosErrors } from '@/utils/utils';
import { notifyError, notify } from '@/Components/Notification';
import { Toaster } from 'vue-sonner';
import TableHeader from './components/TableHeader.vue';
import AdditionalTable from './components/AdditionalTable.vue';
import FormAssignation from './components/FormAssignation.vue';
import FormQuickQuote from './components/FormQuickQuote.vue';

const { project, auth, searchCondition, cost_line, type, optionZones } = defineProps({
    project: Object,
    auth: Object,
    cost_line: Object,
    type: String,
    searchCondition: {
        type: String,
        Required: false
    },
    optionZones: Array
})
const initialStateQuote = {
    id: null,
    project_id: '',
    delivery_place: '',
    delivery_time: null,
    user_id: auth.user.id,
    observations: '',
    fee: '',
    project_quote_valuations: [],
}

const formQuickQuote = ref(null)
const formQuote = useForm({ ...initialStateQuote })
const valuation = ref({
    days: '',
    unit: '',
    metrado: '',
    unit_value: 0,
    description: ''
});
// const formExport = ref({
//     startDate: "",
//     endDate: ""
// })
// const modalExport = ref(false)

const formAssignation = ref(null)
const projects = ref(project);
const showQuickQuote = ref(false)
const showValuation = ref(false)
// const confirmingProjectDeletion = ref(false);
// const projectToDelete = ref('');

function editProject(pext) {
    formAssignation.value.editProject(pext)
}

// const delete_project = () => {
//     const projectId = projectToDelete.value;
//     router.delete(route('projectmanagement.delete', { project_id: projectId }), {
//         onSuccess: () => {
//             createOrEditModal()
//             router.visit(route('projectmanagement.index'))
//         }
//     });
// }

// const confirmProjectDeletion = (employeeId) => {
//     projectToDelete.value = employeeId;
//     confirmingProjectDeletion.value = true;
// };

const createOrEditModal = () => {
    formAssignation.value.createOrEditModal()

};

function openQuickQuote(item) {
    formQuickQuote.value.openQuickQuote(item)
}

function updatePext(pext, action) {
    const validations = projects.value.data || projects.value
    if (action === "delete") {
        let index = validations.findIndex(item => item.id === pext.id)
        validations[index].splice(index, 1)
        notify('Eliminación Exitosa')
    } if (action === "updateQuote") {
        let index = validations.findIndex(item => item.project_id === pext.project_id)
        validations[index].project.project_quote = pext
        notify('Cotizacion Exitosa')
    }

    if (validations.length > projects.value.per_page) {
        validations.pop();
    }
}


function openModalValuation() {
    showValuation.value = !showValuation.value
}

function addValuation() {
    if (valuation.value.description && valuation.value.metrado && valuation.value.unit && valuation.value.unit_value && valuation.value.days) {
        const newvaluation = {
            description: valuation.value.description,
            metrado: valuation.value.metrado,
            unit: valuation.value.unit,
            unit_value: valuation.value.unit_value,
            days: valuation.value.days
        };
        formQuote.project_quote_valuations.push(newvaluation);
        valuation.value.description = '';
        valuation.value.metrado = '';
        valuation.value.unit = '';
        valuation.value.days = '';
        valuation.value.unit_value = '';
    } else {
        notifyError('Por favor completa todos los campos del formulario.');
    }
}

async function submitQuickQuote() {
    let url = route('projectmanagement.pext.store.quote', { project_quote_id: formQuote.id })
    try {
        let response = await axios.post(url, formQuote)
        closeQuickQuote()
        updatePext(response.data, 'updateQuote')
    } catch (error) {
        if (error.response) {
            if (error.response.data.errors) {
                setAxiosErrors(error.response.data.errors, formQuote)
            } else {
                notifyError("Server error:", error.response.data.message)
            }
        } else {
            notifyError("Network or other error:", error)
        }
    }
}

function deleteValoration(index) {
    formQuote.project_quote_valuations.splice(index, 1)
}


// function modalExportExcel() {
//     modalExport.value = !modalExport.value
// }

// async function exportExcel() {
//     const uniqueParam = `timestamp=${new Date().getTime()}`;
//     let url =
//         route("projectmanagement.pext.export.expenses") +
//         `?start_date=${encodeURIComponent(formExport.startDate)}&end_date=${encodeURIComponent(formExport.endDate)}&${uniqueParam}`;
//     window.location.href = url;
//     modalExportExcel()
// }
</script>
