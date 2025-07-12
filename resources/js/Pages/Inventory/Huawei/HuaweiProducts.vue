<template>

  <Head title="Datos de Productos" />
  <AuthenticatedLayout :redirectRoute="'huawei.loads'">
    <template #header>
      Datos de Productos
    </template>
    <div class="min-w-full rounded-lg shadow">
      <div class="flex justify-between items-center mt-4">
        <!-- Botones alineados horizontalmente -->
        <div class="flex space-x-4 ml-4">
          <div v-if="props.noPg == null">
            <Link :href="route('huawei.loads.products', { loadId: props.loadId, noPg: 1 })"
              class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            No asociados
            </Link>
          </div>
          <div v-else>
            <Link :href="route('huawei.loads.products', { loadId: props.loadId })"
              class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Asociados
            </Link>
          </div>
          <div>
            <button @click.prevent="exportProducts"
              class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
              Exportar
            </button>
          </div>
        </div>
        <!-- Texto "Total" pegado a la derecha -->
        <div class="flex-none mr-4">
          Total: S/. {{ props.total.toFixed(2) }}
        </div>
      </div>
      <div class="overflow-x-auto mt-3">
        <table class="w-full whitespace-no-wrap">
          <thead>
            <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Nombre</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Nombre de Anexo</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Unidad de Anexo</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Cantidad</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Precio Unitario</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Zona</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                Total</th>
              <th
                class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in huawei_products.data" :key="item.id" class="text-gray-700">
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.name }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <div class="w-[200px]">{{ item.anexe_name }}</div>
              </td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.anexe_unit }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.quantity }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ (!item.price_guide1 &&
                !item.price_guide2 ?
                '' : (item.price_guide1 ? "S/. " + item.price_guide1.unit_price.toFixed(2) : "S/. " +
                item.price_guide2.unit_price.toFixed(2))) }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.zone }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ (!item.price_guide1 &&
                !item.price_guide2 ?
                '' : (item.price_guide1 ? "S/. " + (item.price_guide1.unit_price * item.quantity).toFixed(2) : "S/. " +
                  (item.price_guide2.unit_price * item.quantity).toFixed(2))) }}</td>
              <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
                <div class="flex items-center">
                  <button v-if="!item.price_guide1 && !item.price_guide2" @click="openAssociateModal(item.id)"
                    class="text-blue-600 hover:underline mr-2">
                    Asociar
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
        <pagination :links="props.huawei_products.links" />
      </div>
    </div>

    <Modal :show="associateModal">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
          Asociar
        </h2>
        <form @submit.prevent="submit">
          <div class="space-y-12">
            <div class="border-b border-gray-900/10 pb-12">

              <div>
                <InputLabel for="anexe1" class="text-gray-700 mt-3">Anexos 1</InputLabel>
                <select v-model="form.anexe1" id="anexe1" class="border rounded-md px-3 py-2 mb-3 w-full mt-3">
                  <option value="">Seleccionar Anexo</option>
                  <option v-for="anexe in anexes1" :key="anexe.id" :value="anexe.id">{{ anexe.original_name }}</option>
                </select>
                <InputError :message="form.errors.anexe1" />
              </div>

              <div>
                <InputLabel for="anexe2" class="text-gray-700 mt-3">Anexos 2</InputLabel>
                <select v-model="form.anexe2" id="anexe2" class="border rounded-md px-3 py-2 mb-3 w-full mt-3">
                  <option value="">Seleccionar Anexo</option>
                  <option v-for="anexe in anexes2" :key="anexe.id" :value="anexe.id">{{ anexe.original_name }}</option>
                </select>
                <InputError :message="form.errors.anexe2" />
              </div>

              <div class="mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeAssociateModal"> Cancelar </SecondaryButton>
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                  class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Guardar</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </Modal>

    <ConfirmUpdateModal :confirmingupdate="showModalEdit" itemType="Producto" />
  </AuthenticatedLayout>
</template>

<script setup>
import { ref } from 'vue';
import { useForm, router, Link, Head } from '@inertiajs/vue3';
import Pagination from '@/Components/Pagination.vue';
import Modal from '@/Components/Modal.vue';
import InputLabel from '@/Components/InputLabel.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmUpdateModal from '@/Components/ConfirmUpdateModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';

const props = defineProps({
  huawei_products: Object,
  loadId: String,
  noPg: String,
  total: Number,
});

const showModal = ref(false);
const associateModal = ref(false);
const anexes1 = ref([]);
const anexes2 = ref([]);
const showModalEdit = ref(false);

const openAssociateModal = async (huawei_product) => {

  try {
    const response = await axios.get(route('huawei.loads.products.similarities', { huawei_product: huawei_product }));
    if (response.data.result1) {
      anexes1.value = [response.data.result1];
    } else {
      anexes1.value = [];
    }
    if (response.data.result2) {
      anexes2.value = [response.data.result2];
    } else {
      anexes2.value = [];
    }
  } catch (error) {
    console.error('Error al obtener similitudes:', error);
  }
  form.huawei_product = huawei_product;
  associateModal.value = true;
}

const closeAssociateModal = () => {
  form.reset();
  associateModal.value = false;
}

const form = useForm({
  huawei_product: '',
  anexe1: '',
  anexe2: ''
});

const submit = () => {
  form.put(route('huawei.loads.products.associate', { loadId: props.loadId, huawei_product: form.huawei_product }), {
    onSuccess: () => {
      closeAssociateModal();
      form.reset();
      showModalEdit.value = true;
      setTimeout(() => {
        showModalEdit.value = false;
        router.visit(route('huawei.loads.products', { loadId: props.loadId }));
      }, 2000);
    },
    onError: (errors) => {
      console.error('Error en la petición PUT:', errors);
      // Aquí puedes manejar los errores de la validación u otros errores
    }
  });
}

const exportProducts = () => {
  window.location.href = route('huawei.loads.exportpdf', { loadId: props.loadId });
};

</script>
