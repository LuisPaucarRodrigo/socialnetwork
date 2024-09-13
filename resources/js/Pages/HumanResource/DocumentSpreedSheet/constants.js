export const principalData = [
    { title: 'Estado', propName: 'contract?.state', propAlign: 'text-center' },
    { title: 'Personal', propName: ['lastname', 'name'], propAlign: 'text-left' },
]

export const personalData = [
    { title: 'DNI', propName: 'dni', propAlign: 'text-center' },
    { title: 'Fecha de Ingreso', propName: 'contract?.hire_date', propAlign: 'text-center', propType: 'date' },
    { title: 'Número de Celular', propName: 'phone1', propAlign: 'text-center' },
    { title: 'Correo Personal', propName: 'email', propAlign: 'text-center' },
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