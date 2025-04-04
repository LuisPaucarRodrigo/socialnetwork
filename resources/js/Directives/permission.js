import { checkPermissions } from "@/Helpers/permissionHelper";

export default {
    single: {
        mounted(el, binding) {
            checkPermissions(el, binding, "SINGLE");
        },
    },
    and: {
        mounted(el, binding) {
            checkPermissions(el, binding, "AND");
        },
    },
    or: {
        mounted(el, binding) {
            checkPermissions(el, binding, "OR");
        },
    },
};
