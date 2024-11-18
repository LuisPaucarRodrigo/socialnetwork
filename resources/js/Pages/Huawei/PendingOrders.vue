<template>
    <Head title="Huawei" />
    <AuthenticatedLayout>
        <template #header> Pedidos Pendientes </template>
        <div class="min-w-full rounded-lg shadow">
            <div>
                <div>
                    <div class="overflow-x-auto mt-3 h-[85vh]">
                        <table class="w-full whitespace-no-wrap">
                            <thead>
                                <tr
                                    class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500"
                                >
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                    >
                                        Fecha de Pedido
                                    </th>
                                    <th
                                    class="border-b-2 border-gray-200 bg-gray-100 px-2 py-2 text-center text-[11px] font-semibold uppercase tracking-wider text-gray-600"
                                >
                                    <TableAutocompleteFilter
                                        labelClass="text-[11px]"
                                        label="N° de Pedido"
                                        :options="props.order_numbers"
                                        v-model="
                                            filterForm.selectedOrderNumbers
                                        "
                                        width="w-48"
                                    />
                                </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                    >
                                        Materiales
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                    >
                                        Equipos
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                    >
                                        Observación
                                    </th>
                                    <th
                                        class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center"
                                    ></th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr
                                    v-for="item in props.search
                                        ? props.pending_orders
                                        : pending_orders.data"
                                    :key="item.id"
                                    class="text-gray-700"
                                >
                                    <td
                                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center min-w-[250px]"
                                    >
                                        {{ formattedDate(item.order_date) }}
                                    </td>
                                    <td
                                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        {{ item.order_number }}
                                    </td>
                                    <td
                                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        <div class="flex justify-center items-center">
                                            <button type="button" @click="openMaterials(item)"
                                                    class="text-green-600 hover:underline">
                                                <EyeIcon class="h-5 w-5" />
                                            </button>
                                        </div>
                                    </td>
                                    <td
                                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        <div class="flex justify-center items-center">
                                            <button type="button" @click="openEquipments(item)"
                                                    class="text-green-600 hover:underline">
                                                <EyeIcon class="h-5 w-5" />
                                            </button>
                                        </div>
                                    </td>

                                    <td
                                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                        {{ item.observation }}
                                    </td>
                                    <td
                                        class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"
                                    >
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div
                        v-if="!props.search"
                        class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between"
                    >
                        <pagination :links="props.pending_orders.links" />
                    </div>
                </div>
            </div>
        </div>

        <Modal :show="materialModal" :closeable="true">
          <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">Materiales del Pedido</h2>
              <div class="overflow-x-auto mt-3 col-span-2">
                <table class="w-full whitespace-no-wrap">
                  <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                      <th class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        N°
                      </th>
                      <th class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        Código SAP
                      </th>
                      <th class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Material
                      </th>
                      <th class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Cantidad
                      </th>
                      <th class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Unidad
                      </th>
                      <th class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Precio Unitario
                      </th>
                      <th class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Observación
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in materials" :key="index" class="text-gray-700">
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ index + 1 }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ item.huawei_material.claro_code }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ item.huawei_material.name }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ item.quantity }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ item.huawei_material.unit }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap text-right">{{ item.unit_price ? "S/. " + item.unit_price.toFixed(2) : '' }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ item.observation }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="col-span-2 mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeMaterials">Cerrar</SecondaryButton>
              </div>
          </div>
        </Modal>

        <Modal :show="equipmentModal" :closeable="true">
          <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">Equipos del Pedido</h2>
              <div class="overflow-x-auto mt-3 col-span-2">
                <table class="w-full whitespace-no-wrap">
                  <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                      <th class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        N°
                      </th>
                      <th class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        Código SAP
                      </th>
                      <th class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Equipo
                      </th>
                      <th class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Serie
                      </th>
                      <th class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Precio Unitario
                      </th>
                      <th class="border-b-2 text-center border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Observación
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in equipments" :key="index" class="text-gray-700">
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ index + 1 }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ item.huawei_equipment_serie.huawei_equipment.claro_code }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ item.huawei_equipment_serie.huawei_equipment.name }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ item.huawei_equipment_serie.serie_number }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm whitespace-nowrap text-right">{{ item.unit_price ? "S/. " + item.unit_price.toFixed(2) : '' }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center whitespace-nowrap">{{ item.observation }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="col-span-2 mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeEquipments">Cerrar</SecondaryButton>
              </div>
          </div>
        </Modal>

    </AuthenticatedLayout>
</template>

<script setup>
import { Head, useForm, router } from "@inertiajs/vue3";
import Pagination from "@/Components/Pagination.vue";
import { EyeIcon } from "@heroicons/vue/24/outline";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import TextInput from "@/Components/TextInput.vue";
import { ref, watch } from "vue";
import { formattedDate } from "@/utils/utils";
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import TableAutocompleteFilter from "@/Components/TableAutocompleteFilter.vue";

const props = defineProps({
    pending_orders: Object,
    order_numbers: Array,
    search: String,
});

const materials = ref([]);
const equipments = ref([]);
const materialModal = ref(false);
const equipmentModal = ref(false);
const filterMode = ref(false);

const searchForm = useForm({
    searchTerm: props.search ? props.search : "",
});

const openMaterials = (item) => {
    materials.value = item.huawei_entry_details.filter(detail => detail.huawei_equipment_serie_id == null);
    materialModal.value = true;
}

const closeMaterials = () => {
    materials.value = [];
    materialModal.value = false;
}

const openEquipments = (item) => {
    equipments.value = item.huawei_entry_details.filter(detail => detail.huawei_material_id == null);
    equipmentModal.value = true;
}

const closeEquipments = () => {
    equipments.value = [];
    equipmentModal.value = false;
}

const assignGuide = () => {

}

const search = () => {
    if (searchForm.searchTerm == "") {
        if (props.equipment) {
            router.visit(
                route("huawei.inventory.show", {
                    warehouse: props.warehouse,
                    equipment: 1,
                })
            );
        } else {
            router.visit(
                route("huawei.inventory.show", { warehouse: props.warehouse })
            );
        }
    } else {
        if (props.equipment) {
            router.visit(
                route("huawei.inventory.show.search", {
                    warehouse: props.warehouse,
                    request: searchForm.searchTerm,
                    equipment: 1,
                })
            );
        } else {
            router.visit(
                route("huawei.inventory.show.search", {
                    warehouse: props.warehouse,
                    request: searchForm.searchTerm,
                })
            );
        }
    }
};

const filterForm = ref({
    selectedOrderNumbers: props.order_numbers
});

watch(
    () => [
        filterForm.value.selectedOrderNumbers
    ],
    () => {
        (filterMode.value = true), search_advance(filterForm.value);
    },
    {deep:true}
)

async function search_advance($data) {
    let url = route("huawei.inventory.pendingorders.searchadvance");
    try {
        let response = await axios.post(url, $data);
        dataToRender.value.data = response.data.orders;
    } catch (error) {
        console.error(error);
    }
}

</script>
