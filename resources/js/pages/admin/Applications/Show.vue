<script setup lang="ts">
import { computed } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

interface Doc {
    id: number;
    document_type: string;
    path: string;
    original_name: string;
}

interface AppDetail {
    id: number;
    order_number: string;
    name: string;
    passport_number: string;
    phone: string;
    email: string | null;
    address: string | null;
    notes: string | null;
    status: string;
    payment_receipt_path: string | null;
    country: { name_ar: string; name_en: string };
    visa_type: { name_ar: string; name_en: string };
    documents: Doc[];
    created_at: string;
}

const props = defineProps<{ application: AppDetail; statuses: string[] }>();

const statusLabels: Record<string, string> = {
    under_review: 'تحت الدراسة',
    approved_processing: 'تم قبول الطلب وجارٍ العمل على استخراج التأشيرة',
    visa_ready: 'التأشيرة جاهزة',
    visa_cancelled: 'تأشيرة ملغاة',
    deleted: 'طلب محذوف',
    other: 'حالة أخرى',
};

const docTypeLabels: Record<string, string> = {
    passport: 'جواز سفر',
    degree: 'شهادة تخرج',
    personal_photo: 'صورة شخصية',
    travel_ticket: 'تذكرة سفر',
    medical_certificate: 'شهادة طبية',
    hotel_booking: 'حجز فندق',
    other: 'أخرى',
};

const statusForm = useForm({ status: props.application.status });

function updateStatus() {
    statusForm.put(`/admin/applications/${props.application.id}/status`, { preserveScroll: true });
}

function isImagePath(name: string) {
    return /\.(jpe?g|png|gif|webp)$/i.test(name);
}

const hasReceipt = computed(() => !!props.application.payment_receipt_path);
</script>

<template>
    <Head :title="`طلب ${application.order_number}`" />

    <AdminLayout>
        <Link href="/admin/applications" class="text-sm text-ink/50 hover:text-teal">← رجوع لكل الطلبات</Link>

        <div class="mt-4 grid gap-6 lg:grid-cols-3">
            <!-- بيانات الطلب -->
            <div class="lg:col-span-2 rounded-2xl bg-white p-6 shadow-sm shadow-ink/5">
                <div class="flex items-start justify-between">
                    <div>
                        <h1 class="font-display text-xl font-bold text-ink" dir="ltr">{{ application.order_number }}</h1>
                        <p class="mt-1 text-sm text-ink/50">{{ application.name }} — <span dir="ltr">{{ application.passport_number }}</span></p>
                    </div>
                </div>

                <dl class="mt-6 grid gap-4 text-sm sm:grid-cols-2">
                    <div>
                        <dt class="font-medium text-ink/50">الدولة</dt>
                        <dd class="mt-1 text-ink">{{ application.country.name_ar }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-ink/50">نوع التأشيرة</dt>
                        <dd class="mt-1 text-ink">{{ application.visa_type.name_ar }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-ink/50">الهاتف</dt>
                        <dd class="mt-1 text-ink" dir="ltr">{{ application.phone }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-ink/50">الإيميل</dt>
                        <dd class="mt-1 text-ink" dir="ltr">{{ application.email ?? '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="font-medium text-ink/50">العنوان</dt>
                        <dd class="mt-1 text-ink">{{ application.address ?? '—' }}</dd>
                    </div>
                    <div class="sm:col-span-2">
                        <dt class="font-medium text-ink/50">ملاحظات</dt>
                        <dd class="mt-1 whitespace-pre-line text-ink">{{ application.notes ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-ink/50">تاريخ الطلب</dt>
                        <dd class="mt-1 text-ink">{{ new Date(application.created_at).toLocaleString('ar-EG') }}</dd>
                    </div>
                </dl>

                <!-- إيصال الدفع -->
                <div v-if="hasReceipt" class="mt-6 border-t border-paper-dark pt-6">
                    <h2 class="font-display text-sm font-bold text-ink">إيصال الدفع</h2>
                    <div class="mt-3 max-w-xs overflow-hidden rounded-xl border border-paper-dark">
                        <img :src="`/admin/applications/${application.id}/receipt/view`" alt="إيصال الدفع" class="w-full object-cover" />
                    </div>
                    <a :href="`/admin/applications/${application.id}/receipt/download`" class="mt-2 inline-block text-xs font-bold text-teal hover:underline">تحميل الإيصال</a>
                </div>

                <!-- المستندات -->
                <div class="mt-6 border-t border-paper-dark pt-6">
                    <h2 class="font-display text-sm font-bold text-ink">المستندات المرفقة ({{ application.documents.length }})</h2>
                    <p v-if="!application.documents.length" class="mt-2 text-sm text-ink/40">مفيش مستندات مرفوعة</p>

                    <div v-else class="mt-4 grid gap-4 sm:grid-cols-2">
                        <div v-for="doc in application.documents" :key="doc.id" class="overflow-hidden rounded-xl border border-paper-dark">
                            <img
                                v-if="isImagePath(doc.original_name)"
                                :src="`/admin/applications/documents/${doc.id}/view`"
                                :alt="doc.original_name"
                                class="h-40 w-full object-cover"
                            />
                            <div v-else class="flex h-40 items-center justify-center bg-paper text-xs text-ink/40">PDF</div>
                            <div class="flex items-center justify-between p-3">
                                <span class="truncate text-xs text-ink/60">{{ docTypeLabels[doc.document_type] ?? doc.document_type }}</span>
                                <a :href="`/admin/applications/documents/${doc.id}/download`" class="shrink-0 rounded-lg bg-teal/10 px-2.5 py-1 text-xs font-bold text-teal hover:bg-teal/20">تحميل</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- تغيير الحالة -->
            <div class="rounded-2xl bg-white p-6 shadow-sm shadow-ink/5">
                <h2 class="font-display text-sm font-bold text-ink">حالة الطلب</h2>
                <form class="mt-4 space-y-3" @submit.prevent="updateStatus">
                    <select v-model="statusForm.status" class="w-full rounded-lg border border-paper-dark px-4 py-2.5 text-sm outline-none focus:border-teal">
                        <option v-for="s in statuses" :key="s" :value="s">{{ statusLabels[s] ?? s }}</option>
                    </select>
                    <button
                        type="submit"
                        :disabled="statusForm.processing"
                        class="w-full rounded-xl bg-teal py-2.5 font-display text-sm font-bold text-white hover:bg-teal-light disabled:opacity-50"
                    >
                        حفظ الحالة
                    </button>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
