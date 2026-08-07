<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';
import AppLayout from '../../Layouts/AppLayout.vue';

const { t } = useI18n();

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

function submit() {
    form.post('/login', { onFinish: () => form.reset('password') });
}
</script>

<template>
    <Head :title="t('auth.login_title')" />

    <AppLayout>
        <section class="flex min-h-[70vh] items-center justify-center bg-paper py-14">
            <div class="w-full max-w-sm rounded-2xl bg-white p-8 shadow-sm shadow-ink/5">
                <h1 class="text-center font-display text-xl font-bold text-ink">{{ t('auth.login_title') }}</h1>

                <form class="mt-6 space-y-4" @submit.prevent="submit">
                    <div>
                        <label class="text-sm font-medium text-ink/70">{{ t('auth.email') }}</label>
                        <input v-model="form.email" type="email" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" dir="ltr" />
                        <p v-if="form.errors.email" class="mt-1 text-xs text-alert">{{ form.errors.email }}</p>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-ink/70">{{ t('auth.password') }}</label>
                        <input v-model="form.password" type="password" class="mt-1 w-full rounded-lg border border-paper-dark px-4 py-2.5 outline-none focus:border-teal" />
                    </div>
                    <label class="flex items-center gap-2 text-sm text-ink/60">
                        <input v-model="form.remember" type="checkbox" class="rounded border-paper-dark" />
                        {{ t('auth.remember') }}
                    </label>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full rounded-xl bg-teal py-3 font-display text-sm font-bold text-white hover:bg-teal-light disabled:opacity-50"
                    >
                        {{ t('auth.submit_login') }}
                    </button>
                </form>

                <p class="mt-5 text-center text-sm text-ink/50">
                    {{ t('auth.no_account') }}
                    <Link href="/register" class="font-bold text-teal hover:underline">{{ t('nav.register') }}</Link>
                </p>
            </div>
        </section>
    </AppLayout>
</template>
