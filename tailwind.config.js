import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.vue',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Work Sans"', 'Inter', ...defaultTheme.fontFamily.sans],
                display: ['Anton', '"Archivo Black"', 'Impact', ...defaultTheme.fontFamily.sans],
                mono: ['"Space Mono"', ...defaultTheme.fontFamily.mono],
                slab: ['"Roboto Slab"', ...defaultTheme.fontFamily.serif],
            },

            // Palette "Tani Merah" — lihat docs/LANDING_REDESIGN_PLAN.md §5.1
            colors: {
                flag: {
                    50: '#FFF2F3',
                    100: '#FFD9DC',
                    200: '#FAA7AE',
                    300: '#F0717B',
                    400: '#E33D4D',
                    500: '#C8102E',
                    600: '#A50B25',
                    700: '#7E0A1E',
                    800: '#590815',
                    900: '#36040C',
                },
                ink: {
                    50: '#F5F5F5',
                    100: '#E0E0E0',
                    200: '#B8B8B8',
                    700: '#2B2B2B',
                    800: '#1A1A1A',
                    900: '#0D0D0D',
                },
                paper: {
                    50: '#FCF9F1',
                    100: '#F4EEDB',
                    200: '#E7DDB7',
                    300: '#D1C894',
                },
                ochre: {
                    400: '#E4B52A',
                    500: '#D4A017',
                    600: '#B0841B',
                    700: '#8B691A',
                },
                earth: {
                    500: '#6B4423',
                    600: '#513217',
                },

                // Aliases agar Tailwind util gradient/ring tetap logis.
                primary: {
                    50: '#FFF2F3',
                    100: '#FFD9DC',
                    200: '#FAA7AE',
                    300: '#F0717B',
                    400: '#E33D4D',
                    500: '#C8102E',
                    600: '#A50B25',
                    700: '#7E0A1E',
                    800: '#590815',
                    900: '#36040C',
                },
            },

            borderRadius: {
                none: '0',
                sharp: '0',
                xs: '2px',
                sm: '2px',
                DEFAULT: '2px',
            },

            // Poster-style hard offset shadow (anti-korporat).
            boxShadow: {
                poster: '8px 8px 0 #0D0D0D',
                'poster-red': '8px 8px 0 #C8102E',
                'poster-sm': '4px 4px 0 #0D0D0D',
                stamp: 'inset 0 -6px 0 0 #C8102E',
                none: 'none',
            },

            letterSpacing: {
                wider: '0.08em',
                widest: '0.18em',
                banner: '0.24em',
            },

            fontSize: {
                // Skala perfect-fourth (1.333).
                '2xs': ['0.6875rem', { lineHeight: '1rem' }],
                display: ['clamp(3rem, 8vw, 7.3rem)', { lineHeight: '0.92', letterSpacing: '-0.01em' }],
                hero: ['clamp(2.25rem, 5.5vw, 5rem)', { lineHeight: '0.95', letterSpacing: '-0.005em' }],
                stat: ['clamp(3.5rem, 9vw, 9.75rem)', { lineHeight: '0.9', letterSpacing: '-0.02em' }],
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
    ],
};
