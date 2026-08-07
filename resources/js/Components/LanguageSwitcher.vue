<script setup lang="ts">
import { ref, onMounted, onBeforeUnmount } from 'vue';
import { useI18n } from 'vue-i18n';
import { setAppLocale, SUPPORTED_LOCALES } from '../i18n';

const { locale } = useI18n();
const open = ref(false);
const rootEl = ref<HTMLElement | null>(null);

function choose(code: (typeof SUPPORTED_LOCALES)[number]['code']) {
    setAppLocale(code);
    open.value = false;
}

function onClickOutside(e: MouseEvent) {
    if (rootEl.value && !rootEl.value.contains(e.target as Node)) open.value = false;
}

onMounted(() => document.addEventListener('click', onClickOutside));
onBeforeUnmount(() => document.removeEventListener('click', onClickOutside));
</script>

<template>
    <div ref="rootEl" class="relative">
        <button
            type="button"
            class="flex items-center gap-2 rounded-full border border-white/20 px-3 py-1.5 text-sm font-medium text-white/90 transition hover:border-brass hover:text-brass"
            @click="open = !open"
        >
            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6">
                <circle cx="12" cy="12" r="9" />
                <path d="M3 12h18M12 3c2.5 2.6 3.8 5.7 3.8 9s-1.3 6.4-3.8 9c-2.5-2.6-3.8-5.7-3.8-9S9.5 5.6 12 3Z" />
            </svg>
            <span>{{ SUPPORTED_LOCALES.find((l) => l.code === locale)?.label }}</span>
        </button>

        <div
            v-if="open"
            class="absolute end-0 z-50 mt-2 w-40 overflow-hidden rounded-xl border border-paper-dark bg-white py-1 text-ink shadow-xl"
        >
            <button
                v-for="l in SUPPORTED_LOCALES"
                :key="l.code"
                type="button"
                class="block w-full px-4 py-2 text-start text-sm hover:bg-paper"
                :class="l.code === locale ? 'font-bold text-teal' : ''"
                @click="choose(l.code)"
            >
                {{ l.label }}
            </button>
        </div>
    </div>
</template>
