<script setup lang="ts">
import { reactive, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

interface Application {
    id: number;
    order_number: string;
    name: string;
    passport_number: string;
    phone: string;
    status: string;
    country: { name_ar: string; name_en: string };
    visa_type: { name_ar: string; name_en: string };
    created_at: string;
}

const props = defineProps<{
    applications: { data: Application[]; links: { url: string | null; label: string; active: boolean }[] };
    filters: { q?: string; status?: string; country_id?: string; visa_type_key?: string };
    statuses: string[];
    countries: { id: number; name_ar: string }[];
    visaTypesList: { key: string; name_ar: string }[];
}>();

const statusLabels: Record<string, string> = {
    under_review: 'تحت الدراسة',
    approved_processing: 'جارٍ الاستخراج',
    visa_ready: 'التأشيرة جاهزة',
    visa_cancelled: 'ملغاة',
    deleted: 'محذوف',
    other: 'أخرى',
};

const statusStyles: Record<string, string> = {
    under_review: 'bg-brass/15 text-brass',
    approved_processing: 'bg-teal/15 text-teal',
    visa_ready: 'bg-teal text-white',
    visa_cancelled: 'bg-alert/15 text-alert',
    deleted: 'bg-ink/10 text-ink/50',
    other: 'bg-ink/10 text-ink/60',
};

const filters = reactive({
    q: props.filters.q ?? '',
    status: props.filters.status ?? '',
    country_id: props.filters.country_id ?? '',
    visa_type_key: props.filters.visa_type_key ?? '',
});

let debounceTimer: ReturnType<typeof setTimeout>;
function applyFilters() {
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => {
        router.get('/admin/applications', filters, { preserveState: true, replace: true });
    }, 300);
}

watch(filters, applyFilters);

function resetFilters() {
    filters.q = '';
    filters.status = '';
    filters.country_id = '';
    filters.visa_type_key = '';
}
</script>

<template>
    <Head title="طلبات التأشيرات" />

    <AdminLayout>
        <h1 class="font-display text-2xl font-extrabold text-ink">طلبات التأشيرات</h1>

        <!-- البحث والفلاتر -->
        <div class="mt-5 flex flex-wrap items-end gap-3 rounded-2xl bg-white p-4 shadow-sm shadow-ink/5">
            <div class="flex-1 min-w-[220px]">
                <label class="text-xs font-medium text-ink/50">بحث برقم الطلب / جواز السفر / الاسم</label>
                <input v-model="filters.q" placeholder="PYK-000123 أو رقم الجواز أو الاسم" class="mt-1 w-full rounded-lg border border-paper-dark px-3 py-2 text-sm outline-none focus:border-teal" />
            </div>
            <div>
                <label class="text-xs font-medium text-ink/50">الحالة</label>
                <select v-model="filters.status" class="mt-1 rounded-lg border border-paper-dark px-3 py-2 text-sm outline-none focus:border-teal">
                    <option value="">كل الحالات</option>
                    <option v-for="s in statuses" :key="s" :value="s">{{ statusLabels[s] ?? s }}</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-medium text-ink/50">الدولة</label>
                <select v-model="filters.country_id" class="mt-1 rounded-lg border border-paper-dark px-3 py-2 text-sm outline-none focus:border-teal">
                    <option value="">كل الدول</option>
                    <option v-for="c in countries" :key="c.id" :value="c.id">{{ c.name_ar }}</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-medium text-ink/50">نوع التأشيرة</label>
                <select v-model="filters.visa_type_key" class="mt-1 rounded-lg border border-paper-dark px-3 py-2 text-sm outline-none focus:border-teal">
                    <option value="">كل الأنواع</option>
                    <option v-for="v in visaTypesList" :key="v.key" :value="v.key">{{ v.name_ar }}</option>
                </select>
            </div>
            <button type="button" class="rounded-lg border border-paper-dark px-4 py-2 text-sm text-ink/60 hover:bg-paper" @click="resetFilters">
                مسح الفلاتر
            </button>
        </div>

        <div class="mt-4 overflow-hidden rounded-2xl bg-white shadow-sm shadow-ink/5">
            <table class="w-full text-sm">
                <thead class="bg-paper text-xs text-ink/50">
                    <tr>
                        <th class="px-5 py-3 text-start font-medium">رقم الطلب</th>
                        <th class="px-5 py-3 text-start font-medium">الاسم</th>
                        <th class="px-5 py-3 text-start font-medium">الدولة / نوع التأشيرة</th>
                        <th class="px-5 py-3 text-start font-medium">الحالة</th>
                        <th class="px-5 py-3 text-start font-medium">التاريخ</th>
                        <th class="px-5 py-3"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-paper-dark">
                    <tr v-for="a in applications.data" :key="a.id">
                        <td class="px-5 py-3 font-bold text-ink" dir="ltr">{{ a.order_number }}</td>
                        <td class="px-5 py-3 text-ink">
                            {{ a.name }}
                            <div class="text-xs text-ink/40" dir="ltr">{{ a.passport_number }}</div>
                        </td>
                        <td class="px-5 py-3 text-ink/60">{{ a.country.name_ar }} — {{ a.visa_type.name_ar }}</td>
                        <td class="px-5 py-3">
                            <span class="rounded-full px-2.5 py-1 text-xs font-bold" :class="statusStyles[a.status] ?? statusStyles.other">
                                {{ statusLabels[a.status] ?? a.status }}
                            </span>
                        </td>
                        <td class="px-5 py-3 text-ink/40">{{ new Date(a.created_at).toLocaleDateString('ar-EG') }}</td>
                        <td class="px-5 py-3 text-end">
                            <Link :href="`/admin/applications/${a.id}`" class="font-medium text-teal hover:underline">عرض</Link>
                        </td>
                    </tr>
                    <tr v-if="!applications.data.length">
                        <td colspan="6" class="px-5 py-10 text-center text-ink/40">مفيش طلبات مطابقة للفلتر ده</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex flex-wrap gap-2">
            <Link
                v-for="(link, i) in applications.links"
                :key="i"
                :href="link.url ?? ''"
                v-html="link.label"
                class="rounded-lg px-3 py-1.5 text-sm"
                :class="link.active ? 'bg-teal text-white' : 'bg-white text-ink/60 hover:bg-paper-dark'"
            />
        </div>
    </AdminLayout>
</template>
