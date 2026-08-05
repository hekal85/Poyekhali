<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';

interface DocDraft {
    text_ar: string;
    text_en: string;
}

interface VisaTypeDraft {
    key: string;
    name_ar: string;
    name_en: string;
    fee: number | null;
    documents: DocDraft[];
}

interface CountryDraft {
    id?: number;
    slug: string;
    flag: string;
    name_ar: string;
    name_en: string;
    region: 'gulf' | 'other';
    processing_time_ar: string;
    processing_time_en: string;
    order: number;
    is_active: boolean;
    image_url?: string | null;
    visa_types: VisaTypeDraft[];
}

const props = defineProps<{
    initial?: Partial<CountryDraft>;
    submitUrl: string;
    submitMethod: 'post' | 'put';
}>();

const form = useForm({
    slug: props.initial?.slug ?? '',
    flag: props.initial?.flag ?? '',
    name_ar: props.initial?.name_ar ?? '',
    name_en: props.initial?.name_en ?? '',
    region: props.initial?.region ?? 'gulf',
    processing_time_ar: props.initial?.processing_time_ar ?? '',
    processing_time_en: props.initial?.processing_time_en ?? '',
    order: props.initial?.order ?? 0,
    is_active: props.initial?.is_active ?? true,
    image: null as File | null,
    visa_types: (props.initial?.visa_types?.length
        ? props.initial.visa_types
        : [{ key: 'work', name_ar: '', name_en: '', fee: null, documents: [] }]) as VisaTypeDraft[],
});

function onImageChange(e: Event) {
    const target = e.target as HTMLInputElement;
    form.image = target.files?.[0] ?? null;
}

function addVisaType() {
    form.visa_types.push({ key: '', name_ar: '', name_en: '', fee: null, documents: [] });
}

function removeVisaType(i: number) {
    form.visa_types.splice(i, 1);
}

function addDocument(vtIndex: number) {
    form.visa_types[vtIndex].documents.push({ text_ar: '', text_en: '' });
}

function removeDocument(vtIndex: number, docIndex: number) {
    form.visa_types[vtIndex].documents.splice(docIndex, 1);
}

function submit() {
    if (props.submitMethod === 'put') {
        // Laravel محتاج POST + _method=PUT لما بنبعت ملف (multipart)
        form.transform((data) => ({ ...data, _method: 'put' })).post(props.submitUrl, { forceFormData: true });
    } else {
        form.post(props.submitUrl, { forceFormData: true });
    }
}

defineExpose({ form });
</script>

