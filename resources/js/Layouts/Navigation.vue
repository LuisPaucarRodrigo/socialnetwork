<template>
    <div :class="$page.props.showingMobileMenu ? 'block' : 'hidden'" @click="$page.props.showingMobileMenu = false"
        class="fixed inset-0 z-20 bg-black opacity-50 transition-opacity lg:hidden"></div>

    <div :class="$page.props.showingMobileMenu ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
        class="overflow-y-auto fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 transition duration-300 transform lg:translate-x-0 lg:static lg:inset-0">
        <div class="flex justify-center items-center mt-8">
            <div class="flex items-center">
                <span class="mx-2 text-2xl font-semibold text-white">CCIP</span>
            </div>
        </div>

        <nav class="mt-10" x-data="{ isMultiLevelMenuOpen: false }">
            <!-- <nav-link :href="route('users.index')" :active="route().current('users.index')">
                <template #icon>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                </template>
Usuarios
</nav-link> -->
            <template v-if="hasPermission('UserManager')">
                <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                    @click="showingUsersAndRols = !showingUsersAndRols">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                        </path>
                    </svg>
                    <span class="mx-3">Usuarios y Roles</span>
                </a>
                <MyTransition :transitiondemonstration="showingUsersAndRols">
                    <Link class="w-full" :href="route('users.index')">Usuarios</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingUsersAndRols">
                    <Link class="w-full" :href="route('rols.index')">Roles</Link>
                </MyTransition>
            </template>

            <template v-if="hasPermission('HumanResourceManager') || hasPermission('HumanResource')">
                <a v-if="subSectionsPorVencer.length + subSectionsPorVencer7.length > 0 || permissionsPorVencer.length + vacationPorVencer3.length + vacationPorVencer7.length > 0 || formationProgramsAlarms.length > 0 || employeeBirthdayAlarms.length > 0"
                    class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                    @click="showingHumanResource = (showingMembers && showingMembers7) ? false : !showingHumanResource; showingMembers = showingMembers7 = false">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="red" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                    <span class="mx-3 ">Recursos Humanos</span>
                </a>
                <a v-else class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                    @click="showingHumanResource = !showingHumanResource">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        :stroke="'currentColor'" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                    <span class="mx-3">Recursos Humanos</span>
                </a>
                <MyTransition :transitiondemonstration="showingHumanResource">
                    <div class="relative">
                        <button @click="showEmployeeBirthdayAlarms = !showEmployeeBirthdayAlarms">
                            <Link class="w-full" :href="route('management.employees')">Empleados</Link>
                            <span v-if="employeeBirthdayAlarms.length > 0"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                                {{ employeeBirthdayAlarms.length }}
                            </span>
                        </button>
                    </div>
                </MyTransition>
                <template v-if="employeeBirthdayAlarms.length !== 0">
                    <MyTransition v-for="item in employeeBirthdayAlarms" :key="item.id" class="ml-4"
                        :transitiondemonstration="showEmployeeBirthdayAlarms">
                        <Link class="w-full flex items-center"
                            :href="route('management.employees.show', { id: item.id })">
                        <svg class="w-4 h-4 mr-2 text-red-600"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                        </svg>
                        <span>{{ item.name }} {{ item.lastname }}</span>
                        </Link>
                    </MyTransition>
                </template>
                <MyTransition :transitiondemonstration="showingHumanResource">
                    <Link class="w-full" :href="route('employees.external.index')">Empleados Externos</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingHumanResource">
                    <Link class="w-full" :href="route('spreadsheets.index')">Nomina</Link>
                </MyTransition>

                <MyTransition :transitiondemonstration="showingHumanResource">
                    <div class="relative">
                        <button @click="showFormationProgramsAlarms = !showFormationProgramsAlarms">
                            <Link class="w-full" :href="route('management.employees.formation_development')">Formacion y
                            Desarrollo
                            </Link>
                            <span v-if="formationProgramsAlarms.length > 0"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                                {{ formationProgramsAlarms.length }}
                            </span>
                        </button>
                    </div>
                </MyTransition>
                <!-- formation programs alarms -->
                <template v-if="formationProgramsAlarms.length !== 0">
                    <MyTransition v-for="item in formationProgramsAlarms" :key="item.id" class="ml-4"
                        :transitiondemonstration="showFormationProgramsAlarms">
                        <Link class="w-full flex items-center"
                            :href="route('management.employees.formation_development.detail', { employee_id: item.id })">
                        <svg :class="`w-4 h-4 mr-2 ${item.critical ? 'text-red-600' : 'text-yellow-400'} dark:text-red`"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path
                                d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                        </svg>
                        <span>{{ item.name }} {{ item.lastname }}</span>
                        </Link>
                    </MyTransition>
                </template>


                <MyTransition :transitiondemonstration="showingHumanResource">
                    <div class="relative">
                        <button @click="alarmVacaPermisions">
                            <span
                                v-if="permissionsPorVencer.length + vacationPorVencer3.length + vacationPorVencer7.length > 0"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                                {{ permissionsPorVencer.length + vacationPorVencer3.length + vacationPorVencer7.length
                                }}</span>
                        </button>
                        <Link class="w-full" :href="route('management.vacation')">Vacaciones y Permisos</Link>
                    </div>
                </MyTransition>
                <!-- vacatioon permissions alarms -->
                <template v-if="showingPermissionsAlarm && showingVacationAlarm">
                    <div class="mb-4">
                        <MyTransition v-for="item in permissionsPorVencer" :key="item.id" class="ml-4"
                            :transitiondemonstration="showingPermissionsAlarm">
                            <Link class="w-full flex items-center"
                                :href="route('management.vacation.information.details', { vacation: item.id })">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-yellow-600 dark:text-red" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                                </svg>
                                <span>{{ item.employee.name }}</span>
                            </div>
                            </Link>
                        </MyTransition>
                        <MyTransition v-for="item in vacationPorVencer3" :key="item.id" class="ml-4"
                            :transitiondemonstration="showingVacationAlarm">
                            <Link class="w-full flex items-center"
                                :href="route('management.vacation.information.details', { vacation: item.id })">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                                </svg>
                                <span>{{ item.employee.name }}</span>
                            </div>
                            </Link>
                        </MyTransition>
                        <MyTransition v-for="item in vacationPorVencer7" :key="item.id" class="ml-4"
                            :transitiondemonstration="showingVacationAlarm">
                            <Link class="w-full flex items-center"
                                :href="route('management.vacation.information.details', { vacation: item.id })">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-yellow-600 dark:text-yellow" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                                </svg>
                                <span>{{ item.employee.name }}</span>
                            </div>
                            </Link>
                        </MyTransition>
                    </div>
                </template>
                <MyTransition :transitiondemonstration="showingHumanResource">
                    <Link class="w-full" :href="route('documents.index')">Documentos</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingHumanResource">
                    <div class="relative">
                        <button @click="toggleMembers"><span
                                v-if="subSectionsPorVencer.length + subSectionsPorVencer7.length > 0"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                                {{ subSectionsPorVencer.length + subSectionsPorVencer7.length }}
                            </span>
                        </button>
                        <Link class="w-full" :href="route('sections.subSections')">Alarmas RRHH</Link>
                    </div>
                </MyTransition>
                <template v-if="showingMembers && showingMembers7">
                    <div class="mb-4">
                        <MyTransition v-for="item in subSectionsPorVencer" :key="item.id" class="ml-4"
                            :transitiondemonstration="showingMembers">
                            <Link class="w-full flex items-center"
                                :href="route('sections.subSection', { subSection: item.id })">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                                </svg>
                                <span>{{ item.name }}</span>
                            </div>
                            </Link>
                        </MyTransition>
                        <MyTransition v-for="item in subSectionsPorVencer7" :key="item.id" class="ml-4"
                            :transitiondemonstration="showingMembers7">
                            <Link class="w-full flex items-center"
                                :href="route('sections.subSection', { subSection: item.id })">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-yellow-600 dark:text-yellow" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                                </svg>
                                <span>{{ item.name }}</span>
                            </div>
                            </Link>
                        </MyTransition>
                    </div>
                </template>
            </template>

            <template v-if="hasPermission('InventoryManager') || hasPermission('Inventory')">
                <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                    @click="showingInventory = !showingInventory">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 7.125C2.25 6.504 2.754 6 3.375 6h6c.621 0 1.125.504 1.125 1.125v3.75c0 .621-.504 1.125-1.125 1.125h-6a1.125 1.125 0 01-1.125-1.125v-3.75zM14.25 8.625c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v8.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-8.25zM3.75 16.125c0-.621.504-1.125 1.125-1.125h5.25c.621 0 1.125.504 1.125 1.125v2.25c0 .621-.504 1.125-1.125 1.125h-5.25a1.125 1.125 0 01-1.125-1.125v-2.25z" />
                    </svg>
                    <span class="mx-3">Inventario</span>
                </a>
                <MyTransition :transitiondemonstration="showingInventory">
                    <Link class="w-full" :href="route('inventory.purchaseproducts')">Productos</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingInventory">
                    <Link class="w-full" :href="route('warehouses.warehouses')">Almacenes</Link>
                </MyTransition>
            </template>

            <template v-if="hasPermission('PurchasingManager') || hasPermission('Purchasing')">
                <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                    @click="showingShoppingArea = !showingShoppingArea">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" :stroke="purchaseOrdersAlarms.length + shoppingPurchases.length + shoppingPurchases7.length
        > 0 ? 'red' : 'currentColor'" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <span class="mx-3">Area de Compras</span>
                </a>

                <MyTransition :transitiondemonstration="showingShoppingArea">
                    <Link class="w-full" :href="route('providersmanagement.index')">Proveedores</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingShoppingArea">
                    <div class="relative">
                        <button @click="tooglePurchaseRequest"><span
                                v-if="shoppingPurchases.length + shoppingPurchases7.length > 0"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                                {{ shoppingPurchases.length + shoppingPurchases7.length }}</span>
                        </button>
                        <Link class="w-full" :href="route('purchasesrequest.index')">Solicitudes</Link>
                    </div>
                </MyTransition>
                <template v-if="showShoppingPurchaseRequestAlarms">
                    <MyTransition v-for="item in shoppingPurchases" :key="item.id" class="ml-4"
                        :transitiondemonstration="showShoppingPurchaseRequestAlarms">
                        <Link class="w-full flex items-center"
                            :href="route('purchasingrequest.details', { id: item.id })">
                        <div class="flex items-center"> <!-- Contenedor del ícono y el título -->
                            <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                            </svg>
                            <span class="overflow-hidden whitespace-nowrap">{{ item.title }}</span>
                            <!-- Estilo específico para el título -->
                        </div>
                        </Link>
                    </MyTransition>
                    <MyTransition v-for="item in shoppingPurchases7" :key="item.id" class="ml-4"
                        :transitiondemonstration="showShoppingPurchaseRequestAlarms">
                        <Link class="w-full flex items-center"
                            :href="route('purchasingrequest.details', { id: item.id })">
                        <div class="flex items-center"> <!-- Contenedor del ícono y el título -->
                            <svg class="w-4 h-4 mr-2 text-yellow-600 dark:text-yellow" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                            </svg>
                            <span class="overflow-hidden whitespace-nowrap">{{ item.title }}</span>
                            <!-- Estilo específico para el título -->
                        </div>
                        </Link>
                    </MyTransition>
                </template>

                <MyTransition :transitiondemonstration="showingShoppingArea">
                    <div class="relative">
                        <button @click="showPurchaseOrdersAlarms = !showPurchaseOrdersAlarms">
                            <span v-if="purchaseOrdersAlarms.length > 0"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                                {{ purchaseOrdersAlarms.length }}
                            </span>
                        </button>
                        <Link class="w-full" :href="route('purchaseorders.index')">Ordenes</Link>
                    </div>
                </MyTransition>
                <!-- purchase order alarms -->
                <template v-if="purchaseOrdersAlarms.length !== 0">
                    <MyTransition v-for="item in purchaseOrdersAlarms" :key="item.id" class="ml-4"
                        :transitiondemonstration="showPurchaseOrdersAlarms">
                        <Link class="w-full flex items-center"
                            :href="route('purchaseorders.details', { purchase_order_id: item.id })">
                        <div class="flex items-center">
                            <svg :class="`w-4 h-4 mr-2 ${item.critical ? 'text-red-600' : 'text-yellow-400'} dark:text-red`"
                                aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                viewBox="0 0 20 20">
                                <path
                                    d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                            </svg>
                            <span>{{ item.purchase_quote.purchasing_requests.title }}</span>
                        </div>

                        </Link>
                    </MyTransition>
                </template>

                <MyTransition :transitiondemonstration="showingShoppingArea">
                    <Link class="w-full" :href="route('purchaseorders.history')">Compras Completadas</Link>
                </MyTransition>
            </template>

            <template v-if="hasPermission('ProjectManager') || hasPermission('Project')">
                <a v-if="cicsasubSectionsPorVencer.length + cicsasubSectionsPorVencer7.length > 0"
                    class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                    @click="showingProyectArea = (cicsashowingMembers && cicsashowingMembers7) ? false : !showingProyectArea; cicsashowingMembers = cicsashowingMembers7 = false">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="red" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                    <span class="mx-3">Area de Proyectos</span>
                </a>
                <a v-else class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                    @click="showingProyectArea = !showingProyectArea">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" />
                    </svg>
                    <span class="mx-3">Area de Proyectos</span>
                </a>
                <MyTransition :transitiondemonstration="showingProyectArea">
                    <Link class="w-full" :href="route('customers.index')">Clientes</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingProyectArea">
                    <Link class="w-full" :href="route('preprojects.titles')">PRO</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingProyectArea">
                    <Link class="w-full" :href="route('preprojects.index')">Anteproyectos</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingProyectArea">
                    <Link class="w-full" :href="route('projectmanagement.index')">Proyectos</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingProyectArea">
                    <Link class="w-full" :href="route('checklist.index')">
                        Checklist
                    </Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingProyectArea">
                    <Link class="w-full" :href="route('project.backlog.index')">
                        Backlog
                    </Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingProyectArea">
                    <div class="relative">
                        <button @click="toggleMembersCicsa">
                            <span v-if="cicsasubSectionsPorVencer.length + cicsasubSectionsPorVencer7.length > 0"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                                {{ cicsasubSectionsPorVencer.length + cicsasubSectionsPorVencer7.length }}
                            </span>
                        </button>
                        <Link class="w-full" :href="route('member.cicsa')">Alarmas Cicsa</Link>
                    </div>
                </MyTransition>

                <template v-if="cicsashowingMembers && cicsashowingMembers7">
                    <div class="mb-4">
                        <MyTransition v-for="item in cicsasubSectionsPorVencer" :key="item.id" class="ml-4"
                            :transitiondemonstration="cicsashowingMembers">
                            <Link class="w-full flex items-center"
                                :href="route('member.cicsa.show', { subSection: item.id })">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                                </svg>
                                <span>{{ item.name }}</span>
                            </div>
                            </Link>
                        </MyTransition>
                        <MyTransition v-for="item in cicsasubSectionsPorVencer7" :key="item.id" class="ml-4"
                            :transitiondemonstration="cicsashowingMembers7">
                            <Link class="w-full flex items-center"
                                :href="route('member.cicsa.show', { subSection: item.id })">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-yellow-600 dark:text-yellow" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                                </svg>
                                <span>{{ item.name }}</span>
                            </div>

                            </Link>
                        </MyTransition>
                    </div>
                </template>
            </template>


            <template v-if="hasPermission('FinanceManager') || hasPermission('Finance')">
                <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                    @click="showingFinance = !showingFinance">
                    <svg v-if="financePurchases.length + financePurchases7.length + paymentAlarms3.length + paymentAlarms7.length > 0"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="red" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>

                    <span class="mx-3">Finanzas</span>
                </a>
                <MyTransition :transitiondemonstration="showingFinance">
                    <Link class="w-full" :href="route('selectproject.index')">Presupuestos</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingFinance">
                    <div class="relative">
                        <button @click="tooglePurchaseQuote"><span
                                v-if="financePurchases.length + financePurchases7.length > 0"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                                {{ financePurchases.length + financePurchases7.length }}</span>
                        </button>
                        <Link class="w-full" :href="route('managementexpense.index')">Aprobación de Compras</Link>
                    </div>
                </MyTransition>
                <template v-if="showFinancePurchaseQuoteAlarms">
                    <div class="mb-4">
                        <MyTransition v-for="item in financePurchases" :key="item.id" class="ml-4"
                            :transitiondemonstration="showFinancePurchaseQuoteAlarms">
                            <Link class="w-full flex items-center"
                                :href="route('managementexpense.details', { purchase_quote: item.id })">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                                </svg>
                                <span>{{ item.purchasing_requests.title }}</span>
                            </div>
                            </Link>
                        </MyTransition>
                        <MyTransition v-for="item in financePurchases7" :key="item.id" class="ml-4"
                            :transitiondemonstration="showFinancePurchaseQuoteAlarms">
                            <Link class="w-full flex items-center"
                                :href="route('managementexpense.details', { purchase_quote: item.id })">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-yellow-600 dark:text-yellow" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                                </svg>
                                <span>{{ item.purchasing_requests.title }}</span>
                            </div>
                            </Link>
                        </MyTransition>
                    </div>
                </template>
                <MyTransition :transitiondemonstration="showingFinance">
                    <Link class="w-full" :href="route('deposits.index')">Depósitos</Link>
                </MyTransition>

                <MyTransition :transitiondemonstration="showingFinance">
                    <div class="relative">
                        <button @click="tooglePayment"><span v-if="paymentAlarms3.length + paymentAlarms7.length > 0"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                                {{ paymentAlarms3.length + paymentAlarms7.length }}</span>
                        </button>
                        <Link class="w-full" :href="route('payment.index')">Pagos OC</Link>
                    </div>
                </MyTransition>
                <template v-if="paymentAlarms3.length !== 0">
                    <MyTransition v-for="item in paymentAlarms3" :key="item.id" class="ml-4"
                        :transitiondemonstration="paymentPorVencer">
                        <Link class="w-full flex items-center" :href="route('payment.index')">
                        <div class="flex items-center"> <!-- Contenedor del ícono y el título -->
                            <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                            </svg>
                            <span class="overflow-hidden whitespace-nowrap">{{ item.cod_payment }}</span>
                        </div>
                        </Link>
                    </MyTransition>
                    <MyTransition v-for="item in paymentAlarms7" :key="item.id" class="ml-4"
                        :transitiondemonstration="paymentPorVencer">
                        <Link class="w-full flex items-center" :href="route('payment.index')">
                        <div class="flex items-center"> <!-- Contenedor del ícono y el título -->
                            <svg class="w-4 h-4 mr-2 text-yellow-600 dark:text-red" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                            </svg>
                            <span class="overflow-hidden whitespace-nowrap">{{ item.cod_payment }}</span>
                        </div>
                        </Link>
                    </MyTransition>
                </template>
            </template>


            <template v-if="hasPermission('DocumentGestion')">
                <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#" @click="showDocs = !showDocs">
                    <svg v-if="archiveAlarms.length + archiveAlarms7.length > 0" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
                    </svg>
                    <svg v-else xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6 text-white">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776" />
                    </svg>

                    <span class="mx-3">Gestion Documentaria</span>
                </a>
                <MyTransition :transitiondemonstration="showDocs">
                    <div class="relative">
                        <button @click="toogleArchives"><span v-if="archiveAlarms.length + archiveAlarms7.length > 0"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                                {{ archiveAlarms.length + archiveAlarms7.length }}</span>
                        </button>
                        <Link class="w-full" :href="route('documment.management.folders')">CCIP</Link>
                    </div>
                </MyTransition>

                <template v-if="showArchivesAlarms">
                    <div class="mb-4">
                        <MyTransition v-for="item in archiveAlarms" :key="item.id" class="ml-4"
                            :transitiondemonstration="showArchivesAlarms">
                            <Link class="w-full flex items-center"
                                :href="route('archives.show', { folder: item.archive.folder_id })">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                                </svg>
                                <span>{{ item.archive.folder.name + '/' + item.archive.name + '.' +
        item.archive.extension }}</span>
                            </div>
                            </Link>
                        </MyTransition>
                        <MyTransition v-for="item in archiveAlarms7" :key="item.id" class="ml-4"
                            :transitiondemonstration="showArchivesAlarms">
                            <Link class="w-full flex items-center"
                                :href="route('archives.show', { folder: item.archive.folder_id })">
                            <div class="flex items-center">
                                <svg class="w-4 h-4 mr-2 text-yellow-600 dark:text-yellow" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                    <path
                                        d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                                </svg>
                                <span>{{ item.archive.folder.name + '/' + item.archive.name + '.' +
        item.archive.extension }}</span>
                            </div>
                            </Link>
                        </MyTransition>
                    </div>
                </template>
                <MyTransition :transitiondemonstration="showDocs">
                    <div class="relative">
                        <Link class="w-full" :href="route('local.drive.index', {root: 1})">Local Drive</Link>
                    </div>
                </MyTransition>
                <!-- <MyTransition v-if="currentAuth.user.role_id === 1" :transitiondemonstration="showDocs">
                    <Link class="w-full" :href="route('documment.management.folders.validation')">Aprobación</Link>
                </MyTransition> -->

            </template>


            <template v-if="true">
                <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#" @click="showCicsa = !showCicsa">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 21a9.004 9.004 0 0 0 8.716-6.747M12 21a9.004 9.004 0 0 1-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 0 1 7.843 4.582M12 3a8.997 8.997 0 0 0-7.843 4.582m15.686 0A11.953 11.953 0 0 1 12 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0 1 21 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0 1 12 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 0 1 3 12c0-1.605.42-3.113 1.157-4.418" />
                    </svg>

                    <span class="mx-3">CICSA</span>
                </a>
                <MyTransition :transitiondemonstration="showCicsa">
                    <Link class="w-full" :href="route('cicsa.index')">Proceso CICSA</Link>
                </MyTransition>
            </template>











            <template v-if="hasPermission('SocialNetwork')">
                <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                    @click="showSocialNetworkSot = !showSocialNetworkSot">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                    </svg>

                    <span class="mx-3">Social Network</span>
                </a>
                <MyTransition :transitiondemonstration="showSocialNetworkSot">
                    <Link class="w-full" :href="route('socialnetwork.sot')">SOT</Link>
                </MyTransition>
            </template>

            <template v-if="hasPermission('HuaweiManager')">
                <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#" @click="showHuawei = !showHuawei">
                    <svg fill="white" width="23px" height="23px" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M4.896 8.188c0 0-2.469 2.359-2.604 4.854v0.464c0.109 2.016 1.63 3.203 1.63 3.203 2.438 2.385 8.344 5.385 9.729 6.063 0 0 0.083 0.042 0.135-0.010l0.026-0.052v-0.057c-3.786-8.25-8.917-14.464-8.917-14.464zM12.865 24.802c-0.026-0.109-0.13-0.109-0.13-0.109l-9.839 0.349c1.063 1.906 2.865 3.37 4.745 2.932 1.281-0.333 4.214-2.375 5.172-3.068 0.083-0.068 0.052-0.12 0.052-0.12zM12.974 23.76c-4.323-2.922-12.693-7.385-12.693-7.385-0.203 0.609-0.266 1.198-0.281 1.729v0.094c0 1.427 0.531 2.427 0.531 2.427 1.068 2.255 3.12 2.938 3.12 2.938 0.938 0.396 1.87 0.411 1.87 0.411 0.161 0.026 5.865 0 7.385 0 0.068 0 0.109-0.068 0.109-0.068v-0.078c0-0.042-0.042-0.068-0.042-0.068zM12.078 4.255c-1.938 0.495-3.328 2.198-3.427 4.198v0.547c0.042 0.802 0.214 1.401 0.214 1.401 0.88 3.865 5.151 10.198 6.068 11.531 0.068 0.068 0.135 0.042 0.135 0.042 0.052-0.021 0.083-0.078 0.078-0.135 1.417-14.13-1.479-17.891-1.479-17.891-0.427 0.026-1.589 0.307-1.589 0.307zM23.146 7.281c0 0-0.651-2.401-3.25-3.042 0 0-0.76-0.188-1.563-0.292 0 0-2.906 3.745-1.495 17.906 0.016 0.094 0.083 0.104 0.083 0.104 0.094 0.042 0.13-0.036 0.13-0.036 0.964-1.375 5.203-7.682 6.068-11.521 0 0 0.479-1.87 0.026-3.12zM19.255 24.708c0 0-0.094 0-0.12 0.063 0 0-0.016 0.094 0.036 0.135 0.932 0.682 3.802 2.667 5.177 3.068 0 0 0.214 0.068 0.573 0.078h0.182c0.922-0.026 2.536-0.49 4-3.010l-9.865-0.333zM29.693 13.495c0.188-2.75-2.589-5.297-2.589-5.307 0 0-5.13 6.214-8.891 14.401 0 0-0.042 0.104 0.026 0.172l0.052 0.010h0.083c1.411-0.703 7.276-3.693 9.703-6.052 0 0 1.536-1.24 1.615-3.224zM31.719 16.349c0 0-8.37 4.49-12.693 7.396 0 0-0.068 0.057-0.042 0.151 0 0 0.042 0.078 0.094 0.078 1.547 0 7.417 0 7.563-0.026 0 0 0.76-0.026 1.693-0.385 0 0 2.078-0.667 3.161-3.031 0 0 0.974-1.932 0.224-4.182z" />
                    </svg>
                    <span class="mx-3">Huawei</span>
                </a>
                <MyTransition :transitiondemonstration="showHuawei">
                    <Link class="w-full" :href="route('huawei.inventory.show')">Inventario de Huawei</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showHuawei">
                    <Link class="w-full" :href="route('huawei.sites')">Sites de Huawei</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showHuawei">
                    <Link class="w-full" :href="route('huawei.titles')">Huawei PRO</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showHuawei">
                    <Link class="w-full" :href="route('huawei.projects')">Proyectos de Huawei</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showHuawei">
                    <Link class="w-full" :href="route('huawei.specialrefunds')">Devoluciones Especiales</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showHuawei">
                    <Link class="w-full" :href="route('huawei.generalbalance')">Balance General</Link>
                </MyTransition>
            </template>

        </nav>
    </div>
