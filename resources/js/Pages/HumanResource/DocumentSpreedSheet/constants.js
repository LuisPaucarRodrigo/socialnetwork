export const principalData = [
    { title: 'Estado', propName: 'contract?.state', propClass: 'text-center' },
    { title: 'Personal', propName: ['name', 'lastname'], propClass: 'text-left sticky left-0 z-10 bg-yellow-100', 
        propClassExternal: 'text-left sticky left-0 z-10 bg-indigo-100' ,titleClass: 'sticky left-0 z-10' },
]

export const personalData = [
    { title: 'DNI', propName: 'dni', propClass: 'text-center' },
    { title: 'Fecha de Ingreso', propName: 'contract?.hire_date', propClass: 'text-center', propType: 'date' },
    { title: 'Número de Celular', propName: 'phone1', propClass: 'text-center' },
    { title: 'Correo Personal', propName: 'email', propClass: 'text-center' },
    { title: 'Correo Empresa', propName: 'email_company', propClass: 'text-center' },
]

import { formattedDate } from "@/utils/utils";

export function getProp({ obj, path = null, sep=' ', type = null }) {
    if (!path) {
        return obj;
    }
    if (Array.isArray(path)) {
        return path
            .map(singlePath => accessNested(obj, singlePath))
            .filter(value => value != null)
            .join(sep);
    }
    if (type === 'date') {
        return formattedDate(accessNested(obj, path));
    }
    return accessNested(obj, path);
}

function accessNested(obj, path) {
    return path.split('.').reduce((acc, part) =>
        acc?.[part.replace('?', '')], obj);
}