<script setup lang="ts">
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLayout from '../../Layouts/AppLayout.vue';
import CountryCard from '../../Components/CountryCard.vue';
import type { Country } from '../../types/country';

const props = defineProps<{ countries: Country[] }>();
const { t } = useI18n();

const gulf = computed(() => props.countries.filter((c) => c.region === 'gulf'));
const other = computed(() => props.countries.filter((c) => c.region === 'other'));
</script>

<template>
    <Head :title="t('countries.title')" />

    <AppLayout>
        <section class="bg-ink py-16 text-white">
            <div class="mx-auto max-w-7xl px-6">
                <h1 class="font-display text-3xl font-extrabold md:text-4xl">{{ t('countries.title') }}</h1>
                <p class="mt-3 max-w-xl text-white/70">{{ t('countries.subtitle') }}</p>
            </div>
        </section>

        <section class="py-16">
            <div class="mx-auto max-w-7xl px-6">
                <h2 class="font-display text-xl font-bold text-ink">{{ t('countries.gulf') }}</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <CountryCard v-for="c in gulf" :key="c.slug" :country="c" />
                </div>

                <h2 class="mt-16 font-display text-xl font-bold text-ink">{{ t('countries.other') }}</h2>
                <div class="mt-6 grid gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <CountryCard v-for="c in other" :key="c.slug" :country="c" />
                </div>
            </div>
        </section>
    </AppLayout>
</template>
