export default {
    mounted(el, binding) {
        const { permissions, role_id } = window?.appAuth || {};
        if (role_id === 1) return;
        const requiredPermissions = Array.isArray(binding.value) 
            ? binding.value 
            : [binding.value];
        const hasPermission = requiredPermissions.some(permission => 
            permissions.includes(permission));
        if (!hasPermission) {
            el.parentNode?.removeChild(el);
        }
    },
};
