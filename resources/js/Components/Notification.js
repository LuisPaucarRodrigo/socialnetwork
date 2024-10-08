import { toast } from "vue-sonner";

export function notify (message) {
    if (message !== null){
        toast.success("Éxito", {
            description: message,
            duration: 2200,
            position:"top-center",
            closeButton: true,
        })
    }
}
export function notifyWarning (message) {
    if (message !== null){
        toast.warning("Info", {
            description: message,
            duration: 2200,
            position: "top-center",
            closeButton: true
        });
    }
}

export function notifyError (message) {
    if (message !== null){
        toast.error("Error", {
            description: message,
            duration: 2200,
            position: "top-center",
            closeButton: true,
        });
    }
}