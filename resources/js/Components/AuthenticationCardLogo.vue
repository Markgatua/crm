<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <Link :href="'/'">
        <div class="flex flex-col items-center select-none">
            <div class="w-12 h-12 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center shadow-lg mb-2">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a4 4 0 00-5.477-3.77M9 20H4v-2a4 4 0 015.477-3.77m0 0A4 4 0 1112 6a4 4 0 012.523 7.23M9.477 16.23A4 4 0 0112 6"/>
                </svg>
            </div>
            <span class="text-xl font-bold text-white tracking-tight">CRM <span class="text-white/80">by sell.ke</span></span>
        </div>
    </Link>
</template>
