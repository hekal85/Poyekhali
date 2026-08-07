import { createI18n } from 'vue-i18n';
import ar from './locales/ar.json';
import en from './locales/en.json';
import ru from './locales/ru.json';
import fr from './locales/fr.json';
import de from './locales/de.json';
import it from './locales/it.json';
import es from './locales/es.json';
import zh from './locales/zh.json';

export type AppLocale = 'ar' | 'en' | 'ru' | 'fr' | 'de' | 'it' | 'es' | 'zh';

export const RTL_LOCALES: AppLocale[] = ['ar'];

export const SUPPORTED_LOCALES: { code: AppLocale; label: string }[] = [
    { code: 'ar', label: 'العربية' },
    { code: 'en', label: 'English' },
    { code: 'ru', label: 'Русский' },
    { code: 'fr', label: 'Français' },
    { code: 'de', label: 'Deutsch' },
    { code: 'it', label: 'Italiano' },
    { code: 'es', label: 'Español' },
    { code: 'zh', label: '中文' },
];

function detectInitialLocale(): AppLocale {
    const stored = window.localStorage.getItem('poyekhali_locale') as AppLocale | null;
    if (stored && SUPPORTED_LOCALES.some((l) => l.code === stored)) return stored;

    // اللغة الافتراضية عربي لأن الجمهور المستهدف مصري
    return 'ar';
}

export const i18n = createI18n({
    legacy: false,
    globalInjection: true,
    locale: detectInitialLocale(),
    fallbackLocale: 'en',
    messages: { ar, en, ru, fr, de, it, es, zh },
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
