<script setup lang="ts">
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

defineProps<{
    user: { name: string; is_admin: boolean };
}>();

const { t } = useI18n();
const open = ref(false);
</script>

<template>
    <div class="relative">
        <button
            type="button"
            class="flex items-center gap-2 font-display text-sm text-white/85 hover:text-white"
            @click="open = !open"
        >
            <span class="flex h-8 w-8 items-center justify-center rounded-full bg-brass/20 text-xs font-bold text-brass">
                {{ user.name.charAt(0).toUpperCase() }}
            </span>
            {{ user.name }}
            <svg class="h-3.5 w-3.5 transition" :class="open ? 'rotate-180' : ''" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6" />
            </svg>
        </button>

        <div v-if="open" class="absolute end-0 top-11 z-50 w-52 rounded-2xl bg-white p-2 text-ink shadow-2xl" @click="open = false">
            <Link v-if="user.is_admin" href="/admin" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-paper">
                {{ t('user_menu.admin_panel') }}
            </Link>
            <Link href="/dashboard" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-paper">
                {{ t('user_menu.dashboard') }}
            </Link>
            <Link href="/dashboard#applications" class="block rounded-lg px-3 py-2 text-sm font-medium hover:bg-paper">
                {{ t('user_menu.my_applications') }}
            </Link>
            <hr class="my-1 border-paper-dark" />
            <Link href="/logout" method="post" as="button" class="block w-full rounded-lg px-3 py-2 text-start text-sm font-medium text-alert hover:bg-alert/5">
                {{ t('user_menu.logout') }}
            </Link>
        </div>
    </div>
</template>
