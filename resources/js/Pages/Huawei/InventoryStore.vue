  <template>
      <Head title="Agregar Entrada" />
      <AuthenticatedLayout :redirectRoute="{route: 'huawei.inventory.show', params: {warehouse: 1} }">
        <template #header>
          <h1 class="text-lg font-semibold leading-7 text-gray-900">Agregar Entrada</h1>
        </template>
        <div class="min-w-full rounded-lg shadow p-6">
          <form class="mb-8">
            <div class="grid grid-cols-2 gap-6">
              <div>
                <label for="guide_number" class="block text-sm font-medium text-gray-700">Número de Guía</label>
                <input
                  id="guide_number"
                  type="text"
                  v-model="form.guide_number"
                  class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                />
                <InputError :message="form.errors.guide_number" />
              </div>

              <div>
                <label for="entry_date" class="block text-sm font-medium text-gray-700">Fecha de Entrada</label>
                <input
                  id="entry_date"
                  type="date"
                  v-model="form.entry_date"
                  class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                />
                <InputError :message="form.errors.entry_date" />
              </div>

              <div class="col-span-1">
                <label for="entry_date" class="block text-sm font-medium text-gray-700">Observaciones</label>
                <textarea
                  id="observation"
                  v-model="form.observation"
                  class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                />
                <InputError :message="form.errors.observation" />
              </div>

              <div>
                <label for="warehouse" class="block text-sm font-medium text-gray-700">Almacén</label>
                <select
                    id="warehouse"
                    v-model="form.warehouse"
                    @change="getInventory(form.warehouse)"
                    class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                >
                    <option value="" disabled>Selecciona una almacén</option>
                    <option value="1">Claro</option>
                    <option value="2">Entel</option>
                </select>
                <InputError :message="form.errors.warehouse" />
            </div>

            </div>

          </form>
          <div class="grid grid-cols-2 gap-x-12 gap-y-12">
            <div v-if="ready" class="col-span-2">
              <div class="flex items-center mb-4">
                  <h2 class="text-lg font-medium leading-7 text-gray-900 mr-4">Materiales</h2>
                  <button @click="openMaterialModal" type="button" class="text-blue-500 hover:text-purple-500">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                          class="w-7 h-7">
                      <path stroke-linecap="round" stroke-linejoin="round"
                              d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m-6 3.75 3 3m0 0 3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75"/>
                      </svg>
                  </button>
              </div>


              <div class="overflow-x-auto mt-3">
                <table class="w-full whitespace-no-wrap">
                  <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        Material
                      </th>
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        Código de Claro
                      </th>
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        Precio Unitario
                      </th>
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        Cantidad
                      </th>
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        Unidad
                      </th>
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        Observación
                      </th>
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in newMaterials" :key="index" class="text-gray-700">
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.name }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.claro_code }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.unit_price ? item.unit_price : '-' }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.quantity }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.unit }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.observation }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                        <div class="flex items-center">
                          <button @click.prevent="delete_material(index)" class="text-red-600 hover:underline mr-2"><TrashIcon class="h-5 w-5" /></button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div v-if="form.guide_number && form.entry_date && ready" class="col-span-2">
              <div class="flex items-center mb-4">
                  <h2 class="text-lg font-medium leading-7 text-gray-900 mr-4">Equipos</h2>
                  <button @click="openEquipmentModal" type="button" class="text-blue-500 hover:text-purple-500">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                          class="w-7 h-7">
                      <path stroke-linecap="round" stroke-linejoin="round"
                              d="M7.5 7.5h-.75A2.25 2.25 0 0 0 4.5 9.75v7.5a2.25 2.25 0 0 0 2.25 2.25h7.5a2.25 2.25 0 0 0 2.25-2.25v-7.5a2.25 2.25 0 0 0-2.25-2.25h-.75m-6 3.75 3 3m0 0 3-3m-3 3V1.5m6 9h.75a2.25 2.25 0 0 1 2.25 2.25v7.5a2.25 2.25 0 0 1-2.25 2.25h-7.5a2.25 2.25 0 0 1-2.25-2.25v-.75"/>
                      </svg>
                  </button>
              </div>
              <div class="overflow-x-auto mt-3">
                <table class="w-full whitespace-no-wrap">
                  <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        Equipo
                      </th>
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        Código de Claro
                      </th>
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        Marca
                      </th>
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        Modelo
                      </th>

                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        Series
                      </th>
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in newEquipments" :key="index" class="text-gray-700">
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.name }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ item.claro_code }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ props.brands.find(brand => brand.id == item.brand)?.name }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ props.brand_models.find(model => model.id == item.brand_model)?.name }}</td>

                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center"><button @click.prevent="openSerieModal(item)" class="text-green-600 hover:underline mr-2"><EyeIcon class="h-5 w-5" /></button></td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                        <div class="flex items-center">
                          <button @click.prevent="delete_equipment(index)" class="text-red-600 hover:underline mr-2"><TrashIcon class="h-5 w-5" /></button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

          </div>
          <div class="mt-6 flex items-center justify-end gap-x-3">
              <Link :href="route('huawei.inventory.show', {warehouse: 1})" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-700">Cancelar</Link>
              <button @click="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-800">Guardar</button>
            </div>
        </div>

