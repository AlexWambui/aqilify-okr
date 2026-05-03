<script setup lang="ts">
import Navbar from '@/components/custom/Navbar/Index.vue';
</script>

<template>
    <div class="GuestLayout">
        <Navbar />

        <main class="py-2 px-4 lg:px-16">
            <slot />
        </main>
    </div>
</template>
