<template>
  <Head title="Backlog" />

  <AuthenticatedLayout :redirectRoute="'socialnetwork.sot'">
      <template #header> Backlog </template>

      <div class="min-w-full rounded-lg shadow">
          <PrimaryButton @click="addBacklogRow" type="button">
              + Agregar
          </PrimaryButton>
          <div class="overflow-x-auto">
              <table class="w-full">
                  <thead>
                      <tr
                          class="border-b bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-500"
                      >
                          <th
                              v-for="(item, key) in backlogHeaders"
                              :key="key"
                              :ref="item.headerRef"
                              class="border border-gray-300 bg-gray-100 px-3 py-1 text-center text-[10px] font-semibold uppercase tracking-wider text-gray-600"
                          >
                              <p class="text-center w-[200px]">
                                  {{ item.headerName }}
                              </p>
                          </th>
                          <th
                              v-if="auth.user.role_id === 1"
                              class="border border-gray-300 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600"
                          ></th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr
                          v-for="(item, key) in backlogsToRender"
                          :key="key"
                          class="text-gray-700"
                      >
                          <td
                              v-for="(b_item, b_key) in backlogItems"
                              :key="b_key"
                              :class="[
                                  'border border-gray-200 px-2 bg-white py-1 text-[12px]',
                                  item?.id ? '' : 'h-16', b_item.editable ? 'cursor-pointer' : ''
                              ]"
                              @dblclick="
                                  b_item.editable
                                      ? editCell(key, b_key, b_item.propType)
                                      : null
                              "
                          >
                              <!--  -->
                              <template v-if="isEditing(key, b_key)" >
                                  <div
                                      v-if="
                                          b_item.propType === 'autocomplete'
                                      "
                                      :id="`autocomplete-${key}-${b_key}`"
                                      class="w-[200px] autocomplete"
                                  >
                                      <input
                                          list="siteOptions"
                                          autocomplete="off"
                                          :id="`${key}-${b_key}`"
                                          @input="searchItems(key, b_key)"
                                          @keydown.down.prevent="
                                              moveHighlight(1)
                                          "
                                          @keydown.up.prevent="
                                              moveHighlight(-1)
                                          "
                                          @blur="saveEdit(key, b_key)"
                                          @keydown.enter.prevent="
                                              selectHighlightedItem(
                                                  key,
                                                  b_key
                                              )
                                          "
                                          class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                      />
                                      <transition name="fade">
                                          <ul
                                              v-if="
                                                  activeAutocomplete ===
                                                      `${key}-${b_key}` &&
                                                  results.length &&
                                                  showResults
                                              "
                                              class="w-full mt-1 border border-gray-300 bg-white rounded-md shadow-lg h-40 overflow-y-auto z-40"
                                          >
                                              <li
                                                  v-for="(
                                                      item, index
                                                  ) in results"
                                                  :key="item.id"
                                                  :class="[
                                                      'px-3 py-2 cursor-pointer',
                                                      {
                                                          'bg-gray-100':
                                                              highlightedIndex ===
                                                              index,
                                                      },
                                                  ]"
                                                  @mousedown="
                                                      selectItem(
                                                          item,
                                                          key,
                                                          b_key
                                                      )
                                                  "
                                              >
                                                  {{ item.site_name }} - {{ item.site_id }}
                                              </li>
                                          </ul>
                                      </transition>
                                  </div>

                                  <select
                                      v-if="b_item.propType === 'select'"
                                      :id="`${key}-${b_key}`"
                                      v-model="item[b_item.propName]"
                                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                      @keydown.enter="saveEdit(key, b_key)"
                                      @blur="saveEdit(key, b_key)"
                                  >
                                      <option
                                          class=""
                                          v-if="b_item.options"
                                          v-for="(opt, i) in b_item.options"
                                          :key="i"
                                      >
                                          {{ opt }}
                                      </option>
                                      <option
                                          class=""
                                          v-if="
                                              b_item.advancedOptions &&
                                              item.system
                                          "
                                          v-for="(opt, i) in b_item
                                              .advancedOptions[item.system]"
                                          :key="i"
                                      >
                                          {{ opt }}
                                      </option>
                                  </select>

                                  <input
                                      v-if="
                                          ['text', 'number', 'date'].includes(
                                              b_item.propType
                                          )
                                      "
                                      :type="b_item.propType"
                                      :id="`${key}-${b_key}`"
                                      v-model="item[b_item.propName]"
                                      @blur="saveEdit(key, b_key)"
                                      @keydown.enter="saveEdit(key, b_key)"
                                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                  />
                                  <textarea
                                      v-if="b_item.propType === 'textarea'"
                                      :type="b_item.propType"
                                      :id="`${key}-${b_key}`"
                                      v-model="item[b_item.propName]"
                                      @blur="saveEdit(key, b_key)"
                                      @keydown.enter="saveEdit(key, b_key)"
                                      class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                  />
                              </template>
                              <template v-else>
                                  <p class="text-gray-900 text-center">
                                      {{ getProplabel(item, b_item) }}
                                  </p>
                              </template>
                          </td>

                          <td
                              v-if="auth.user.role_id === 1"
                              class="border-b border-gray-200 bg-white px-2 py-1"
                          >
                              <div class="flex space-x-3 justify-center">
                                  <button
                                      type="button"
                                      @click="
                                          item?.id
                                              ? openSotDeleteModal(item.id)
                                              : deleteBacklogRow(key)
                                      "
                                  >
                                      <svg
                                          xmlns="http://www.w3.org/2000/svg"
                                          fill="none"
                                          viewBox="0 0 24 24"
                                          stroke-width="1.5"
                                          stroke="currentColor"
                                          class="w-4 h-4 text-red-500"
                                      >
                                          <path
                                              stroke-linecap="round"
                                              stroke-linejoin="round"
                                              d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"
                                          />
                                      </svg>
                                  </button>
                              </div>
                          </td>
                      </tr>
                  </tbody>
              </table>
          </div>

          <!-- <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
              <pagination :links="backlogsToRender.links" />
          </div> -->
      </div>

      <Modal :show="showSotDeleteModal" @close="closeSotDeleteModal">
          <div class="p-6">
              <h2 class="text-lg font-medium text-gray-900">
                  ¿Estás seguro de que quieres eliminar el registro del
                  backlog?
              </h2>

              <p class="mt-1 text-sm text-gray-600">
                  Una vez que se elimine el registro, todos sus recursos y
                  datos se eliminarán permanentemente.
              </p>

              <div class="mt-6 flex justify-end">
                  <SecondaryButton @click="closeSotDeleteModal">
                      Cancelar
                  </SecondaryButton>

                  <DangerButton class="ml-3" @click="deleteSot">
                      Eliminar
                  </DangerButton>
              </div>
          </div>
      </Modal>

      <SuccessOperationModal
          :confirming="confirmSotDelete"
          :title="'Registro Eliminado'"
          :message="'El registro del backlog fue eliminado con éxito'"
      />
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import Pagination from "@/Components/Pagination.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import { Head, router } from "@inertiajs/vue3";
import { ref, nextTick } from "vue";
import SelectSNSotComponent from "@/Components/SelectSNSotComponent.vue";
import { formattedDate } from "@/utils/utils";
import SuccessOperationModal from "@/Components/SuccessOperationModal.vue";
import { EyeIcon } from "@heroicons/vue/24/outline";
import TestChildCompo from "@/Components/TestChildCompo.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

