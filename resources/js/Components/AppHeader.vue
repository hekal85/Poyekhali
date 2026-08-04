<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import LanguageSwitcher from './LanguageSwitcher.vue';

const { t } = useI18n();
const mobileOpen = ref(false);

const links = [
    { href: '/', key: 'home' },
    { href: '/countries', key: 'countries' },
    { href: '/#process', key: 'process' },
    { href: '/contact', key: 'contact' },
];
</script>

<template>
    <header class="sticky top-0 z-50 bg-ink text-white">
        <!-- شريط علوي: هاتف + واتساب -->
        <div class="hidden border-b border-white/10 md:block">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-2 text-xs text-white/70">
                <span>+20 100 000 0000 · info@poyekhali.com</span>
                <span>{{ t('contact.hours_value') }}</span>
            </div>
        </div>

        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <Link href="/" class="flex items-center gap-2">
                <span class="font-display text-2xl font-extrabold tracking-tight">
                    بيخ<span class="text-brass">الي</span>
                </span>
            </Link>

            <nav class="hidden items-center gap-8 lg:flex">
                <a
                    v-for="link in links"
                    :key="link.key"
                    :href="link.href"
                    class="nav-link font-display text-sm font-medium text-white/85 hover:text-white"
                >
                    {{ t(`nav.${link.key}`) }}
                </a>
            </nav>

            <div class="hidden items-center gap-4 lg:flex">
                <LanguageSwitcher />
                <Link
                    href="/contact"
                    class="rounded-full bg-brass px-5 py-2 font-display text-sm font-bold text-ink transition hover:bg-brass-light"
                >
                    {{ t('nav.apply') }}
                </Link>
            </div>

            <button class="lg:hidden" @click="mobileOpen = !mobileOpen" aria-label="menu">
                <svg class="h-7 w-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                    <path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16" />
                </svg>
            </button>
        </div>

        <div v-if="mobileOpen" class="border-t border-white/10 px-6 py-4 lg:hidden">
            <a
                v-for="link in links"
                :key="link.key"
                :href="link.href"
                class="block py-2 font-display text-sm font-medium text-white/85"
                @click="mobileOpen = false"
            >
                {{ t(`nav.${link.key}`) }}
            </a>
            <div class="mt-3 flex items-center justify-between">
                <LanguageSwitcher />
                <Link href="/contact" class="rounded-full bg-brass px-5 py-2 font-display text-sm font-bold text-ink">
                    {{ t('nav.apply') }}
                </Link>
            </div>
        </div>
    </header>
</template>
