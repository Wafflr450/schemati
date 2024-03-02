import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './vendor/usernotnull/tall-toasts/config/**/*.php',
        './vendor/usernotnull/tall-toasts/resources/views/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            textShadow: {
                sm: '0 1px 2px var(--tw-shadow-color)',
                DEFAULT: '0 2px 4px var(--tw-shadow-color)',
                lg: '0 8px 16px var(--tw-shadow-color)',
            },
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },
    darkMode: 'media',
    daisyui: {
        themes: [
            {
                mytheme: {
                    "primary": "#9333ea",
                    "secondary": "#d946ef",
                    "accent": "#e879f9",
                    "neutral": "#1b1b1b",
                    "base-100": "#202126",
                    "info": "#00f6ff",
                    "success": "#a3f322",
                    "warning": "#ffba00",
                    "error": "#ff1f67",
                },
            },
        ],
    },

    plugins: [forms, typography, require('flowbite/plugin'), require("daisyui")],
};
