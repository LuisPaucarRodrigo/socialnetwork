export function checkPermissions(el, binding, mode) {
    const { permissions, role_id } = window?.appAuth || {};
    if (role_id === 1) return;

    const requiredPermissions = Array.isArray(binding.value) ? binding.value : [binding.value];
    let hasPermission = false;
    if (mode === "and") {
        hasPermission = requiredPermissions.every(permission => permissions.includes(permission));
    } else if (mode === "or") {
        hasPermission = requiredPermissions.some(permission => permissions.includes(permission));
    } else {
        hasPermission = permissions.includes(binding.value);
    }

    if (!hasPermission) {
        el.parentNode?.removeChild(el);
    }
}
