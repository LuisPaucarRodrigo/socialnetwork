import { watch } from 'vue';

export function useLazyRefInvoker(refVar, showFlag) {
    function invokeWhenReady(methodName, ...args) {
        showFlag.value = true;

        if (refVar.value) {
            refVar.value[methodName](...args);
            return;
        }

        const stop = watch(
            () => refVar.value,
            (instance) => {
                if (instance) {
                    instance[methodName](...args);
                    stop();
                }
            }
        );
    }

    return { invokeWhenReady };
}
