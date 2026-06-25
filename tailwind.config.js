/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './app/Filament/**/*.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                heading: ['Montserrat', 'Arial', 'sans-serif'],
                body: ['PT Sans', 'Arial', 'sans-serif'],
            },
            colors: {
                brand: {
                    primary: '#D32F2F',
                    'primary-hover': '#B71C1C',
                    secondary: '#2C2C2C',
                    accent: '#5BC0DE',
                },
                surface: {
                    DEFAULT: '#F5F5F5',
                    hover: '#ECECEC',
                },
                text: {
                    primary: '#333333',
                    secondary: '#777777',
                    muted: '#999999',
                },
                border: {
                    DEFAULT: '#E0E0E0',
                    hover: '#CCCCCC',
                },
                footer: {
                    bg: '#2C2C2C',
                    text: '#AAAAAA',
                },
            },
            maxWidth: {
                page: '1200px',
            },
        },
    },
    plugins: [
        function ({ addUtilities }) {
            addUtilities({
                '.x-cloak': { display: 'none !important' },
            });
        },
    ],
};