<Modal :show="materialModal">
  <div class="p-6">
    <h2 class="text-base font-medium leading-7 text-gray-900">Agregar Material</h2>
    <form @submit.prevent="add_material" class="grid grid-cols-1 md:grid-cols-2 gap-6">
      <!-- Contenido del formulario en un solo contenedor con grid -->
      <div class="col-span-1 md:col-span-2">
        <InputLabel class="mb-1" for="name">Nombre</InputLabel>
        <div class="flex items-center gap-2">
            <p>{{ '(' + prefix + ') ' }}</p>
            <input
                v-model="materialForm.name"
                type="text"
                class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600"
                @input="(e) => handleAutocomplete(e, true)"
                autocomplete="off"
                list="material-list"
            />
        </div>
        <datalist id="material-list">
          <option v-for="material in materials" :key="material.id" :value="material.name" :data-value="material"></option>
        </datalist>
      </div>

      <div>
        <InputLabel class="mb-1" for="claro_code">Código de Claro</InputLabel>
        <input type="text" :disabled="autoCompletement" v-model="materialForm.claro_code" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
      </div>

      <div>
        <InputLabel class="mb-1" for="unit_price">Precio Unitario</InputLabel>
        <input type="number" step="0.01" min="0" v-model="materialForm.unit_price" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
      </div>

      <div>
        <InputLabel class="mb-1" for="unit">Unidad</InputLabel>
        <select :disabled="autoCompletement" v-model="materialForm.unit" class="block w-full py-1.5 rounded-md sm:text-sm form-select focus:border-indigo-600">
          <option value="" disabled>Selecciona una unidad</option>
          <option>Unidad</option>
          <option>Metros</option>
          <option>Kilogramos</option>
          <option>Paquete</option>
        </select>
      </div>

      <div>
        <InputLabel class="mb-1" for="quantity">Cantidad</InputLabel>
        <input type="number" min="0" step="0.01" v-model="materialForm.quantity" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
      </div>

      <div class="col-span-1 md:col-span-2">
        <InputLabel class="mb-1" for="observation">Observación</InputLabel>
        <textarea v-model="materialForm.observation" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600"></textarea>
      </div>

      <!-- Botones de Acción -->
      <div class="col-span-1 md:col-span-2 mt-6 flex items-center justify-end gap-x-6">
        <SecondaryButton @click="closeMaterialModal">Cancelar</SecondaryButton>
        <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">Guardar</PrimaryButton>
      </div>
    </form>
  </div>
