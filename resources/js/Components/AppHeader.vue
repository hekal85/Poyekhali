<script setup lang="ts">
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import LanguageSwitcher from './LanguageSwitcher.vue';
import UserMenu from './UserMenu.vue';
import NotificationBell from './NotificationBell.vue';

const { t } = useI18n();
const mobileOpen = ref(false);
const page = usePage<{ auth?: { user?: { name: string; is_admin: boolean } } }>();

const links = [
    { href: '/', key: 'home' },
    { href: '/countries', key: 'countries' },
    { href: '/track', key: 'track' },
    { href: '/contact', key: 'contact' },
];

// صورة مؤقتة لحد ما تستبدلها بصورة يوري جاجارين بتاعتك - رابط صورة NASA فعلي وتم التأكد
// إنه شغال (ملكية عامة CC0 - إطلاق مركبة سويوز ببايكونور، تصوير NASA/Carla Cioffi):
// المصدر: https://picryl.com/media/expedition-30-soyuz-rollout-dd28e3
const logoImage = 'https://cdn2.picryl.com/photo/2011/12/19/expedition-30-soyuz-rollout-dd28e3-1024.jpg';
</script>

<template>
    <header class="sticky top-0 z-50 bg-ink text-white">
        <div class="hidden border-b border-white/10 md:block">
            <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-2 text-xs text-white/70">
                <span dir="ltr">+7 993 644-58-81 · hekal_85@hotmail.com</span>
                <span>{{ t('contact.hours_value') }}</span>
            </div>
        </div>

        <div class="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">
            <Link href="/" class="flex items-center gap-3">
                <span class="relative h-11 w-11 shrink-0 overflow-hidden rounded-full ring-2 ring-brass/60">
                    <img :src="logoImage" alt="Poyekhali" class="h-full w-full object-cover" loading="lazy" />
                </span>
                <span class="font-logo text-2xl font-extrabold tracking-tight" dir="ltr">
                    Поехали<span class="text-brass">!</span>
                </span>
            </Link>

            <nav class="hidden items-center gap-7 lg:flex">
                <a
                    v-for="link in links"
                    :key="link.key"
                    :href="link.href"
                    class="nav-link font-display text-sm font-medium text-white/85 hover:text-white"
                >
                    {{ t(`nav.${link.key}`) }}
                </a>
            </nav>

            <div class="hidden items-center gap-3 lg:flex">
                <LanguageSwitcher />

                <template v-if="page.props.auth?.user">
                    <NotificationBell />
                    <UserMenu :user="page.props.auth.user" />
                </template>
                <template v-else>
                    <Link href="/login" class="font-display text-sm text-white/70 hover:text-white">{{ t('nav.login') }}</Link>
                    <Link href="/register" class="font-display text-sm text-white/70 hover:text-white">{{ t('nav.register') }}</Link>
                </template>

                <Link
                    href="/apply"
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
            <Link href="/login" v-if="!page.props.auth?.user" class="block py-2 font-display text-sm font-medium text-white/85">{{ t('nav.login') }}</Link>
            <Link href="/register" v-if="!page.props.auth?.user" class="block py-2 font-display text-sm font-medium text-white/85">{{ t('nav.register') }}</Link>

            <template v-if="page.props.auth?.user">
                <Link v-if="page.props.auth.user.is_admin" href="/admin" class="block py-2 font-display text-sm font-medium text-white/85">{{ t('user_menu.admin_panel') }}</Link>
                <Link href="/dashboard" class="block py-2 font-display text-sm font-medium text-white/85">{{ t('user_menu.dashboard') }}</Link>
                <Link href="/logout" method="post" as="button" class="block py-2 font-display text-sm font-medium text-white/85">{{ t('user_menu.logout') }}</Link>
            </template>

            <div class="mt-3 flex items-center justify-between">
                <LanguageSwitcher />
                <Link href="/apply" class="rounded-full bg-brass px-5 py-2 font-display text-sm font-bold text-ink">
                    {{ t('nav.apply') }}
                </Link>
            </div>
        </div>
    </header>
</template>
