<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import { flagEmoji } from '../../../types/country';

interface AdminCountry {
    id: number;
    slug: string;
    flag: string;
    name_ar: string;
    name_en: string;
    region: 'gulf' | 'other';
    is_active: boolean;
    visa_types_count: number;
}

defineProps<{ countries: AdminCountry[] }>();

function destroy(id: number, name: string) {
    if (confirm(`متأكد إنك عايز تمسح "${name}"؟ هيتمسح معاها كل أنواع التأشيرات والمستندات بتاعتها.`)) {
        router.delete(`/admin/countries/${id}`);
    }
}
</script>

<template>
    <Head title="الدول والتأشيرات" />

    <AdminLayout>
        <div class="flex items-center justify-between">
            <h1 class="font-display text-2xl font-extrabold text-ink">الدول والتأشيرات</h1>
            <Link
                href="/admin/countries/create"
                class="rounded-xl bg-teal px-5 py-2.5 font-display text-sm font-bold text-white hover:bg-teal-light"
            >
                + إضافة دولة
            </Link>
        </div>

        <div class="mt-6 overflow-hidden rounded-2xl bg-white shadow-sm shadow-ink/5">
            <table class="w-full text-sm">
                <thead class="bg-paper text-start text-xs text-ink/50">
                    <tr>
                        <th class="px-5 py-3 text-start font-medium">الدولة</th>
                        <th class="px-5 py-3 text-start font-medium">المنطقة</th>
                        <th class="px-5 py-3 text-start font-medium">عدد أنواع التأشيرة</th>
                        <th class="px-5 py-3 text-start font-medium">الحالة</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-paper-dark">
                    <tr v-for="c in countries" :key="c.id">
                        <td class="px-5 py-3">
                            <span class="me-2">{{ flagEmoji(c.flag) }}</span>
                            {{ c.name_ar }} / {{ c.name_en }}
                        </td>
                        <td class="px-5 py-3 text-ink/60">{{ c.region === 'gulf' ? 'خليج' : 'أخرى' }}</td>
                        <td class="px-5 py-3 text-ink/60">{{ c.visa_types_count }}</td>
                        <td class="px-5 py-3">
                            <span
                                class="rounded-full px-2.5 py-1 text-xs font-bold"
                                :class="c.is_active ? 'bg-teal/10 text-teal' : 'bg-alert/10 text-alert'"
                            >
                                {{ c.is_active ? 'فعّالة' : 'موقوفة' }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-end">
                            <Link :href="`/admin/countries/${c.id}/edit`" class="font-medium text-teal hover:underline">تعديل</Link>
                            <button type="button" class="ms-4 font-medium text-alert hover:underline" @click="destroy(c.id, c.name_ar)">
                                حذف
                            </button>
                        </td>
                    </tr>
                    <tr v-if="!countries.length">
                        <td colspan="5" class="px-5 py-10 text-center text-ink/40">لسه مفيش دول مضافة</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </AdminLayout>
</template>
