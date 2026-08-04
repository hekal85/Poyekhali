<script setup lang="ts">
import AppHeader from '../Components/AppHeader.vue';
import AppFooter from '../Components/AppFooter.vue';
</script>

<template>
    <div class="flex min-h-screen flex-col bg-paper text-ink">
        <AppHeader />
        <main class="flex-1">
            <slot />
        </main>
        <AppFooter />
    </div>
</template>
