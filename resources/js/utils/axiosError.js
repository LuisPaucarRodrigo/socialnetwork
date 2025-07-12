
import { notifyError } from "@/Components/Notification";
import { setAxiosErrors } from "./utils";

export function useAxiosErrorHandler(error, form = null) {
    console.log(error)
    if (error.response) {
        if (error.response.data.errors && form) {
            setAxiosErrors(error.response.data.errors, form);
        } else {
            notifyError("Server error:", error.response.data?.message || "Unknown server error");
        }
    } else {
        notifyError("Network or other error:", error.message || "Unknown network error");
    }
}
