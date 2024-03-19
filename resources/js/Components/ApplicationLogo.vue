<script setup>
import { Link } from '@inertiajs/vue3';
</script>
<template>
    <Link :href="route('inicio')">
    <div class="p-6 flex flex-col items-center">
        <img class="w-4/5" src="/images/geseg_logo.png" />
    </div>
    </Link>
</template>