</template>

<script>
import NavLink from '@/Components/NavLink.vue';
import MyTransition from '@/Components/MyTransition.vue';
import { Link, } from '@inertiajs/vue3';
import { ref } from 'vue';
import axios from 'axios';

export default {
    props: {
        userPermissions: {
            type: Array,
        },
        auth: {
            type: Object
        }
    },

    components: {
        NavLink,
        Link,
        MyTransition,
    },

    data() {
        return {
            employeeBirthdayAlarms:[],
            permissionsPorVencer: [],
            vacationPorVencer3: [],
            vacationPorVencer7: [],
            subSectionsPorVencer: [],
            subSectionsPorVencer7: [],
            cicsasubSectionsPorVencer: [],
            cicsasubSectionsPorVencer7: [],

            purchaseOrdersAlarms: [],
            paymentAlarms3: [],
            paymentAlarms7: [],
            formationProgramsAlarms: [],

            financePurchases: [],
            financePurchases7: [],

            shoppingPurchases: [],
            shoppingPurchases7: [],

            archiveAlarms: [],
            archiveAlarms7: [],
        };
    },

    computed: {
        currentAuth() {
            return this.$page.props.auth;
        },
    },

    setup() {
        let showingUsersAndRols = ref(false)
        let showingHumanResource = ref(false)
        let showingFinance = ref(false)
        let showingInventory = ref(false)
        let showingProyectArea = ref(false)
        let showingShoppingArea = ref(false)

        let showingPermissionsAlarm = ref(false)
        let showingVacationAlarm = ref(false)

        let showingMembers = ref(false)
        let showingMembers7 = ref(false)
        let cicsashowingMembers = ref(false)
        let cicsashowingMembers7 = ref(false)

        let showPurchaseOrdersAlarms = ref(false)
        let paymentPorVencer = ref(false)
        let showFinancePurchaseQuoteAlarms = ref(false)
        let showShoppingPurchaseRequestAlarms = ref(false)
        let showFormationProgramsAlarms = ref(false)
        let showArchivesAlarms = ref(false)
        let showEmployeeBirthdayAlarms = ref(false)

        let showDocs = ref(false)
        let showCicsa = ref(false)
        let showSocialNetworkSot = ref(false)
        let showHuawei = ref(false);

        return {
            showingUsersAndRols,
            showingHumanResource,
            showingFinance,
            showingInventory,
            showingProyectArea,
            showingShoppingArea,
            showingPermissionsAlarm,
            showingVacationAlarm,
            showingMembers,
            showingMembers7,
            cicsashowingMembers,
            cicsashowingMembers7,
            showPurchaseOrdersAlarms,
            paymentPorVencer,
            showFinancePurchaseQuoteAlarms,
            showShoppingPurchaseRequestAlarms,
            showFormationProgramsAlarms,
            showArchivesAlarms,
            showEmployeeBirthdayAlarms,
            showDocs,
            showCicsa,
            showSocialNetworkSot,
            showHuawei
        }
    },

    methods: {
        hasPermission(permission) {
            return this.$page.props.userPermissions.includes(permission);
        },

        async fetchAlarmHappyBirthdayCount() {
            try {
                const response = await axios.get(route('management.employees.happy.birthday'));
                this.employeeBirthdayAlarms = response.data.happyBirthday;
            } catch (error) {
                console.error('Error al obtener el cumpleaños de los empleados:', error);
            }
        },

        async fetchAlarmPermissionsCount() {
            try {
                const response = await axios.get(route('alarm.permissions'));
                this.permissionsPorVencer = response.data.permissions;
            } catch (error) {
                console.error('Error al obtener el contador de permissions:', error);
            }
        },

        async fetchAlarmVacationCount() {
            try {
                const response = await axios.get(route('alarm.vacation'));
                this.vacationPorVencer3 = response.data.vacation3;
                this.vacationPorVencer7 = response.data.vacation7;
            } catch (error) {
                console.error('Error al obtener el contador de vacation:', error);
            }
        },

        async fetchSubSectionsCount() {
            try {
                const response = await axios.get(route('sections.alarm'));
                this.subSectionsPorVencer = response.data.subSections;
                this.subSectionsPorVencer7 = response.data.subSections7;
            } catch (error) {
                console.error('Error al obtener el contador de subsecciones:', error);
            }
        },

        async fetchCicsaSubSectionsCount() {
            try {
                const response = await axios.get(route('member.cicsa.alarm'));
                this.cicsasubSectionsPorVencer = response.data.subSections;
                this.cicsasubSectionsPorVencer7 = response.data.subSections7;
            } catch (error) {
                console.error('Error al obtener el contador de subsecciones:', error);
            }
        },

        async fetchPaymentsAlarm() {
            try {
                const response = await axios.get(route('payment.alarm'));
                this.paymentAlarms3 = response.data.payment3Days;
                this.paymentAlarms7 = response.data.payment7Days;

            } catch (error) {
                console.error('Error al obtener el contador de payments:', error);
            }
        },

        async fetchPurchaseOrderAlarms() {
            try {
                const response = await axios.get(route('purchaseorders.alarms'));
                this.purchaseOrdersAlarms = [...response.data.purchaseOrders3d.map(i => ({ ...i, 'critical': true })),
                ...response.data.purchaseOrders7d
                ]
            } catch (error) {
                console.error('Error al obtener alarmas de finanzas:', error);
            }
        },

        async fetchFormationProgramAlarms() {
            try {
                const response = await axios.get(route('employees_in_programs.alarms'))
                this.formationProgramsAlarms = [
                    ...response.data.alarm3d.map(i => ({ ...i, critical: true })),
                    ...response.data.alarm7d
                ]
            } catch (error) {
                console.error('Error al obtener alarmas de programa de formación:', error);
            }
        },

        async fetchFinancePurchases() {
            try {
                const response = await axios.get(route('approve_quote.alarm'));
                this.financePurchases = response.data.purchasesLessThanThreeDays;
                this.financePurchases7 = response.data.purchasesBetweenFourAndSevenDays;
            } catch (error) {
                console.error('Error al obtener el contador de subsecciones:', error);
            }
        },

        async fetchPurchasesRequest() {
            try {
                const response = await axios.get(route('purchasesrequest.alarm'));
                this.shoppingPurchases = Object.values(response.data.purchasesLessThanThreeDays);
                this.shoppingPurchases7 = Object.values(response.data.purchasesBetweenFourAndSevenDays);
            } catch (error) {
                console.error('Error al obtener el contador de subsecciones:', error);
            }
        },

        async fetchArchiveRequest() {
            try {
                const response = await axios.get(route('archives.alarms'));
                this.archiveAlarms = Object.values(response.data.alarms3);
                this.archiveAlarms7 = Object.values(response.data.alarms7);
            } catch (error) {
                console.error('Error al obtener el contador de archivos:', error);
            }
        },

        alarmVacaPermisions() {
            this.showingPermissionsAlarm = !this.showingPermissionsAlarm;
            this.showingVacationAlarm = !this.showingVacationAlarm;
        },

        toggleMembers() {
            this.showingMembers7 = !this.showingMembers7;
            this.showingMembers = !this.showingMembers;
        },
        toggleMembersCicsa() {
            this.cicsashowingMembers7 = !this.cicsashowingMembers7;
            this.cicsashowingMembers = !this.cicsashowingMembers;
        },
        tooglePurchaseQuote() {
            this.showFinancePurchaseQuoteAlarms = !this.showFinancePurchaseQuoteAlarms;
        },
        tooglePayment() {
            this.paymentPorVencer = !this.paymentPorVencer;
        },
        tooglePurchaseRequest() {
            this.showShoppingPurchaseRequestAlarms = !this.showShoppingPurchaseRequestAlarms;
        },
        toogleArchives() {
            this.showArchivesAlarms = !this.showArchivesAlarms;
        }
    },

    mounted() {
        if (this.hasPermission('HumanResourceManager') || this.hasPermission('HumanResource')) {
            this.fetchAlarmHappyBirthdayCount();
            this.fetchAlarmPermissionsCount();
            this.fetchAlarmVacationCount();
            this.fetchFormationProgramAlarms();
            this.fetchSubSectionsCount();
        }
        if (this.hasPermission('ProjectManager') || this.hasPermission('Project')) {
            this.fetchCicsaSubSectionsCount();
        }

        if (this.hasPermission('PurchasingManager') || this.hasPermission('Purchasing')) {
            this.fetchPurchasesRequest();
            this.fetchPurchaseOrderAlarms();
        }
        if (this.hasPermission('FinanceManager') || this.hasPermission('Finance')) {
            this.fetchFinancePurchases();
            this.fetchPaymentsAlarm();
        }
        if (this.hasPermission('DocumentGestion')) {
            this.fetchArchiveRequest();
        }
        setInterval(() => {
            if (this.hasPermission('HumanResourceManager') || this.hasPermission('HumanResource')) {
                this.fetchAlarmHappyBirthdayCount();
                this.fetchAlarmPermissionsCount();
                this.fetchAlarmVacationCount();
                this.fetchFormationProgramAlarms();
                this.fetchSubSectionsCount();
            }
            if (this.hasPermission('ProjectManager') || this.hasPermission('Project')) {
                this.fetchCicsaSubSectionsCount();
            }

            if (this.hasPermission('PurchasingManager') || this.hasPermission('Purchasing')) {
                this.fetchPurchasesRequest();
                this.fetchPurchaseOrderAlarms();
            }
            if (this.hasPermission('FinanceManager') || this.hasPermission('Finance')) {
                this.fetchFinancePurchases();
                this.fetchPaymentsAlarm();
            }
            if (this.hasPermission('DocumentGestion')) {
                this.fetchArchiveRequest();
            }
        }, 60000);
    },
}
</script>
