<template>

    <Head title="Information Additional" />
    <AuthenticatedLayout :redirectRoute="'management.employees'">
        <template #header>
            Perfil
        </template>
        <Toaster richColors />
        <form @submit.prevent="submit">
            <div class="space-y-6">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informacion Personal</h2>
                    <div>
                        <ModalImage v-model="form.cropped_image" @imagenRecortada="handleImagenRecortada" />
                        <InputError :message="form.errors.cropped_image" />
                    </div>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <InputLabel for="first-name">Nombre</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.name" id="first-name" autocomplete="off" />
                                <InputError :message="form.errors.name" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="last_name">Apellido
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.lastname" id="last_name" autocomplete="off" />
                                <InputError :message="form.errors.lastname" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="gender">Genero</InputLabel>
                            <div class="mt-2">
                                <select id="gender" v-model="form.gender" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Genero</option>
                                    <option>Masculino</option>
                                    <option>Femenino</option>
                                </select>
                                <InputError :message="form.errors.gender" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="state_civil">Estado Civil
                            </InputLabel>
                            <div class="mt-2">
                                <select id="state_civil" v-model="form.state_civil" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Estado Civil</option>
                                    <option>Casado(a)</option>
                                    <option>Soltero(a)</option>
                                    <option>Viudo(a)</option>
                                    <option>Divorciado(a)</option>
                                    <option>Conviviente</option>
                                </select>
                                <InputError :message="form.errors.state_civil" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="birthdate">Fecha de Nacimiento
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.birthdate" id="birthdate" />
                                <InputError :message="form.errors.birthdate" />
                            </div>
                        </div>

                        <div class="sm:col-span-2 sm:col-start-1">
                            <InputLabel for="dni">Documento Nacional de
                                Identidad</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.dni" id="dni" maxlength="8" />
                                <InputError :message="form.errors.dni" />
                            </div>
                        </div>

                        <div class="sm:col-span-4">
                            <InputLabel for="email">Correo Electronico
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="email" id="email" v-model="form.email" autocomplete="off" />
                                <InputError :message="form.errors.email" />
                            </div>
                        </div>

                        <div class="sm:col-span-4">
                            <InputLabel for="email_company">Correo
                                Electronico
                                de Empresa
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="email" id="email_company" v-model="form.email_company"
                                    autocomplete="off" />
                                <InputError :message="form.errors.email_company" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="phone1">Telefono 1</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.phone1" id="phone1" autocomplete="off"
                                    maxlength="9" />
                                <InputError :message="form.errors.phone1" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="nro_cuenta">Nro de Cuenta</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.nro_cuenta" id="nro_cuenta" autocomplete="off" />
                                <InputError :message="form.errors.nro_cuenta" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informacion de Contrato</h2>
                    <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2">
                            <InputLabel for="cost_line_id">
                                Linea de Negocio
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="form.cost_line_id" id="cost_line_id" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Linea de Negocio</option>
                                    <option v-for="item, i in costLines" :key="i" :value="item.id">{{ item.name }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.cost_line_id" />
                            </div>
                        </div>
                        <div class="mt-3 col-span-2 md:col-span-1">
                            <InputLabel for="discount_remuneration">
                                ¿Tiene Descuento sobre remuneración?
                            </InputLabel>
                            <div class="mt-2 flex gap-4">
                                <label class="flex gap-2 items-center">
                                    Sí
                                    <input type="radio" v-model="form.discount_remuneration" id="discount_remuneration"
                                        :value="true"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <label class="flex gap-2 items-center">
                                    No
                                    <input type="radio" v-model="form.discount_remuneration" id="discount_remuneration"
                                        :value="false"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <InputError :message="form.errors.discount_remuneration" />
                            </div>
                        </div>
                        <div class="mt-3 col-span-2 md:col-span-1">
                            <InputLabel for="life_ley">
                                ¿Tiene Pòliza de Vida?
                            </InputLabel>
                            <div class="mt-2 flex gap-4">
                                <label class="flex gap-2 items-center">
                                    Sí
                                    <input type="radio" v-model="form.life_ley" id="life_ley" :value="true"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <label class="flex gap-2 items-center">
                                    No
                                    <input type="radio" v-model="form.life_ley" id="life_ley" :value="false"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <InputError :message="form.errors.life_ley" />
                            </div>
                        </div>
                        <div class="mt-3 col-span-2 md:col-span-1">
                            <InputLabel for="discount_sctr">
                                ¿Tiene SCTR?
                            </InputLabel>
                            <div class="mt-2 class flex gap-4">
                                <label class="flex gap-2 items-center">
                                    Sí
                                    <input type="radio" v-model="form.discount_sctr" id="discount_sctr" :value="true"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <label class="flex gap-2 items-center">
                                    No
                                    <input type="radio" v-model="form.discount_sctr" id="discount_sctr" :value="false"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <InputError :message="form.errors.discount_sctr" />
                            </div>
                        </div>

                        <div class="mt-3 col-span-2 md:col-span-1">
                            <InputLabel for="state_travel_expenses">
                                ¿Tiene Refrigerio?
                            </InputLabel>
                            <div class="mt-2 class flex gap-4">
                                <label class="flex gap-2 items-center">
                                    Sí
                                    <input type="radio" v-model="form.state_travel_expenses" id="state_travel_expenses"
                                        :value="true"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <label class="flex gap-2 items-center">
                                    No
                                    <input type="radio" v-model="form.state_travel_expenses" id="state_travel_expenses"
                                        :value="false"
                                        class="block border-0 py-1.5 text-gray-900 shadow-sm ring-1 h-4 w-4 ring-inset ring-gray-500 placeholder:text-gray-400 focus:ring-0 sm:text-sm sm:leading-6" />
                                </label>
                                <InputError :message="form.errors.state_travel_expenses" />
                            </div>
                        </div>

                        <div class="sm:col-span-2 sm:col-start-1">
                            <InputLabel for="pension_type">Regimen
                                Pensionario
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="form.pension_type" id="pension_type" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Sistema de Pension</option>
                                    <option v-for="pension in pensions" :key="pension" :value="pension">
                                        {{ pension }}
                                    </option>
                                </select>
                                <InputError :message="form.errors.pension_type" />
                            </div>
                        </div>

                        <div class="sm:col-span-2" v-if="form.pension_type !== 'ONP'">
                            <InputLabel for="cuspp">
                                CUSPP
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.cuspp" id="cuspp" maxlength="12" :toUppercase="true"/>
                                <InputError :message="form.errors.cuspp" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="type_contract">
                                Tipo de Contrato
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="form.type_contract" id="type_contract" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Tipo de Contrato</option>
                                    <option>No Fiscalizado</option>
                                    <option>Administrativo</option>
                                </select>
                                <InputError :message="form.errors.type_contract" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="personal_segment">
                                Segmento de Personal
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="form.personal_segment" required id="personal_segment"
                                    autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar</option>
                                    <option>MOD</option>
                                    <option>MOI</option>
                                    <option>Administrativo</option>
                                </select>
                                <InputError :message="form.errors.personal_segment" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="basic_salary">Salario Basico
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" v-model="form.basic_salary" id="basic_salary"
                                    autocomplete="off" />
                                <InputError :message="form.errors.basic_salary" />
                            </div>
                        </div>

                        <div v-if="form.state_travel_expenses" class="sm:col-span-2">
                            <InputLabel for="amount_travel_expenses">Monto de Viaticos
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="number" v-model="form.amount_travel_expenses"
                                    id="amount_travel_expenses" autocomplete="off" />
                                <InputError :message="form.errors.amount_travel_expenses" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="hire_date">Fecha de Inicio
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.hire_date" id="hire_date" autocomplete="off" />
                                <InputError :message="form.errors.hire_date" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Educacion</h2>
                    <div>
                        <InputLabel for="curriculum_vitae">Curriculum Vitae
                        </InputLabel>
                        <InputFile type="file" v-model="form.curriculum_vitae" id="curriculum_vitae"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        <InputError :message="form.errors.curriculum_vitae" />
                    </div>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <InputLabel for="education_level">Nivel
                                Educativo
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="form.education_level" id="education_level" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Nivel Educativo</option>
                                    <option>Universidad</option>
                                    <option>Instituto</option>
                                    <option>Otros</option>
                                </select>
                                <InputError :message="form.errors.education_level" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="education_status">Estato de
                                Educacion</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.education_status" id="education_status" autocomplete="off"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Estado Educativo</option>
                                    <option>Completo</option>
                                    <option>Incompleto</option>
                                    <option>En Progreso</option>
                                </select>
                                <InputError :message="form.errors.education_status" />
                            </div>
                        </div>

                        <div class="sm:col-span-4">
                            <InputLabel for="specialization">Especializacion
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.specialization" id="specialization"
                                    autocomplete="off" />
                                <InputError :message="form.errors.specialization" />
                            </div>
                        </div>
                    </div>
                </div>


                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Domicilio</h2>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <InputLabel for="street_address">Direccion
                                Actual
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.street_address" id="street_address"
                                    autocomplete="off" />
                                <InputError :message="form.errors.street_address" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="address">Distrito
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.district" id="address" autocomplete="off" />
                                <InputError :message="form.errors.district" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="province">Provincia
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.province" id="province" autocomplete="off" />
                                <InputError :message="form.errors.province" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="department">Departamento
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.department" id="department" autocomplete="off" />
                                <InputError :message="form.errors.department" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">
                            En caso de emergencia, autorizar a la organización a comunicarse
                        </h2>
                        <button type="button" @click="addEmergency"
                            class="font-medium text-indigo-600 hover:text-indigo-500 self-start sm:self-end">Agregar
                            contactos</button>
                    </div>

                    <div v-for="(emergency, index) in form.emergencyContacts" :key="index">
                        <div class="flex justify-end mt-10">
                            <button type="button" @click="removeEmergency(index)"
                                class="font-medium text-red-600 hover:text-indigo-500">Eliminar</button>
                        </div>
                        <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-2">
                                <InputLabel for="emergency_name">Nombre
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="emergency.emergency_name" id="emergency_name" />
                                    <InputError
                                        :message="form.errors['emergencyContacts.' + index + '.emergency_name']" />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <InputLabel for="emergency_lastname">
                                    Apellido
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="emergency.emergency_lastname"
                                        id="emergency_lastname" />
                                    <InputError
                                        :message="form.errors['emergencyContacts.' + index + '.emergency_lastname']" />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <InputLabel for="emergency_relations">
                                    Relacion:
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="emergency.emergency_relations"
                                        id="emergency_relations" />
                                    <InputError
                                        :message="form.errors['emergencyContacts.' + index + '.emergency_relations']" />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <InputLabel for="emergency_phone">Telefono:
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="emergency.emergency_phone" id="emergency_phone"
                                        maxlength="9" />
                                    <InputError
                                        :message="form.errors['emergencyContacts.' + index + '.emergency_phone']" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                        <h2 class="text-base font-semibold leading-7 text-gray-900 mb-4 sm:mb-0">Información de
                            familiares
                            dependientes (hijos y/o padres)</h2>
                        <button type="button" @click="addDependent"
                            class="font-medium text-indigo-600 hover:text-indigo-500 self-start sm:self-end">Agregar
                            familiar</button>
                    </div>
                    <div v-for="(dependent, index) in form.familyDependents" :key="index">
                        <div class="flex justify-end mt-10">
                            <button type="button" @click="removeDependent(index)"
                                class="font-medium text-red-600 hover:text-indigo-500">
                                Eliminar
                            </button>
                        </div>
                        <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <InputLabel for="family_dni">
                                    Documento Nacional de Identidad
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="dependent.family_dni" id="family_dni"
                                        maxlength="8" />
                                    <InputError :message="form.errors['familyDependents.' + index + '.family_dni']" />
                                </div>
                            </div>

                            <div class="sm:col-span-2 ">
                                <InputLabel for="family_education">Nivel
                                    Educativo</InputLabel>
                                <div class="mt-2">
                                    <select v-model="dependent.family_education" id="family_education"
                                        autocomplete="off"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">Seleccionar Nivel Educativo</option>
                                        <option>Universidad</option>
                                        <option>Instituto</option>
                                        <option>Secundaria</option>
                                        <option>Primaria</option>
                                        <option>Inicial</option>
                                        <option>Otros</option>
                                    </select>
                                    <InputError
                                        :message="form.errors['familyDependents.' + index + '.family_education']" />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <InputLabel for="family_relation">Relacion
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="dependent.family_relation" id="family_relation" />
                                    <InputError
                                        :message="form.errors['familyDependents.' + index + '.family_relation']" />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <InputLabel for="family_name">Nombre
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="dependent.family_name" id="family_name" />
                                    <InputError :message="form.errors['familyDependents.' + index + '.family_name']" />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <InputLabel for="family_lastname">Apellido
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="dependent.family_lastname" id="family_lastname" />
                                    <InputError
                                        :message="form.errors['familyDependents.' + index + '.family_lastname']" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informacion de Salud</h2>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <InputLabel for="blood_group">Grupo Sanguineo
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="form.blood_group" id="blood_group"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Grupo Sanguineo</option>
                                    <option value="A+">A+</option>
                                    <option value="A-">A-</option>
                                    <option value="B+">B+</option>
                                    <option value="B-">B-</option>
                                    <option value="AB+">AB+</option>
                                    <option value="AB-">AB-</option>
                                    <option value="O+">O+</option>
                                    <option value="O-">O-</option>
                                    <option value="O-">RH+</option>
                                </select>
                                <InputError :message="form.errors.blood_group" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="weight">Peso (kg)</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.weight" id="weight" autocomplete="off" />
                                <InputError :message="form.errors.weight" />
                            </div>
                        </div>

                        <div class="sm:col-span-2 sm:col-start-1">
                            <InputLabel for="height">Altura (cm)
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.height" id="height" autocomplete="off" />
                                <InputError :message="form.errors.height" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="shoe_size">Talla de Zapato
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.shoe_size" id="shoe_size" autocomplete="off" />
                                <InputError :message="form.errors.shoe_size" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="shirt_size">Talla de Polo
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.shirt_size" id="shirt_size" autocomplete="off" />
                                <InputError :message="form.errors.shirt_size" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="pants_size">Talla de Pantalones
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.pants_size" id="pants_size" autocomplete="off" />
                                <InputError :message="form.errors.pants_size" />
                            </div>
                        </div>

                        <div class="col-span-full">
                            <InputLabel for="medical_condition">¿Tiene
                                alguna
                                condición médica? Si es así, por favor especifique</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.medical_condition" id="medical_condition" required
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.medical_condition" />
                            </div>
                        </div>

                        <div class="col-span-full">
                            <InputLabel for="allergies">¿Eres alérgico a
                                algún
                                medicamento? Si es así, por favor especifique</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.allergies" id="allergies" required
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.allergies" />
                            </div>
                        </div>

                        <div class="col-span-full">
                            <InputLabel for="operations">¿Ha tenido alguna
                                operación? Si es así, por favor especifique</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.operations" id="operations" required
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.operations" />
                            </div>
                        </div>

                        <div class="col-span-full">
                            <InputLabel for="accidents">¿Ha sufrido algún
                                accidente grave? Si es así, por favor especifique</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.accidents" id="accidents" required
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.accidents" />
                            </div>
                        </div>

                        <div class="col-span-full">
                            <InputLabel for="vaccinations">¿Has recibido
                                alguna
                                vacuna? Si es así, por favor especifique</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.vaccinations" id="vaccinations" required
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.vaccinations" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <PrimaryButton type="submit" :class="{ 'opacity-25': form.processing }">
                    {{ employees ? "Actualizar" : "Crear" }}
                </PrimaryButton>
            </div>
        </form>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="empleado" />

        <Modal :show="showDocumentsModal" :max-width="'6xl'">
            <div class="p-6 flex flex-col gap-6">
                <h2>Documentos</h2>
                <div>
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <div v-for="section in sections" :key="section.id"
                            class="bg-white p-4 rounded-sm shadow-sm border border-gray-300 relative">
                            <!-- Encabezado de la sección -->
                            <div class="flex items-center justify-between mb-2">
                                <label class="flex items-center justify-between w-full cursor-pointer">
                                    <span class="text-sm font-semibold text-gray-800 break-words">
                                        {{ section.name }}
                                    </span>
                                </label>
                            </div>

                            <div class="border-t border-gray-200 my-2"></div>

                            <!-- Subdivisiones -->
                            <div class="space-y-2">
                                <div v-for="sub in section.subdivisions" :key="sub.id"
                                    class="flex items-center justify-between">
                                    <div class="grid grid-cols-2 w-full items-center">
                                        <div>
                                            <label class="flex items-center justify-between w-full cursor-pointer">
                                                <span class="text-sm break-words font-medium">
                                                    {{ sub.name }}
                                                </span>
                                            </label>
                                        </div>
                                        <div>
                                            <select v-model="documentsForm[sub.id]" :class="[
                                                'block w-full rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset sm:text-sm sm:leading-6',
                                                documentsForm[sub.id] === 'No Corresponde' ? 'text-red-600 focus:ring-red-600' : 'text-indigo-700 focus:ring-indigo-600'
                                            ]">
                                                <option>Corresponde</option>
                                                <option>No Corresponde</option>
                                            </select>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-6 flex items-center justify-end gap-x-3">
                    <SecondaryButton type="button" @click="closeDocumentsModal"> Cerrar </SecondaryButton>
                    <PrimaryButton type="button" @click="submitDocuments">
                        Aceptar
                    </PrimaryButton>
                </div>
            </div>
        </Modal>


    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import Modal from '@/Components/Modal.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import ModalImage from '@/Layouts/ModalImage.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputFile from '@/Components/InputFile.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { toFormData } from '@/utils/utils';
