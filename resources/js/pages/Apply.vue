<script setup lang="ts">
import { computed, ref } from 'vue';
import { Head, Link, useForm, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLayout from '../Layouts/AppLayout.vue';
import SuccessModal from '../Components/SuccessModal.vue';
import type { Country } from '../types/country';

const props = defineProps<{ countries: Country[] }>();
const { t, locale } = useI18n();
const page = usePage<{ flash: { success?: { order_number: string } } }>();

const showSuccessModal = ref(false);
const lastOrderNumber = ref('');
const copied = ref(false);

function copyOrderNumber() {
    navigator.clipboard?.writeText(lastOrderNumber.value);
    copied.value = true;
    setTimeout(() => (copied.value = false), 2000);
}

const docTypeKeys = ['passport', 'degree', 'personal_photo', 'travel_ticket', 'medical_certificate', 'hotel_booking', 'other'] as const;

interface DocRow {
    type: string;
    file: File | null;
}

const form = useForm({
    name: '',
    passport_number: '',
    country_id: '' as number | '',
    visa_type_id: '' as number | '',
    address: '',
    phone: '',
    email: '',
    notes: '',
    payment_receipt: null as File | null,
    documents: [] as DocRow[],
});

const selectedCountry = computed(() => props.countries.find((c) => c.id === form.country_id));
const availableVisaTypes = computed(() => selectedCountry.value?.visa_types ?? []);

function onCountryChange() {
    form.visa_type_id = '';
}

// لو الرابط جاي من صفحة تفاصيل دولة (?country=slug) أو من صفحة نوع تأشيرة (?visa_type_id=id)،
// نملأ الفورم تلقائيًا بدل ما المستخدم يختار من الأول
function prefillFromQuery() {
    const params = new URLSearchParams(window.location.search);
    const countrySlug = params.get('country');
    const visaTypeId = params.get('visa_type_id');

    if (visaTypeId) {
        const idNum = Number(visaTypeId);
        for (const c of props.countries) {
            const match = c.visa_types.find((v) => v.id === idNum);
            if (match) {
                form.country_id = c.id;
                form.visa_type_id = idNum;
                break;
            }
        }
        return;
    }

    if (countrySlug) {
        const c = props.countries.find((c) => c.slug === countrySlug);
        if (c) form.country_id = c.id;
    }
}
prefillFromQuery();

function onReceiptChange(e: Event) {
    const target = e.target as HTMLInputElement;
    form.payment_receipt = target.files?.[0] ?? null;
}

function addDocRow() {
    form.documents.push({ type: 'passport', file: null });
}

function removeDocRow(i: number) {
    form.documents.splice(i, 1);
}

function onDocFileChange(e: Event, i: number) {
    const target = e.target as HTMLInputElement;
    form.documents[i].file = target.files?.[0] ?? null;
}

function submit() {
    form.transform((data) => {
        const fd: Record<string, any> = { ...data };
        // Inertia هيحوّل documents[i].file/type تلقائيًا لما تبقى مصفوفة كائنات فيها File
        return fd;
    }).post('/apply', {
        forceFormData: true,
        preserveScroll: true,
        onSuccess: () => {
            const orderNumber = page.props.flash?.success?.order_number;
            if (orderNumber) {
                lastOrderNumber.value = orderNumber;
                showSuccessModal.value = true;
            }
            form.reset();
        },
    });
}

addDocRow(); // صف واحد افتراضي عشان الشكل ميبقاش فاضي
</script>

<template>
    <Head :title="t('apply.title')" />

    <AppLayout>
        <section class="bg-ink py-14 text-white">
            <div class="mx-auto max-w-4xl px-6">
                <h1 class="font-display text-3xl font-extrabold md:text-4xl">{{ t('apply.title') }}</h1>
                <p class="mt-3 max-w-xl text-white/70">{{ t('apply.subtitle') }}</p>
            </div>
        </section>

        <section class="py-14">
            <div class="mx-auto max-w-4xl px-6">
                <!-- Modal نجاح تقديم الطلب -->
                <SuccessModal
                    :open="showSuccessModal"
                    :title="t('apply.success_title')"
                    @close="showSuccessModal = false"
                >
                    <p class="text-sm text-ink/70">{{ t('apply.success_message') }}</p>

                    <p class="mt-4 rounded-xl bg-paper px-6 py-3 font-display text-2xl font-extrabold tracking-wider text-ink" dir="ltr">
                        {{ lastOrderNumber }}
                    </p>

                    <p class="mt-3 text-sm font-bold text-brass">{{ t('apply.keep_order_number') }}</p>

                    <div class="mt-4 flex justify-center gap-3">
                        <button
                            type="button"
                            class="rounded-lg border border-paper-dark px-4 py-2 text-xs font-bold text-ink/70 hover:bg-paper"
                            @click="copyOrderNumber"
                        >
                            {{ copied ? t('apply.copied') : t('apply.copy_order_number') }}
                        </button>
                        <Link :href="`/track?order_number=${lastOrderNumber}`" class="rounded-lg border border-teal px-4 py-2 text-xs font-bold text-teal hover:bg-teal/5">
                            {{ t('nav.track') }}
                        </Link>
                    </div>
                </SuccessModal>

                <form @submit.prevent="submit" enctype="multipart/form-data" class="space-y-8">
                    <!-- بيانات أساسية -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm shadow-ink/5">
                        <div class="grid gap-5 sm:grid-cols-2">
                            <div>
                                <label class="text-sm font-medium text-ink/70">{{ t('apply.name') }}</label>
                                <input v-model="form.name" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" />
                                <p v-if="form.errors.name" class="mt-1 text-xs text-alert">{{ form.errors.name }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-ink/70">{{ t('apply.passport_number') }}</label>
                                <input v-model="form.passport_number" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" dir="ltr" />
                                <p v-if="form.errors.passport_number" class="mt-1 text-xs text-alert">{{ form.errors.passport_number }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-ink/70">{{ t('apply.country') }}</label>
                                <select v-model="form.country_id" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" @change="onCountryChange">
                                    <option value="" disabled>—</option>
                                    <option v-for="c in countries" :key="c.id" :value="c.id">
                                        {{ locale === 'ar' ? c.name.ar : c.name.en }}
                                    </option>
                                </select>
                                <p v-if="form.errors.country_id" class="mt-1 text-xs text-alert">{{ form.errors.country_id }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-ink/70">{{ t('apply.visa_type') }}</label>
                                <select
                                    v-model="form.visa_type_id"
                                    :disabled="!selectedCountry"
                                    class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal disabled:bg-paper disabled:text-ink/30"
                                >
                                    <option value="" disabled>{{ selectedCountry ? '—' : t('apply.select_country_first') }}</option>
                                    <option v-for="v in availableVisaTypes" :key="v.key" :value="v.id">
                                        {{ locale === 'ar' ? v.name.ar : v.name.en }}
                                    </option>
                                </select>
                                <p v-if="form.errors.visa_type_id" class="mt-1 text-xs text-alert">{{ form.errors.visa_type_id }}</p>
                            </div>

                            <div>
                                <label class="text-sm font-medium text-ink/70">{{ t('apply.phone') }}</label>
                                <input v-model="form.phone" type="tel" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" />
                                <p v-if="form.errors.phone" class="mt-1 text-xs text-alert">{{ form.errors.phone }}</p>
                            </div>
                            <div>
                                <label class="text-sm font-medium text-ink/70">{{ t('apply.email') }}</label>
                                <input v-model="form.email" type="email" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" dir="ltr" />
                            </div>

                            <div class="sm:col-span-2">
                                <label class="text-sm font-medium text-ink/70">{{ t('apply.address') }}</label>
                                <input v-model="form.address" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" />
                                <p v-if="form.errors.address" class="mt-1 text-xs text-alert">{{ form.errors.address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- إيصال الدفع -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm shadow-ink/5">
                        <label class="text-sm font-medium text-ink/70">{{ t('apply.payment_receipt') }}</label>
                        <input type="file" accept="image/*" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 text-sm outline-none focus:border-teal" @change="onReceiptChange" />
                    </div>

                    <!-- المستندات: عمودين - نوع المستند + الملف -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm shadow-ink/5">
                        <div class="flex items-center justify-between">
                            <h2 class="font-display text-lg font-bold text-ink">{{ t('apply.documents_title') }}</h2>
                            <button type="button" class="text-sm font-bold text-teal hover:underline" @click="addDocRow">{{ t('apply.add_document') }}</button>
                        </div>

                        <div class="mt-4 space-y-3">
                            <div v-for="(row, i) in form.documents" :key="i" class="grid grid-cols-1 gap-3 rounded-xl border border-paper-dark p-3 sm:grid-cols-[1fr_1fr_auto]">
                                <div>
                                    <label class="text-xs font-medium text-ink/50">{{ t('apply.document_type') }}</label>
                                    <select v-model="row.type" class="mt-1 w-full rounded-lg border border-paper-dark px-3 py-2 text-sm outline-none focus:border-teal">
                                        <option v-for="dt in docTypeKeys" :key="dt" :value="dt">{{ t(`apply.doc_types.${dt}`) }}</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-ink/50">{{ t('apply.document_file') }}</label>
                                    <input type="file" accept=".pdf,.jpg,.jpeg,.png" class="mt-1 w-full rounded-lg border border-paper-dark px-3 py-2 text-sm outline-none focus:border-teal" @change="onDocFileChange($event, i)" />
                                </div>
                                <button type="button" class="self-end pb-2 text-xs font-bold text-alert hover:underline" @click="removeDocRow(i)">
                                    {{ t('apply.remove') }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ملاحظات -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm shadow-ink/5">
                        <label class="text-sm font-medium text-ink/70">{{ t('apply.notes') }}</label>
                        <textarea v-model="form.notes" rows="4" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal"></textarea>
                    </div>

                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full rounded-xl bg-teal py-3.5 font-display text-sm font-bold text-white hover:bg-teal-light disabled:opacity-50"
                    >
                        {{ form.processing ? '...' : t('apply.submit') }}
                    </button>
                </form>
            </div>
        </section>
    </AppLayout>
</template>
