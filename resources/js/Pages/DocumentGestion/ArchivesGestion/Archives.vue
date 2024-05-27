<template>

  <Head title="Gestion de Documentos" />
  <AuthenticatedLayout
    :redirectRoute="{ route: 'documment.management.folders', params: { folder_id: folder?.upper_folder_id } }">
    <template #header>
      {{ props.folder.path }}
    </template>
    <div class="flex gap-4 justify-between rounded-lg shadow">
      <div class="flex flex-col sm:flex-row gap-4 justify-between w-full">
        <div v-if="props.folder.availability == 1">
          <div v-if="userHasPermission" class="flex gap-4 items-center">
            <PrimaryButton @click="openCreateDocumentModal" type="button"
              class="block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
              + Agregar Archivo
            </PrimaryButton>
          </div>
        </div>
        <div v-if="props.folder.availability == 3">
          <div v-if="userHasPermission && props.folder.last_owner == props.auth.user.id"
            class="flex gap-4 items-center">
            <PrimaryButton @click="openCreateDocumentModal" type="button"
              class="block rounded-md bg-indigo-600 px-4 py-2 text-center text-sm text-white hover:bg-indigo-500">
              + Agregar Archivo
            </PrimaryButton>
          </div>
        </div>
      </div>
    </div>


    <div class="overflow-x-auto rounded-lg shadow mt-2">
      <table class="w-full whitespace-no-wrap">
        <thead>
          <tr class="border-b bg-gray-50 text-left text-xs font-semibold uppercase tracking-wide text-gray-500">
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">

            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-left text-xs font-semibold uppercase tracking-wider text-gray-600">
              Nombre del Archivo
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
              Usuario
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
              Tamaño
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
              Versión
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
              Comentarios
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
              Exportar
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
              Evaluación
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
              Acciones
            </th>
            <th
              class="border-b-2 border-gray-200 bg-gray-100 px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-600">
            </th>
          </tr>
        </thead>
        <tbody>
          <template v-for="archive in archives.data" :key="archive.id">
            <tr class="text-gray-700">
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm justify-center relative">
              <img v-if="archive.type == 'stable'" src="/image/projectimage/stable.png" alt=""
                class="absolute top-0 left-0 w-10 h-10">
              <img v-if="archive.observation_state == 1" src="/image/projectimage/rejected.png" alt=""
              class="absolute top-0 left-0 w-10 h-10">

              <svg v-if="props.folder.archive_type === 'Word'" height="30px" width="30px" version="1.1" id="Layer_1"
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                xml:space="preserve">
                <path style="fill:#E2E5E7;"
                  d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z" />
                <path style="fill:#B0B7BD;" d="M384,128h96L352,0v96C352,113.6,366.4,128,384,128z" />
                <polygon style="fill:#CAD1D8;" points="480,224 384,128 480,128 " />
                <path style="fill:#50BEE8;" d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16
                          V416z" />
                <g>
                  <path style="fill:#FFFFFF;"
                    d="M92.576,384c-4.224,0-8.832-2.32-8.832-7.936v-72.656c0-4.608,4.608-7.936,8.832-7.936h29.296
                            c58.464,0,57.168,88.528,1.136,88.528H92.576z M100.64,311.072v57.312h21.232c34.544,0,36.064-57.312,0-57.312H100.64z" />
                  <path style="fill:#FFFFFF;" d="M228,385.28c-23.664,1.024-48.24-14.72-48.24-46.064c0-31.472,24.56-46.944,48.24-46.944
                            c22.384,1.136,45.792,16.624,45.792,46.944C273.792,369.552,250.384,385.28,228,385.28z M226.592,308.912
                            c-14.336,0-29.936,10.112-29.936,30.32c0,20.096,15.616,30.336,29.936,30.336c14.72,0,30.448-10.24,30.448-30.336
                            C257.04,319.008,241.312,308.912,226.592,308.912z" />
                  <path style="fill:#FFFFFF;" d="M288.848,339.088c0-24.688,15.488-45.92,44.912-45.92c11.136,0,19.968,3.328,29.296,11.392
                            c3.456,3.184,3.84,8.816,0.384,12.4c-3.456,3.056-8.704,2.688-11.776-0.384c-5.232-5.504-10.608-7.024-17.904-7.024
                            c-19.696,0-29.152,13.952-29.152,29.552c0,15.872,9.328,30.448,29.152,30.448c7.296,0,14.08-2.96,19.968-8.192
                            c3.952-3.072,9.456-1.552,11.76,1.536c2.048,2.816,3.056,7.552-1.408,12.016c-8.96,8.336-19.696,10-30.336,10
                            C302.8,384.912,288.848,363.776,288.848,339.088z" />
                </g>
                <path style="fill:#CAD1D8;"
                  d="M400,432H96v16h304c8.8,0,16-7.2,16-16v-16C416,424.8,408.8,432,400,432z" />
              </svg>

              <svg v-if="props.folder.archive_type === 'Imágenes'" height="30px" width="30px" viewBox="0 0 24 24"
                fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M3.17157 3.17157C2 4.34314 2 6.22876 2 9.99999V14C2 17.7712 2 19.6568 3.17157 20.8284C4.34315 22 6.22876 22 10 22H14C17.7712 22 19.6569 22 20.8284 20.8284C22 19.6569 22 17.7712 22 14V14V9.99999C22 7.16065 22 5.39017 21.5 4.18855V17C20.5396 17 19.6185 16.6185 18.9393 15.9393L18.1877 15.1877C17.4664 14.4664 17.1057 14.1057 16.6968 13.9537C16.2473 13.7867 15.7527 13.7867 15.3032 13.9537C14.8943 14.1057 14.5336 14.4664 13.8123 15.1877L13.6992 15.3008C13.1138 15.8862 12.8212 16.1788 12.5102 16.2334C12.2685 16.2758 12.0197 16.2279 11.811 16.0988C11.5425 15.9326 11.3795 15.5522 11.0534 14.7913L11 14.6667C10.2504 12.9175 9.87554 12.0429 9.22167 11.7151C8.89249 11.5501 8.52413 11.4792 8.1572 11.5101C7.42836 11.5716 6.75554 12.2445 5.40989 13.5901L3.5 15.5V2.88739C3.3844 2.97349 3.27519 3.06795 3.17157 3.17157Z"
                  fill="#222222" />
                <path
                  d="M3 10C3 8.08611 3.00212 6.75129 3.13753 5.74416C3.26907 4.76579 3.50966 4.2477 3.87868 3.87868C4.2477 3.50966 4.76579 3.26907 5.74416 3.13753C6.75129 3.00212 8.08611 3 10 3H14C15.9139 3 17.2487 3.00212 18.2558 3.13753C19.2342 3.26907 19.7523 3.50966 20.1213 3.87868C20.4903 4.2477 20.7309 4.76579 20.8625 5.74416C20.9979 6.75129 21 8.08611 21 10V14C21 15.9139 20.9979 17.2487 20.8625 18.2558C20.7309 19.2342 20.4903 19.7523 20.1213 20.1213C19.7523 20.4903 19.2342 20.7309 18.2558 20.8625C17.2487 20.9979 15.9139 21 14 21H10C8.08611 21 6.75129 20.9979 5.74416 20.8625C4.76579 20.7309 4.2477 20.4903 3.87868 20.1213C3.50966 19.7523 3.26907 19.2342 3.13753 18.2558C3.00212 17.2487 3 15.9139 3 14V10Z"
                  stroke="#222222" stroke-width="2" />
                <circle cx="15" cy="9" r="2" fill="#222222" />
              </svg>

              <svg v-if="props.folder.archive_type === 'Excel'" height="30px" width="30px" version="1.1" id="Layer_1"
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                xml:space="preserve">
                <path style="fill:#E2E5E7;"
                  d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z" />
                <path style="fill:#B0B7BD;" d="M384,128h96L352,0v96C352,113.6,366.4,128,384,128z" />
                <polygon style="fill:#CAD1D8;" points="480,224 384,128 480,128 " />
                <path style="fill:#84BD5A;" d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16
	V416z" />
                <g>
                  <path style="fill:#FFFFFF;" d="M144.336,326.192l22.256-27.888c6.656-8.704,19.584,2.416,12.288,10.736
		c-7.664,9.088-15.728,18.944-23.408,29.04l26.096,32.496c7.04,9.6-7.024,18.8-13.936,9.328l-23.552-30.192l-23.152,30.848
		c-6.528,9.328-20.992-1.152-13.696-9.856l25.712-32.624c-8.064-10.112-15.872-19.952-23.664-29.04
		c-8.048-9.6,6.912-19.44,12.8-10.464L144.336,326.192z" />
                  <path style="fill:#FFFFFF;" d="M197.36,303.152c0-4.224,3.584-7.808,8.064-7.808c4.096,0,7.552,3.6,7.552,7.808v64.096h34.8
		c12.528,0,12.8,16.752,0,16.752H205.44c-4.48,0-8.064-3.184-8.064-7.792v-73.056H197.36z" />
                  <path style="fill:#FFFFFF;" d="M272.032,314.672c2.944-24.832,40.416-29.296,58.08-15.728c8.704,7.024-0.512,18.16-8.192,12.528
		c-9.472-6-30.96-8.816-33.648,4.464c-3.456,20.992,52.192,8.976,51.296,43.008c-0.896,32.496-47.968,33.248-65.632,18.672
		c-4.24-3.456-4.096-9.072-1.792-12.544c3.328-3.312,7.024-4.464,11.392-0.88c10.48,7.152,37.488,12.528,39.392-5.648
		C321.28,339.632,268.064,351.008,272.032,314.672z" />
                </g>
                <path style="fill:#CAD1D8;"
                  d="M400,432H96v16h304c8.8,0,16-7.2,16-16v-16C416,424.8,408.8,432,400,432z" />
              </svg>

              <svg v-if="props.folder.archive_type === 'Power Point'" height="30px" width="30px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"
                xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve">
                <path style="fill:#E2E5E7;"
                  d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z" />
                <path style="fill:#B0B7BD;" d="M384,128h96L352,0v96C352,113.6,366.4,128,384,128z" />
                <polygon style="fill:#CAD1D8;" points="480,224 384,128 480,128 " />
                <path style="fill:#F15642;" d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16
	V416z" />
                <g>
                  <path style="fill:#FFFFFF;"
                    d="M105.456,303.152c0-4.224,3.328-8.832,8.688-8.832h29.552c16.64,0,31.616,11.136,31.616,32.48
		c0,20.224-14.976,31.488-31.616,31.488h-21.36v16.896c0,5.632-3.568,8.816-8.176,8.816c-4.224,0-8.688-3.184-8.688-8.816v-72.032
		H105.456z M122.336,310.432v31.872h21.36c8.576,0,15.36-7.568,15.36-15.504c0-8.944-6.784-16.368-15.36-16.368H122.336z" />
                  <path style="fill:#FFFFFF;" d="M191.616,303.152c0-4.224,3.328-8.832,8.704-8.832h29.552c16.64,0,31.616,11.136,31.616,32.48
		c0,20.224-14.976,31.488-31.616,31.488h-21.36v16.896c0,5.632-3.584,8.816-8.192,8.816c-4.224,0-8.704-3.184-8.704-8.816V303.152z
		 M208.496,310.432v31.872h21.36c8.576,0,15.36-7.568,15.36-15.504c0-8.944-6.784-16.368-15.36-16.368H208.496z" />
                  <path style="fill:#FFFFFF;" d="M301.68,311.472h-22.368c-11.136,0-11.136-16.368,0-16.368h60.496c11.392,0,11.392,16.368,0,16.368
		h-21.232v64.608c0,11.12-16.896,11.392-16.896,0V311.472z" />
                </g>
                <path style="fill:#CAD1D8;"
                  d="M400,432H96v16h304c8.8,0,16-7.2,16-16v-16C416,424.8,408.8,432,400,432z" />
              </svg>

              <svg v-if="props.folder.archive_type == 'PDF'" height="30px" width="30px" version="1.1" id="Layer_1"
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                xml:space="preserve">
                <path style="fill:#E2E5E7;"
                  d="M128,0c-17.6,0-32,14.4-32,32v448c0,17.6,14.4,32,32,32h320c17.6,0,32-14.4,32-32V128L352,0H128z" />
                <path style="fill:#B0B7BD;" d="M384,128h96L352,0v96C352,113.6,366.4,128,384,128z" />
                <polygon style="fill:#CAD1D8;" points="480,224 384,128 480,128 " />
                <path style="fill:#F15642;" d="M416,416c0,8.8-7.2,16-16,16H48c-8.8,0-16-7.2-16-16V256c0-8.8,7.2-16,16-16h352c8.8,0,16,7.2,16,16
                            V416z" />
                <g>
                  <path style="fill:#FFFFFF;"
                    d="M101.744,303.152c0-4.224,3.328-8.832,8.688-8.832h29.552c16.64,0,31.616,11.136,31.616,32.48
                              c0,20.224-14.976,31.488-31.616,31.488h-21.36v16.896c0,5.632-3.584,8.816-8.192,8.816c-4.224,0-8.688-3.184-8.688-8.816V303.152z
                              M118.624,310.432v31.872h21.36c8.576,0,15.36-7.568,15.36-15.504c0-8.944-6.784-16.368-15.36-16.368H118.624z" />
                  <path style="fill:#FFFFFF;"
                    d="M196.656,384c-4.224,0-8.832-2.304-8.832-7.92v-72.672c0-4.592,4.608-7.936,8.832-7.936h29.296
                              c58.464,0,57.184,88.528,1.152,88.528H196.656z M204.72,311.088V368.4h21.232c34.544,0,36.08-57.312,0-57.312H204.72z" />
                  <path style="fill:#FFFFFF;" d="M303.872,312.112v20.336h32.624c4.608,0,9.216,4.608,9.216,9.072c0,4.224-4.608,7.68-9.216,7.68
                              h-32.624v26.864c0,4.48-3.184,7.92-7.664,7.92c-5.632,0-9.072-3.44-9.072-7.92v-72.672c0-4.592,3.456-7.936,9.072-7.936h44.912
                              c5.632,0,8.96,3.344,8.96,7.936c0,4.096-3.328,8.704-8.96,8.704h-37.248V312.112z" />
                </g>
                <path style="fill:#CAD1D8;"
                  d="M400,432H96v16h304c8.8,0,16-7.2,16-16v-16C416,424.8,408.8,432,400,432z" />
              </svg>



            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
              <p class="text-gray-900 whitespace-no-wrap">{{ getDocumentName(archive.name) }}</p>
            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
              <p class="text-gray-900 whitespace-no-wrap">{{ archive.user.name }}</p>
            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
              <p class="text-gray-900 whitespace-no-wrap">{{ archive.size }} kB</p>
            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
              <p class="text-gray-900 whitespace-no-wrap">{{ archive.version.toFixed(2) }}</p>
            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
              <p class="text-gray-900 whitespace-no-wrap">{{ archive.comment }}</p>
            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
              <div v-if="archive.type='stable'" class="flex flex-col items-center space-y-2">
                <div class="flex items-center">
                                    <a target="_blank" :href="route('archives.get.pdf', { archive: archive.id })"
                                        class="text-green-600 hover:underline">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                        </svg>
                                    </a>
                                </div>
              </div>
              <div v-else>No disponible</div>

            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
              <div v-if="archive.type !== 'stable' && archive.approve_status && ![1, 3, 5].includes(archive.observation_state)" class="flex flex-col items-center space-y-2">
                <div v-if="archive.archive_users.length == 0">
                  <button v-if="props.auth.user.role_id === 1 || props.auth.user.id === archive.user_id"
                  @click="openPermissionModal(archive.id, archive.users_active.map((item) => item.id), archive.users_available)"
                  class="text-blue-600 underline">
                  Administrar
                </button>
                </div>
                <Link
                  v-if="props.auth.user.role_id === 1 || archive.users_active.some(user => user.id === props.auth.user.id)"
                  :href="route('archives.observations', { folder: props.folder.id, archive: archive.id })"
                  class="text-blue-600 underline">
                Observaciones
                </Link>
              </div>
              <div v-else>
                <p class="text-gray-900 whitespace-no-wrap">No permitido</p>
              </div>
            </td>

            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
              <div class="flex justify-center items-center space-x-3">
                <button @click="downloadDocument(archive.id)" class="flex items-center text-blue-600 hover:underline">
                  <ArrowDownIcon class="h-4 w-4 ml-1" />
                </button>
                <button v-if="hasPermission('UserManager')" @click="confirmDeleteDocument(archive.id)"
                  class="flex items-center text-red-600 hover:underline">
                  <TrashIcon class="h-4 w-4" />
                </button>
              </div>
            </td>
            <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm">
              <button v-if="archive.type != 'stable'" type="button" @click="toggleDetails(archive)"
                class="text-blue-900 whitespace-no-wrap">
                <svg v-if="archiveRow[0]?.archive_id !== archive.id" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                    d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round"
                    d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                </svg>
              </button>
            </td>

          </tr>
          <template v-if="archiveRow[0]?.archive_id === archive.id">
            <tr class="border-b bg-gray-50 text-xs font-semibold uppercase tracking-wide text-gray-500">
              <th class="border-b-2 border-gray-200 bg-white px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-400">

                </th>
                <th class="border-b-2 border-gray-200 bg-white px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-400">

                  </th>
                  <th class="border-b-2 border-gray-200 bg-white px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-400">

                  </th>
                  <th class="border-b-2 border-gray-200 bg-white px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-400">

                  </th>
                  <th class="border-b-2 border-gray-200 bg-white px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-400">

                  </th>
              <th class="border-b-2 border-gray-200 bg-white px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Usuario
                </th>
                <th class="border-b-2 border-gray-200 bg-white px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Fecha Máxima de Evaluación
                </th>
                <th class="border-b-2 border-gray-200 bg-white px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-400">
                    Resultado de Evaluación
                </th>
                <th class="border-b-2 border-gray-200 bg-white px-5 py-3 text-center text-xs font-semibold uppercase tracking-wider text-gray-400">

                  </th>
            </tr>
            <tr v-for="archive_user in archiveRow" :key="archive_user.id" class="bg-gray-100"
                    >
            <td class="border-b border-gray-200 px-5 py-5 text-sm text-center">

                </td>
                <td class="border-b border-gray-200px-5 py-5 text-sm text-center">