import { notify } from '@/Components/Notification';
import { Toaster } from 'vue-sonner';
import { useAxiosErrorHandler } from '@/utils/axiosError';

const showModal = ref(false);
const props = defineProps({
    pensions: Object,
    employees: {
        type: Object,
        requerid: false
    },
    costLines: Array,
    sections: {
        type: Array,
        required: false
    }
})

const form = useForm({
    curriculum_vitae: null,
    cropped_image: null,
    name: '',
    lastname: '',
    gender: '',
    state_civil: '',
    birthdate: '',
    dni: '',
    email: '',
    email_company: '',
    phone1: '',
    nro_cuenta: '',
    cost_line_id: '',
    personal_segment: '',
    type_contract: '',
    cuspp: '',
    state_travel_expenses: true,
    discount_remuneration: '',
    discount_sctr: '',
    pension_type: '',
    basic_salary: '',
    amount_travel_expenses: '',
    life_ley: '',
    hire_date: '',
    education_level: '',
    education_status: '',
    specialization: '',
    street_address: '',
    department: '',
    province: '',
    district: '',
    emergencyContacts: [],
    familyDependents: [],
    blood_group: '',
    weight: '',
    height: '',
    shoe_size: '',
    shirt_size: '',
    pants_size: '',
    medical_condition: '',
    allergies: '',
    operations: '',
    accidents: '',
    vaccinations: '',
})


