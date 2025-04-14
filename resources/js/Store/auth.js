// src/Stores/auth.js
import { reactive } from 'vue';

export const appAuth = reactive({
    role_id: null,
    permissions: []
});

export function resetAppAuth() {
    if(appAuth.role_id) appAuth.role_id = null;
    if(appAuth.permissions.length) appAuth.permissions = [];
}