import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    // Wajib `class`: Filament menambah `.dark` di <html> lewat JS / forced mode.
    // Default `media` membuat `dark:*` hanya mengikuti prefers-color-scheme,
    // sehingga teks tetap gray-950 di latar gelap → kontras hancur.
    darkMode: 'class',
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
                serif: ['"Work Sans"', 'Inter', ...defaultTheme.fontFamily.sans],
                display: ['Anton', '"Archivo Black"', 'Impact', ...defaultTheme.fontFamily.sans],
                inline: ['Anton', '"Archivo Black"', ...defaultTheme.fontFamily.sans],
                mono: ['"Space Mono"', ...defaultTheme.fontFamily.mono],
                slab: ['"Roboto Slab"', ...defaultTheme.fontFamily.serif],
            },

            colors: {
                flag: {
                    50: 'hsl(var(--flag-50) / <alpha-value>)',
                    100: 'hsl(var(--flag-100) / <alpha-value>)',
                    200: 'hsl(var(--flag-200) / <alpha-value>)',
                    300: 'hsl(var(--flag-300) / <alpha-value>)',
                    400: 'hsl(var(--flag-400) / <alpha-value>)',
                    500: 'hsl(var(--flag-500) / <alpha-value>)',
                    600: 'hsl(var(--flag-600) / <alpha-value>)',
                    700: 'hsl(var(--flag-700) / <alpha-value>)',
                    800: 'hsl(var(--flag-800) / <alpha-value>)',
                    900: 'hsl(var(--flag-900) / <alpha-value>)',
                },
                ink: {
                    50: 'hsl(var(--ink-50) / <alpha-value>)',
                    100: 'hsl(var(--ink-100) / <alpha-value>)',
                    200: 'hsl(var(--ink-200) / <alpha-value>)',
                    700: 'hsl(var(--ink-700) / <alpha-value>)',
                    800: 'hsl(var(--ink-800) / <alpha-value>)',
                    900: 'hsl(var(--ink-900) / <alpha-value>)',
                },
                paper: {
                    50: 'hsl(var(--paper-50) / <alpha-value>)',
                    100: 'hsl(var(--paper-100) / <alpha-value>)',
                    200: 'hsl(var(--paper-200) / <alpha-value>)',
                    300: 'hsl(var(--paper-300) / <alpha-value>)',
                },
                ochre: {
                    400: 'hsl(var(--ochre-400) / <alpha-value>)',
                    500: 'hsl(var(--ochre-500) / <alpha-value>)',
                    600: 'hsl(var(--ochre-600) / <alpha-value>)',
                    700: 'hsl(var(--ochre-700) / <alpha-value>)',
                },
                earth: {
                    500: 'hsl(var(--earth-500) / <alpha-value>)',
                    600: 'hsl(var(--earth-600) / <alpha-value>)',
                },

                // Aliases agar Tailwind util gradient/ring tetap logis.
                primary: {
                    50: 'hsl(var(--flag-50) / <alpha-value>)',
                    100: 'hsl(var(--flag-100) / <alpha-value>)',
                    200: 'hsl(var(--flag-200) / <alpha-value>)',
                    300: 'hsl(var(--flag-300) / <alpha-value>)',
                    400: 'hsl(var(--flag-400) / <alpha-value>)',
                    500: 'hsl(var(--flag-500) / <alpha-value>)',
                    600: 'hsl(var(--flag-600) / <alpha-value>)',
                    700: 'hsl(var(--flag-700) / <alpha-value>)',
                    800: 'hsl(var(--flag-800) / <alpha-value>)',
                    900: 'hsl(var(--flag-900) / <alpha-value>)',
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
                poster: '8px 8px 0 hsl(var(--ink-900) / 1)',
                'poster-red': '8px 8px 0 hsl(var(--flag-500) / 1)',
                'poster-sm': '4px 4px 0 hsl(var(--ink-900) / 1)',
                stamp: 'inset 0 -6px 0 0 hsl(var(--flag-500) / 1)',
                none: 'none',
            },

            letterSpacing: {
                wider: '0.08em',
                widest: '0.18em',
                banner: '0.24em',
            },

            fontSize: {
                '2xs': ['0.6875rem', { lineHeight: '1rem' }],
                display: ['clamp(3.4rem, 8vw, 6.875rem)', { lineHeight: '0.92', letterSpacing: '-0.01em' }],
                hero: ['clamp(2.1rem, 5.5vw, 4.25rem)', { lineHeight: '0.95', letterSpacing: '-0.005em' }],
                stat: ['clamp(3.4rem, 9vw, 8.5rem)', { lineHeight: '0.9', letterSpacing: '-0.02em' }],
            },
        },
    },
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
    ],
};
