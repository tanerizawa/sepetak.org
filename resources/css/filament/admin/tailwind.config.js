import preset from '../../../../vendor/filament/filament/tailwind.config.preset'

/**
 * Panel admin "Tani Merah" — mengikuti palet di tailwind.config.js root
 * tetapi disesuaikan untuk kebutuhan panel data-heavy:
 *  - primary tetap flag (merah) agar CTA/primary action konsisten dengan situs publik.
 *  - font display Anton hanya untuk heading/brand; body tetap Work Sans agar
 *    tabel dan form tetap mudah dibaca.
 *  - radius 0 (sharp) konsisten dengan gaya poster.
 */
export default {
    presets: [preset],
    content: [
        './app/Filament/**/*.php',
        './resources/views/filament/**/*.blade.php',
        './vendor/filament/**/*.blade.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['"Work Sans"', 'Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                display: ['Anton', '"Archivo Black"', 'Impact', 'sans-serif'],
                mono: ['"Space Mono"', 'ui-monospace', 'SFMono-Regular', 'monospace'],
            },
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
            },
            boxShadow: {
                poster: '8px 8px 0 #0D0D0D',
                'poster-sm': '4px 4px 0 #0D0D0D',
                'poster-red': '6px 6px 0 #C8102E',
            },
            borderRadius: {
                none: '0',
                DEFAULT: '2px',
                sm: '2px',
            },
            letterSpacing: {
                widest: '0.18em',
                banner: '0.24em',
            },
        },
    },
}
