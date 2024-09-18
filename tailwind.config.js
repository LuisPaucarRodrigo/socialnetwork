import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import flowbite from 'flowbite/plugin';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './node_modules/flowbite/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            boxShadow: {
                'header-gray-300': 'inset 1px 1px 1px rgba(209, 213, 219, 1), inset -1px -1px 0px rgba(209, 213, 219, 1)',
        'header-custom': 'inset 1px 1px 1px rgba(0, 0, 0, 1), inset 0px -1px 0px rgba(0, 0, 0, 1)',
              }
        },
    },

    plugins: [forms, flowbite],
};