if (props.employees) {
    form.curriculum_vitae = null;
    form.cropped_image = null;
    form.name = props.employees.name;
    form.lastname = props.employees.lastname;
    form.gender = props.employees.gender;
    form.state_civil = props.employees.state_civil;
    form.birthdate = props.employees.birthdate;
    form.dni = props.employees.dni;
    form.email = props.employees.email;
    form.email_company = props.employees.email_company;
    form.phone1 = props.employees.phone1;
    form.cost_line_id = props.employees.contract.cost_line_id;
    form.type_contract = props.employees.contract.type_contract;
    form.cuspp = props.employees.contract.cuspp;
    form.state_travel_expenses = props.employees.contract.state_travel_expenses == 1 ? true : false;
    form.discount_remuneration = props.employees.contract.discount_remuneration == 1 ? true : false;
    form.discount_sctr = props.employees.contract.discount_sctr == 1 ? true : false;
    form.pension_type = props.employees.contract.pension_type;
    form.basic_salary = props.employees.contract.basic_salary;
    form.nro_cuenta = props.employees.contract.nro_cuenta;
    form.amount_travel_expenses = props.employees.contract.amount_travel_expenses;
    form.life_ley = props.employees.contract.life_ley == 1 ? true : false;
    form.hire_date = props.employees.contract.hire_date;
    form.education_level = props.employees.education.education_level;
    form.education_status = props.employees.education.education_status;
    form.specialization = props.employees.education.specialization;
    form.street_address = props.employees.address.street_address;
    form.department = props.employees.address.department;
    form.province = props.employees.address.province;
    form.district = props.employees.address.district;
    form.emergencyContacts = props.employees.emergency;
    form.familyDependents = props.employees.family;
    form.blood_group = props.employees.health.blood_group;
    form.weight = props.employees.health.weight;
    form.height = props.employees.health.height;
    form.shoe_size = props.employees.health.shoe_size;
    form.shirt_size = props.employees.health.shirt_size;
    form.pants_size = props.employees.health.pants_size;
    form.medical_condition = props.employees.health.medical_condition;
    form.allergies = props.employees.health.allergies;
    form.operations = props.employees.health.operations;
    form.accidents = props.employees.health.accidents;
    form.vaccinations = props.employees.health.vaccinations;
    form.personal_segment = props.employees.contract.personal_segment
}



