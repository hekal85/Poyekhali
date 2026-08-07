<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLayout from '../../Layouts/AppLayout.vue';
import { flagEmoji, type Country } from '../../types/country';

const props = defineProps<{ country: Country }>();
const { t, locale } = useI18n();

const selectedKey = ref(props.country.visa_types[0]?.key ?? '');

const selectedVisaType = computed(() =>
    props.country.visa_types.find((v) => v.key === selectedKey.value) ?? props.country.visa_types[0],
);

function selectVisaType(key: string) {
    selectedKey.value = key;
}
</script>

<template>
    <Head :title="locale === 'ar' ? country.name.ar : country.name.en" />

    <AppLayout>
        <section class="relative overflow-hidden bg-ink py-14 text-white">
            <img
                v-if="country.image_url"
                :src="country.image_url"
                alt=""
                class="absolute inset-0 h-full w-full object-cover opacity-25"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-ink via-ink/80 to-ink/40"></div>

            <div class="relative mx-auto max-w-5xl px-6">
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
                            {{ t('countries.processing_time') }}: {{ country.processing_time_en }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-14">
            <div class="mx-auto grid max-w-5xl gap-10 px-6 md:grid-cols-5">
                <div class="md:col-span-2">
                    <h2 class="font-display text-lg font-bold text-ink">{{ t('country_page.visa_types_title') }}</h2>
                    <p class="mt-1 text-xs text-ink/50">
                        {{ locale === 'ar' ? 'اضغط على نوع عشان تشوف مستنداته' : 'Click a type to see its documents' }}
                    </p>

                    <div class="mt-4 space-y-3">
                        <button
                            v-for="v in country.visa_types"
                            :key="v.key"
                            type="button"
                            class="w-full rounded-xl border p-4 text-start transition"
                            :class="v.key === selectedVisaType?.key
                                ? 'border-teal bg-teal/5 shadow-sm shadow-teal/10'
                                : 'border-paper-dark bg-white hover:border-teal/40'"
                            @click="selectVisaType(v.key)"
                        >
                            <div class="flex items-center justify-between">
                                <p class="font-display text-sm font-bold text-ink">
                                    {{ locale === 'ar' ? v.name.ar : v.name.en }}
                                </p>
                                <span
                                    v-if="v.key === selectedVisaType?.key"
                                    class="flex h-5 w-5 items-center justify-center rounded-full bg-teal text-white"
                                >
                                    <svg class="h-3 w-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                                    </svg>
                                </span>
                            </div>
                            <p class="mt-1 text-sm text-teal">
                                {{ t('countries.fee_from') }} {{ v.fee.toLocaleString() }} {{ t('countries.egp') }}
                            </p>
                            <p v-if="v.processing_time_ar" class="mt-0.5 text-xs text-ink/40">{{ v.processing_time_ar }}</p>
                        </button>
                        <p v-if="!country.visa_types.length" class="text-sm text-ink/40">
                            {{ locale === 'ar' ? 'مفيش أنواع تأشيرات مفعّلة لهذه الدولة حاليًا.' : 'No active visa types for this country right now.' }}
                        </p>
                    </div>
                </div>

                <div class="md:col-span-3">
                    <h2 class="font-display text-lg font-bold text-ink">
                        {{ t('country_page.documents_title') }}
                        <span class="text-ink/40">
                            — {{ selectedVisaType ? (locale === 'ar' ? selectedVisaType.name.ar : selectedVisaType.name.en) : '' }}
                        </span>
                    </h2>

                    <transition name="fade" mode="out-in">
                        <ul :key="selectedVisaType?.key" class="mt-4 space-y-3">
                            <li
                                v-for="(doc, i) in selectedVisaType?.documents ?? []"
                                :key="i"
                                class="flex items-start gap-3 rounded-xl border border-paper-dark bg-white p-4"
                            >
                                <svg class="mt-0.5 h-5 w-5 shrink-0 text-teal" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                                </svg>
                                <span class="text-sm text-ink/80">{{ locale === 'ar' ? doc.ar : doc.en }}</span>
                            </li>
                        </ul>
                    </transition>
                </div>
            </div>
        </section>

        <section class="bg-teal py-16 text-white">
            <div class="mx-auto max-w-5xl px-6 text-center">
                <h2 class="font-display text-2xl font-extrabold md:text-3xl">{{ t('country_page.cta_title') }}</h2>
                <p class="mt-3 text-white/80">{{ t('country_page.cta_desc') }}</p>
                <Link
                    :href="`/apply?country=${country.slug}`"
                    class="mt-8 inline-block rounded-full bg-brass px-8 py-3 font-display text-sm font-bold text-ink hover:bg-brass-light"
                >
                    {{ t('country_page.cta_button') }}
                </Link>
            </div>
        </section>
    </AppLayout>
</template>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.15s ease; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