</td>
<td class="border-b border-gray-200 px-5 py-5 text-sm text-center">

</td>
<td class="border-b border-gray-200 px-5 py-5 text-sm text-center">

</td>
<td class="border-b border-gray-200 px-5 py-5 text-sm text-center">

</td>

                <td :class="[
                'text-gray-700',
                {
                    'border-l-8': true,
                    'border-yellow-500': archive_user.state === 'Pendiente' && Date.parse(archive_user.due_date) > Date.now() + (3 * 24 * 60 * 60 * 1000) && Date.parse(archive_user.due_date) <= Date.now() + (7 * 24 * 60 * 60 * 1000),
                    'border-red-500': archive_user.state === 'Pendiente' && Date.parse(archive_user.due_date) <= Date.now() + (3 * 24 * 60 * 60 * 1000),
                }
            ]" class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                    <p class="text-gray-900 whitespace-no-wrap">
                        {{ archive_user.user.name }}
                    </p>
                </td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                    <p class="text-gray-900 whitespace-no-wrap">
                        {{ formattedDate(archive_user.due_date) }}
                    </p>
                </td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">
                    <p class="text-gray-900 whitespace-no-wrap">
                        {{ archive_user.state }}
                    </p>
                </td>
                <td class="border-b border-gray-200 bg-white px-5 py-5 text-sm text-center">

