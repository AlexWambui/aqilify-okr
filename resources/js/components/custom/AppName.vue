<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const name = page.props.name;
</script>

<template>
    <slot>
        {{ name }}
    </slot>
</template>