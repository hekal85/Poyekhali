<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '../../Layouts/AdminLayout.vue';

interface Submission {
    id: number;
    name: string;
    phone: string;
    country_interest: string | null;
    read_at: string | null;
    created_at: string;
}

defineProps<{
    stats: { countries: number; submissions: number; unread: number; with_documents: number };
    latestSubmissions: Submission[];
}>();
</script>

<template>
    <Head title="لوحة التحكم" />

    <AdminLayout>
        <h1 class="font-display text-2xl font-extrabold text-ink">نظرة عامة</h1>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <div class="rounded-2xl bg-white p-5 shadow-sm shadow-ink/5">
                <p class="text-xs text-ink/50">عدد الدول</p>
                <p class="mt-2 font-display text-3xl font-extrabold text-ink">{{ stats.countries }}</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm shadow-ink/5">
                <p class="text-xs text-ink/50">كل الرسائل</p>
                <p class="mt-2 font-display text-3xl font-extrabold text-ink">{{ stats.submissions }}</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm shadow-ink/5">
                <p class="text-xs text-ink/50">لسه ما اتقرتش</p>
                <p class="mt-2 font-display text-3xl font-extrabold text-brass">{{ stats.unread }}</p>
            </div>
            <div class="rounded-2xl bg-white p-5 shadow-sm shadow-ink/5">
                <p class="text-xs text-ink/50">معاها مستندات مرفوعة</p>
                <p class="mt-2 font-display text-3xl font-extrabold text-teal">{{ stats.with_documents }}</p>
            </div>
        </div>

        <div class="mt-8 rounded-2xl bg-white p-6 shadow-sm shadow-ink/5">
            <div class="flex items-center justify-between">
                <h2 class="font-display text-lg font-bold text-ink">آخر الرسائل</h2>
                <Link href="/admin/submissions" class="text-sm font-bold text-teal hover:underline">كل الرسائل</Link>
            </div>

            <div class="mt-4 divide-y divide-paper-dark">
                <Link
                    v-for="s in latestSubmissions"
                    :key="s.id"
                    :href="`/admin/submissions/${s.id}`"
                    class="flex items-center justify-between py-3 text-sm hover:text-teal"
                >
                    <div>
                        <p class="font-medium text-ink">{{ s.name }} — {{ s.phone }}</p>
                        <p class="text-xs text-ink/40">{{ s.country_interest ?? '—' }}</p>
                    </div>
                    <span v-if="!s.read_at" class="rounded-full bg-brass/15 px-2.5 py-1 text-xs font-bold text-brass">جديدة</span>
                </Link>
                <p v-if="!latestSubmissions.length" class="py-6 text-center text-sm text-ink/40">لسه مفيش رسائل</p>
            </div>
        </div>
    </AdminLayout>
</template>