watch(() => form.state_travel_expenses, (newVal) => {
    form.amount_travel_expenses = ''
})

const addDependent = () => {
    form.familyDependents.push({
        family_dni: '',
        family_name: '',
        family_lastname: '',
        family_relation: '',
        family_education: ''
    });
}

const addEmergency = () => {
    form.emergencyContacts.push({
        emergency_name: '',
        emergency_lastname: '',
        emergency_relations: '',
        emergency_phone: ''
    });
}

const removeDependent = (index) => {
    form.familyDependents.splice(index, 1);
}

const removeEmergency = (index) => {
    form.emergencyContacts.splice(index, 1);
}

const handleImagenRecortada = (imagenRecorted) => {
    form.cropped_image = imagenRecorted;
};

const createdEmployee = ref(null)
const submit = async () => {
    if (props.employees) {
        let url = route('management.employees.update', props.employees.id)
        form.post(url, {
            onError: (e) => {
                console.log(e)
            }
        })
    } else {
        try {
            const payload = form.data()
            const res = await axios.post(route('management.employees.store'), payload)
            const id = res.data.employee_id
            showModal.value = true
            setTimeout(() => {
                showModal.value = false;
                openDocumentsModal(id)
            }, 2000);
        } catch (error) {
            useAxiosErrorHandler(error, form)
        }
    }
}



const showDocumentsModal = ref(false)
const documentsForm = ref({})
function openDocumentsModal(id) {
    props.sections.forEach(item => {
        item.subdivisions.forEach(subitem => {
            documentsForm.value[subitem.id] = 'Corresponde'
        })
    });
    createdEmployee.value = id
    showDocumentsModal.value = true
}


function closeDocumentsModal() {
    showDocumentsModal.value = false
    router.visit(route('management.employees'))

}

async function submitDocuments() {
    const res = await axios.post(route('management.employees.document.register.masive', { employee_id: createdEmployee.value }), documentsForm.value)
    if (res.status === 200) {
        notify('Estados de documentos registrados')
        setTimeout(() => {
            router.visit(route('management.employees'))
        }, 2000)
    }
}


</script>
