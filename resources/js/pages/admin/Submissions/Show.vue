<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';

interface Attachment {
    id: number;
    path: string;
    original_name: string;
    mime_type: string | null;
}

interface Submission {
    id: number;
    name: string;
    phone: string;
    email: string | null;
    country_interest: string | null;
    message: string | null;
    attachments: Attachment[];
    created_at: string;
}

const props = defineProps<{ submission: Submission }>();

function isImage(mime: string | null): boolean {
    return !!mime && mime.startsWith('image/');
}

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
                    <p class="mt-1 text-sm text-ink/50" dir="ltr">{{ submission.phone }} <span v-if="submission.email">· {{ submission.email }}</span></p>
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
                    <dt class="font-medium text-ink/50">تاريخ الإرسال</dt>
                    <dd class="mt-1 text-ink">{{ new Date(submission.created_at).toLocaleString('ar-EG') }}</dd>
                </div>
            </dl>

            <!-- المرفقات: الصور تظهر مباشرة، باقي الملفات رابط تحميل -->
            <div class="mt-6 border-t border-paper-dark pt-6">
                <h2 class="font-display text-sm font-bold text-ink">المرفقات ({{ submission.attachments.length }})</h2>

                <p v-if="!submission.attachments.length" class="mt-2 text-sm text-ink/40">مفيش مرفقات</p>

                <div v-else class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div v-for="att in submission.attachments" :key="att.id" class="overflow-hidden rounded-xl border border-paper-dark">
                        <img
                            v-if="isImage(att.mime_type)"
                            :src="`/admin/submissions/attachments/${att.id}/view`"
                            :alt="att.original_name"
                            class="h-48 w-full object-cover"
                        />
                        <div v-else class="flex h-48 items-center justify-center bg-paper">
                            <svg class="h-10 w-10 text-ink/20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M7 3h7l5 5v13a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1Z" />
                                <path d="M14 3v5h5" />
                            </svg>
                        </div>
                        <div class="flex items-center justify-between p-3">
                            <span class="truncate text-xs text-ink/60">{{ att.original_name }}</span>
                            <a
                                :href="`/admin/submissions/attachments/${att.id}/download`"
                                class="shrink-0 rounded-lg bg-teal/10 px-2.5 py-1 text-xs font-bold text-teal hover:bg-teal/20"
                            >
                                تحميل
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>
