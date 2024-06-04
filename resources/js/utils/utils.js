export function formattedDate(fecha) {
  if (fecha === undefined || fecha === null){
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