import { backlogHeaders, backlogItems } from "./BacklogConstants";

const { auth } = defineProps({
  auth: Object,
});
const backlogsToRender = ref([]);

function addBacklogRow() {
  backlogsToRender.value.unshift({});
}

function getProplabel(item, b_item) {
  let itemProps = b_item.propName.split("."); // Cambié el nombre de la variable local
  let value = item;
  for (let part of itemProps) {
      value = value?.[part];
      if (value === undefined) break;
  }
  if (value && b_item.variantPropType) {
      switch (b_item.variantPropType) {
          case "amount":
              value = "S/. " + value.toFixed(2);
              break;
          case "date":
              value = formattedDate(value);
              break;
          default:
              break;
      }
  }
  return value;
}

//Delete No ID
function deleteBacklogRow(i) {
  backlogsToRender.value.splice(i, 1);
}

//Delete With ID
const showSotDeleteModal = ref(false);
const sotToDelete = ref(null);
const confirmSotDelete = ref(false);
function openSotDeleteModal(id) {
  sotToDelete.value = id;
  showSotDeleteModal.value = true;
}
function closeSotDeleteModal() {
  sotToDelete.value = null;
  showSotDeleteModal.value = false;
}
function deleteSot() {
  router.delete(
      route("socialnetwork.sot.delete", { sot_id: sotToDelete.value }),
      {
          onSuccess: () => {
              backlogsToRender.value.splice(i, 1);
              closeSotDeleteModal();
              confirmSotDelete.value = true;
              setTimeout(() => {
                  confirmSotDelete.value = false;
              }, 1500);
          },
      }
  );
}

