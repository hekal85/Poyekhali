<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const destinations = [
    { slug: 'saudi-arabia', ar: 'السعودية', en: 'Saudi Arabia' },
    { slug: 'uae', ar: 'الإمارات', en: 'UAE' },
    { slug: 'qatar', ar: 'قطر', en: 'Qatar' },
    { slug: 'kuwait', ar: 'الكويت', en: 'Kuwait' },
];
</script>

<template>
    <footer class="bg-ink text-white">
        <div class="mx-auto grid max-w-7xl gap-10 px-6 py-14 md:grid-cols-4">
            <div class="md:col-span-2">
                <span class="font-display text-2xl font-extrabold">بيخ<span class="text-brass">الي</span></span>
                <p class="mt-4 max-w-md text-sm leading-relaxed text-white/70">{{ t('footer.about') }}</p>
            </div>

            <div>
                <h4 class="font-display text-sm font-bold text-brass">{{ t('footer.quick_links') }}</h4>
                <ul class="mt-4 space-y-2 text-sm text-white/70">
                    <li><Link href="/" class="hover:text-white">{{ t('nav.home') }}</Link></li>
                    <li><Link href="/countries" class="hover:text-white">{{ t('nav.countries') }}</Link></li>
                    <li><Link href="/contact" class="hover:text-white">{{ t('nav.contact') }}</Link></li>
                </ul>
            </div>

            <div>
                <h4 class="font-display text-sm font-bold text-brass">{{ t('footer.destinations') }}</h4>
                <ul class="mt-4 space-y-2 text-sm text-white/70">
                    <li v-for="d in destinations" :key="d.slug">
                        <Link :href="`/countries/${d.slug}`" class="hover:text-white">
                            {{ $i18n.locale === 'ar' ? d.ar : d.en }}
                        </Link>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10 px-6 py-5">
            <p class="mx-auto max-w-7xl text-xs leading-relaxed text-white/50">{{ t('footer.disclaimer') }}</p>
        </div>

        <div class="border-t border-white/10 px-6 py-4 text-center text-xs text-white/40">
            © {{ new Date().getFullYear() }} Poyekhali. {{ t('footer.rights') }}.
        </div>
    </footer>
</template>
