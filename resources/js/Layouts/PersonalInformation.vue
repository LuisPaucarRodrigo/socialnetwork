<template>
    <div class="px-4 sm:px-0">
        <h3 class="text-base font-semibold leading-7 text-gray-900">Informacion Personal</h3>
    </div>
    <div class="mt-6 border-t border-gray-100">
        <div class="text-lg font-semibold mb-4">Personal</div>
        <div class="flex justify-center mt-6">
            <img :src="image_url(details.cropped_image)" alt="Imagen Personal" class="rounded-full h-45 w-45 py-5">
        </div>
        <div class="sm:flex lg:justify-between lg:gap-8">
            <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Nombre Completo</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.name }} {{
                        details.lastname }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Genero</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.gender }}</dd>
                </div>
            </div>
            <div class="sm:w-1/2 sm:pl-4">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">DNI</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.dni }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Estado Civil</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.state_civil }}</dd>
                </div>
            </div>
        </div>
        <div class="sm:flex lg:justify-between lg:gap-8">

            <div class="sm:w-1/2 lg:pr-4 sm:mb-0">
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Fecha de Nacimiento</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ formattedDate(details.birthdate) }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Email</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.email }}</dd>
                </div>
            </div>

            <div class="sm:w-1/2 sm:pl-4">
                <div v-if="details.email_company" class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Email de Empresa</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.email_company }}</dd>
                </div>
                <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
                    <dt class="text-sm font-medium leading-6 text-gray-900">Telefono</dt>
                    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">{{ details.phone1 }}</dd>
                </div>
            </div>
        </div>
    </div>
</template>
  
<script>

import {formattedDate} from '@/utils/utils.js';

export default {
    props: {
        details: {
            type: Object,
            required: true
        }
    },
    methods: {
        image_url(croppedImage) {
            return croppedImage ? `/image/profile/${croppedImage}` : `/image/projectimage/DefaultUser.png`;
        },
        formattedDate(fecha) {
            return formattedDate(fecha);
        }
    }
}

</script>
  