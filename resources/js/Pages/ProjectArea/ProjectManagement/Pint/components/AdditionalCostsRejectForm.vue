<template>
    <div>
        <div class="flex w-full gap-1">
            <textarea
                type="text"
                v-model="item"
                placeholder="Motivo de rechazo (no obligatorio)"
                class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-1 focus:ring-inset focus:ring-indigo-600 sm:text-xs sm:leading-6"
            />
            <div class="flex flex-col gap-2">
                <button
                    type="button"
                    @click="saveRejectReason"
                    class="bg-gray-100 text-indigo-700 rounded-md leading-none text-center flex justify-center items-center p-1.5 focus:outline-none focus:shadow-[inset_0_0_0_1px_#6366f1] hover:shadow-[inset_0_0_0_1px_#6366f1] active:shadow-[inset_0_0_0_1px_#6366f1] transform transition active:scale-95
                            hover:bg-white "
                >
                    <svg
                        width="18px"
                        height="18px"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            fill-rule="evenodd"
                            clip-rule="evenodd"
                            d="M18.1716 1C18.702 1 19.2107 1.21071 19.5858 1.58579L22.4142 4.41421C22.7893 4.78929 23 5.29799 23 5.82843V20C23 21.6569 21.6569 23 20 23H4C2.34315 23 1 21.6569 1 20V4C1 2.34315 2.34315 1 4 1H18.1716ZM4 3C3.44772 3 3 3.44772 3 4V20C3 20.5523 3.44772 21 4 21L5 21L5 15C5 13.3431 6.34315 12 8 12L16 12C17.6569 12 19 13.3431 19 15V21H20C20.5523 21 21 20.5523 21 20V6.82843C21 6.29799 20.7893 5.78929 20.4142 5.41421L18.5858 3.58579C18.2107 3.21071 17.702 3 17.1716 3H17V5C17 6.65685 15.6569 8 14 8H10C8.34315 8 7 6.65685 7 5V3H4ZM17 21V15C17 14.4477 16.5523 14 16 14L8 14C7.44772 14 7 14.4477 7 15L7 21L17 21ZM9 3H15V5C15 5.55228 14.5523 6 14 6H10C9.44772 6 9 5.55228 9 5V3Z"
                            fill="#5145cd"
                        />
                    </svg>
                </button>
                <button
                    type="button"
                    @click="closeRejectReason"
                    class="bg-gray-100 text-gray-500 rounded-md leading-none text-center flex justify-center items-center p-1.5 focus:outline-none focus:shadow-[inset_0_0_0_1px_#4b5563] hover:shadow-[inset_0_0_0_1px_#4b5563] active:shadow-[inset_0_0_0_1px_#4b5563] transform transition active:scale-95
                            hover:bg-white 
                    "
                >
                    <svg
                        width="18px"
                        height="18px"
                        viewBox="0 0 24 24"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <circle
                            cx="12"
                            cy="12"
                            r="9"
                            stroke="#4b5563"
                            stroke-width="2"
                        />
                        <path
                            d="M18 18L6 6"
                            stroke="#4b5563"
                            stroke-width="2"
                        />
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
const { closeRejectReason, saveRejectReason } = defineProps({
    closeRejectReason: Function,
    saveRejectReason: Function,
});


const item = defineModel('item')

</script>
