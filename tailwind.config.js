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
                // 🎨 Gracia Creativa Brand Colors (2026)
                gracia: {
                    primary: '#BF5098',      // Pink Magenta - CTAs, highlights
                    'primary-dark': '#A84385',
                    'primary-light': '#D46BB5',
                    secondary: '#7D5A8C',  // Muted Purple - iconos, supporting
                    'secondary-dark': '#6A4C78',
                    'secondary-light': '#9A7AA8',
                    accent: '#B6D936',      // Lime Green - badges, tags
                    'accent-dark': '#A3C22E',
                    'accent-light': '#C8E058',
                    base: '#0D0D0D',        // Rich Black - text, backgrounds
                    'base-light': '#262626',
                    'base-lighter': '#404040',
                },
            },
        },
    },

    plugins: [forms],
};
