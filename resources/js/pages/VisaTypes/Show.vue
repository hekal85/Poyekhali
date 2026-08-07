<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLayout from '../../Layouts/AppLayout.vue';
import { flagEmoji } from '../../types/country';

interface Entry {
    country_slug: string;
    country_flag: string;
    country_name: { ar: string; en: string };
    fee: number;
    processing_time_ar: string;
    visa_type_id: number;
}

const props = defineProps<{
    key: string;
    name: { ar: string; en: string };
    entries: Entry[];
}>();

const { t, locale } = useI18n();
</script>

<template>
    <Head :title="locale === 'ar' ? name.ar : name.en" />

    <AppLayout>
        <section class="bg-ink py-14 text-white">
            <div class="mx-auto max-w-5xl px-6">
                <h1 class="font-display text-3xl font-extrabold md:text-4xl">
                    {{ locale === 'ar' ? name.ar : name.en }}
                </h1>
                <p class="mt-3 text-white/70">
                    {{ t('visa_types.available_in') }} {{ entries.length }}
                    {{ locale === 'ar' ? 'دولة' : (entries.length === 1 ? 'country' : 'countries') }}
                </p>
            </div>
        </section>

        <section class="py-14">
            <div class="mx-auto grid max-w-5xl gap-5 px-6 sm:grid-cols-2">
                <div
                    v-for="e in entries"
                    :key="e.country_slug"
                    class="flex flex-col rounded-2xl border border-paper-dark bg-white p-6"
                >
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">{{ flagEmoji(e.country_flag) }}</span>
                        <div>
                            <p class="font-display text-base font-bold text-ink">
                                {{ locale === 'ar' ? e.country_name.ar : e.country_name.en }}
                            </p>
                            <p class="text-xs text-ink/50">{{ e.processing_time_ar }}</p>
                        </div>
                    </div>

                    <p class="mt-4 text-sm text-teal">
                        {{ t('countries.fee_from') }} <b>{{ e.fee.toLocaleString() }}</b> {{ t('countries.egp') }}
                    </p>

                    <div class="mt-5 flex gap-2">
                        <Link
                            :href="`/countries/${e.country_slug}`"
                            class="flex-1 rounded-lg border border-paper-dark px-4 py-2 text-center text-xs font-bold text-ink/70 hover:bg-paper"
                        >
                            {{ t('countries.view_details') }}
                        </Link>
                        <Link
                            :href="`/apply?visa_type_id=${e.visa_type_id}`"
                            class="flex-1 rounded-lg bg-brass px-4 py-2 text-center text-xs font-bold text-ink hover:bg-brass-light"
                        >
                            {{ t('visa_types.apply_now') }}
                        </Link>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
