<script setup lang="ts">
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
const steps = ['step1', 'step2', 'step3', 'step4'];
</script>

<template>
    <section id="process" class="bg-white py-20">
        <div class="mx-auto max-w-7xl px-6">
            <div class="max-w-2xl">
                <h2 class="font-display text-3xl font-extrabold text-ink md:text-4xl">{{ t('process.title') }}</h2>
                <p class="mt-3 text-ink/60">{{ t('process.subtitle') }}</p>
            </div>

            <div class="relative mt-14 grid gap-10 md:grid-cols-4">
                <div class="absolute top-6 hidden h-px w-full bg-paper-dark md:block" aria-hidden="true"></div>

                <div v-for="(step, i) in steps" :key="step" class="relative">
                    <div class="relative z-10 flex h-12 w-12 items-center justify-center rounded-full border-2 border-teal bg-white font-display text-lg font-bold text-teal">
                        {{ i + 1 }}
                    </div>
                    <h3 class="mt-5 font-display text-lg font-bold text-ink">{{ t(`process.${step}.title`) }}</h3>
                    <p class="mt-2 text-sm leading-relaxed text-ink/60">{{ t(`process.${step}.desc`) }}</p>
                </div>
            </div>
        </div>
    </section>
</template>
