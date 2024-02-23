<template>
    <Head title="Information Additional" />
    <AuthenticatedLayout :redirectRoute="'management.employees'"> 
        <template #header>
            Perfil
        </template>
        <form @submit.prevent="submit">
            <div class="space-y-12">
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informacion Personal</h2>
                    <div>
                        <ModalImage v-model="form.cropped_image" @imagenRecortada="handleImagenRecortada" />
                        <InputError :message="form.errors.cropped_image" />
                    </div>
                    <div class="grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <InputLabel for="first-name" class="font-medium leading-6 text-gray-900">Nombre</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.name" id="first-name" autocomplete="given-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.name" />
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <InputLabel for="last_name" class="font-medium leading-6 text-gray-900">Apellido</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.lastname" id="last_name" autocomplete="family-name"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.lastname" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="gender" class="font-medium leading-6 text-gray-900">Genero</InputLabel>
                            <div class="mt-2">
                                <select id="gender" v-model="form.gender" autocomplete="gender"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Genero</option>
                                    <option>Masculino</option>
                                    <option>Femenino</option>
                                </select>
                                <InputError :message="form.errors.gender" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="state_civil" class="font-medium leading-6 text-gray-900">Estado Civil
                            </InputLabel>
                            <div class="mt-2">
                                <select id="state_civil" v-model="form.state_civil" autocomplete="country-name"
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
                            <InputLabel for="birthdate" class="font-medium leading-6 text-gray-900">Fecha de Nacimiento
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.birthdate" id="birthdate"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.birthdate" />
                            </div>
                        </div>

                        <div class="sm:col-span-2 sm:col-start-1">
                            <InputLabel for="dni" class="font-medium leading-6 text-gray-900">Documento Nacional de
                                Identidad</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.dni" id="dni" maxlength="8"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.dni" />
                            </div>
                        </div>

                        <div class="sm:col-span-4">
                            <InputLabel for="email" class="font-medium leading-6 text-gray-900">Correo Electronico
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="email" id="email" v-model="form.email" autocomplete="email"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.email" />
                            </div>
                        </div>

                        <div class="sm:col-span-4">
                            <InputLabel for="email_company" class="font-medium leading-6 text-gray-900">Correo Electronico
                                de Empresa
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="email" id="email_company" v-model="form.email_company"
                                    autocomplete="email_company"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.email_company" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="phone1" class="font-medium leading-6 text-gray-900">Telefono 1</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.phone1" id="phone1" autocomplete="postal-code"
                                    maxlength="9"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.phone1" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="phone2" class="font-medium leading-6 text-gray-900">Telefono 2</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.phone2" id="phone2" autocomplete="phone2" maxlength="9"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.phone2" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informacion de Contrato</h2>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-2 sm:col-start-1">
                            <InputLabel for="pension_system" class="font-medium leading-6 text-gray-900">Regimen Pensionario
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="form.pension_system" id="pension_system" autocomplete="pension-regime"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Sistema de Pension</option>
                                    <option v-for="pension in pensions" :key="pension" :value="pension.id">{{ pension.type
                                    }}</option>
                                </select>
                                <InputError :message="form.errors.pension_system" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="basic_salary" class="font-medium leading-6 text-gray-900">Salario Basico
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.basic_salary" id="basic_salary"
                                    autocomplete="basic-salary"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.basic_salary" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="hire_date" class="font-medium leading-6 text-gray-900">Fecha de Inicio
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="Date" v-model="form.hire_date" id="hire_date" autocomplete="hire-date"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.hire_date" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Educacion</h2>
                    <div>
                        <InputFile type="file" v-model="form.curriculum_vitae" id="curriculum_vitae"
                            class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                        <InputError :message="form.errors.curriculum_vitae" />
                    </div>
                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <InputLabel for="education_level" class="font-medium leading-6 text-gray-900">Nivel Educativo
                            </InputLabel>
                            <div class="mt-2">
                                <select v-model="form.education_level" id="education_level" autocomplete="education-level"
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
                            <InputLabel for="education_status" class="font-medium leading-6 text-gray-900">Estato de
                                Educacion</InputLabel>
                            <div class="mt-2">
                                <select v-model="form.education_status" id="education_status" autocomplete="education-level"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                    <option disabled value="">Seleccionar Estado Educativo</option>
                                    <option>Culminado</option>
                                    <option>En Progreso</option>
                                </select>
                                <InputError :message="form.errors.education_status" />
                            </div>
                        </div>

                        <div class="sm:col-span-4">
                            <InputLabel for="specialization" class="font-medium leading-6 text-gray-900">Especializacion
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.specialization" id="specialization"
                                    autocomplete="specialization"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.specialization" />
                            </div>
                        </div>
                    </div>
                </div>


                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Domicilio</h2>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <InputLabel for="street_address" class="font-medium leading-6 text-gray-900">Direccion Actual
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.street_address" id="street_address"
                                    autocomplete="street-address"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.street_address" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="address" class="font-medium leading-6 text-gray-900">Distrito
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.district" id="address" autocomplete="postal-code"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.district" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="province" class="font-medium leading-6 text-gray-900">Provincia</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.province" id="province" autocomplete="address-level1"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.province" />
                            </div>
                        </div>
                        <div class="sm:col-span-2">
                            <InputLabel for="department" class="font-medium leading-6 text-gray-900">Departamento
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.department" id="department"
                                    autocomplete="address-level2"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.department" />
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                        <h2 class="text-base font-semibold leading-7 text-gray-900">En caso de emergencia, autorizar a la
                            organización a comunicarse</h2>
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
                                <InputLabel for="emergency_name" class="font-medium leading-6 text-gray-900">Nombre
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="emergency.emergency_name" id="emergency_name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors['emergencyContacts.' + index + '.emergency_name']" />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <InputLabel for="emergency_lastname" class="font-medium leading-6 text-gray-900">Apellido
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="emergency.emergency_lastname" id="emergency_lastname"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError
                                        :message="form.errors['emergencyContacts.' + index + '.emergency_lastname']" />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <InputLabel for="emergency_relations" class="font-medium leading-6 text-gray-900">Relacion:
                                </InputLabel>
                                <div class="mt-2">
                                    <input type="text" v-model="emergency.emergency_relations" id="emergency_relations"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError
                                        :message="form.errors['emergencyContacts.' + index + '.emergency_relations']" />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <InputLabel for="emergency_phone" class="font-medium leading-6 text-gray-900">Telefono:
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="emergency.emergency_phone" id="emergency_phone"
                                        maxlength="9"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors['emergencyContacts.' + index + '.emergency_phone']" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between">
                        <h2 class="text-base font-semibold leading-7 text-gray-900 mb-4 sm:mb-0">Información de familiares
                            dependientes (hijos y/o padres)</h2>
                        <button type="button" @click="addDependent"
                            class="font-medium text-indigo-600 hover:text-indigo-500 self-start sm:self-end">Agregar
                            familiar</button>
                    </div>
                    <div v-for="(dependent, index) in form.familyDependents" :key="index">
                        <div class="flex justify-end mt-10">
                            <button type="button" @click="removeDependent(index)"
                                class="font-medium text-red-600 hover:text-indigo-500">Eliminar</button>
                        </div>
                        <div class="mt-3 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <InputLabel for="family_dni" class="font-medium leading-6 text-gray-900">Documento Nacional
                                    de Identidad</InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="dependent.family_dni" id="family_dni" maxlength="8"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors['familyDependents.' + index + '.family_dni']" />
                                </div>
                            </div>

                            <div class="sm:col-span-2 ">
                                <InputLabel for="family_education" class="font-medium leading-6 text-gray-900">Nivel
                                    Educativo</InputLabel>
                                <div class="mt-2">
                                    <select v-model="dependent.family_education" id="family_education"
                                        autocomplete="education-level"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                                        <option disabled value="">Seleccionar Nivel Educativo</option>
                                        <option>Universidad</option>
                                        <option>Instituto</option>
                                        <option>Otros</option>
                                    </select>
                                    <InputError :message="form.errors['familyDependents.' + index + '.family_education']" />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <InputLabel for="family_relation" class="font-medium leading-6 text-gray-900">Relacion
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="dependent.family_relation" id="family_relation"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors['familyDependents.' + index + '.family_relation']" />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <InputLabel for="family_name" class="font-medium leading-6 text-gray-900">Nombre
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="dependent.family_name" id="family_name"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors['familyDependents.' + index + '.family_name']" />
                                </div>
                            </div>

                            <div class="sm:col-span-2">
                                <InputLabel for="family_lastname" class="font-medium leading-6 text-gray-900">Apellido
                                </InputLabel>
                                <div class="mt-2">
                                    <TextInput type="text" v-model="dependent.family_lastname" id="family_lastname"
                                        class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                    <InputError :message="form.errors['familyDependents.' + index + '.family_lastname']" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="border-b border-gray-900/10 pb-12">
                    <h2 class="text-base font-semibold leading-7 text-gray-900">Informacion de Salud</h2>

                    <div class="mt-10 grid grid-cols-1 gap-x-6 gap-y-8 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <InputLabel for="blood_group" class="font-medium leading-6 text-gray-900">Grupo Sanguineo
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
                                </select>
                                <InputError :message="form.errors.blood_group" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="weight" class="font-medium leading-6 text-gray-900">Peso (kg)</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.weight" id="weight" autocomplete="weight"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.weight" />
                            </div>
                        </div>

                        <div class="sm:col-span-2 sm:col-start-1">
                            <InputLabel for="height" class="font-medium leading-6 text-gray-900">Altura (cm)</InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.height" id="height" autocomplete="height"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.height" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="shoe_size" class="font-medium leading-6 text-gray-900">Talla de Zapato
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.shoe_size" id="shoe_size" autocomplete="shoe-size"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.shoe_size" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="shirt_size" class="font-medium leading-6 text-gray-900">Talla de Polo
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.shirt_size" id="shirt_size" autocomplete="shirt-size"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.shirt_size" />
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <InputLabel for="pants_size" class="font-medium leading-6 text-gray-900">Talla de Pantalones
                            </InputLabel>
                            <div class="mt-2">
                                <TextInput type="text" v-model="form.pants_size" id="pants_size" autocomplete="pants-size"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" />
                                <InputError :message="form.errors.pants_size" />
                            </div>
                        </div>

                        <div class="col-span-full">
                            <InputLabel for="medical_condition" class="font-medium leading-6 text-gray-900">¿Tiene alguna
                                condición médica? Si es así, por favor especifique</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.medical_condition" id="medical_condition"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                <InputError :message="form.errors.medical_condition" />
                            </div>
                        </div>

                        <div class="col-span-full">
                            <InputLabel for="allergies" class="font-medium leading-6 text-gray-900">¿Eres alérgico a algún
                                medicamento? Si es así, por favor especifique</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.allergies" id="allergies"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                <InputError :message="form.errors.allergies" />
                            </div>
                        </div>

                        <div class="col-span-full">
                            <InputLabel for="operations" class="font-medium leading-6 text-gray-900">¿Ha tenido alguna
                                operación? Si es así, por favor especifique</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.operations" id="operations"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                <InputError :message="form.errors.operations" />
                            </div>
                        </div>

                        <div class="col-span-full">
                            <InputLabel for="accidents" class="font-medium leading-6 text-gray-900">¿Ha sufrido algún
                                accidente grave? Si es así, por favor especifique</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.accidents" id="accidents"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                <InputError :message="form.errors.accidents" />
                            </div>
                        </div>

                        <div class="col-span-full">
                            <InputLabel for="vaccinations" class="font-medium leading-6 text-gray-900">¿Has recibido alguna
                                vacuna? Si es así, por favor especifique</InputLabel>
                            <div class="mt-2">
                                <textarea v-model="form.vaccinations" id="vaccinations"
                                    class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6"></textarea>
                                <InputError :message="form.errors.vaccinations" />
                            </div>
                        </div>
                    </div>
                </div>


            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <button type="submit" :class="{ 'opacity-25': form.processing }"
                    class="rounded-md bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    {{employees ? "Actualizar":"Crear"}}
                </button>
            </div>
        </form>
        <ConfirmCreateModal :confirmingcreation="showModal" itemType="empleado" />
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfirmCreateModal from '@/Components/ConfirmCreateModal.vue';
import ModalImage from '@/Layouts/ModalImage.vue';
import InputLabel from '@/Components/InputLabel.vue';
import TextInput from '@/Components/TextInput.vue';
import InputFile from '@/Components/InputFile.vue';
import InputError from '@/Components/InputError.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

const showModal = ref(false);

const props = defineProps({
    pensions: Object,
    employees: {
        type: Object,
        requerid: false
    },
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
    phone2: '',
    pension_system: '',
    basic_salary: '',
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
    form.phone2 = props.employees.phone2;
    form.pension_system = props.employees.contract.pension.id;
    form.basic_salary = props.employees.contract.basic_salary;
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
}

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

const handleImagenRecortada = (imagenRecortada) => {
    form.cropped_image = imagenRecortada;
};

const submit = () => {
    if (props.employees) {
        form.post(route('management.employees.update', props.employees.id), form)
    } else {
        form.post(route('management.employees.information.create'), {
            onSuccess: () => {
                showModal.value = true
                setTimeout(() => {
                    showModal.value = false;
                    router.visit(route('management.employees'))
                }, 2000);
            },
            onError: () => {
                alert('Ha ocurrido un error. Por favor, inténtelo de nuevo.');
            }
        })
    }
}

</script>