</td>

            </tr>
        </template>


          </template>
        </tbody>
      </table>
    </div>

    <div class="flex flex-col items-center border-t bg-white px-5 py-5 xs:flex-row xs:justify-between">
      <pagination :links="props.archives.links" />
    </div>


    <Modal :show="create_document">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
          Subir archivo
        </h2>
        <form @submit.prevent="submit">
          <div class="border-b border-gray-900/10 pb-12">
            <div class="mt-2">
              <InputLabel for="documentFile">Archivo</InputLabel>
              <div class="mt-2">
                <InputFile type="file" v-model="form.archive" id="documentFile"
                  :accept="'.' + $props.folder.format_type.js" />
                <InputError :message="form.errors.archive" />
              </div>
            </div>
            <div class="mt-2">
              <InputLabel for="comment">Comentario</InputLabel>
              <div class="mt-2">
                <TextInput type="text" v-model="form.comment" id="comment" />
                <InputError :message="form.errors.comment" />
              </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
              <SecondaryButton @click="closeModal"> Cancelar </SecondaryButton>
              <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                Guardar
              </PrimaryButton>
            </div>
          </div>
        </form>
      </div>
    </Modal>

    <Modal :show="permissionModal">
      <div class="p-6">
        <h2 class="text-base font-medium leading-7 text-gray-900">
          Seleccione los evaluadores
        </h2>
        <div
          class="inline-flex items-center p-2 mb-4  mt-4 text-sm text-yellow-800 rounded-lg bg-yellow-50 dark:bg-gray-800 dark:text-yellow-300"
          role="alert">
          <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
            fill="currentColor" viewBox="0 0 20 20">
            <path
              d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
          </svg>
          <span class="sr-only">Info</span>
          <div>
            <span class="font-small">Los usuarios seleccionados podrán aprobar, desestimar y agregar observaciones a los
              archivos.</span>
          </div>
        </div>
        <form @submit.prevent="submitUsers">
          <div class="border-b border-gray-900/10 pb-12">
            <div class="mt-2">
              <InputLabel for="userSelect">Elegir evaluadores</InputLabel>
              <div class="mt-2">
                <select multiple v-model="formUsers.users" id="userSelect"
                  class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                  <option v-for="user in usersAvailable" :value="user">{{ user.name }}</option>
                </select>
                <InputError :message="formUsers.errors.users" />
              </div>
            </div>
            <div class="mt-2">
              <InputLabel for="due_date">Elegir Fecha Máxima de Evaluación</InputLabel>
              <div class="mt-2">
                <input type="date" v-model="formUsers.due_date" id="due_date"
                  class="block w-full shadow-sm focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md">
                <InputError :message="formUsers.errors.due_date" />
              </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
              <SecondaryButton @click="closePermissionModal"> Cancelar </SecondaryButton>
              <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                Guardar
              </PrimaryButton>
            </div>
          </div>
        </form>
      </div>
    </Modal>



    <ConfirmDeleteModal :confirmingDeletion="confirmingDocDeletion" itemType="Archivo" :deleteFunction="deleteDocument"
      @closeModal="closeModalDoc" />
    <ConfirmCreateModal :confirmingcreation="showModal" itemType="Archivo" />
    <ConfirmCreateModal :confirmingcreation="showAssignModal" itemType="Asignación" />
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ConfirmApproveModal from '@/Components/ConfirmApproveModal.vue';
import ConfirmDeleteModal from '@/Components/ConfirmDeleteModal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputFile from '@/Components/InputFile.vue';
import Pagination from '@/Components/Pagination.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import Modal from '@/Components/Modal.vue';
import { ref } from 'vue';
import { Head, useForm, router, Link } from '@inertiajs/vue3';
import { TrashIcon, ArrowDownIcon, BarsArrowUpIcon } from '@heroicons/vue/24/outline';
import { formattedDate } from '@/utils/utils';

