<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import { useI18n } from 'vue-i18n';

interface NotificationItem {
    id: number;
    title: string;
    message: string;
    link: string | null;
    read_at: string | null;
    created_at: string;
}

const { t } = useI18n();
const page = usePage<{ unreadNotificationsCount?: number }>();

const open = ref(false);
const items = ref<NotificationItem[]>([]);
const unread = ref(page.props.unreadNotificationsCount ?? 0);
const loaded = ref(false);

async function toggle() {
    open.value = !open.value;
    if (open.value && !loaded.value) {
        await load();
    }
}

async function load() {
    try {
        const res = await window.axios.get('/notifications');
        items.value = res.data.notifications;
        unread.value = res.data.unread_count;
        loaded.value = true;
    } catch {
        // تجاهل بصمت - الجرس مش عنصر حرج لعمل الموقع
    }
}

async function markAllRead() {
    try {
        await window.axios.post('/notifications/read-all');
        items.value = items.value.map((n) => ({ ...n, read_at: n.read_at ?? new Date().toISOString() }));
        unread.value = 0;
    } catch {
        //
    }
}

onMounted(() => {
    // اتحمّل من الـ shared prop الأول (سريع)، وهيتحدّث فعليًا لما المستخدم يفتح القائمة
});
</script>

<template>
    <div class="relative">
        <button
            type="button"
            class="relative flex h-9 w-9 items-center justify-center rounded-full transition"
            :class="unread > 0 ? 'bg-blue-500/20 text-blue-300 hover:bg-blue-500/30' : 'bg-white/5 text-white/50 hover:bg-white/10'"
            @click="toggle"
        >
            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.4-1.4A2 2 0 0 1 18 14.2V11a6 6 0 1 0-12 0v3.2c0 .5-.2 1-.6 1.4L4 17h5m6 0v1a3 3 0 1 1-6 0v-1m6 0H9" />
            </svg>
            <span
                v-if="unread > 0"
                class="absolute -end-1 -top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white"
            >
                {{ unread > 9 ? '9+' : unread }}
            </span>
        </button>

        <div
            v-if="open"
            class="absolute end-0 top-12 z-50 w-80 rounded-2xl bg-white p-2 text-ink shadow-2xl"
        >
            <div class="flex items-center justify-between px-3 py-2">
                <span class="font-display text-sm font-bold">{{ t('notifications.title') }}</span>
                <button v-if="unread > 0" type="button" class="text-xs font-bold text-teal hover:underline" @click="markAllRead">
                    {{ t('notifications.mark_all_read') }}
                </button>
            </div>

            <div class="max-h-80 divide-y divide-paper-dark overflow-y-auto">
                <Link
                    v-for="n in items"
                    :key="n.id"
                    :href="n.link ?? '/dashboard'"
                    class="block rounded-lg px-3 py-2.5 text-start text-sm hover:bg-paper"
                    :class="!n.read_at ? 'bg-brass/5' : ''"
                >
                    <p class="font-medium text-ink">{{ n.title }}</p>
                    <p class="mt-0.5 text-xs text-ink/50">{{ n.message }}</p>
                </Link>
                <p v-if="loaded && !items.length" class="px-3 py-6 text-center text-sm text-ink/40">
                    {{ t('notifications.empty') }}
                </p>
            </div>
        </div>
    </div>
</template>
