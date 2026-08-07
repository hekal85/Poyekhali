<script setup lang="ts">
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLayout from '../Layouts/AppLayout.vue';

interface Application {
    id: number;
    order_number: string;
    status: string;
    created_at: string;
    country: { name_ar: string; name_en: string };
    visaType: { name_ar: string; name_en: string };
}

interface NotificationItem {
    id: number;
    title: string;
    message: string;
    link: string | null;
    read_at: string | null;
    created_at: string;
}

const props = defineProps<{
    applications: Application[];
    notifications: NotificationItem[];
}>();

const { t, locale } = useI18n();

const statusColors: Record<string, string> = {
    under_review: 'bg-brass/10 text-brass',
    approved_processing: 'bg-blue-500/10 text-blue-600',
    visa_ready: 'bg-teal/10 text-teal',
    visa_cancelled: 'bg-alert/10 text-alert',
    deleted: 'bg-ink/10 text-ink/50',
    other: 'bg-ink/10 text-ink/50',
};

async function markAllRead() {
    try {
        await window.axios.post('/notifications/read-all');
    } catch {
        //
    }
}
</script>

<template>
    <Head :title="t('dashboard.title')" />

    <AppLayout>
        <section class="bg-ink py-14 text-white">
            <div class="mx-auto max-w-5xl px-6">
                <h1 class="font-display text-3xl font-extrabold md:text-4xl">{{ t('dashboard.title') }}</h1>
                <p class="mt-3 text-white/70">{{ t('dashboard.subtitle') }}</p>
            </div>
        </section>

        <section id="applications" class="py-14">
            <div class="mx-auto max-w-5xl px-6">
                <div class="flex items-center justify-between">
                    <h2 class="font-display text-lg font-bold text-ink">{{ t('dashboard.my_applications') }}</h2>
                    <Link href="/apply" class="rounded-full bg-brass px-5 py-2 font-display text-sm font-bold text-ink hover:bg-brass-light">
                        {{ t('dashboard.new_request') }}
                    </Link>
                </div>

                <div class="mt-5 space-y-3">
                    <div
                        v-for="app in applications"
                        :id="app.order_number"
                        :key="app.id"
                        class="flex flex-wrap items-center justify-between gap-3 rounded-2xl border border-paper-dark bg-white p-5"
                    >
                        <div>
                            <p class="font-display text-sm font-bold text-ink" dir="ltr">{{ app.order_number }}</p>
                            <p class="mt-1 text-xs text-ink/50">
                                {{ locale === 'ar' ? app.country.name_ar : app.country.name_en }}
                                —
                                {{ locale === 'ar' ? app.visaType.name_ar : app.visaType.name_en }}
                            </p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-bold" :class="statusColors[app.status]">
                            {{ t(`application_status.${app.status}`) }}
                        </span>
                    </div>

                    <p v-if="!applications.length" class="rounded-2xl border border-dashed border-paper-dark p-8 text-center text-sm text-ink/40">
                        {{ t('dashboard.no_applications') }}
                    </p>
                </div>
            </div>
        </section>

        <section class="border-t border-paper-dark py-14">
            <div class="mx-auto max-w-5xl px-6">
                <div class="flex items-center justify-between">
                    <h2 class="font-display text-lg font-bold text-ink">{{ t('notifications.title') }}</h2>
                    <button v-if="notifications.some((n) => !n.read_at)" type="button" class="text-sm font-bold text-teal hover:underline" @click="markAllRead">
                        {{ t('notifications.mark_all_read') }}
                    </button>
                </div>

                <div class="mt-5 space-y-2">
                    <Link
                        v-for="n in notifications"
                        :key="n.id"
                        :href="n.link ?? '#'"
                        class="block rounded-xl border border-paper-dark bg-white p-4"
                        :class="!n.read_at ? 'border-brass/40 bg-brass/5' : ''"
                    >
                        <p class="text-sm font-bold text-ink">{{ n.title }}</p>
                        <p class="mt-1 text-xs text-ink/60">{{ n.message }}</p>
                    </Link>

                    <p v-if="!notifications.length" class="rounded-2xl border border-dashed border-paper-dark p-8 text-center text-sm text-ink/40">
                        {{ t('notifications.empty') }}
                    </p>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
