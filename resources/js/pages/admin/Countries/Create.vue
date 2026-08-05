<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import AdminLayout from '../../../Layouts/AdminLayout.vue';
import CountryForm from '../../../Components/Admin/CountryForm.vue';
</script>

<template>
    <Head title="إضافة دولة" />

    <AdminLayout>
        <h1 class="font-display text-2xl font-extrabold text-ink">إضافة دولة جديدة</h1>
        <div class="mt-6">
            <CountryForm submit-url="/admin/countries" submit-method="post" />
        </div>
    </AdminLayout>
</template>
