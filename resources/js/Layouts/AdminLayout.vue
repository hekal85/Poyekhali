<script setup lang="ts">
import { Link, router, usePage } from '@inertiajs/vue3';

const page = usePage<{ auth?: { user?: { name: string } } }>();

const links = [
    { href: '/admin', label: 'الرئيسية', icon: 'home' },
    { href: '/admin/countries', label: 'الدول والتأشيرات', icon: 'globe' },
    { href: '/admin/submissions', label: 'رسائل الزوار', icon: 'inbox' },
];

function logout() {
    router.post('/admin/logout');
}
</script>

<template>
    <div class="flex min-h-screen bg-paper" dir="rtl">
        <aside class="hidden w-64 shrink-0 flex-col bg-ink text-white md:flex">
            <div class="border-b border-white/10 px-6 py-5">
                <span class="font-display text-xl font-extrabold">
                    بيخ<span class="text-brass">الي</span>
                </span>
                <p class="mt-0.5 text-xs text-white/40">لوحة التحكم</p>
            </div>

            <nav class="flex-1 space-y-1 px-3 py-4">
                <Link
                    v-for="link in links"
                    :key="link.href"
                    :href="link.href"
                    class="block rounded-lg px-4 py-2.5 text-sm font-medium text-white/75 transition hover:bg-white/5 hover:text-white"
                >
                    {{ link.label }}
                </Link>
            </nav>

            <div class="border-t border-white/10 p-4">
                <p class="truncate px-2 text-xs text-white/40">{{ page.props.auth?.user?.name }}</p>
                <button
                    type="button"
                    class="mt-2 w-full rounded-lg bg-white/5 px-4 py-2 text-sm text-white/75 hover:bg-white/10"
                    @click="logout"
                >
                    تسجيل الخروج
                </button>
            </div>
        </aside>

        <main class="flex-1 p-6 md:p-10">
            <slot />
        </main>
    </div>
</template>
