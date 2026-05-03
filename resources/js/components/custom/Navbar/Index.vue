<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <header class="py-4 px-4 lg:px-16">
        <nav class="flex items-center justify-between">
            <div class="branding">Aaqilify <span>OKR</span></div>

            <div class="nav-links"></div>

            <div class="extras">
                <Link href="/login" class="py-2 px-4 bg-foreground text-background rounded-sm font-medium tracking-wider">Login</Link>
            </div>
        </nav>
    </header>
</template>