<template>
    <form class="space-y-8" @submit.prevent="submit">
        <!-- بيانات أساسية -->
        <div class="rounded-2xl bg-white p-6 shadow-sm shadow-ink/5">
            <h2 class="font-display text-lg font-bold text-ink">بيانات الدولة</h2>

            <div class="mt-4 grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="text-sm font-medium text-ink/70">الاسم بالعربي</label>
                    <input v-model="form.name_ar" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" />
                    <p v-if="form.errors.name_ar" class="mt-1 text-xs text-alert">{{ form.errors.name_ar }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-ink/70">الاسم بالإنجليزي</label>
                    <input v-model="form.name_en" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" />
                    <p v-if="form.errors.name_en" class="mt-1 text-xs text-alert">{{ form.errors.name_en }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-ink/70">Slug (يظهر في الرابط، بالإنجليزي وبدون مسافات)</label>
                    <input v-model="form.slug" placeholder="saudi-arabia" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" dir="ltr" />
                    <p v-if="form.errors.slug" class="mt-1 text-xs text-alert">{{ form.errors.slug }}</p>
                </div>
                <div>
                    <label class="text-sm font-medium text-ink/70">كود العلم (حرفين، مثال: sa)</label>
                    <input v-model="form.flag" maxlength="2" placeholder="sa" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" dir="ltr" />
                    <p v-if="form.errors.flag" class="mt-1 text-xs text-alert">{{ form.errors.flag }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-ink/70">المنطقة</label>
                    <select v-model="form.region" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal">
                        <option value="gulf">دول الخليج</option>
                        <option value="other">دول أخرى</option>
                    </select>
                </div>
                <div class="flex items-end pb-2.5">
                    <label class="flex items-center gap-2 text-sm text-ink/70">
                        <input v-model="form.is_active" type="checkbox" class="rounded border-paper-dark" />
                        الدولة ظاهرة على الموقع
                    </label>
                </div>

                <div>
                    <label class="text-sm font-medium text-ink/70">مدة الإجراءات (عربي)</label>
                    <input v-model="form.processing_time_ar" placeholder="7 - 14 يوم عمل" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" />
                </div>
                <div>
                    <label class="text-sm font-medium text-ink/70">مدة الإجراءات (إنجليزي)</label>
                    <input v-model="form.processing_time_en" placeholder="7 - 14 business days" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" dir="ltr" />
                </div>
            </div>

            <div class="mt-4">
                <label class="text-sm font-medium text-ink/70">صورة بانر الدولة (اختياري)</label>
                <input type="file" accept="image/*" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 text-sm outline-none focus:border-teal" @change="onImageChange" />
                <img v-if="initial?.image_url" :src="initial.image_url" class="mt-3 h-32 w-full rounded-lg object-cover" alt="" />
            </div>
        </div>

        <!-- أنواع التأشيرات -->
        <div class="rounded-2xl bg-white p-6 shadow-sm shadow-ink/5">
            <div class="flex items-center justify-between">
                <h2 class="font-display text-lg font-bold text-ink">أنواع التأشيرات ومستنداتها</h2>
                <button type="button" class="text-sm font-bold text-teal hover:underline" @click="addVisaType">+ إضافة نوع تأشيرة</button>
            </div>

            <div v-for="(vt, vtIndex) in form.visa_types" :key="vtIndex" class="mt-5 rounded-xl border border-paper-dark p-4">
                <div class="flex items-center justify-between">
                    <p class="font-display text-sm font-bold text-teal">نوع تأشيرة #{{ vtIndex + 1 }}</p>
                    <button type="button" class="text-xs font-bold text-alert hover:underline" @click="removeVisaType(vtIndex)">حذف النوع</button>
                </div>

                <div class="mt-3 grid gap-3 sm:grid-cols-2">
                    <input v-model="vt.key" placeholder="key (مثال: work)" class="rounded-lg border border-paper-dark px-3 py-2 text-sm outline-none focus:border-teal" dir="ltr" />
                    <input v-model.number="vt.fee" type="number" placeholder="الرسوم بالجنيه" class="rounded-lg border border-paper-dark px-3 py-2 text-sm outline-none focus:border-teal" />
                    <input v-model="vt.name_ar" placeholder="اسم النوع بالعربي" class="rounded-lg border border-paper-dark px-3 py-2 text-sm outline-none focus:border-teal" />
                    <input v-model="vt.name_en" placeholder="Name in English" class="rounded-lg border border-paper-dark px-3 py-2 text-sm outline-none focus:border-teal" dir="ltr" />
                </div>

                <!-- مستندات النوع ده تحديدًا -->
                <div class="mt-4 rounded-lg bg-paper p-3">
                    <div class="flex items-center justify-between">
                        <p class="text-xs font-bold text-ink/60">المستندات المطلوبة لنوع التأشيرة ده</p>
                        <button type="button" class="text-xs font-bold text-teal hover:underline" @click="addDocument(vtIndex)">+ إضافة مستند</button>
                    </div>

                    <div v-for="(doc, docIndex) in vt.documents" :key="docIndex" class="mt-2 flex gap-2">
                        <input v-model="doc.text_ar" placeholder="نص المستند بالعربي" class="flex-1 rounded-lg border border-paper-dark px-3 py-2 text-sm outline-none focus:border-teal" />
                        <input v-model="doc.text_en" placeholder="Document text in English" class="flex-1 rounded-lg border border-paper-dark px-3 py-2 text-sm outline-none focus:border-teal" dir="ltr" />
                        <button type="button" class="px-2 text-alert" @click="removeDocument(vtIndex, docIndex)">✕</button>
                    </div>
                    <p v-if="!vt.documents.length" class="mt-2 text-xs text-ink/30">مفيش مستندات مضافة لسه</p>
                </div>
            </div>

            <p v-if="!form.visa_types.length" class="mt-4 text-sm text-ink/40">لازم تضيف نوع تأشيرة واحد على الأقل</p>
        </div>

        <div class="flex justify-end gap-3">
            <a href="/admin/countries" class="rounded-xl border border-paper-dark px-6 py-3 font-display text-sm font-bold text-ink/70 hover:bg-white">إلغاء</a>
            <button
                type="submit"
                :disabled="form.processing"
                class="rounded-xl bg-teal px-8 py-3 font-display text-sm font-bold text-white hover:bg-teal-light disabled:opacity-50"
            >
                {{ form.processing ? 'جاري الحفظ...' : 'حفظ' }}
            </button>
        </div>
    </form>
</template>
