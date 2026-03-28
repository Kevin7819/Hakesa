import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                hakesa: {
                    pink: '#F26BB5',
                    'pink-dark': '#D94E9B',
                    'pink-light': '#F5A3D0',
                    teal: '#04BFBF',
                    'teal-dark': '#039999',
                    'teal-light': '#6DD8D8',
                    yellow: '#F2CB05',
                    'yellow-dark': '#CCAA00',
                    gold: '#F2B705',
                    'gold-dark': '#CC9800',
                    light: '#F2F2F2',
                    'light-dark': '#E0E0E0',
                },
            },
        },
    },

    plugins: [forms],
};
