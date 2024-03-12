<template>
    <div :class="$page.props.showingMobileMenu ? 'block' : 'hidden'" @click="$page.props.showingMobileMenu = false"
        class="fixed inset-0 z-20 bg-black opacity-50 transition-opacity lg:hidden"></div>

    <div :class="$page.props.showingMobileMenu ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'"
        class="overflow-y-auto fixed inset-y-0 left-0 z-30 w-64 bg-gray-900 transition duration-300 transform lg:translate-x-0 lg:static lg:inset-0">
        <div class="flex justify-center items-center mt-8">
            <div class="flex items-center">
                <!-- <img src="http://localhost:8000/storage/image/logo_ccip.png" alt="Logo de la empresa" /> -->

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

            <template
                v-if="hasPermission('HumanResourceManager') || hasPermission('HumanResource')">
                <a v-if="subSectionsCount + subSectionsCount7 > 0" 
                    class="flex items-center mt-4 py-2 px-6 text-gray-100"
                    href="#" 
                    @click="showingHumanResource = (showingMembers && showingMembers7) ? false : !showingHumanResource; showingMembers = showingMembers7 = false">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                                d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                    <span class="mx-3">Recursos Humanos</span>
                </a>
                <a v-else class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                    @click="showingHumanResource = !showingHumanResource">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                    </svg>
                    <span class="mx-3">Recursos Humanos</span>
                </a>
                <MyTransition :transitiondemonstration="showingHumanResource">
                    <Link class="w-full" :href="route('management.employees')">Empleados</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingHumanResource">
                    <Link class="w-full" :href="route('spreadsheets.index')">Nomina</Link>
                </MyTransition>

                <MyTransition :transitiondemonstration="showingHumanResource">
                    <Link class="w-full" :href="route('management.employees.formation_development')">Formacion y Desarrollo
                    </Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingHumanResource">
                    <Link class="w-full" :href="route('management.vacation')">Vacaciones y Permisos</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingHumanResource">
                    <Link class="w-full" :href="route('documents.index')">Documentos</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingHumanResource">
                    <div class="relative">
                        <button @click="toggleMembers"><span v-if="subSectionsCount + subSectionsCount7 > 0"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">{{
                                    subSectionsCount + subSectionsCount7 }}</span></button>
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

            <template
                v-if="hasPermission('InventoryManager') || hasPermission('Inventory')">
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
                <a v-if="shoppingPurchasesTotal + shoppingPurchasesTotal7 > 0"
                    class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                    @click="showingShoppingArea = !showingShoppingArea">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red"
                        class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <span class="mx-3">Area de Compras</span>
                </a>
                <a v-else class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
                    @click="showingShoppingArea = !showingShoppingArea">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        :stroke="purchaseOrdersAlarms.length > 0 ? 'red' : 'currentColor'" class="w-6 h-6">
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
                                v-if="shoppingPurchasesTotal + shoppingPurchasesTotal7 > 0"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">{{
                                    shoppingPurchasesTotal + shoppingPurchasesTotal7 }}</span></button>
                        <Link class="w-full" :href="route('purchasesrequest.index')">Solicitudes</Link>
                    </div>
                </MyTransition>
                <template v-if="showShoppingPurchaseRequestAlarms">
                    <div class="mb-4">
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
                                    <span class="overflow-hidden whitespace-nowrap">{{ item.title }}</span> <!-- Estilo específico para el título -->
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
                                    <span class="overflow-hidden whitespace-nowrap">{{ item.title }}</span> <!-- Estilo específico para el título -->
                                </div>
                            </Link>
                        </MyTransition>
                    </div>
                </template>

                <MyTransition :transitiondemonstration="showingShoppingArea">
                    <div class="relative">
                        <button @click="showPurchaseOrdersAlarms = !showPurchaseOrdersAlarms">
                            <span v-if="purchaseOrdersAlarms.length > 0"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">{{
                                    purchaseOrdersAlarms.length }}</span>
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
                        <svg :class="`w-4 h-4 mr-2 ${item.critical ? 'text-red-600' : 'text-yellow-400'} dark:text-red`"
                            aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                        </svg>
                        <span>{{ item.purchase_quote.purchasing_requests.title }}</span>
                        </Link>
                    </MyTransition>
                </template>
                <MyTransition :transitiondemonstration="showingShoppingArea">
                    <Link class="w-full" :href="route('payment.index')">Pagos</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingShoppingArea">
                    <Link class="w-full" :href="route('purchaseorders.history')">Compras Completadas</Link>
                </MyTransition>
            </template>

            <template v-if="hasPermission('ProjectManager') || hasPermission('Project')">
                <a v-if="cicsasubSectionsCount + cicsasubSectionsCount7 > 0" class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#"
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
                    <Link class="w-full" :href="route('preprojects.index')">Anteproyectos</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingProyectArea">
                    <Link class="w-full" :href="route('projectmanagement.index')">Proyectos</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingProyectArea">
                    <Link class="w-full" :href="route('tasks.index')">Tareas</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingProyectArea">
                    <div class="relative">
                        <button @click="toggleMembersCicsa">
                            <span v-if="cicsasubSectionsCount + cicsasubSectionsCount7 > 0"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">
                                {{ cicsasubSectionsCount + cicsasubSectionsCount7 }}
                            </span>
                        </button>
                        <Link class="w-full" :href="route('sections.cicsaSubSections')">Alarmas Cicsa</Link>
                    </div>
                </MyTransition>

                <template v-if="cicsashowingMembers && cicsashowingMembers7">
                    <div class="mb-4">
                        <MyTransition v-for="item in cicsasubSectionsPorVencer" :key="item.id" class="ml-4"
                            :transitiondemonstration="cicsashowingMembers">
                            <Link class="w-full flex items-center"
                                :href="route('sections.cicsaSubSection', { subSection: item.id })">
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
                                :href="route('sections.cicsaSubSection', { subSection: item.id })">
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
                    <svg v-if="financePurchasesTotal + financePurchasesTotal7 > 0" xmlns="http://www.w3.org/2000/svg"
                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="red" class="w-6 h-6">
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
                    <Link class="w-full" :href="route('gangexpense.index')">Gastos</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingFinance">
                    <Link class="w-full" :href="route('selectproject.index')">Presupuestos</Link>
                </MyTransition>
                <MyTransition :transitiondemonstration="showingFinance">
                    <div class="relative">
                        <button @click="tooglePurchaseQuote"><span v-if="financePurchasesTotal + financePurchasesTotal7 > 0"
                                class="absolute top-0 right-0 bg-red-500 text-white rounded-full h-6 w-6 flex items-center justify-center text-xs leading-4">{{
                                    financePurchasesTotal + financePurchasesTotal7 }}</span></button>
                        <Link class="w-full" :href="route('managementexpense.index')">Aprobacion de Compras</Link>
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
            </template>

            <!-- <template v-if="hasPermission('UserManager')">
                <a class="flex items-center mt-4 py-2 px-6 text-gray-100" href="#" @click="toggleMembersCicsa">
                    <div v-if="cicsasubSectionsCount >= 1">
                        <svg class="w-6 h-6 text-red-600 dark:text-red" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                        </svg>
                    </div>
                    <div v-else>
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 16 21">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6"
                                d="M8 3.464V1.1m0 2.365a5.338 5.338 0 0 1 5.133 5.368v1.8c0 2.386 1.867 2.982 1.867 4.175C15 15.4 15 16 14.462 16H1.538C1 16 1 15.4 1 14.807c0-1.193 1.867-1.789 1.867-4.175v-1.8A5.338 5.338 0 0 1 8 3.464ZM4.54 16a3.48 3.48 0 0 0 6.92 0H4.54Z" />
                        </svg>
                    </div>
                    <span class=" ml-2.5">Por vencer de Cicsa: {{ cicsasubSectionsCount7 + cicsasubSectionsCount }}</span>
                </a>
                <div class="mb-4">

                    <MyTransition v-for="subSection in cicsasubSectionsPorVencer" :key="subSection.id"
                        :transitiondemonstration="cicsashowingMembers">
                        <Link class="w-full flex items-center"
                            :href="route('sections.cicsaSubSection', { subSection: subSection.id })">
                        <svg class="w-4 h-4 mr-2 text-red-600 dark:text-red" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                        </svg>
                        <span>{{ subSection.name }}</span>

                        </Link>
                    </MyTransition>

                    <MyTransition v-for="subSection in cicsasubSectionsPorVencer7" :key="subSection.id"
                        :transitiondemonstration="cicsashowingMembers7">
                        <Link class="w-full flex items-center"
                            :href="route('sections.cicsaSubSection', { subSection: subSection.id })">
                        <svg class="w-4 h-4 mr-2 text-yellow-400 dark:text-red" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="M15.133 10.632v-1.8a5.407 5.407 0 0 0-4.154-5.262.955.955 0 0 0 .021-.106V1.1a1 1 0 0 0-2 0v2.364a.944.944 0 0 0 .021.106 5.406 5.406 0 0 0-4.154 5.262v1.8C4.867 13.018 3 13.614 3 14.807 3 15.4 3 16 3.538 16h12.924C17 16 17 15.4 17 14.807c0-1.193-1.867-1.789-1.867-4.175Zm-13.267-.8a1 1 0 0 1-1-1 9.424 9.424 0 0 1 2.517-6.39A1.001 1.001 0 1 1 4.854 3.8a7.431 7.431 0 0 0-1.988 5.037 1 1 0 0 1-1 .995Zm16.268 0a1 1 0 0 1-1-1A7.431 7.431 0 0 0 15.146 3.8a1 1 0 0 1 1.471-1.354 9.425 9.425 0 0 1 2.517 6.391 1 1 0 0 1-1 .995ZM6.823 17a3.453 3.453 0 0 0 6.354 0H6.823Z" />
                        </svg>
                        <span>{{ subSection.name }}</span>
                        </Link>
                    </MyTransition>
                </div>
            </template> -->
        </nav>
    </div>
