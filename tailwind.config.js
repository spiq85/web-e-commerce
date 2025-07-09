import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
const defaultTheme = require('tailwindcss/defaultTheme');
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: { 
                'rubylux-dark': '#0a0a0a',
                'rubylux-ruby': '#FF0066',
                'rubylux-ruby-dark': '#AA0044',
                'rubylux-gradient-start': '#FF0066',
                'rubylux-gradient-end': '#AA0044',
                'rubylux-text-light': '#f0f0f0',
                'rubylux-text-dark': '#333333',
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
