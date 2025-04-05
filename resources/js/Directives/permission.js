import { checkPermissions } from "@/Helpers/permissionHelper";

export default {
    single: {
        mounted(el, binding) {
            checkPermissions(el, binding, "single");
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
};
