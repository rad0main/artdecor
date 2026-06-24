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
                heading: ['Montserrat', 'sans-serif'],
                body: ['PT Sans', 'sans-serif'],
            },
            colors: {
                brand: {
                    primary: '#E1323D',
                    'primary-hover': '#C82832',
                    secondary: '#242E38',
                    accent: '#6EC1E4',
                },
                surface: {
                    DEFAULT: '#F9F9F9',
                    hover: '#F0F0F0',
                },
                text: {
                    primary: '#3C3D41',
                    secondary: '#808285',
                    muted: '#A5A161',
                },
                border: {
                    DEFAULT: '#EFEFEF',
                    hover: '#DEDEDE',
                },
            },
            spacing: {
                '4.5': '1.125rem',
                '18': '4.5rem',
                '88': '22rem',
                '120': '30rem',
            },
            maxWidth: {
                'page': '1200px',
            },
        },
    },
    plugins: [],
};
