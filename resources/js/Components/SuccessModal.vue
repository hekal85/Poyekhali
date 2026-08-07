<script setup lang="ts">
import { Teleport, Transition } from 'vue';

defineProps<{
    open: boolean;
    title: string;
}>();

const emit = defineEmits<{ close: [] }>();
</script>

<template>
    <Teleport to="body">
        <Transition name="modal-fade">
            <div
                v-if="open"
                class="fixed inset-0 z-[100] flex items-center justify-center bg-ink/60 p-4 backdrop-blur-sm"
                @click.self="emit('close')"
            >
                <div class="w-full max-w-md rounded-2xl bg-white p-8 text-center shadow-2xl">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-teal/10">
                        <svg class="h-9 w-9 text-teal" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7" />
                        </svg>
                    </div>

                    <h2 class="mt-5 font-display text-xl font-extrabold text-ink">{{ title }}</h2>

                    <div class="mt-5">
                        <slot />
                    </div>

                    <button
                        type="button"
                        class="mt-6 w-full rounded-xl bg-teal py-3 font-display text-sm font-bold text-white hover:bg-teal-light"
                        @click="emit('close')"
                    >
                        <slot name="close-label">حسنًا</slot>
                    </button>
                </div>
            </div>
        </Transition>
    </Teleport>
</template>

<style scoped>
.modal-fade-enter-active,
.modal-fade-leave-active {
    transition: opacity 0.2s ease;
}
.modal-fade-enter-from,
.modal-fade-leave-to {
    opacity: 0;
}
</style>
