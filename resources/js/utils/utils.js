export function formattedDate(fecha) {
  if (fecha === undefined || fecha === null) {
    return ''
  }
  const tieneHora = fecha.includes(':');
  const fechaObjeto = tieneHora ? new Date(fecha) : new Date(fecha + 'T00:00:00Z');
  if (isNaN(fechaObjeto.valueOf())) {
    return '???????';
  }
  if (tieneHora) {
    fechaObjeto.setUTCHours(fechaObjeto.getUTCHours() - 5);
  }
  const dia = fechaObjeto.getUTCDate();
  const mes = fechaObjeto.getUTCMonth() + 1;
  const año = fechaObjeto.getUTCFullYear();
  const horas = fechaObjeto.getUTCHours();
  const minutos = fechaObjeto.getUTCMinutes();
  const segundos = fechaObjeto.getUTCSeconds();
  let fechaFormateada = `${dia.toString().padStart(2, '0')}/${mes.toString().padStart(2, '0')}/${año}`;
  if (tieneHora) {
    fechaFormateada += ` ${horas.toString().padStart(2, '0')}:${minutos.toString().padStart(2, '0')}:${segundos.toString().padStart(2, '0')}`;
  }
  return fechaFormateada;
}

export function formattedMonth(fecha) {
  if (fecha === undefined || fecha === null) {
    return ''
  }
  const [year, month] = fecha.split('-'); // month = '05'
  const date = new Date(Number(year), Number(month) - 1); // mes empieza en 0

  const nombreMes = date.toLocaleDateString('es-ES', { month: 'long' });
  return `${year} ${nombreMes}`; // → 2025 mayo
}

export function realNumeration(perPage, currentPage, index) {
  return perPage * (currentPage - 1) + (index + 1)
}


export function setAxiosErrors(errors, form) {
  form.clearErrors()
  let formErrors = Object.fromEntries(
    Object.entries(errors).map(([key, val]) => [key, val.join('\n')])
  );
  form.setError(formErrors)
}

export function toFormData(obj, form = null, namespace = '') {
  const formData = form || new FormData();
  for (const [key, value] of Object.entries(obj)) {
    const formKey = namespace ? `${namespace}[${key}]` : key;
    if (
      value instanceof Date ||
      typeof value === 'string' ||
      typeof value === 'number' ||
      typeof value === 'boolean' ||
      value instanceof File
    ) {
      formData.append(formKey, value);
    } else if (value === null || value === undefined) {
      formData.append(formKey, '');
    } else if (typeof value === 'object' && !(value instanceof File)) {
      // Recurse into nested object
      toFormData(value, formData, formKey);
    }
  }

  return formData;
}
