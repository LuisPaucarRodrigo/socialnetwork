<template>

  <Head title="Información del Proyecto" />
  <AuthenticatedLayout :redirectRoute="'selectproject.index'">
    <template #header>
      <div class="mt-3 ml-4">Información del Proyecto</div>
    </template>
    <div class="mt-4 ml-4">
      <div v-if="props.project.initial_budget == null">
        <p class="text-xl font-semibold text-gray-700">No hay presupuesto
          definido</p>
      </div>
      <div v-else>
        <p class="text-xl font-semibold text-gray-700">
          Cotización de Anteproyecto: S/. {{ (project.preproject_quote +
    project.preproject.total_amount_entry).toFixed(2)
          }}
        </p>
        <p class="text-xl font-semibold text-gray-700">
          Cotización de Anteproyecto sin Margen: S/. {{ (project.preproject_quote_no_margin +
    project.preproject.total_amount_entry_not_margin).toFixed(2) }}
        </p>
        <p class="text-xl font-semibold text-gray-700">
          Presupuesto Inicial: S/. {{ props.project.initial_budget.toFixed(2) }}
        </p>
        <p class="text-xl font-semibold text-gray-700">
          Presupuesto Restante: S/. {{ props.project.remaining_budget.toFixed(2) }}
        </p>
      </div>
      <br>
      <p v-if="props.budgetUpdate" class="text-xl font-semibold text-gray-700">Presupuesto Actual: S/. {{
    props.budgetUpdate.new_budget.toFixed(2) }}</p>
      <p v-else></p>
    </div>

    <div  class="inline-block min-w-full overflow-hidden rounded-lg shadow">
      <div v-if="props.project.initial_budget > 0" class="flex justify-end px-5 py-3">
        <button @click="openModal2" class="text-blue-600 hover:underline mr-4">Actualizar Presupuesto</button>
      </div>

      <div v-else class="flex justify-end px-5 py-3">
        <button @click="defineInitialBudget" class="text-blue-600 hover:underline mr-4">Definir Presupuesto</button>
      </div>
    </div>
    <div class="min-w-full overflow-x-auto rounded-lg shadow">
      <table class="w-full table-auto mt-5">
        <thead>
          <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Nuevo Presupuesto
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Aumento / Disminución
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Proyecto
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Razón
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Fecha de Actualización
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Usuario
            </th>

          </tr>
        </thead>
        <tbody>
          <tr v-for="update in budgetUpdates.data" :key="update.id" class="text-gray-700" :class="[
    'text-gray-700',
    {
      'border-l-8': true,
      'border-green-500': update.difference >= 0,
      'border-red-500': update.difference < 0,
    }
  ]">
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
              <p class="text-gray-900 whitespace-no-wrap">S/. {{ update.new_budget.toFixed(2) }}</p>
            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
              <p class="text-gray-900 whitespace-no-wrap">S/. {{ update.difference.toFixed(2) }}</p>
            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
              <p class="text-gray-900 whitespace-no-wrap">{{ update.project.name }}</p>
            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
              <p class="text-gray-900 whitespace-no-wrap">{{ update.reason }}</p>
            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
              <p class="text-gray-900 whitespace-no-wrap">{{ formattedDate(update.created_at) }}</p>
            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
              <p class="text-gray-900 whitespace-no-wrap">{{ update.user.name }}</p>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Paginación -->
    <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
      <pagination :links="budgetUpdates.links" />
    </div>
    <Modal :show="isModalOpen2">
      <div class="p-6">
        <h2 class="text-2xl font-semibold mb-4">Actualizar Presupuesto</h2>
        <!-- Formulario para definir el presupuesto -->
        <form @submit.prevent="submit2">
          <div class="mb-4">
            <label for="initial_budget" class="block text-sm font-medium text-gray-700">Presupuesto Inicial</label>
            <input type="number" disabled readonly step="0.01" id="initial_budget" name="initial_budget"
              :value="budgetUpdate ? props.budgetUpdate.new_budget : props.project.initial_budget" required
              class="mt-1 p-2 border rounded-md w-full">
          </div>
          <div class="mb-4">
            <label for="initial_budget" class="block text-sm font-medium text-gray-700">Diferencia</label>
            <input type="number" step="0.01" id="initial_budget" name="initial_budget" v-model="form2.difference"
              required class="mt-1 p-2 border rounded-md w-full">
          </div>
          <div class="mb-4">
            <label for="initial_budget" class="block text-sm font-medium text-gray-700">Nuevo
              Presupuesto</label>
            <input type="number" disabled readonly step="0.01" id="initial_budget" name="initial_budget"
              v-model="form2.new_budget" required class="mt-1 p-2 border rounded-md w-full">
          </div>
          <div class="mb-4">
            <label for="reason" class="block text-sm font-medium text-gray-700">Razón</label>
            <textarea id="reason" name="reason" v-model="form2.reason" required
              class="mt-1 p-2 border rounded-md w-full" />
          </div>
          <div class="flex justify-end">
            <SecondaryButton type="button" @click="closeModal2" class="text-gray-500 mr-2">Cancelar</SecondaryButton>
            <PrimaryButton type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</PrimaryButton>
          </div>
        </form>
      </div>
    </Modal>

    <Modal :show="defineModal">
      <div class="p-6">
        <h2 class="text-2xl font-semibold mb-4">Definir Presupuesto</h2>
        <!-- Formulario para definir el presupuesto -->
        <form @submit.prevent="submit3">
          <div class="mb-4">
            <label for="initial_budget" class="block text-sm font-medium text-gray-700">Presupuesto Inicial</label>
            <input type="number" step="0.01" id="initial_budget" name="initial_budget" v-model="form3.initial_budget"
              required class="mt-1 p-2 border rounded-md w-full">
          </div>
          <div class="flex justify-end">
            <SecondaryButton type="button" @click="closeDefine" class="text-gray-500 mr-2">Cancelar</SecondaryButton>
            <PrimaryButton type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Guardar</PrimaryButton>
          </div>
        </form>
      </div>
    </Modal>
    <Modal :show="approvating">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
          ¿Está seguro de aprobar este presupuesto?
        </h2>
        <p class="mt-1 text-sm text-gray-600">
          Este presupuesto sobrepasa el precio de la Cotización de Anteproyecto, lo
          cual generaría perdidas.
        </p>
        <div class="space-y-12">
          <div class="border-gray-900/10">
            <div class="mt-6 flex items-center justify-end gap-x-3">
              <SecondaryButton @click="closeApprove"> Cancelar </SecondaryButton>
              <PrimaryButton @click="form3.initial_budget ? confirm(true, false) : confirm(true, true)"
                :class="{ 'opacity-25': form2.processing }"
                class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Confirmar</PrimaryButton>
            </div>
          </div>
        </div>
      </div>
    </Modal>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, watch } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { formattedDate } from '@/utils/utils';

