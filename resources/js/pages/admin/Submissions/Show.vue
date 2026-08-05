<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

interface Submission {
    id: number;
    name: string;
    phone: string;
    country_interest: string | null;
    message: string | null;
    document_path: string | null;
    document_name: string | null;
    created_at: string;
}

const props = defineProps<{ submission: Submission }>();

function destroy() {
    if (confirm('متأكد إنك عايز تمسح الرسالة دي؟')) {
        router.delete(`/admin/submissions/${props.submission.id}`);
    }
}
</script>

<template>
    <Head :title="`رسالة من ${submission.name}`" />

    <AdminLayout>
        <Link href="/admin/submissions" class="text-sm text-ink/50 hover:text-teal">← رجوع لكل الرسائل</Link>

        <div class="mt-4 rounded-2xl bg-white p-6 shadow-sm shadow-ink/5">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="font-display text-xl font-bold text-ink">{{ submission.name }}</h1>
                    <p class="mt-1 text-sm text-ink/50" dir="ltr">{{ submission.phone }}</p>
                </div>
                <button type="button" class="text-sm font-bold text-alert hover:underline" @click="destroy">حذف الرسالة</button>
            </div>

            <dl class="mt-6 space-y-4 text-sm">
                <div>
                    <dt class="font-medium text-ink/50">الدولة المهتم بيها</dt>
                    <dd class="mt-1 text-ink">{{ submission.country_interest ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-ink/50">الرسالة</dt>
                    <dd class="mt-1 whitespace-pre-line text-ink">{{ submission.message ?? '—' }}</dd>
                </div>
                <div>
                    <dt class="font-medium text-ink/50">المستند المرفق</dt>
                    <dd class="mt-1">
                        <a
                            v-if="submission.document_path"
                            :href="`/storage/${submission.document_path}`"
                            target="_blank"
                            class="inline-flex items-center gap-1 rounded-lg bg-teal/10 px-3 py-1.5 text-teal hover:bg-teal/20"
                        >
                            تحميل: {{ submission.document_name }}
                        </a>
                        <span v-else class="text-ink/30">لا يوجد مستند</span>
                    </dd>
                </div>
                <div>
                    <dt class="font-medium text-ink/50">تاريخ الإرسال</dt>
                    <dd class="mt-1 text-ink">{{ new Date(submission.created_at).toLocaleString('ar-EG') }}</dd>
                </div>
            </dl>
        </div>
    </AdminLayout>
</template>
