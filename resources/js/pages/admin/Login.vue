<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/admin/login', {
        onFinish: () => form.reset('password'),
    });
}
</script>

<template>
    <Head title="تسجيل الدخول - لوحة التحكم" />

    <div class="flex min-h-screen items-center justify-center bg-ink px-6" dir="rtl">
        <div class="w-full max-w-sm rounded-2xl bg-white p-8 shadow-xl">
            <div class="text-center">
                <span class="font-display text-2xl font-extrabold text-ink">
                    بيخ<span class="text-brass">الي</span>
                </span>
                <p class="mt-1 text-sm text-ink/50">لوحة التحكم</p>
            </div>

            <form class="mt-8 space-y-4" @submit.prevent="submit">
                <div>
                    <label class="text-sm font-medium text-ink/70">البريد الإلكتروني</label>
                    <input
                        v-model="form.email"
                        type="email"
                        autofocus
                        class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal"
                    />
                    <p v-if="form.errors.email" class="mt-1 text-xs text-alert">{{ form.errors.email }}</p>
                </div>

                <div>
                    <label class="text-sm font-medium text-ink/70">كلمة المرور</label>
                    <input
                        v-model="form.password"
                        type="password"
                        class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal"
                    />
                </div>

                <label class="flex items-center gap-2 text-sm text-ink/60">
                    <input v-model="form.remember" type="checkbox" class="rounded border-paper-dark" />
                    تذكرني
                </label>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full rounded-xl bg-teal py-3 font-display text-sm font-bold text-white hover:bg-teal-light disabled:opacity-50"
                >
                    دخول
                </button>
            </form>
        </div>
    </div>
</template>
