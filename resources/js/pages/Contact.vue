<script setup lang="ts">
import { reactive } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLayout from '../Layouts/AppLayout.vue';

const { t } = useI18n();

// ملاحظة: عدّل هذا الفورم عشان يتصل بـ route فعلي لاستقبال طلبات التواصل عندك
const form = useForm({
    name: '',
    phone: '',
    country: '',
    message: '',
});

const submitted = reactive({ value: false });

function submit() {
    // form.post('/contact', { onSuccess: () => (submitted.value = true) });
    submitted.value = true; // مؤقتًا لحد ما تربط الراوت الحقيقي
}
</script>

<template>
    <Head :title="t('contact.title')" />

    <AppLayout>
        <section class="bg-ink py-14 text-white">
            <div class="mx-auto max-w-5xl px-6">
                <h1 class="font-display text-3xl font-extrabold md:text-4xl">{{ t('contact.title') }}</h1>
                <p class="mt-3 max-w-xl text-white/70">{{ t('contact.subtitle') }}</p>
            </div>
        </section>

        <section class="py-16">
            <div class="mx-auto grid max-w-5xl gap-10 px-6 md:grid-cols-5">
                <form class="md:col-span-3" @submit.prevent="submit">
                    <div v-if="submitted.value" class="mb-6 rounded-xl bg-teal/10 p-4 text-sm text-teal">
                        ✓
                    </div>

                    <div class="grid gap-5 sm:grid-cols-2">
                        <div>
                            <label class="text-sm font-medium text-ink/70">{{ t('contact.name') }}</label>
                            <input v-model="form.name" type="text" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" />
                        </div>
                        <div>
                            <label class="text-sm font-medium text-ink/70">{{ t('contact.phone') }}</label>
                            <input v-model="form.phone" type="tel" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" />
                        </div>
                    </div>

                    <div class="mt-5">
                        <label class="text-sm font-medium text-ink/70">{{ t('contact.country') }}</label>
                        <input v-model="form.country" type="text" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" />
                    </div>

                    <div class="mt-5">
                        <label class="text-sm font-medium text-ink/70">{{ t('contact.message') }}</label>
                        <textarea v-model="form.message" rows="4" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal"></textarea>
                    </div>

                    <button type="submit" class="mt-6 w-full rounded-xl bg-teal py-3 font-display text-sm font-bold text-white hover:bg-teal-light sm:w-auto sm:px-8">
                        {{ t('contact.submit') }}
                    </button>
                </form>

                <div class="md:col-span-2 space-y-6">
                    <a
                        href="https://wa.me/201000000000"
                        target="_blank"
                        class="flex items-center justify-center gap-2 rounded-xl bg-[#1DA851] py-3 font-display text-sm font-bold text-white hover:opacity-90"
                    >
                        {{ t('contact.whatsapp') }}
                    </a>

                    <div class="rounded-xl border border-paper-dark bg-white p-5">
                        <h3 class="font-display text-sm font-bold text-ink">{{ t('contact.address_title') }}</h3>
                        <p class="mt-2 text-sm text-ink/60">6 شارع النصر، مدينة نصر، القاهرة</p>
                    </div>

                    <div class="rounded-xl border border-paper-dark bg-white p-5">
                        <h3 class="font-display text-sm font-bold text-ink">{{ t('contact.hours_title') }}</h3>
                        <p class="mt-2 text-sm text-ink/60">{{ t('contact.hours_value') }}</p>
                    </div>
                </div>
            </div>
        </section>
    </AppLayout>
</template>