const props = defineProps({
  archives: Object,
  folder: Object,
  auth: Object,
  userPermissions: Array,
  userHasPermission: Boolean
});
console.log(props.archives)
const usersAvailable = ref([]);
const archiveRow = ref(0);

const toggleDetails = (archive) => {

    if (archiveRow.value[0]?.archive_id === archive.archive_users[0]?.archive_id) {
        archiveRow.value = 0;
    } else {
        archiveRow.value = archive.archive_users;

    }
}


const hasPermission = (permission) => {
  return props.userPermissions.includes(permission);
}

const form = useForm({
  archive: null,
  comment: '',
  folder_id: props.folder.id,
  user_id: props.auth.user.id,
});

const formUsers = useForm({
  archive_id: null,
  due_date: '',
  users: []
});

const create_document = ref(false);
const archive_id = ref(null);
const showModal = ref(false);
const showAssignModal = ref(false);
const confirmingDocDeletion = ref(false);
const docToDelete = ref(null);
const permissionModal = ref(false);

const openCreateDocumentModal = () => {
  create_document.value = true;
};

const closeModal = () => {
  form.reset()
  create_document.value = false;
};

const openPermissionModal = (id, users, availableUsers) => {
  usersAvailable.value = availableUsers;
  formUsers.archive_id = id;
  formUsers.users = users;
  permissionModal.value = true;
}

