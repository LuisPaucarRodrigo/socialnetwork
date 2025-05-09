
import { notifyError } from "@/Components/Notification";
import { setAxiosErrors } from "./utils";

export function useAxiosErrorHandler() {
    const handleAxiosError = (error, form = null) => {
        if (error.response) {
            if (error.response.data.errors && form) {
                setAxiosErrors(error.response.data.errors, form);
            } else {
                notifyError("Server error:", error.response.data);
            }
        } else {
            notifyError("Network or other error:", error.message || error);
        }
    };

    return {
        handleAxiosError,
    };
}
