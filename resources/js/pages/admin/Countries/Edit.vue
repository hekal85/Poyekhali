<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import CountryForm from '../../../Components/Admin/CountryForm.vue';

interface EditVisaType {
    key: string;
    name_ar: string;
    name_en: string;
    fee: number;
    documents: { text_ar: string; text_en: string }[];
}

interface EditCountry {
    id: number;
    slug: string;
    flag: string;
    name_ar: string;
    name_en: string;
    region: 'gulf' | 'other';
    processing_time_ar: string;
    processing_time_en: string;
    order: number;
    is_active: boolean;
    image_path: string | null;
    visa_types: EditVisaType[];
}

const props = defineProps<{ country: EditCountry }>();

const imageUrl = props.country.image_path ? `/storage/${props.country.image_path}` : null;
</script>

<template>
    <Head :title="`تعديل ${country.name_ar}`" />

    <AdminLayout>
        <h1 class="font-display text-2xl font-extrabold text-ink">تعديل: {{ country.name_ar }}</h1>
        <div class="mt-6">
            <CountryForm
                :submit-url="`/admin/countries/${country.id}`"
                submit-method="put"
                :initial="{ ...country, image_url: imageUrl }"
            />
        </div>
    </AdminLayout>
</template>
