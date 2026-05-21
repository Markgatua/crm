<script setup>
import { Link } from "@inertiajs/vue3";
defineProps({
    title: String,
    name: String,
    optionalText: { default: null },
});
</script>

<template>
    <div class="px-4 pt-5 pb-4 sm:px-6 lg:px-8 flex items-center justify-between">
        <div>
            <div class="flex items-center gap-1.5 mb-1">
                <Link :href="route('dashboard')" class="text-xs text-gray-400 hover:text-primary transition-colors font-medium">Home</Link>
                <svg class="w-3 h-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-xs text-gray-500 font-medium">{{ name }}</span>
            </div>
            <h1 class="text-xl font-bold text-gray-900 tracking-tight">
                {{ title }}
                <span v-if="optionalText" class="text-gray-400 font-normal ml-2 text-base">{{ optionalText }}</span>
            </h1>
        </div>
        <slot name="actions" />
    </div>
</template>
