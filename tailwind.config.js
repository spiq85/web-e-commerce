import defaultTheme from 'tailwindcss/defaultTheme';
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
                'rubylux-dark': '#1a1a1a',
                'rubylux-ruby': '#e0115f',
                'rubylux-ruby-dark': '#a00a40',
                'rubylux-gradient-start': '#e0115f',
                'rubylux-gradient-end': '#a00a40',
                'rubylux-text-light': '#f0f0f0',
                'rubylux-text-dark': '#333333',
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
