<script setup lang="ts">
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import type { Country } from '../types/country';
import { flagEmoji } from '../types/country';

const props = defineProps<{ country: Country }>();
const { t, locale } = useI18n();

const cheapestFee = computed(() =>
    Math.min(...props.country.visa_types.map((v) => v.fee)),
);
</script>

<template>
    <Link
        :href="`/countries/${country.slug}`"
        class="group flex flex-col rounded-2xl border border-paper-dark bg-white p-6 transition hover:-translate-y-1 hover:shadow-xl hover:shadow-ink/5"
    >
        <div class="flex items-center justify-between">
            <span class="text-4xl leading-none">{{ flagEmoji(country.flag) }}</span>
            <span class="rounded-full bg-teal/10 px-3 py-1 text-xs font-semibold text-teal">
                {{ country.visa_types.length }} {{ t('countries.visa_types') }}
            </span>
        </div>

        <h3 class="mt-4 font-display text-xl font-bold text-ink">
            {{ locale === 'ar' ? country.name.ar : country.name.en }}
        </h3>

        <p class="mt-1 text-xs text-ink/50">
            {{ t('countries.processing_time') }}: {{ locale === 'ar' ? country.processing_time.ar : country.processing_time.en }}
        </p>

        <div class="mt-5 flex items-center justify-between border-t border-paper-dark pt-4">
            <span class="text-sm text-ink/60">
                {{ t('countries.fee_from') }}
                <b class="text-ink">{{ cheapestFee.toLocaleString() }}</b>
                {{ t('countries.egp') }}
            </span>
            <span class="flex items-center gap-1 font-display text-sm font-bold text-teal transition group-hover:gap-2">
                {{ t('countries.view_details') }}
                <svg class="h-4 w-4 rtl:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6" />
                </svg>
            </span>
        </div>
    </Link>
</template>
