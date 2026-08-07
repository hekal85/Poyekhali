<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLayout from '../Layouts/AppLayout.vue';
import VisaStamp from '../Components/VisaStamp.vue';
import ProcessSteps from '../Components/ProcessSteps.vue';
import CountryCard from '../Components/CountryCard.vue';
import type { Country } from '../types/country';

const props = defineProps<{
    countries: Country[];
    stats: { countries: number; applications: number; clients: number; years: number };
}>();
const { t, locale } = useI18n();

const selectedSlug = ref('');

function goToCountry() {
    if (selectedSlug.value) {
        router.visit(`/countries/${selectedSlug.value}`);
    }
}

const trustItems = ['licensed', 'transparent', 'support', 'fast'] as const;

const prefix = '0';
const suffix = '.jpg';
const fallbackSlide = 'https://cdn2.picryl.com/photo/2011/12/19/expedition-30-soyuz-rollout-dd28e3-1024.jpg';

const heroSlides = Array.from({ length: 34 }, (_, i) =>
  `${prefix}${i + 1 < 10 ? '0' : ''}${i + 1}${suffix}`
);

const randomSlides = ref([...heroSlides]);
const activeSlide = ref(0);
let slideTimer: ReturnType<typeof setInterval> | undefined;

onMounted(() => {
    shuffleSlides();

    slideTimer = setInterval(() => {
        activeSlide.value = (activeSlide.value + 1) % randomSlides.value.length;
    }, 5000);
});
onUnmounted(() => {
    if (slideTimer) clearInterval(slideTimer);
});

// دالة عشوائية
function shuffleSlides() {
    randomSlides.value = [...heroSlides].sort(() => Math.random() - 0.5);
    activeSlide.value = 0; // نرجع لأول صورة جديدة بعد التجول
}
</script>

<template>
    <Head :title="t('hero.title')" />

    <AppLayout>
        <!-- Hero -->
        <section class="relative overflow-hidden bg-ink text-white">
            <div
                v-for="(src, i) in heroSlides"
                :key="i"
                class="hero-slide"
                :class="{ 'hero-slide--active': i === activeSlide }"
                :style="{ backgroundImage: `url(${src})` }"
            ></div>

            <div
                class="absolute inset-0 bg-gradient-to-b from-ink/55 via-ink/35 to-ink/80"
            ></div>

            <div
                class="relative mx-auto grid max-w-7xl items-center gap-12 px-6 py-20 md:grid-cols-2 md:py-28"
            >
                <div>
                    <span
                        class="font-display text-sm font-bold tracking-wide text-brass"
                    >
                        {{ t('hero.eyebrow') }}
                    </span>
                    <h1
                        class="mt-4 font-display text-4xl leading-tight font-extrabold md:text-5xl"
                    >
                        {{ t('hero.title') }}
                    </h1>
                    <p class="mt-5 max-w-lg text-white/70">
                        {{ t('hero.subtitle') }}
                    </p>

                    <form
                        class="mt-8 flex flex-col gap-3 sm:flex-row"
                        @submit.prevent="goToCountry"
                    >
                        <label class="sr-only">{{
                            t('hero.select_label')
                        }}</label>
                        <select
                            v-model="selectedSlug"
                            class="w-full rounded-xl border border-white/15 bg-white/5 px-4 py-3 text-sm text-white outline-none focus:border-brass sm:w-64"
                        >
                            <option value="" disabled selected class="text-ink">
                                {{ t('hero.select_placeholder') }}
                            </option>
                            <option
                                v-for="c in props.countries"
                                :key="c.slug"
                                :value="c.slug"
                                class="text-ink"
                            >
                                {{ locale === 'ar' ? c.name.ar : c.name.en }}
                            </option>
                        </select>
                        <button
                            type="submit"
                            class="rounded-xl bg-brass px-6 py-3 font-display text-sm font-bold text-ink transition hover:bg-brass-light"
                        >
                            {{ t('hero.cta_check') }}
                        </button>
                    </form>

                    <div
                        class="mt-10 flex flex-wrap gap-8 border-t border-white/10 pt-8"
                    >
                        <div>
                            <p
                                class="font-display text-2xl font-extrabold text-brass"
                            >
                                {{ props.stats.countries }}+
                            </p>
                            <p class="text-xs text-white/60">
                                {{ t('hero.stat_countries') }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="font-display text-2xl font-extrabold text-brass"
                            >
                                {{ props.stats.applications }}+
                            </p>
                            <p class="text-xs text-white/60">
                                {{ t('hero.stat_applications') }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="font-display text-2xl font-extrabold text-brass"
                            >
                                {{ props.stats.clients }}+
                            </p>
                            <p class="text-xs text-white/60">
                                {{ t('hero.stat_clients') }}
                            </p>
                        </div>
                        <div>
                            <p
                                class="font-display text-2xl font-extrabold text-brass"
                            >
                                {{ props.stats.years }}
                            </p>
                            <p class="text-xs text-white/60">
                                {{ t('hero.stat_years') }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-center md:justify-end">
                    <VisaStamp :size="280" />
                </div>
            </div>
        </section>

        <!-- Trust -->
        <section class="bg-paper py-16">
            <div class="mx-auto max-w-7xl px-6">
                <h2
                    class="font-display text-2xl font-extrabold text-ink md:text-3xl"
                >
                    {{ t('trust.title') }}
                </h2>
                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div
                        v-for="item in trustItems"
                        :key="item"
                        class="rounded-2xl bg-white p-6 shadow-sm shadow-ink/5"
                    >
                        <h3 class="font-display text-base font-bold text-teal">
                            {{ t(`trust.${item}.title`) }}
                        </h3>
                        <p class="mt-2 text-sm leading-relaxed text-ink/60">
                            {{ t(`trust.${item}.desc`) }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <ProcessSteps />

        <!-- Countries preview -->
        <section class="bg-paper py-20">
            <div class="mx-auto max-w-7xl px-6">
                <div class="flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <h2
                            class="font-display text-3xl font-extrabold text-ink md:text-4xl"
                        >
                            {{ t('countries.title') }}
                        </h2>
                        <p class="mt-3 text-ink/60">
                            {{ t('countries.subtitle') }}
                        </p>
                    </div>
                    <Link
                        href="/countries"
                        class="font-display text-sm font-bold text-teal hover:underline"
                    >
                        {{ t('countries.view_all') }}
                    </Link>
                </div>

                <div class="mt-10 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <CountryCard
                        v-for="c in props.countries.slice(0, 4)"
                        :key="c.slug"
                        :country="c"
                    />
                </div>
            </div>
        </section>
    </AppLayout>
</template>
