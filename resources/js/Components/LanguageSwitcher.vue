<script setup lang="ts">
import { computed } from 'vue';
import { useI18n } from 'vue-i18n';
import { setAppLocale, SUPPORTED_LOCALES, type AppLocale } from '../i18n';

const { locale } = useI18n();

const otherLocale = computed<AppLocale>(() =>
    locale.value === 'ar' ? 'en' : 'ar',
);

function switchLocale() {
    setAppLocale(otherLocale.value);
}
</script>

<template>
    <button
        type="button"
        class="flex items-center gap-2 rounded-full border border-white/20 px-3 py-1.5 text-sm font-medium text-white/90 transition hover:border-brass hover:text-brass"
        @click="switchLocale"
    >
        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
            <circle cx="12" cy="12" r="9" />
            <path d="M3 12h18M12 3c2.5 2.6 3.8 5.7 3.8 9s-1.3 6.4-3.8 9c-2.5-2.6-3.8-5.7-3.8-9S9.5 5.6 12 3Z" />
        </svg>
        <span>{{ SUPPORTED_LOCALES.find((l) => l.code === otherLocale)?.label }}</span>
    </button>
</template>