const closePermissionModal = () => {
  formUsers.reset();
  permissionModal.value = false;
}

const submit = () => {
  form.post(route('archives.post', { folder: props.folder.id }), {
    onSuccess: () => {
      closeModal();
      form.reset();
      showModal.value = true
      setTimeout(() => {
        showModal.value = false;
        router.visit(route('archives.show', { folder: props.folder.id }))
      }, 2000);
    },
    onError: () => {
      form.reset();
    },
    onFinish: () => {
      form.reset();
    }
  });
};

const submitUsers = () => {
  formUsers.post(route('archives.assign.users', { folder: props.folder.id, archive: formUsers.archive_id }), {
    onSuccess: () => {
      closePermissionModal();
      formUsers.reset();
      showAssignModal.value = true
      setTimeout(() => {
        showAssignModal.value = false;
        router.visit(route('archives.show', { folder: props.folder.id }))
      }, 2000);
    },
    onError: (e) => {
      console.error(e)
    },
    onFinish: () => {
    }
  });
};

const confirmDeleteDocument = (documentId) => {
  docToDelete.value = documentId;
  confirmingDocDeletion.value = true;
};

const closeModalDoc = () => {
  confirmingDocDeletion.value = false;
};

const deleteDocument = () => {
  const docId = docToDelete.value;
  if (docId) {
    router.delete(route('archives.destroy', { folder: props.folder.id, archive: docId }), {
      onSuccess: () => closeModalDoc()
    });
  }
};

function downloadDocument(documentId) {
  const backendDocumentUrl = route('archives.download', { folder: props.folder.id, archive: documentId });
  window.open(backendDocumentUrl, '_blank');
};


const getDocumentName = (documentTitle) => {
  const parts = documentTitle.split('-');
  return parts.length > 1 ? parts.slice(0, -1).join('-') : documentTitle;
};


</script>
