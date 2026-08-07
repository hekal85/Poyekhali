<script setup lang="ts">
import { useForm, Head, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLayout from '../Layouts/AppLayout.vue';

interface TrackResult {
    order_number: string;
    status: string;
    country: { ar: string; en: string };
    visa_type: { ar: string; en: string };
    created_at: string;
}

const props = defineProps<{ result?: TrackResult | null; notFound?: boolean; searched?: boolean }>();
const { t, locale } = useI18n();
usePage();

const form = useForm({
    order_number: '',
    passport_number: '',
});

function submit() {
    form.post('/track', { preserveScroll: true, preserveState: true });
}

const statusStyles: Record<string, string> = {
    under_review: 'bg-brass/15 text-brass',
    approved_processing: 'bg-teal/15 text-teal',
    visa_ready: 'bg-teal text-white',
    visa_cancelled: 'bg-alert/15 text-alert',
    deleted: 'bg-ink/10 text-ink/50',
    other: 'bg-ink/10 text-ink/60',
};
</script>

<template>
    <Head :title="t('track.title')" />

    <AppLayout>
        <section class="bg-ink py-14 text-white">
            <div class="mx-auto max-w-2xl px-6">
                <h1 class="font-display text-3xl font-extrabold md:text-4xl">{{ t('track.title') }}</h1>
                <p class="mt-3 text-white/70">{{ t('track.subtitle') }}</p>
            </div>
        </section>

        <section class="py-14">
            <div class="mx-auto max-w-2xl px-6">
                <form class="rounded-2xl bg-white p-6 shadow-sm shadow-ink/5" @submit.prevent="submit">
                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-ink/70">{{ t('track.order_number') }}</label>
                            <input v-model="form.order_number" placeholder="PYK-000123" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" dir="ltr" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-ink/70">{{ t('track.passport_number') }}</label>
                            <input v-model="form.passport_number" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" dir="ltr" />
                        </div>
                    </div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="mt-5 w-full rounded-xl bg-teal py-3 font-display text-sm font-bold text-white hover:bg-teal-light disabled:opacity-50"
                    >
                        {{ t('track.submit') }}
                    </button>
                </form>

                <!-- النتيجة -->
                <div v-if="props.searched" class="mt-8">
                    <div v-if="props.result" class="rounded-2xl border border-paper-dark bg-white p-6">
                        <h2 class="font-display text-lg font-bold text-ink">{{ t('track.result_title') }}</h2>
                        <dl class="mt-4 space-y-3 text-sm">
                            <div class="flex justify-between border-b border-paper-dark pb-2">
                                <dt class="text-ink/50">{{ t('track.order_number') }}</dt>
                                <dd class="font-bold text-ink" dir="ltr">{{ props.result.order_number }}</dd>
                            </div>
                            <div class="flex justify-between border-b border-paper-dark pb-2">
                                <dt class="text-ink/50">{{ t('track.country_label') }}</dt>
                                <dd class="text-ink">{{ locale === 'ar' ? props.result.country.ar : props.result.country.en }}</dd>
                            </div>
                            <div class="flex justify-between border-b border-paper-dark pb-2">
                                <dt class="text-ink/50">{{ t('track.visa_type_label') }}</dt>
                                <dd class="text-ink">{{ locale === 'ar' ? props.result.visa_type.ar : props.result.visa_type.en }}</dd>
                            </div>
                            <div class="flex justify-between border-b border-paper-dark pb-2">
                                <dt class="text-ink/50">{{ t('track.date_label') }}</dt>
                                <dd class="text-ink">{{ props.result.created_at }}</dd>
                            </div>
                            <div class="flex items-center justify-between pt-1">
                                <dt class="text-ink/50">{{ t('track.status_label') }}</dt>
                                <dd class="rounded-full px-3 py-1 text-xs font-bold" :class="statusStyles[props.result.status] ?? statusStyles.other">
                                    {{ t(`application_status.${props.result.status}`) }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                    <div v-else class="rounded-2xl border border-dashed border-paper-dark p-6 text-center text-sm text-ink/50">
                        {{ t('track.not_found') }}
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