const props = defineProps({
  project: Object,
  budgetUpdate: Object,
  budgetUpdates: Object,
  auth: Object,
  userPermissions:Array
});

const isModalOpen2 = ref(false);
const approvating = ref(false);

const closeApprove = () => {
  approvating.value = false;
}

const form2 = useForm({
  new_budget: null,
  project_id: null,
  difference: null,
  reason: '',
  user_id: null,
});

const form3 = useForm({
  initial_budget: null,
});

const defineModal = ref(false);

const openModal2 = () => {
  isModalOpen2.value = true;
};

const defineInitialBudget = () => {
  defineModal.value = true;
};

const closeDefine = () => {
  form3.reset();
  defineModal.value = false;
};

const closeModal2 = () => {
  form2.reset();
  isModalOpen2.value = false;
};

const submit2 = () => {
  if (form2.new_budget > (props.project.preproject_quote + props.project.preproject.total_amount_entry)) {
    approvating.value = true;
  } else {
    form2.project_id = props.project.id;
    form2.user_id = props.auth.user.id;
    form2.post(route('budgetupdates.store', { project: props.project.id }, form2));
    closeModal2();
  }
};

const submit3 = () => {
  if (form3.initial_budget > (props.project.preproject_quote + props.project.preproject.total_amount_entry)) {
    approvating.value = true;
  } else {
    form3.put(route('initialbudget.define', { project: props.project.id }));
    closeDefine();
  }
};

const confirm = (val = false, form) => {
  if (val) {
    if (form) {
      form2.project_id = props.project.id;
      form2.user_id = props.auth.user.id;
      form2.post(route('budgetupdates.store', { project: props.project.id }, form2));
      closeApprove();
      closeModal2();
    } else {
      form3.put(route('initialbudget.define', { project: props.project.id }));
      closeApprove();
      closeDefine();
    }
  } else {
    if (form) {
      closeApprove();
      closeModal2;
    } else {
      closeApprove();
      closeDefine;
    }
  }
}

watch(() => form2.difference, (newVal) => {
  const diffValue = newVal !== '' ? newVal : 0;
  const initialBudget = props.project.initial_budget || 0;
  const currentBudget = props.budgetUpdate && props.budgetUpdate.new_budget !== null ?
    parseFloat(props.budgetUpdate.new_budget) : initialBudget;

  form2.new_budget = (parseFloat(currentBudget) + parseFloat(diffValue)).toFixed(2);
});

</script>