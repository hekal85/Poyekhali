<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

interface Submission {
    id: number;
    name: string;
    phone: string;
    country_interest: string | null;
    document_path: string | null;
    read_at: string | null;
    created_at: string;
}

defineProps<{
    submissions: { data: Submission[]; links: { url: string | null; label: string; active: boolean }[] };
}>();
</script>

<template>
    <Head title="رسائل الزوار" />

    <AdminLayout>
        <h1 class="font-display text-2xl font-extrabold text-ink">رسائل الزوار</h1>

        <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm shadow-ink/5">
            <table class="w-full text-sm">
                <thead class="bg-paper text-xs text-ink/50">
                    <tr>
                        <th class="px-5 py-3 text-start font-medium">الاسم</th>
                        <th class="px-5 py-3 text-start font-medium">الموبايل</th>
                        <th class="px-5 py-3 text-start font-medium">الدولة المهتم بيها</th>
                        <th class="px-5 py-3 text-start font-medium">مستند مرفق</th>
                        <th class="px-5 py-3 text-start font-medium">التاريخ</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-paper-dark">
                    <tr
                        v-for="s in submissions.data"
                        :key="s.id"
                        :class="!s.read_at ? 'bg-brass/5' : ''"
                    >
                        <td class="px-5 py-3 font-medium text-ink">
                            <span v-if="!s.read_at" class="me-2 inline-block h-2 w-2 rounded-full bg-brass"></span>
                            {{ s.name }}
                        </td>
                        <td class="px-5 py-3 text-ink/60" dir="ltr">{{ s.phone }}</td>
                        <td class="px-5 py-3 text-ink/60">{{ s.country_interest ?? '—' }}</td>
                        <td class="px-5 py-3 text-ink/60">
                            <span v-if="s.document_path" class="rounded-full bg-teal/10 px-2 py-1 text-xs text-teal">مرفق</span>
                            <span v-else class="text-ink/30">—</span>
                        </td>
                        <td class="px-5 py-3 text-ink/40">{{ new Date(s.created_at).toLocaleDateString('ar-EG') }}</td>
                        <td class="px-5 py-3 text-end">
                            <Link :href="`/admin/submissions/${s.id}`" class="font-medium text-teal hover:underline">عرض</Link>
                        </td>
                    </tr>
                    <tr v-if="!submissions.data.length">
                        <td colspan="6" class="px-5 py-10 text-center text-ink/40">لسه مفيش رسائل</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
            <Link
                v-for="(link, i) in submissions.links"
                :key="i"
                :href="link.url ?? ''"
                v-html="link.label"
                class="rounded-lg px-3 py-1.5 text-sm"
                :class="link.active ? 'bg-teal text-white' : 'bg-white text-ink/60 hover:bg-paper-dark'"
            />
        </div>
    </AdminLayout>
</template>
