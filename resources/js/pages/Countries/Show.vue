<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLayout from '../../Layouts/AppLayout.vue';
import { flagEmoji, type Country } from '../../types/country';

const props = defineProps<{ country: Country }>();
const { t, locale } = useI18n();
</script>

<template>
    <Head :title="locale === 'ar' ? country.name.ar : country.name.en" />

    <AppLayout>
        <section class="bg-ink py-14 text-white">
            <div class="mx-auto max-w-5xl px-6">
                <Link href="/countries" class="inline-flex items-center gap-1 text-sm text-white/60 hover:text-brass">
                    <svg class="h-4 w-4 rtl:rotate-180" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 18l-6-6 6-6M5 12h14" />
                    </svg>
                    {{ t('country_page.back') }}
                </Link>
                <div class="mt-4 flex items-center gap-4">
                    <span class="text-5xl">{{ flagEmoji(country.flag) }}</span>
                    <div>
                        <h1 class="font-display text-3xl font-extrabold md:text-4xl">
                            {{ locale === 'ar' ? country.name.ar : country.name.en }}
                        </h1>
                        <p class="mt-1 text-sm text-white/60">
                            {{ t('countries.processing_time') }}:
                            {{ locale === 'ar' ? country.processing_time.ar : country.processing_time.en }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-14">
            <div class="mx-auto grid max-w-5xl gap-10 px-6 md:grid-cols-5">
                <!-- Visa types -->
                <div class="md:col-span-2">
                    <h2 class="font-display text-lg font-bold text-ink">{{ t('country_page.visa_types_title') }}</h2>
                    <div class="mt-4 space-y-3">
                        <div
                            v-for="v in country.visa_types"
                            :key="v.key"
                            class="rounded-xl border border-paper-dark bg-white p-4"
                        >
                            <p class="font-display text-sm font-bold text-ink">
                                {{ locale === 'ar' ? v.name.ar : v.name.en }}
                            </p>
                            <p class="mt-1 text-sm text-teal">
                                {{ t('countries.fee_from') }} {{ v.fee.toLocaleString() }} {{ t('countries.egp') }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Documents checklist -->
                <div class="md:col-span-3">
                    <h2 class="font-display text-lg font-bold text-ink">{{ t('country_page.documents_title') }}</h2>
                    <ul class="mt-4 space-y-3">
                        <li
                            v-for="(doc, i) in country.documents"
                            :key="i"
                            class="flex items-start gap-3 rounded-xl border border-paper-dark bg-white p-4"
                        >
                            <svg class="mt-0.5 h-5 w-5 shrink-0 text-teal" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                            </svg>
                            <span class="text-sm text-ink/80">{{ locale === 'ar' ? doc.ar : doc.en }}</span>
                        </li>
                    </ul>
                </div>
            </div>
        </section>

        <!-- CTA -->
        <section class="bg-teal py-16 text-white">
            <div class="mx-auto max-w-5xl px-6 text-center">
                <h2 class="font-display text-2xl font-extrabold md:text-3xl">{{ t('country_page.cta_title') }}</h2>
                <p class="mt-3 text-white/80">{{ t('country_page.cta_desc') }}</p>
                <Link
                    href="/contact"
                    class="mt-8 inline-block rounded-full bg-brass px-8 py-3 font-display text-sm font-bold text-ink hover:bg-brass-light"
                >
                    {{ t('country_page.cta_button') }}
                </Link>
            </div>
        </section>
    </AppLayout>
</template>
