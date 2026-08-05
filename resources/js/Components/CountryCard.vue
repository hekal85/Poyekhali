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
        class="group flex flex-col overflow-hidden rounded-2xl border border-paper-dark bg-white transition hover:-translate-y-1 hover:shadow-xl hover:shadow-ink/5"
    >
        <!-- بانر: صورة مرفوعة من لوحة التحكم، أو نمط هندسي بديل لو مفيش صورة -->
        <div class="relative h-32 w-full overflow-hidden bg-ink">
            <img
                v-if="country.image_url"
                :src="country.image_url"
                :alt="locale === 'ar' ? country.name.ar : country.name.en"
                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
            />
            <svg v-else class="h-full w-full opacity-40" viewBox="0 0 300 120" preserveAspectRatio="none">
                <defs>
                    <pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse">
                        <path d="M 20 0 L 0 0 0 20" fill="none" stroke="var(--color-brass)" stroke-width="0.6" />
                    </pattern>
                </defs>
                <rect width="300" height="120" fill="url(#grid)" />
            </svg>
            <span class="absolute end-3 top-3 text-3xl drop-shadow">{{ flagEmoji(country.flag) }}</span>
        </div>

        <div class="flex flex-1 flex-col p-6">
            <div class="flex items-center justify-between">
                <h3 class="font-display text-xl font-bold text-ink">
                    {{ locale === 'ar' ? country.name.ar : country.name.en }}
                </h3>
                <span class="rounded-full bg-teal/10 px-3 py-1 text-xs font-semibold text-teal">
                    {{ country.visa_types.length }} {{ t('countries.visa_types') }}
                </span>
            </div>

            <p class="mt-1 text-xs text-ink/50">
                {{ t('countries.processing_time') }}: {{ locale === 'ar' ? country.processing_time.ar : country.processing_time.en }}
            </p>

            <div class="mt-auto flex items-center justify-between border-t border-paper-dark pt-4 mt-5">
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
        </div>
    </Link>
</template>
