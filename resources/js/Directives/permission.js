import { checkPermissions } from "@/Helpers/permissionHelper";

export default {
    single: {
        mounted(el, binding) {
            checkPermissions(el, binding, "");
        },
    },
    and: {
        mounted(el, binding) {
            checkPermissions(el, binding, "and");
        },
    },
    or: {
        mounted(el, binding) {
            checkPermissions(el, binding, "or");
        },
    },
    not: {
        mounted(el, binding) {
            checkPermissions(el, binding, "not"); 
        },
    },
};
