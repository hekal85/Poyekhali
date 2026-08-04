import { createI18n } from 'vue-i18n';
import ar from './locales/ar.json';
import en from './locales/en.json';

export type AppLocale = 'ar' | 'en';

export const RTL_LOCALES: AppLocale[] = ['ar'];

export const SUPPORTED_LOCALES: { code: AppLocale; label: string }[] = [
    { code: 'ar', label: 'العربية' },
    { code: 'en', label: 'English' },
];

function detectInitialLocale(): AppLocale {
    const stored = window.localStorage.getItem('poyekhali_locale') as AppLocale | null;
    if (stored === 'ar' || stored === 'en') return stored;

    // اللغة الافتراضية عربي لأن الجمهور المستهدف مصري
    return 'ar';
}

export const i18n = createI18n({
    legacy: false,
    globalInjection: true,
    locale: detectInitialLocale(),
    fallbackLocale: 'ar',
    messages: { ar, en },
});

export function isRtl(locale: AppLocale): boolean {
    return RTL_LOCALES.includes(locale);
}

/**
 * يبدّل اللغة، يحفظها، ويظبط اتجاه الصفحة (dir) والـ lang على <html>
 */
export function setAppLocale(locale: AppLocale) {
    (i18n.global.locale as any).value = locale;
    window.localStorage.setItem('poyekhali_locale', locale);
    document.documentElement.setAttribute('lang', locale);
    document.documentElement.setAttribute('dir', isRtl(locale) ? 'rtl' : 'ltr');
}
