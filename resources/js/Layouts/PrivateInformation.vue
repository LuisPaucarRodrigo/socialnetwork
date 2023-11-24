<!-- ComponenteA.vue -->
<template>
    <div class="px-4 sm:px-0">
        <h3 class="text-base font-semibold leading-7 text-gray-900">Informacion Privada</h3>
    </div>
    <div class="mt-6 border-t border-gray-100">
        <div class="text-lg font-semibold mb-4">Domicilio</div>
        <div class="sm:flex lg:justify-between lg:gap-8 mb-8">
            <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Departamento</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.address.department }}
                    </dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Provincial</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.address.province }}
                    </dd>
                </div>
            </div>
            <div class="sm:w-1/2 sm:pl-4">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Codigo Postal</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.address.postal_code }}
                    </dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Direccion</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.address.street_address
                    }}</dd>
                </div>
            </div>
        </div>
        <div class="text-lg font-semibold mb-4">Dependientes</div>
        <div class="sm:flex lg:justify-between lg:gap-8 mb-8">
            <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Nombre Completo</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.family.family_name }}
                        {{ details.family.family_lastname }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Relacion</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.family.family_relation
                    }}</dd>
                </div>
            </div>

            <div class="sm:w-1/2 sm:pl-4">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Nivel Educativo</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.family.family_education
                    }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">DNI</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.family.family_dni }}
                    </dd>
                </div>
            </div>
        </div>
        <div class="text-lg font-semibold mb-4">Emergencia</div>
        <div class="sm:flex lg:justify-between lg:gap-8">
            <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Nombre Completo</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{
                        details.emergency.emergency_name }} {{ details.emergency.emergency_lastname }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Relacion</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{
                        details.emergency.emergency_relations }}</dd>
                </div>
            </div>

            <div class="sm:w-1/2 sm:pl-4">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Telefono</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{
                        details.emergency.emergency_phone }}</dd>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>

export default {
    props: {
        details: {
            type: Object,
            required: true
        }
    }
}

</script>
  