</Modal>

    <Modal :show="equipmentModal">
    <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">Agregar Equipo</h2>
        <form @submit.prevent="add_equipment" class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Contenido del Formulario -->
        <div class="col-span-1 md:col-span-2">
            <InputLabel class="mb-1" for="name">Nombre</InputLabel>
            <div class="flex items-center gap-2">
            <p>{{ '(' + prefix + ') ' }}</p>
            <input v-model="equipmentForm.name"
                    type="text"
                    class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600"
                    @input="(e) => handleAutocomplete(e, false)"
                    autocomplete="off"
                    list="material-list" />
            </div>
            <datalist id="material-list">
            <option v-for="equipment in equipments" :key="equipment.id" :value="equipment.name" :data-value="equipment"></option>
            </datalist>
        </div>

        <div class="col-span-1">
            <div class="flex items-center gap-2">
                <InputLabel for="brand" class="font-medium leading-6 text-gray-900 mb-1">Marca</InputLabel>

            <button type="button" @click="new_brand">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </button>
            </div>
            <select :disabled="autoCompletement" v-model="equipmentForm.brand" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600">
            <option value="" disabled>Seleccionar Marca</option>
            <option v-for="brand in props.brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
            </select>
        </div>

        <div class="col-span-1">
            <div class="flex items-center gap-2">
                <InputLabel for="model" class="font-medium leading-6 text-gray-900 mb-1">Modelo</InputLabel>

            <button type="button" @click="new_brand_model">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </button>
            </div>
            <select :disabled="autoCompletement" v-model="equipmentForm.brand_model" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600">
            <option value="" disabled>Seleccionar Modelo</option>
            <option v-for="model in filteredModels" :key="model.id" :value="model.id">{{ model.name }}</option>
            </select>
        </div>

        <div class="col-span-1">
            <InputLabel class="mb-1" for="claro_code">Código de Claro</InputLabel>
            <input type="text" :disabled="autoCompletement" v-model="equipmentForm.claro_code" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
        </div>

        <div class="col-span-1">
            <InputLabel class="mb-1" for="unit_price">Precio Unitario</InputLabel>
            <input type="number" step="0.01" min="0" v-model="equipmentForm.unit_price" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
        </div>

        <div class="col-span-1">
            <InputLabel class="mb-1" for="assigned_diu">DIU Asignada</InputLabel>
            <input type="text" v-model="equipmentForm.assigned_diu" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
        </div>

        <div class="col-span-1">
            <InputLabel class="mb-1" for="observation">Observaciones del Equipo</InputLabel>
            <input v-model="equipmentForm.observation" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
        </div>

        <div class="col-span-1 md:col-span-2">
            <InputLabel class="mb-1" for="new_serie">Agregar Serie</InputLabel>
            <div class="flex items-center">
            <input type="text" v-model="newSerie" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600" />
            <button type="button" @click="addSerie" class="ml-2">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-indigo-500">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </button>
            </div>
        </div>

        <!-- Tabla -->
        <div class="col-span-1 sm:col-span-2 overflow-x-auto mt-3">
            <table class="w-full whitespace-no-wrap">
            <thead>
                <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">N°</th>
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Serie</th>
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">DIU</th>
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Precio Unitario</th>
                <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">Observación</th>
                <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(item, index) in equipmentForm.series" :key="index" class="text-gray-700">
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ index + 1 }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.serie }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.assigned_diu }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-right">{{ item.unit_price ? "S/. " + item.unit_price.toFixed(2) : '' }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.observation }}</td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-right">
                    <button @click.prevent="removeSerie(index)" class="text-red-600 hover:underline"><TrashIcon class="h-5 w-5" /></button>
                </td>
                </tr>
            </tbody>
            </table>
        </div>

        <!-- Botones de Acción -->
        <div class="col-span-1 sm:col-span-2 mt-6 flex items-center justify-end gap-x-6">
            <SecondaryButton @click="closeEquipmentModal">Cancelar</SecondaryButton>
            <PrimaryButton type="submit">Guardar</PrimaryButton>
        </div>
        </form>
    </div>
    </Modal>


        <Modal :show="serieModal">
          <div class="p-6">
            <h2 class="text-base font-medium leading-7 text-gray-900">Series del Equipo</h2>
              <div class="overflow-x-auto mt-3 col-span-2">
                <table class="w-full whitespace-no-wrap">
                  <thead>
                    <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        N°
                      </th>
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600 text-center">
                        Serie
                      </th>
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        DIU
                      </th>
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Precio Unitario
                      </th>
                      <th class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
                        Observación
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in selectedEquipment.series" :key="index" class="text-gray-700">
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">{{ index + 1 }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.serie }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.assigned_diu }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-right">{{ item.unit_price ? "S/. " + item.unit_price.toFixed(2) : '' }}</td>
                      <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">{{ item.observation }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="col-span-2 mt-6 flex items-center justify-end gap-x-6">
                <SecondaryButton @click="closeSerieModal">Cerrar</SecondaryButton>
              </div>
          </div>
        </Modal>

        <Modal :show="new_brand_modal || new_brand_model_modal">
              <form class="p-6" @submit.prevent="submitName">
                  <h2 class="text-lg font-medium text-gray-900">
                      Nuevo(a) {{ new_brand_modal ? 'Marca' : 'Modelo' }}
                  </h2>
                  <div class="grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6 mt-2">
                      <div class="sm:col-span-6">
                          <InputLabel for="name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                          <div class="mt-2">
                              <TextInput id="name" required :to-uppercase="true"
                                  class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"
                                  v-model="formname.name" />
                              <InputError :message="formname.errors.name" />
                          </div>
                      </div>

                      <div v-if="new_brand_model_modal" class="sm:col-span-6">
                          <InputLabel for="name" class="font-medium leading-6 text-gray-900">Marca</InputLabel>
                          <div class="mt-2">
                            <select v-model="formname.brand_id" class="block w-full py-1.5 rounded-md sm:text-sm form-input focus:border-indigo-600">
                              <option value="" disabled>Seleccionar Marca</option>
                              <option v-for="brand in props.brands" :key="brand.id" :value="brand.id">{{ brand.name }}</option>
                            </select>
                              <InputError :message="formname.errors.brand_id" />
                          </div>
                      </div>

                  </div>
                  <div class="mt-6 flex gap-3 justify-end">
                      <SecondaryButton type="button" @click="new_brand_modal ? close_new_brand() : close_new_brand_model()"> Cerrar </SecondaryButton>
                      <PrimaryButton type="submit"> Agregar </PrimaryButton>
                  </div>
              </form>
          </Modal>

          <SuccessOperationModal :confirming="addSuccess" title="" message="" />
          <SuccessOperationModal :confirming="showModal" title="Éxito" message="La entrada se registró correctamente." />
          <ErrorOperationModal :showError="showErrorModal" :title="'Error'" :message="'Debe proporcionar al menos un equipo o material'" />
          <ErrorOperationModal :showError="emptyModal" :title="'Error'" :message="'Debe llenar todos los campos, excepto el precio unitario, código de claro, diu y observación.'" />
          <ErrorOperationModal :showError="existingSerie" :title="'Error'" :message="'El número de serie ya está registrado para este equipo.'" />
          <ErrorOperationModal :showError="newExistingResource" :title="'Error'" :message="'El material o equipo acaba de ser registrado.'" />

        </AuthenticatedLayout>
    </template>

    <script setup>
    import { ref, computed } from 'vue';
    import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
    import { Head, Link, useForm, router } from '@inertiajs/vue3';
    import { EyeIcon, TrashIcon } from '@heroicons/vue/24/outline';
    import InputError from '@/Components/InputError.vue';
    import Modal from '@/Components/Modal.vue';
    import SecondaryButton from '@/Components/SecondaryButton.vue';
    import PrimaryButton from '@/Components/PrimaryButton.vue';
    import InputLabel from '@/Components/InputLabel.vue';
    import TextInput from '@/Components/TextInput.vue';
    import axios from 'axios';
    import SuccessOperationModal from '@/Components/SuccessOperationModal.vue';
    import ErrorOperationModal from '@/Components/ErrorOperationModal.vue';

    const props = defineProps({
      brand_models: Object,
      brands: Object,
    });

    const materialModal = ref(false);
    const equipmentModal = ref(false);
    const new_brand_modal = ref(false);
    const new_brand_model_modal = ref(false);
    const addSuccess = ref(false);
    const newSerie = ref('');
    const serieModal = ref(false);
    const selectedEquipment = ref(null);
    const showModal = ref(false);
    const showErrorModal = ref(false);
    const autoCompletement = ref(false);
    const emptyModal = ref(false);
    const foundEquipment = ref(null);
    const foundMaterial = ref(null);
    const existingSerie = ref(false);
    const newExistingResource = ref(false);
    const materials = ref(null);
    const equipments = ref(null);
    const ready = ref(false);
    const prefix = ref(null);

    const openMaterialModal = () => {
      materialModal.value = true;
    }

    const closeMaterialModal = () => {
      materialForm.reset();
      materialModal.value = false
    }

    const openEquipmentModal = () => {
      equipmentModal.value = true;
    }

    const closeEquipmentModal = () => {
      equipmentForm.reset();
      equipmentForm.series = []; // Resetear las series
      equipmentModal.value = false;
    }

    const form = useForm({
      guide_number: '',
      entry_date: '',
      observation: '',
      warehouse: '',
      materials: [],
      equipments: [],
    });

    const materialForm = useForm({
      name: '',
      claro_code: '',
      quantity: '',
      unit_price: '',
      material_id: '',
      unit: '',
      observation: ''
    });

    const equipmentForm = useForm({
      name: '',
      claro_code: '',
      brand: '',
      brand_model: '',
      unit_price: '',
      series: [],
      equipment_id: '',
      assigned_diu: '',
      observation: '',
    });

    const addSerie = async () => {
        if (newSerie.value.trim() === '') {
            return; // No se permite serie vacía
        }

        if (autoCompletement.value && foundEquipment.value) {

            const foundSerie = ref(null);

            const res = await axios.post(route('huawei.inventory.create.verifyserie', { equipment: foundEquipment.value.id }), {
                serie_number: newSerie.value // Envía el número de serie en el cuerpo de la petición
            })

            if (res.data.message == 'found') {
                foundSerie.value = 1;
            }else{
                foundSerie.value = null;
            }

            const foundSerie2 = equipmentForm.series.find(seriesItem => seriesItem.serie === newSerie.value);

            if (foundSerie.value || foundSerie2) {
                existingSerie.value = true;
                setTimeout(() => {
                    existingSerie.value = false;
                }, 2000);
            } else {
                equipmentForm.series.push({
                    serie: newSerie.value,
                    assigned_diu: equipmentForm.assigned_diu,
                    unit_price: equipmentForm.unit_price,
                    observation: equipmentForm.observation
                });
                newSerie.value = '';
            }

        } else {
            const foundSerie = equipmentForm.series.find(seriesItem => seriesItem.serie === newSerie.value);
            if (foundSerie) {
                existingSerie.value = true;
                setTimeout(() => {
                    existingSerie.value = false;
                }, 2000);
            }else{
                equipmentForm.series.push({
                    serie: newSerie.value,
                    assigned_diu: equipmentForm.assigned_diu,
                    unit_price: equipmentForm.unit_price,
                    observation: equipmentForm.observation
                });
                newSerie.value = '';
            }
        }
    };


    const removeSerie = (index) => {
      equipmentForm.series.splice(index, 1);
    };

    const openSerieModal = (selEquipment) => {
      selectedEquipment.value = selEquipment;
      serieModal.value = true
    }

    const closeSerieModal = () => {
      selectedEquipment.value = null;
      serieModal.value = false;
    }

    const filteredModels = computed(() => {
      if (materialModal.value) {
        // Si el modal de material está abierto
        return props.brand_models.filter(model => model.brand_id === materialForm.brand);
      } else {
        // Si el modal de equipo está abierto
        return props.brand_models.filter(model => model.brand_id === equipmentForm.brand);
      }
    });


    const add_material = () => {

      if (!materialForm.name || !materialForm.quantity || !materialForm.unit){
        emptyModal.value = true;
        setTimeout(() => {
          emptyModal.value = false;
        }, 2000);
      }else{
        const existingMaterial = newMaterials.value.find(item => item.name === materialForm.name);

        if (existingMaterial){
            newExistingResource.value = true;
            setTimeout(() => {
                newExistingResource.value = false;
            }, 2000);
        }else{
            newMaterials.value.push({
            name: materialForm.name,
            claro_code: materialForm.claro_code,
            quantity: materialForm.quantity,
            unit_price: materialForm.unit_price,
            material_id: materialForm.material_id,
            observation: materialForm.observation,
            unit: materialForm.unit
            });

            materialForm.reset();
            autoCompletement.value = false;
            closeMaterialModal();
        }
        }
    };

    const delete_material = (index) => {
      newMaterials.value.splice(index, 1);
    }

    const add_equipment = () => {
      if (!equipmentForm.name || !equipmentForm.brand || !equipmentForm.brand_model || equipmentForm.series.length == 0){
        emptyModal.value = true;
        setTimeout(() => {
          emptyModal.value = false;
        }, 2000);
      }else{
          const existingEquipment = newEquipments.value.find(item => item.name === equipmentForm.name);
          if (existingEquipment){
                newExistingResource.value = true;
                setTimeout(() => {
                    newExistingResource.value = false;
                }, 2000);
            }else{
                newEquipments.value.push({
                    name: equipmentForm.name,
                    claro_code: equipmentForm.claro_code,
                    brand: equipmentForm.brand,
                    brand_model: equipmentForm.brand_model,
                    series: equipmentForm.series ,
                    equipment_id: equipmentForm.equipment_id,
                    unit: equipmentForm.unit
                });

                equipmentForm.reset();
                autoCompletement.value = false;

                closeEquipmentModal();
            }

        }
    };

    const delete_equipment = (index) => {
      newEquipments.value.splice(index, 1);
    }

    const newMaterials = ref([]);
    const newEquipments = ref([]);

    const new_brand = () => {
      new_brand_modal.value = true;
    }

    const close_new_brand = () => {
      formname.reset();
      new_brand_modal.value = false;
    }

    const new_brand_model = () => {
      new_brand_model_modal.value = true;
    }

    const close_new_brand_model = () => {
      formname.reset();
      new_brand_model_modal.value = false;
    }

    const formname = useForm({
      name: '',
      brand_id: '',
      errors: {}
    });

    const submitName = () => {
      const url = new_brand_modal.value ? route('huawei.inventory.create.brand') : route('huawei.inventory.create.brandmodel')
      axios.post(url, { ...formname.data() })
          .then(response => {
              if (response.status === 200) {
                  let newItem = response.data.new
                  new_brand_modal.value
                      ? props.brands.push({ ...newItem })
                      : props.brand_models.push({ ...newItem })
                  new_brand_modal.value
                      ? close_new_brand()
                      : close_new_brand_model()
                  addSuccess.value = true
                  setTimeout(() => {
                      addSuccess.value = false
                  }, 600)
                  formname.reset()
              } else {
                  throw new Error('Fallo en el servidor con status ' + response.status)
              }
          })
          .catch(error => {
            if (error.response && error.response.status === 422) {
              // Manejar errores de validación
              formname.errors = error.response.data.errors;
            } else {
              console.error(error);
            }
          })
  };

    const submit = () => {
      form.materials = newMaterials;
      form.equipments = newEquipments;
      form.post(route('huawei.inventory.store'), {
            onSuccess: () => {
                showModal.value = true;
                setTimeout(() => {
                    showModal.value = false;
                    router.visit(route('huawei.inventory.show', {warehouse: 1}))
                }, 2000);
            },
            onError: (e) => {
              if (e.error_empty){
                showErrorModal.value = true;
                setTimeout(() => {
                  showErrorModal.value = false;
                }, 3000);
              }
            }
        })
    }
    const handleAutocomplete = (e, material) => {
    if (material) {
        foundMaterial.value = materials.value.find(material =>
        material.name === e.target.value
        );

        if (foundMaterial.value) {
        autoCompletement.value = true;
        materialForm.claro_code = foundMaterial.value.claro_code;
        materialForm.material_id = foundMaterial.value.id;
        materialForm.unit = foundMaterial.value.unit;
        } else {
        autoCompletement.value = false;
        materialForm.claro_code = '';
        materialForm.material_id = '';
        materialForm.unit = '';
        }
    } else {
        foundEquipment.value = equipments.value.find(equipment =>
        equipment.name === e.target.value
        );

        if (foundEquipment.value) {
        autoCompletement.value = true;
        equipmentForm.claro_code = foundEquipment.value.claro_code;
        equipmentForm.brand = foundEquipment.value.brand_model.brand_id;
        equipmentForm.brand_model = foundEquipment.value.model_id;
        equipmentForm.equipment_id = foundEquipment.value.id;
        equipmentForm.unit = foundEquipment.value.unit
        } else {
        autoCompletement.value = false;
        equipmentForm.claro_code = '';
        equipmentForm.brand = '';
        equipmentForm.brand_model = '';
        equipmentForm.equipment_id = '';
        equipmentForm.unit = '';
        }
    }
    };

    const getInventory = async (value) => {
        ready.value = false;

        await axios.get(route('huawei.inventory.create.getinventory', {value: value}))
            .then(res => {
                materials.value = res.data.materials;
                equipments.value = res.data.equipments;
                ready.value = true;
                switch (value){
                    case '1':
                        prefix.value = 'Claro';
                        break;
                    case '2':
                        prefix.value = 'Entel';
                        break;
                    default:
                        prefix.value = null;
                        break;
                }

            })
            .catch(e => {
                ready.value = false;
                console.error(e);
            })
    };

    </script>
