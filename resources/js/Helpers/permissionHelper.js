export function checkPermissions(el, binding, mode) {
    const { functionalities: permissions, role_id } = window?.appAuth || {};
    if (role_id === 1 && mode !== 'not') return
    const requiredPermissions = Array.isArray(binding.value) ? binding.value : [binding.value];

    let hasPermission = false;
    if (mode === "and") {
        hasPermission = requiredPermissions.every(p => permissions.includes(p));
    } else if (mode === "or") {
        hasPermission = requiredPermissions.some(p => permissions.includes(p));
    } else if (mode === "not") {
        if(role_id === 1) el.parentNode?.removeChild(el);
        let hasPer = requiredPermissions.every(p => permissions.includes(p));
        hasPer = !hasPer
        if(hasPer) return
        else el.parentNode?.removeChild(el);
    } else {
        hasPermission = permissions.includes(binding.value);
    }
    if (!hasPermission) {
        el.parentNode?.removeChild(el);
    }
}