</template>
<script>
import NavLink from '@/Components/NavLink.vue';
import MyTransition from '@/Components/MyTransition.vue'
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue'
import axios from 'axios';

export default {
    props: {
        userPermissions: {
            type: Array,
        }
    },

    components: {
        NavLink,
        Link,
        MyTransition,
    },

    data() {
        return {
            subSectionsCount: 0,
            subSectionsCount7: 0,
            subSectionsPorVencer: [],
            subSectionsPorVencer7: [],
            cicsasubSectionsCount: 0,
            cicsasubSectionsCount7: 0,
            cicsasubSectionsPorVencer: [],
            cicsasubSectionsPorVencer7: [],
            purchaseOrdersAlarms: [],


            financePurchasesTotal: 0,
            financePurchasesTotal7: 0,
            financePurchases: [],
            financePurchases7: [],

            shoppingPurchasesTotal: 0,
            shoppingPurchasesTotal7: 0,
            shoppingPurchases: [],
            shoppingPurchases7: [],
        };
    },

    setup() {
        let showingUsersAndRols = ref(false)
        let showingHumanResource = ref(false)
        let showingFinance = ref(false)
        let showingInventory = ref(false)
        let showingProyectArea = ref(false)
        let showingShoppingArea = ref(false)
        let showingMembers = ref(false)
        let showingMembers7 = ref(false)
        let cicsashowingMembers = ref(false)
        let cicsashowingMembers7 = ref(false)

        let showPurchaseOrdersAlarms = ref(false)
        let isCriticalPurchaseOrdersAlarms = ref(false)
        let showFinancePurchaseQuoteAlarms = ref(false)
        let showShoppingPurchaseRequestAlarms = ref(false)
        return {
            showingUsersAndRols,
            showingHumanResource,
            showingFinance,
            showingInventory,
            showingProyectArea,
            showingShoppingArea,
            showingMembers,
            showingMembers7,
            cicsashowingMembers,
            cicsashowingMembers7,
            showPurchaseOrdersAlarms,
            showFinancePurchaseQuoteAlarms,
            showShoppingPurchaseRequestAlarms,
        }
    },

    methods: {
        hasPermission(permission) {
            return this.$page.props.userPermissions.includes(permission);
        },

        async fetchSubSectionsCount() {
            try {
                const response = await axios.get('/doTask');
                this.subSectionsCount = response.data.totalSubSections;
                this.subSectionsPorVencer = response.data.subSections;
            } catch (error) {
                console.error('Error al obtener el contador de subsecciones:', error);
            }
        },

        async fetchSubSectionsCount7() {
            try {
                const response = await axios.get('/doTask2');
                this.subSectionsCount7 = response.data.totalSubSections;
                this.subSectionsPorVencer7 = response.data.subSections;
            } catch (error) {
                console.error('Error al obtener el contador de subsecciones:', error);
            }
        },

        async fetchCicsaSubSectionsCount() {
            try {
                const response = await axios.get('/cicsaDoTask');
                this.cicsasubSectionsCount = response.data.totalSubSections;
                this.cicsasubSectionsPorVencer = response.data.subSections;
            } catch (error) {
                console.error('Error al obtener el contador de subsecciones:', error);
            }
        },

        async fetchCicsaSubSectionsCount7() {
            try {
                const response = await axios.get('/cicsaDoTask2');
                this.cicsasubSectionsCount7 = response.data.totalSubSections;
                this.cicsasubSectionsPorVencer7 = response.data.subSections;

            } catch (error) {
                console.error('Error al obtener el contador de subsecciones:', error);
            }
        },

        async fetchFinanceAlarms() {
            try {
                const response = await axios.get(route('purchaseorders.alarms'));
                this.purchaseOrdersAlarms = [...response.data.purchaseOrders3d.map(i => ({ ...i, 'critical': true })),
                ...response.data.purchaseOrders7d
                ]
            } catch (error) {
                console.error('Error al obtener alarmas de finanzas:', error);
            }
        },
        async fetchFinancePurchases() {
            try {
                const response = await axios.get(route('finance.task'));
                this.financePurchases = response.data.purchasesLessThanThreeDays;
                this.financePurchasesTotal = response.data.totalPurchasesLessThanThreeDays;
                this.financePurchases7 = response.data.purchasesBetweenFourAndSevenDays;
                this.financePurchasesTotal7 = response.data.totalPurchasesBetweenFourAndSevenDays;
            } catch (error) {
                console.error('Error al obtener el contador de subsecciones:', error);
            }
        },

        async fetchPurchasesRequest() {
            try {
                const response = await axios.get(route('purchasesrequest.task'));
                this.shoppingPurchases = response.data.purchasesLessThanThreeDays;
                this.shoppingPurchasesTotal = response.data.totalPurchasesLessThanThreeDays;
                this.shoppingPurchases7 = response.data.purchasesBetweenFourAndSevenDays;
                this.shoppingPurchasesTotal7 = response.data.totalPurchasesBetweenFourAndSevenDays;
            } catch (error) {
                console.error('Error al obtener el contador de subsecciones:', error);
            }
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
        tooglePurchaseRequest() {
            this.showShoppingPurchaseRequestAlarms = !this.showShoppingPurchaseRequestAlarms;
        }

    },

    mounted() {
        this.fetchSubSectionsCount();
        this.fetchSubSectionsCount7();
        this.fetchCicsaSubSectionsCount();
        this.fetchCicsaSubSectionsCount7();
        this.fetchFinanceAlarms();
        this.fetchFinancePurchases();
        this.fetchPurchasesRequest();
        setInterval(() => {
            this.fetchSubSectionsCount();
            this.fetchSubSectionsCount7();
            this.fetchCicsaSubSectionsCount();
            this.fetchCicsaSubSectionsCount7();
            this.fetchFinanceAlarms();
            this.fetchFinancePurchases();
            this.fetchPurchasesRequest();
        }, 60000);
    },

}
</script>

<!-- <style scoped></style> -->
