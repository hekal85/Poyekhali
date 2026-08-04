<script setup lang="ts">
withDefaults(
    defineProps<{
        size?: number;
        label?: string;
    }>(),
    { size: 220 },
);
</script>

<template>
    <div
        class="visa-stamp shrink-0"
        :style="{ width: `${size}px`, height: `${size}px` }"
    >
        <svg viewBox="0 0 200 200" class="h-full w-full">
            <circle cx="100" cy="100" r="92" fill="none" stroke="var(--color-brass)" stroke-width="2" opacity="0.55" />
            <circle cx="100" cy="100" r="76" fill="none" stroke="var(--color-teal)" stroke-width="1.4" stroke-dasharray="2 6" />
            <path id="stampCircle" d="M 100 30 A 70 70 0 1 1 99.9 30" fill="none" />
            <text font-size="11" letter-spacing="3" fill="var(--color-ink)" font-family="Cairo, sans-serif">
                <textPath href="#stampCircle" startOffset="2%">
                    {{ label ?? 'POYEKHALI • بيخالي • VISA SERVICES •' }}
                </textPath>
            </text>
            <g transform="translate(100 100)">
                <circle r="42" fill="var(--color-teal)" opacity="0.08" />
                <path
                    d="M-16 2 L-4 14 L18 -12"
                    fill="none"
                    stroke="var(--color-teal)"
                    stroke-width="6"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                />
            </g>
        </svg>
    </div>
</template>
