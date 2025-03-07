export function modulePermission(moduleName, userModules){
    if(!moduleName) return false
    return userModules.includes(moduleName)
}
export function subModulePermission(subModuleName, userSubModules){
    if(!subModuleName) return false
    return userSubModules.includes(subModuleName)
}