<template>

    <Head title="Productos" />
    <AuthenticatedLayout :redirectRoute="'warehouses.warehouses'">
        <template #header>
            Aprobación de Despachos
        </template>

        <div class="min-w-full p-3 rounded-lg shadow">

            <div class="flex gap-2 m-1 justify-end items-center">
                <select   
                    class="border rounded-md px-4 py-1 w-[150px]"
                    @change="optionChange"
                >
                    <option>Por Aprobar</option>
                    <option>Aprobados</option>
                    <option selected>Rechazados</option>
                </select>

            </div>

            <div class="min-w-full overflow-x-auto rounded-lg shadow">
                <table class="w-full table-auto">
                    <thead>
                        <tr
                            class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Código del Proyecto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Almacén
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Producto
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Precio Unitario
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad Solicitada
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Cantidad Actual de Salidas
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                                Observaciones
                            </th>
                            <th
                                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <template  v-for="item in props.project_entries.data" :key="item.id">
                            
                            <tr
                                class="text-gray-700 border-b">
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ item.project.code }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ item.entry.inventory.warehouse.name }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ item.entry.inventory.purchase_product.name }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">S/. {{ item.unitary_price }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ item.quantity }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ item.current_output_quantity }}</p>
                                </td>
                                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ item.observation }}</p>
                                </td>
                                <td class="border-b border-gray-300 bg-white px-5 py-5 text-sm">
                                    <div>
                                        <p v-if="item.state == false" :class="'text-red-500'">
                                            Rechazado
                                        </p>
                                    </div>
                                </td>
                                
                            </tr> 
                        </template>
                    </tbody>
                </table>
            </div>
            <div class="flex flex-col items-center border-t px-5 py-5 xs:flex-row xs:justify-between">
                <pagination :links="project_entries.links" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import { ref } from 'vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import { formattedDate } from '@/utils/utils';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';


const props = defineProps({
    project_entries: Object,
    warehouseId: Number,
    auth: Object
});


const declineModal = ref(false);
const acceptModal = ref(false);
let project_entry_id = null;

const closeAcceptModal = () => {
    acceptModal.value = false;
}

const closeDeclineModal = () => {
    declineModal.value = false;
}

const openAcceptModal = (id) => {
  project_entry_id = id;
  acceptModal.value = true;
}

const openDeclineModal = (id) => {
  project_entry_id = id;
  declineModal.value = true;
}

const declineRequest = () => { 
  const data = { state: false, project_entry_id: project_entry_id };
  router.post(route('warehouses.dispatches.acceptordecline', {warehouse: props.warehouseId}), data, {
    preserveScroll: true,
    onSuccess:() => {
      acceptModal.value = false
    }
  });
}

const acceptRequest = () => {
  const data = { state: true, project_entry_id: project_entry_id };
  router.post(route('warehouses.dispatches.acceptordecline', {warehouse: props.warehouseId}), data, {
    preserveScroll: true,
    onSuccess:() => {
      router.visit(route('warehouses.dispatches', {warehouse: props.warehouseId}))
      declineModal.value = false
    }
  });
}


//Expandible row
const row = ref(0);
const toggleDetails = (outputs) => {
    if (row.value === outputs[0].project_entry_id) {
        row.value = 0;
    } else {
        row.value = outputs[0].project_entry_id;
    }
}



const showModal = ref(false)
const showSuccessModal = ref(false)
const form = useForm({
    project_entry_id:'',
    quantity:''
})
                                    
const showModalOutput = (id) => {
    form.project_entry_id = id
    showModal.value = true
}

const closeModal = () => {
    showModal.value = false
    form.reset()
}


const submit = () => {
    form.post(route('inventory.special_dispatch_output.store'), {
        onSuccess: () => {
            closeModal();
            showSuccessModal.value = true
            setTimeout(()=>{
                showSuccessModal.value = false
            }, 2000)
        },
    })
}
const deleteOutput = (id) => {
    router.delete(route('inventory.special_dispatch_output.destroy', {
        project_entry_output_id:id
    }))
}



const optionChange = (e) => {
    if (e.target.value === "Por Aprobar" ) {
        router.get(route('warehouses.dispatches', {warehouse: props.warehouseId}))
    } else if (e.target.value === "Aprobados") {
        router.get(route('warehouses.dispatches.approved', {warehouse: props.warehouseId}))
    }
}



</script>