//Editing Cells
const editingCells = ref({});
function isEditing(key, b_key) {
  return editingCells.value[`${key}-${b_key}`] === true;
}
function editCell(key, b_key, propType) {
  editingCells.value[`${key}-${b_key}`] = true;
  propType === "autocomplete" && activateAutocomplete(key, b_key);
  nextTick(() => {
      const input = document.getElementById(`${key}-${b_key}`);
      if (input) {
          input.focus();
      }
      if (propType === "autocomplete")
          input.value =
              backlogsToRender.value[key]?.backlog_site?.site_id ?? "";
  });
}

function saveEdit(key, b_key) {
  deactivateAutocomplete();
  delete editingCells.value[`${key}-${b_key}`];
}

//edit cells autocomplete
const results = ref([]);
const highlightedIndex = ref(-1);
const showResults = ref(false);
const activeAutocomplete = ref(null);

function activateAutocomplete(key, b_key) {
  activeAutocomplete.value = `${key}-${b_key}`;
}
function deactivateAutocomplete() {
  results.value = [];
  activeAutocomplete.value = null;
}

const searchItems = async (key, b_key) => {
  let query = document.getElementById(`${key}-${b_key}`).value;
  if (query.trim() === "") {
      results.value = [];
      showResults.value = false;
      return;
  }
  try {
      const response = await axios.get(
          route("project.backlog.autocomplete"),
          {
              params: { query: query },
          }
      );
      results.value = response.data;
      showResults.value = true;
      highlightedIndex.value = -1;
  } catch (error) {
      console.error("Error fetching items:", error);
  }
};

const selectItem = (item, key, b_key) => {
  if (item) {
      const input = document.getElementById(`${key}-${b_key}`);
      input.value = item.site_id;
      results.value = [];
      backlogsToRender.value[key].backlog_site = { ...item };
      backlogsToRender.value[key].backlog_site_id = item.id;
  } else {
      delete backlogsToRender.value[key].backlog_site;
      delete backlogsToRender.value[key].backlog_site_id;
  }
  saveEdit(key, b_key);
};

const selectHighlightedItem = (key, b_key) => {
  if (
      highlightedIndex.value >= 0 &&
      highlightedIndex.value < results.value.length
  ) {
      selectItem(results.value[highlightedIndex.value], key, b_key);
  } else {
      selectItem(null, key, b_key);
  }
};

const moveHighlight = (direction) => {
  if (results.value.length === 0) return;
  highlightedIndex.value =
      (highlightedIndex.value + direction + results.value.length) %
      results.value.length;
};

document.addEventListener("mousedown", (event) => {
  const autocompleteElement = document.getElementById(
      `${activeAutocomplete.value}`
  );
  if (autocompleteElement && !autocompleteElement.contains(event.target)) {
      deactivateAutocomplete();
  }
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
