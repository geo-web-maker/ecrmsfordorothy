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
            // ── Typography ──────────────────────────────────────────────
            fontFamily: {
                sans: ['Inter', 'system-ui', '-apple-system', ...defaultTheme.fontFamily.sans],
            },

            // ── Brand Colour Palette ─────────────────────────────────────
            colors: {
                portal: {
                    ink: '#012d1d',
                    container: '#1b4332',
                    secondary: '#2c694e',
                    'secondary-fixed': '#b1f0ce',
                    'on-secondary-container': '#316e52',
                    'leaf-tint': '#D8F3DC',
                    surface: '#f8f9fa',
                    'surface-bright': '#f8f9fa',
                    'on-surface': '#191c1d',
                    'on-surface-variant': '#414844',
                    'on-primary-container': '#86af99',
                    'outline-variant': '#c1c8c2',
                    'secondary-container': '#aeeecb',
                },
                primary: {
                    DEFAULT:  '#5E8B3D',   // Primary Green
                    dark:     '#3F6B2A',   // Dark Green Accent
                    light:    '#7ab355',   // Lighter green tint
                },
                sage:     '#DDE8C8',       // Soft Sage Background
                moss:     '#7B8F69',       // Muted Moss Green
                cream:    '#F3F5EA',       // Light Cream Sections
                eco: {
                    // Semantic text shades
                    dark:   '#1a2e0f',
                    mid:    '#3d5229',
                    muted:  '#5e6e4e',
                    // Card surface
                    card:   '#ffffff',
                    border: 'rgba(94,139,61,0.12)',
                    // Footer accent
                    accent: '#a3e079',
                    live:   '#7deb7a',
                },
            },

            // ── Border Radius ─────────────────────────────────────────────
            borderRadius: {
                card: '1.5rem',   // --radius-card
                btn:  '50px',     // --radius-btn
                icon: '14px',
            },

            fontSize: {
                'portal-xl': ['48px', { lineHeight: '56px', letterSpacing: '-0.02em', fontWeight: '700' }],
                'portal-lg': ['32px', { lineHeight: '40px', letterSpacing: '-0.01em', fontWeight: '700' }],
                'portal-md': ['24px', { lineHeight: '32px', fontWeight: '600' }],
                'portal-body-lg': ['18px', { lineHeight: '28px', fontWeight: '400' }],
            },

            spacing: {
                'portal-gutter': '24px',
                'portal-margin-mobile': '16px',
                'portal-margin-desktop': '48px',
            },

            // ── Box Shadows ───────────────────────────────────────────────
            boxShadow: {
                card:       '0 8px 32px rgba(63,107,42,0.10)',
                'card-hover': '0 20px 48px rgba(63,107,42,0.18)',
                btn:        '0 4px 14px rgba(94,139,61,0.30)',
                'btn-hover':'0 8px 24px rgba(63,107,42,0.40)',
                nav:        '0 2px 20px rgba(63,107,42,0.06)',
                mock:       '0 24px 60px rgba(30,60,10,0.30)',
                icon:       '0 4px 14px rgba(94,139,61,0.12)',
                bullet:     '0 2px 8px rgba(63,107,42,0.06)',
                'bullet-hover': '0 6px 20px rgba(63,107,42,0.12)',
            },

            // ── Keyframe Animations ───────────────────────────────────────
            keyframes: {
                fadeInUp: {
                    from: { opacity: '0', transform: 'translateY(36px)' },
                    to:   { opacity: '1', transform: 'translateY(0)' },
                },
                floatMock: {
                    '0%, 100%': { transform: 'translateY(0px) rotate(0.3deg)' },
                    '50%':      { transform: 'translateY(-10px) rotate(-0.3deg)' },
                },
                pulseDot: {
                    '0%, 100%': { opacity: '0.3' },
                    '50%':      { opacity: '1' },
                },
            },

            // ── Named Animations ─────────────────────────────────────────
            animation: {
                'fade-in-up':  'fadeInUp 0.9s ease both',
                'float-mock':  'floatMock 6s infinite ease-in-out',
                'pulse-dot':   'pulseDot 1.5s infinite',
            },

            // ── Max-width ─────────────────────────────────────────────────
            maxWidth: {
                site: '1200px',
                'portal-container': '1280px',
            },

            // ── Backdrop Blur ─────────────────────────────────────────────
            backdropBlur: {
                nav: '18px',
                btn: '8px',
            },
        },
    },

    plugins: [forms],
};
